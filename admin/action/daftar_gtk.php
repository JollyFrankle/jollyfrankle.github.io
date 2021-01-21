<?php
include('../database_connection.php');
session_start();
// 100% disesuaikan TAB ok

if($_POST["npsn"] && $_SESSION["adm_level"]==2 && (!in_array(sql_value($connect, "SELECT dt_dati2 FROM tbl_sekdb WHERE dt_npsn=:npsn",array(':npsn'=>$_POST["npsn"])),$_SESSION["adm_dati2"]) || !in_array(sql_value($connect, "SELECT dt_jp FROM tbl_sekdb WHERE dt_npsn=:npsn",array(':npsn'=>$_POST["npsn"])),$_SESSION["adm_jp"]))) {
		die();
}

if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
	// TINDAKAN: Mendapatkan daftar GTK dari sebuah sekolah/pencarian
	if($_POST["action"]=="fetch")
	{
		// QUERY: Mendapatkan daftar GTK dari suatu sekolah (dengan NPSN)
		if($_POST["type"]=="gtksek")
			$query = "
			SELECT * FROM tbl_gtk
			WHERE gtk_npsn = :npsn
		";
		// QUERY: Mendapatkan daftar GTK daru suatu hasil pencarian
		if($_POST["type"]=="search"){
			$query="
			SELECT tbl_gtk.*,tbl_sekdb.dt_npsn,tbl_sekdb.dt_nama
			FROM tbl_gtk, tbl_sekdb
			WHERE tbl_gtk.gtk_npsn=tbl_sekdb.dt_npsn ";
			if($_SESSION["adm_level"]==2) {
				$query.="
				AND dt_dati2 IN (".implode(',', array_map('intval', $_SESSION["adm_dati2"])).")
				AND dt_jp IN(".implode(',', array_map('arrslashes', $_SESSION["adm_jp"])).") ";
			}
			$_POST["s_value"] = addslashes($_POST["s_value"]);
			$query.=" AND (
				gtk_nama LIKE '%".$_POST["s_value"]."%'
				OR gtk_nip LIKE '%".$_POST["s_value"]."%'
				OR gtk_nuptk LIKE '%".$_POST["s_value"]."%'
				OR JSON_UNQUOTE(JSON_EXTRACT(gtk_kual,'$[1]')) LIKE '%".$_POST["s_value"]."%'
				OR JSON_UNQUOTE(JSON_EXTRACT(gtk_ttl,'$[0]')) LIKE '%".$_POST["s_value"]."%'
				OR JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[1]')) LIKE '%".$_POST["s_value"]."%'
				) LIMIT 10
			";
		}
		$st = $connect->prepare($query);
		if($_POST["type"]=="gtksek") $st->execute(array(':npsn'=>$_POST["npsn"]));
		if($_POST["type"]=="search") $st->execute();
		$result = $st->fetchAll();
		$data = array();
		foreach($result as &$r)
		{
      $r=array_map("filter_xsshtml",$r);
			$rdt = array();
			// Perpanjangan istilah 3-huruf untuk status kepegawaian
			$p_statpeg = '';
			if($r["statpeg"] == "PNS") $p_statpeg='PNS';
			if($r["statpeg"] == "KON") $p_statpeg='Teko Provinsi';
			if($r["statpeg"] == "KAB") $p_statpeg='Teko Kabupaten/Kota';
			if($r["statpeg"] == "GTY") $p_statpeg='GTY';
			if($r["statpeg"] == "GTT") $p_statpeg='GTTY';
			if($r["statpeg"] == "HON") $p_statpeg='Honor Komite';

			// Perpanjangan istilah 3-huruf untuk status di Dapodik
			$p_dapodik ='';
			if($r["dapodik"] == "IND") $p_dapodik='Induk';
			if($r["dapodik"] == "NON") $p_dapodik='Non-Induk';
			if($r["dapodik"] == "BLM") $p_dapodik='Belum Terdaftar';

			$p_nip = '';
			if($r["statpeg"] == "PNS" && $r["gtk_nip"]) $p_nip = '<div class="text-nowrap small">NIP. '.$r["gtk_nip"].'</div>';
			$p_nuptk = '';
			if($r["dapodik"] != "BLM" && $r["gtk_nuptk"]) $p_nuptk = '<div class="text-nowrap small">NUPTK. '.$r["gtk_nuptk"].'</div>';
			$p_tamsil ='';
			if($r["statpeg"] == "GTT" || $r["statpeg"] == "HON")
			{
			if($r["tamsil"] == 0)
				$p_tamsil = '<div class="text-danger small">Terima Tamsil: <b>Tidak</b></div>';
			else
				$p_tamsil = '<div class="text-success small">Terima Tamsil: <b>Ya</b></div>';
			}
			if($r["statpeg"] == "PNS" && $r["gtk_sert"] == "0")
			{
			if($r["tamsil"] == 0)
				$p_tamsil = '<div class="text-danger small">Terima Tunj. Non-sertif: <b>Tidak</b></div>';
			else
				$p_tamsil = '<div class="text-success small">Terima Tunj. Non-sertif: <b>Ya</b></div>';
			}
			$p_sek_induk = '';
			if($r["dapodik"] == "NON")
				$p_sek_induk = '<div class="text-primary small">Sekolah induk: <b>'.$r["sek_induk"].'</b></div>';
			$p_gaji = ''; $arrgaji=json_decode($r["gtk_gaji"]);
			if($r["gtk_gaji"]) {
				$p_gaji='<div class="small text-primary">Gaji BOS: Rp'.number_format($arrgaji[0],0,',','.');
				if($r["statpeg"]=='GTT') $p_gaji.=' - Yayasan: Rp'.number_format($arrgaji[1],0,',','.');
				if($r["statpeg"]=='HON') $p_gaji.=' - Komite: Rp'.number_format($arrgaji[1],0,',','.');
				$p_gaji.='</div>';
			}
			
			$b_laporan = '<button class="btn btn-info btn-sm btn_lap" id="'.$r["gtk_id"].'">Lap. Kehadiran</button>';
			$b_edit		= '<button class="btn btn-primary btn-sm btn_ubah" id="'.$r["gtk_id"].'">Ubah Data</button>';
			$b_hapus	 = '<button class="btn btn-danger btn-sm btn_hps" id="'.$r["gtk_id"].'" nama_gtk="'.$r["gtk_nama"].'">Hapus</button>';
			$b_view = '<a href="dftgtk?npsn='.$r["dt_npsn"].'" class="btn btn-sm btn-primary">Lihat Sekolah</a>';
			$b_pak='';
			if($r["statpeg"]=='PNS'){$b_pak = '<a href="pak?id_gtk='.$r["gtk_id"].'" class="btn btn-sm btn-dark">PAK</a>';}
			
			$kual = json_decode($r["gtk_kual"]);
			$r_kual = '<div class="font-weight-bold">'.$kual[0].'</div><div class="small">'.$kual[1].'</div>';
			$p_sert = '';
			if($r["gtk_sert"]==0) $p_sert = 'Belum'; else $p_sert = 'Sudah';
			$ttl = json_decode($r["gtk_ttl"]);
			if($r["gtk_ttl"] && $ttl[0] && $ttl[1]) $r_ttl = $ttl[0].', '.date("d/m/Y", strtotime($ttl[1])); else $r_ttl=NULL;
			
			$jenis=json_decode($r["gtk_jenis"]);
			$p_terakhir=$p_mapel=$p_berkala=$p_tmt_kepsek=$p_tmtpt=$p_mapel='';
			if($r["gtk_sk_ber"])
				$p_berkala = date("d/m/Y", strtotime($r["gtk_sk_ber"]));
			if($r["gtk_tmtpt"])
				$p_tmtpt = date("d/m/Y", strtotime($r["gtk_tmtpt"]));
			if($jenis[1] && $jenis[0]=='Kepsek')
				$p_tmt_kepsek = '<div class="text-primary small">Sejak <b>'.date("d/m/Y", strtotime($jenis[1])).'</b></div>';
			if($jenis[0]!="Kepsek")
				$p_mapel = $jenis[1];

			if($_POST["type"]=="gtksek")
				$rdt[] = $r["gtk_no"];
			$rdt[] = '<div style="font-weight:500">'.$r["gtk_nama"].'</div>'.$p_nip.$p_nuptk;
			if($_POST["type"]=="search")
				$rdt[] = '<h6 class="m-0">'.$r["dt_nama"].'</h6><div class="small">('.$r["dt_npsn"].')</div>';
			$rdt[] = $r_ttl;
			$rdt[] = $r["gtk_jk"];
			$rdt[] = $r_kual;
			$rdt[] = $p_statpeg.$p_tamsil.$p_gaji;
			$rdt[] = $p_sert;
			$rdt[] = $r["gtk_gol"];
			$rdt[] = $p_tmtpt;
			$rdt[] = $p_berkala;
			$rdt[] = $jenis[0].$p_tmt_kepsek;
			$rdt[] = $p_mapel;
			$rdt[] = $jenis[2];
			$rdt[] = $p_dapodik.$p_sek_induk;
			if($_POST["type"]=="gtksek")
				$rdt[] = $b_laporan.$b_pak.$b_edit.$b_hapus;
			if($_POST["type"]=="search")
				$rdt[]=$b_pak.$b_view;
			$data[] = $rdt;
		}
		unset($r);
		$opt = array("data"=>$data);
	}
	// TINDAKAN: Perbarui bulan dan tahun laporan (hak hanya untuk webmaster)
	if($_POST["action"] == "update_lb" && $_SESSION["adm_level"]==0)
	{
    // Validasi: bulan dan tahun harus sesuai format.
    $_POST["lb_periode"][0]=date(m,strtotime($_POST["lb_periode"][0]));
    $_POST["lb_periode"][1]=date(Y,strtotime($_POST["lb_periode"][1]));
		$data = array(
			':sek_lb_periode'	 => json_encode($_POST["lb_periode"]),
			':sek_lastmod'			=> date("Y-m-d H:i:s"),
			':npsn'						 => $_POST["npsn"]
		);
		$query = "
			UPDATE tbl_sekolah 
			SET sek_lb_periode = :sek_lb_periode,
			sek_lastmod = :sek_lastmod
			WHERE sek_npsn = :npsn
		";
		$st = $connect->prepare($query);
		$st->execute($data);
		if($st->rowCount()>0)
		{
			$opt=array(
				"success" =>	true,
				"text"		=>	date("H:i:s").': Laporan Bulanan telah diperbarui ke bulan '.bulan($_POST["lb_periode"][0]).' '.$_POST["lb_periode"][1].'.'
			);
		}
	}
	echo json_encode($opt);
}