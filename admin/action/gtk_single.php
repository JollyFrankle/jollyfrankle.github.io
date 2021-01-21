<?php include('../database_connection.php');
session_start();
// 95% SELESAI MIGRASI KE VERSI BARU

$_POST["gtk_id"]= addslashes($_POST["gtk_id"]); $_POST["npsn"]=addslashes($_POST["npsn"]);
// VALIDASI: Apakah tindakan ini terlarang atau tidak? (info lengkap di dokumentasi)
if((isset($_POST["gtk_id"]) && $_POST["action"]!='Tambah') && ($_SESSION["adm_level"]==2 && (!in_array(sql_value($connect, "SELECT tbl_sekdb.dt_dati2 FROM tbl_sekdb,tbl_gtk WHERE tbl_gtk.gtk_id='".$_POST["gtk_id"]."' AND tbl_gtk.gtk_npsn=tbl_sekdb.dt_npsn"),$_SESSION["adm_dati2"]) || !in_array(sql_value($connect, "SELECT tbl_sekdb.dt_jp FROM tbl_sekdb,tbl_gtk WHERE tbl_gtk.gtk_id='".$_POST["gtk_id"]."' AND tbl_gtk.gtk_npsn=tbl_sekdb.dt_npsn"),$_SESSION["adm_jp"])))) die();

if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
	// TINDAKAN: Menambah atau mengubah profil/data GTK
	if($_POST["action"] == 'Tambah' || $_POST["action"] == 'Ubah')
	{
		if(empty($_POST["gtk_sk_ber"]))
			$gtk_sk_ber = NULL; else
			$gtk_sk_ber = $_POST["gtk_sk_ber"];
		if($_POST["gtk_jenis"] == 'Kepsek' && sql_value($connect, "SELECT count(gtk_id) FROM tbl_gtk WHERE gtk_npsn = '".$_POST["npsn"]."' AND gtk_id <> '".$_POST["gtk_id"]."' AND gtk_jenis = 'Kepsek'") > 0) {
			$error_gtk_jenis = 'Anda sudah pernah menetapkan Kepala Sekolah.';
			$error++;
		}
		if(!empty($_POST["gtk_ttl"][0]) && !empty($_POST["gtk_ttl"][1]))
			$gtk_ttl = json_encode($_POST["gtk_ttl"]);
		if(empty($_POST["gtk_tmtpt"]))
			$gtk_tmtpt = NULL;else
			$gtk_tmtpt = $_POST["gtk_tmtpt"];
		if(empty($_POST["gtk_gaji"]))
			$gtk_gaji = NULL; else
			$gtk_gaji = json_encode($_POST["gtk_gaji"]);

		if($error > 0)
			$opt = array(
				'error'		 =>	true,
				'error_jenis'=> $error_gtk_jenis
			);
		else
		{
			$data = array(
				':gtk_no'			 =>	$_POST["gtk_no"],
				':gtk_nama'			=>	$_POST["gtk_nama"],
				':gtk_nip'			=>	$_POST["gtk_nip"],
				':gtk_nuptk'		=>	$_POST["gtk_nuptk"],
				':statpeg'			=>	$_POST["statpeg"],
				':dapodik'			=>	$_POST["dapodik"],
				':tamsil'			 =>	$_POST["tamsil"],
				':sek_induk'		=>	$_POST["sek_induk"],
				':gtk_jk'			 =>	$_POST["gtk_jk"],
				':gtk_kual'		 =>	json_encode($_POST["gtk_kual"]),
				':gtk_sert'		 =>	$_POST["gtk_sert"],
				':gtk_gol'			=>	$_POST["gtk_gol"],
				':gtk_sk_ber'	 =>	$gtk_sk_ber,
				':gtk_jenis'		=>	json_encode($_POST["gtk_jenis"]),
				':gtk_ttl'			=>	$gtk_ttl,
				':gtk_tmtpt'		=>	$gtk_tmtpt,
				':gtk_karpeg'	 =>	$_POST["gtk_karpeg"],
				':gtk_gaji'		 =>	$gtk_gaji
			);
			// TINDAKAN - QUERY: Menambah GTK baru ke sekolah
			if($_POST["action"] == 'Tambah')
			{
				$data[":gtk_npsn"]=$_POST["npsn"];
				$query = "
					INSERT INTO tbl_gtk 
					(gtk_no, gtk_nama, gtk_nip, gtk_nuptk, statpeg, dapodik, tamsil, sek_induk, gtk_npsn, gtk_jk, gtk_kual, gtk_sert, gtk_gol, gtk_sk_ber, gtk_jenis, gtk_ttl, gtk_tmtpt, gtk_karpeg, gtk_gaji) 
					VALUES (:gtk_no, :gtk_nama, :gtk_nip, :gtk_nuptk, :statpeg, :dapodik, :tamsil, :sek_induk, :gtk_npsn, :gtk_jk, :gtk_kual, :gtk_sert, :gtk_gol, :gtk_sk_ber, :gtk_jenis, :gtk_ttl, :gtk_tmtpt, :gtk_karpeg, :gtk_gaji)
				";
				sql_dbop($connect, "UPDATE tbl_sekolah SET sek_lastmod = '".date("Y-m-d H:i:s")."' WHERE sek_npsn='".$_POST["npsn"]."'");
				$st = $connect->prepare($query);
				if($st->execute($data))
				{
					$opt = array(
						'success'	=>	true,
						"text"		=>	date("H:i:s").': GTK <b>'.filter_xsshtml($_POST["gtk_nama"]).'</b> telah ditambahkan untuk '.$_POST["npsn"]
					);
				}
			}
			// TINDAKAN - QUERY: Mengubah data GTK pada sebuah sekolah
			if($_POST["action"] == 'Ubah')
			{
				$data[":gtk_id"]=$_POST["gtk_id"];
				$query = "
					UPDATE tbl_gtk 
					SET gtk_no = :gtk_no,
					gtk_nama = :gtk_nama, 
					gtk_nip = :gtk_nip, 
					gtk_nuptk = :gtk_nuptk, 
					statpeg = :statpeg,
					dapodik = :dapodik,
					tamsil = :tamsil,
					sek_induk = :sek_induk,
					gtk_jk = :gtk_jk,
					gtk_kual = :gtk_kual,
					gtk_sert = :gtk_sert,
					gtk_gol = :gtk_gol,
					gtk_sk_ber = :gtk_sk_ber,
					gtk_jenis = :gtk_jenis,
					gtk_ttl = :gtk_ttl,
					gtk_tmtpt = :gtk_tmtpt,
					gtk_karpeg = :gtk_karpeg,
					gtk_gaji = :gtk_gaji
					WHERE gtk_id = :gtk_id
				";
				sql_dbop($connect, "UPDATE tbl_sekolah SET sek_lastmod = '".date("Y-m-d H:i:s")."' WHERE sek_npsn = '".$_POST["npsn"]."'");
				$st = $connect->prepare($query);
				if($st->execute($data))
				{
					$opt = array(
						'success'	=>	true,
						"text"		=>	date("H:i:s").': Data GTK <b>'.filter_xsshtml($_POST["gtk_nama"]).'</b> telah diubah untuk '.$_POST["npsn"]
					);
				}
			}
		}
	}
	
	// TINDAKAN: Mengambil data untuk ditampilkan saat menyunting
	if($_POST["action"] == "edit_fetch")
	{
		$query = "
			SELECT * FROM tbl_gtk 
			WHERE gtk_id = :gtk
		";
		$st = $connect->prepare($query);
		if($st->execute(array(":gtk"=>$_POST["gtk_id"])))
		{
			$result = $st->fetchAll();
			foreach($result as $r)
			{
				$o_kual = json_decode($r["gtk_kual"]);
				$o_jen	= json_decode($r["gtk_jenis"]);
				$o_ttl	= json_decode($r["gtk_ttl"]);
				$o_gaji = json_decode($r["gtk_gaji"]);
				$opt = array(
					"gtk_no"		=> $r["gtk_no"],
					"gtk_nama"	=> $r["gtk_nama"],
					"gtk_nip"	 => $r["gtk_nip"],
					"gtk_nuptk" => $r["gtk_nuptk"],
					"statpeg"	 => $r["statpeg"],
					"dapodik"	 => $r["dapodik"],
					"tamsil"		=> $r["tamsil"],
					"sek_induk" => $r["sek_induk"],
					"gtk_id"		=> $r["gtk_id"],
					"gtk_jk"		=> $r["gtk_jk"],
					"kual_pt"	 => $o_kual[0],
					"kual_ps"	 => $o_kual[1],
					"gtk_sert"	=> $r["gtk_sert"],
					"gtk_gol"	 => $r["gtk_gol"],
					"gtk_sk_ber"=> $r["gtk_sk_ber"],
					"gtk_jenis" => $o_jen[0],
					"mapel"		 => $o_jen[1],
					"gtk_kelas" => $o_jen[2],
					"gtk_tmt_kepsek" => $o_jen[1],
					"ttl_tempat"=> $o_ttl[0],
					"ttl_tgl"	 => $o_ttl[1],
					"gtk_tmtpt" => $r["gtk_tmtpt"],
					"gtk_karpeg"=> $r["gtk_karpeg"],
					"gaji_bos"	=> $o_gaji[0],
					"gaji_tmb"	=> $o_gaji[1]
				);
			}
		}
	}
	// TINDAKAN: Menghapus GTK dari daftar
	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_gtk 
		WHERE gtk_id = :gtk
		";
		sql_dbop($connect, "UPDATE tbl_sekolah SET sek_lastmod = '".date("Y-m-d H:i:s")."' WHERE sek_npsn = '".$_POST["npsn"]."'");
		$st = $connect->prepare($query);
		$st->execute(array(":gtk"=>$_POST["gtk_id"]));
		if($st->rowCount()==1)
		{
			$opt = array(
				'success' =>	true,
				"text"		=>	date("H:i:s").': Data GTK <b>'.filter_xsshtml($_POST["gtk_nama"]).'</b> telah dihapus secara permanen untuk '.$_POST["npsn"]
			);
		}
		else
		{
			$opt = array(
				"error" =>	true,
				"text"	=>	date("H:i:s").': Tidak ditemukan data yang sesuai dengan permintaan Anda (<b>'.filter_xsshtml($_POST["gtk_nama"]).'</b>). Silakan ulangi permintaan Anda.'
			);
		}
	}
	
	// TINDAKAN: Mengambil informasi presensi tanggal tertentu
	if($_POST["action"] == "kehadiran")
	{
		$query = "
			SELECT * FROM tbl_gtk
			WHERE gtk_id = :gtk
		";
		$st = $connect->prepare($query);
		$st->execute(array(":gtk"=>$_POST["gtk_id"]));
		$result = $st->fetchAll();
		foreach($result as &$row)
		{
		  $row=array_map("filter_xsshtml",$row);
			$p_nip = '';
			if($row["statpeg"] == "PNS" && $row["gtk_nip"]) {
				$p_nip = 'NIP. '.$row["gtk_nip"];
			}
			$p_nuptk = '';
			if($row["dapodik"] != "BLM" && $row["gtk_nuptk"]) {
				$p_nuptk = 'NUPTK. '.$row["gtk_nuptk"];
			}
			if(!empty($p_nip) && !empty($p_nuptk)) {
				$p_nip = 'NIP. '.$row["gtk_nip"].' — ';
			}
			$p_hd = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'HD');
			$p_tk = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'TB');
			$p_ct = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'CT');
			$p_dl = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'DL');
			$p_sk = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'SK');
			$p_iz = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'IZ');
			$p_tg = jlhkeh($connect, $_POST["gtk_id"], $_POST["awal"], $_POST["akhir"], 'TG');
			$p_tot = $p_hd + $p_tk + $p_ct + $p_dl + $p_iz + $p_sk + $p_tg;
			$graph = '
			<div class="progress" style="height:50px">
				<div class="progress-bar bg-primary" role="progressbar" style="width: '.($p_hd/$p_tot*100).'%" aria-valuenow="'.$p_hd.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
				<div class="progress-bar bg-danger" role="progressbar" style="width: '.($p_tk/$p_tot*100).'%" aria-valuenow="'.$p_tk.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
				<div class="progress-bar bg-success" role="progressbar" style="width: '.($p_iz/$p_tot*100).'%" aria-valuenow="'.$p_iz.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
				<div class="progress-bar bg-warning" role="progressbar" style="width: '.($p_sk/$p_tot*100).'%" aria-valuenow="'.$p_sk.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
				<div class="progress-bar bg-info" role="progressbar" style="width: '.($p_dl/$p_tot*100).'%" aria-valuenow="'.$p_dl.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
				<div class="progress-bar bg-dark" role="progressbar" style="width: '.($p_ct/$p_tot*100).'%" aria-valuenow="'.$p_ct.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
				<div class="progress-bar bg-secondary" role="progressbar" style="width: '.($p_tg/$p_tot*100).'%" aria-valuenow="'.$p_tg.'" aria-valuemin="0" aria-valuemax="'.$p_tot.'"></div>
			</div>
			<div class="row mt-3">
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-primary">■</span> Hadir <b>('.str_replace(".",",",round(($p_hd/$p_tot*100), 2)).'%)</b>
				</div>
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-danger">■</span> Tanpa Berita <b>('.str_replace(".",",",round(($p_tk/$p_tot*100), 2)).'%)</b>
				</div>
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-success">■</span> Izin <b>('.str_replace(".",",",round(($p_iz/$p_tot*100), 2)).'%)</b>
				</div>
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-warning">■</span> Sakit <b>('.str_replace(".",",",round(($p_sk/$p_tot*100), 2)).'%)</b>
				</div>
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-info">■</span> Dinas Luar <b>('.str_replace(".",",",round(($p_dl/$p_tot*100), 2)).'%)</b>
				</div>
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-dark">■</span> Cuti <b>('.str_replace(".",",",round(($p_ct/$p_tot*100), 2)).'%)</b>
				</div>
				<div class="col-sm-6 text-truncate mb-2">
					<span class="text-secondary">■</span> Tugas Belajar <b>('.str_replace(".",",",round(($p_tg/$p_tot*100), 2)).'%)</b>
				</div>
			</div>
			';
			$table = '
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>Status Kehadiran</th>
						<th>Keterangan</th>
					</tr>
				</thead>
			<tbody>
			';
			$q_tkeh = "
				SELECT dfhd_status, dfhd_tgl, dfhd_ket FROM tbl_dft_hadir
				WHERE gtk_id = :id
				AND (dfhd_tgl BETWEEN :awal AND :akhir)
				ORDER BY dfhd_tgl DESC
			";
			$st = $connect->prepare($q_tkeh);
			$st->execute(array(":id"=>$_POST["gtk_id"],":awal"=>$_POST["awal"],":akhir"=>$_POST["akhir"]));
			$row_q2 = $st->fetchAll();
			foreach($row_q2 as $r)
			{
				$status='';
				if($r["dfhd_status"] == "HD")$status= '<div class="text-primary">Hadir</div>';
				if($r["dfhd_status"] == "TB")$status= '<div class="text-danger">Tanpa Berita</div>';
				if($r["dfhd_status"] == "DL")$status= '<div class="text-success">Dinas Luar</div>';
				if($r["dfhd_status"] == "CT")$status= '<div class="text-info">Cuti</div>';
				if($r["dfhd_status"] == "SK")$status= '<div class="text-info">Sakit</div>';
				if($r["dfhd_status"] == "IZ")$status= '<div class="text-info">Izin</div>';
				if($r["dfhd_status"] == "TG")$status= '<div class="text-success">Tugas Belajar</div>';
				$table.='
				<tr>
					<td>'.date("d/m/Y", strtotime($r["dfhd_tgl"])).'</td>
					<td>'.$status.'</td>
					<td>'.$r["dfhd_ket"].'</td>
				</tr>';
			}
			$table.='</tbody></table>';
			$opt = array(
				'nama'		=>	$row["gtk_nama"],
				'nip'			 =>	$p_nip,
				'nuptk'		 =>	$p_nuptk,
				'hd'				=>	$p_hd,
				'tk'				=>	$p_tk,
				'ct'				=>	$p_ct,
				'dl'				=>	$p_dl,
				'sk'				=>	$p_sk,
				'iz'				=>	$p_iz,
				'tg'				=>	$p_tg,
				'total'		 =>	$p_tot,
				'bar'			 =>	$graph,
				'table'		 =>	$table,
				'awal'			=>	date("j M Y", strtotime($_POST["awal"])),
				'akhir'		 =>	date("j M Y", strtotime($_POST["akhir"])),
				'now'			 =>	date("j M Y, H:i:s").' WITA'
			);
		}
		unset($row);
	}
	echo json_encode($opt);
}