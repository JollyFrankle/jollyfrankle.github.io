<?php
include('../../admin/database_connection.php');
session_start();

if(!isset($_POST["action"]) || !isset($_SESSION["sek_id"]) || (sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'") && !isset($_SESSION["adm_id"]))){
include('../../403.html');
http_response_code(403);
} else {
	// TINDAKAN: Mengubah informasi rombel sekolah
	if($_POST["action"] == "ubah_infosek")
	{
		$data = array(
			':sek_rombel'	 =>	json_encode($_POST["sek_rombel"]),
			':sek_npsn'			=>	$_SESSION["sek_npsn"],
			":sek_lastmod"	=>	date("Y-m-d H:i:s")
		);
		$query = "
			UPDATE tbl_sekolah 
			SET sek_rombel = :sek_rombel,
			sek_lastmod = :sek_lastmod
			WHERE sek_npsn = :sek_npsn
		";
		sql_dbop($connect, "UPDATE tbl_sekolah SET sek_lastmod = '".date("Y-m-d H:i:s")."' WHERE sek_id = '".$_SESSION["sek_id"]."'");
		$st = $connect->prepare($query);
		$st->execute($data);
		if($st->rowCount()>0)
		{
			$opt = array(
				'success'	=>	true,
				"text"		=>	date("H:i:s").': Data sekolah telah diperbarui.'
			);
		}
		else
		{
			$opt = array(
				"error" =>	true,
				"text"	=>	date("H:i:s").": Data sekolah telah diperbarui, namun tidak ada perubahan."
			);
		}
	}
	
	// TINDAKAN: Mengubah informasi akun
	if($_POST["action"] == "ubah_akun")
	{
		if(sql_value($connect, "SELECT count(sek_id) FROM tbl_sekolah WHERE sek_email=:email AND sek_id!=:sek",array(':email'=>$_POST["sek_email"],':sek'=>$_SESSION["sek_id"])) != 0) {
			$error++;
			$e_email='Alamat surel ini sudah pernah digunakan oleh akun lain!';
		}
		if(strlen($_POST["sek_password"])<8) {
			$error++;
			$e_password='Kata sandi harus berisikan minimal 8 karakter.';
		}
		if($error==0)
		{
			$data = array(
				':sek_email'		=>	$_POST["sek_email"],
				':sek_password' =>	password_hash($_POST["sek_password"], PASSWORD_DEFAULT),
				':sek_id'				=>	$_SESSION["sek_id"]
				);
			$query = "
				UPDATE tbl_sekolah 
				SET sek_email = :sek_email,
				sek_password = :sek_password
				WHERE sek_id = :sek_id
			";
			sql_dbop($connect, "UPDATE tbl_sekolah SET sek_lastmod = '".date("Y-m-d H:i:s")."' WHERE sek_id = '".$_SESSION["sek_id"]."'");
			$st = $connect->prepare($query);
			if($st->execute($data))
			{
				$opt = array(
					'success'	=>	true,
					"text"		=>	date("H:i:s").': Data akun telah diperbarui. Silakan refresh halaman untuk melihat perubahan.',
					);
				$_SESSION["sek_email"] = $_POST["sek_email"];
			}
		}
		else
		{
			$opt = array(
				'error'         =>	true,
				'error_email'   =>	$e_email,
				'error_password'=>	$e_password
			);
		}
	}
	
	// TINDAKAN: Mendapatkan daftar unggahan
	if($_POST["action"] == "fetch_ung")
	{
		$query = "
		  SELECT * FROM tbl_unggahan
      WHERE ung_npsn = :npsn
		";
		$st = $connect->prepare($query);
		$st->execute(array(":npsn"=>$_SESSION["sek_npsn"]));
		$result = $st->fetchAll();
		$data = array();
		foreach($result as &$r)
		{
		  $r=array_map("filter_xsshtml",$r);
			$rdt = array();
      $p_tipe = '';
      if($r['ung_tipe'] == 'PDH') $p_tipe='Pertanggungjawaban Daftar Hadir';
      $p_desc = "<i class='text-muted'>Tidak ada deskripsi tentang berkas.</i>";
      if(!empty($r['ung_desc'])) $p_desc=$r['ung_desc'];
      $b_view = '<a class="btn btn-primary btn-sm" target="_blank" href="../storage/uploads/'.$r['ung_file'].'">Tampilkan</a>';
      $b_delete = '<button class="btn btn-danger btn-sm b_unghps" data-ung_nama="'.$r['ung_file'].'">Hapus</button>';

			$rdt[] = $p_tipe;
			$rdt[] = $p_desc;
			$rdt[] = $r['ung_periode'];
			$rdt[] = $r["ung_tgl"];
			$rdt[] = $b_view.$b_delete;
			$data[] = $rdt;
		}
		unset($r);
		$opt = array("data"=>$data);
	}

	// TINDAKAN: Menghapus unggahan
  if($_POST["action"] == "ung_hps")
	{
    $data=array(':nama'=>$_POST["ung_nama"],':npsn'=>$_SESSION["sek_npsn"]);
		$query = "
			DELETE FROM tbl_unggahan 
			WHERE ung_file = :nama
			AND ung_npsn = :npsn
		";
		$st = $connect->prepare($query);
		$st->execute($data);
		if($st->rowCount()==1)
		{
			$opt=array(
				"success" =>	true,
				"text"		=>	date("H:i:s").': Unggahan berkas telah dihapus secara permanen.'
			);
			unlink('../../storage/uploads/'.$_POST["ung_nama"]);
		}
		else
		{
			$opt=array(
				"error"   =>	true,
				"text"		=>	date("H:i:s").': Data tidak ditemukan atau permintaan cacat. Silakan ulangi permintaan Anda.'
			);
		}
	}
	echo json_encode($opt);
}