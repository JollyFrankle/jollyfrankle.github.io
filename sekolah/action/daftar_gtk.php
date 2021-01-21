<?php 
include('../../admin/database_connection.php');
session_start();

if(!isset($_POST["action"]) || !isset($_SESSION["sek_id"])	|| (sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'") && !isset($_SESSION["adm_id"]))){
include('../../403.html');
http_response_code(403);
} else {
	// TINDAKAN: Mendapatkan daftar GTK dari sekolah yang login
	if($_POST["action"] == "fetch")
	{
		$query = "
			SELECT * FROM tbl_gtk
			WHERE gtk_npsn = :npsn";
		$st = $connect->prepare($query);
		$st->execute(array(":npsn"=>$_SESSION["sek_npsn"]));
		$result = $st->fetchAll();
		$data = array();
		foreach($result as &$r)
		{
      $r=array_map("filter_xsshtml",$r);
			$rdt = array();
			// Perpanjangan istilah 3-huruf untuk status kepegawaian
			$p_statpeg = '';
			if($r["statpeg"] == "PNS") $p_statpeg='PNS';
			if($r["statpeg"] == "KON") $p_statpeg='Teko Prov.';
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
			if($r["statpeg"] == "GTT" || $r["statpeg"] == "HON") {
				if($r["tamsil"] == 0)
					$p_tamsil = '<div class="text-danger small">Terima Tamsil: <b>Tidak</b></div>';
					else $p_tamsil = '<div class="text-success small">Terima Tamsil: <b>Ya</b></div>';
			}
			if($r["statpeg"] == "PNS" && $r["gtk_sert"] == "0") {
				if($r["tamsil"] == 0)
					$p_tamsil = '<div class="text-danger small">Terima Tunj. Non-sertif: <b>Tidak</b></div>';
					else $p_tamsil = '<div class="text-success small">Terima Tunj. Non-sertif: <b>Ya</b></div>';
			}
			$p_sek_induk = '';
			if($r["dapodik"] == "NON") {
				$p_sek_induk = '<div class="text-primary small">Sekolah induk: <b>'.$r["sek_induk"].'</b></div>';
			}
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
			
			$kual = json_decode($r["gtk_kual"]);
			$r_kual = '<div class="font-weight-bold">'.$kual[0].'</div><div class="small">'.$kual[1].'</div>';
			$p_sert = '';
			if($r["gtk_sert"] == '0') {$p_sert = 'Belum';} else $p_sert = 'Sudah';
			$ttl = json_decode($r["gtk_ttl"]);
			if($r["gtk_ttl"] && $ttl[0] && $ttl[1]) $r_ttl = $ttl[0].', '.date("d/m/Y", strtotime($ttl[1])); else $r_ttl=NULL;
			
			$jenis = json_decode($r["gtk_jenis"]);
			$p_terakhir=$p_mapel=$p_berkala=$p_tmt_kepsek=$p_tmtpt=$p_mapel='';
			if($r["gtk_sk_ber"])
				$p_berkala = date("d/m/Y", strtotime($r["gtk_sk_ber"]));
			if($r["gtk_tmtpt"])
				$p_tmtpt = date("d/m/Y", strtotime($r["gtk_tmtpt"]));
			if($jenis[1] && $jenis[0]=='Kepsek')
				$p_tmt_kepsek = '<div class="text-primary small">Sejak <b>'.date("d/m/Y", strtotime($jenis[1])).'</b></div>';
			if($jenis[0]!="Kepsek")
				$p_mapel = $jenis[1];

			$rdt[] = $r["gtk_no"];
			$rdt[] = '<div style="font-weight:500">'.$r["gtk_nama"].'</div>'.$p_nip.$p_nuptk;
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
			$rdt[] = $b_laporan.$b_edit.$b_hapus;
			$data[] = $rdt;
		}
		unset($r);
		$opt=array("data"=>$data);
	}
	// TINDAKAN: Perbarui bulan dan tahun laporan
	if($_POST["action"] == "update_lb")
	{
    // Validasi: bulan dan tahun harus sesuai format.
    $_POST["lb_periode"][0]=date(m,strtotime($_POST["lb_periode"][0]));
    $_POST["lb_periode"][1]=date(Y,strtotime($_POST["lb_periode"][1]));
		$data = array(
			':sek_lb_periode' =>	json_encode($_POST["lb_periode"]),
			':sek_lastmod'		=>	date("Y-m-d H:i:s"),
			':npsn' 					=>	$_SESSION["sek_npsn"]
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