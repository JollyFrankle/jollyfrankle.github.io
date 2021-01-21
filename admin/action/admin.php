<?php 
include('../database_connection.php');
session_start();
// TAB ok

if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
	function level($x){
		if($x==1)return 'Admin Dinas';
		if($x==2)return 'Admin Wilayah';
		if($x==3)return 'Pengakses Presensi';
	}
	function html_list($list){
		return '<ul class="pl-3 small mb-0">'.$list.'</ul>';
	}
	// TINDAKAN: Dapatkan daftar Admin
	if($_POST["action"] == "fetch" && ($_SESSION["adm_id"]==0 || $_SESSION["adm_id"]==1))
	{
		$query = "
			SELECT * FROM tbl_admin
			WHERE adm_level!=0
		";
		$st = $connect->prepare($query);
		$st->execute();
		$result = $st->fetchAll();
		$data = array();
		foreach($result as $row)
		{
			$rdt = array();
			$p_identitas = '<div style="font-weight:500">'.$row["adm_nama"].'</div>'.'<div class="small">Kontak: '.$row["adm_email"].'</div>';
			$b_edit = '<button class="edit_akun btn btn-sm btn-primary" data-admid="'.$row["adm_id"].'">Ubah</button>';
			$b_otp = '<button class="otp_adm btn btn-sm btn-success" data-admid="'.$row["adm_id"].'" data-admnama="'.$row["adm_nama"].'">Generate OTP</button>';
			$p_dati2=''; $p_jp='';
			foreach(json_decode($row["adm_dati2"],true)["dt2"] as $ad2)$p_dati2.='<li>'.dati2($ad2).'</li>';
			foreach(json_decode($row["adm_dati2"],true)["jp"] as $ad2)$p_jp.='<li>'.$ad2.'</li>';
			$t_pj=''; $t_wil='';
			if($row["adm_level"]==2)
			{
				$t_pj ='<div class="small">Penanggungjawab:</div>';
				$t_wil='<div class="small">Wilayah:</div>';
			}
			$rdt[] = $p_identitas;
			$rdt[] = '<h6 class="mb-0">'.level($row["adm_level"]).'</h6>'.$t_pj.html_list($p_jp).$t_wil.html_list($p_dati2);
			$rdt[] = $row["adm_lastmod"];
			$rdt[] = $b_edit.$b_otp;
			$data[] = $rdt;
		}
		$opt = array("data"=>$data);
	}
	
	// TINDAKAN: Dapatkan info akun admin
	if($_POST["action"] == "fetch_accinfo" && ($_SESSION["adm_id"]==0 || $_SESSION["adm_id"]==1))
	{
		$query = "
			SELECT adm_nama, adm_email, adm_level, adm_dati2 FROM tbl_admin
			WHERE adm_id = :id
		";
		$st = $connect->prepare($query);
		if($st->execute(array(':id'=>$_POST["adm_id"])))
		{
			$res = $st->fetchAll();
			foreach($res as $r)
			{
				$opt=array(
					"adm_nama"	=> $r["adm_nama"],
					"adm_email" => $r["adm_email"],
					"adm_level" => $r["adm_level"],
					"adm_dati2" => $r["adm_dati2"]
				);
			}
		}
	}
	
	// TINDAKAN: Mengubah atau menambahkan admin baru
	if(($_POST["action"] == "Ubah" || $_POST["action"] == "Tambah") && ($_SESSION["adm_id"]==0 || $_SESSION["adm_id"]==1))
	{
		$error_email='';
		// Validasi: Admin Level hanya 1, 2, dan 3 [keterangan di dokumentasi]
		if($_POST["adm_level"]<1 || $_POST["adm_level"]>3) $error++;

		// Validasi: Tidak boleh menyunting info akun webmaster (ID: 1)
		if($_POST["adm_id"]==1) $error++;

		// Validasi: Apabila bukan seorang admin wilayah, maka Dati 2 adalah tidak ada.
		if(empty($_POST["adm_dati2"]["dt2"]))$_POST["adm_dati2"]["dt2"]=array();
		if(empty($_POST["adm_dati2"]["jp"])) $_POST["adm_dati2"]["jp"]=array();
		if($_POST["adm_level"]!=2) $o_dt2_jp=NULL; else $o_dt2_jp=json_encode($_POST["adm_dati2"]);
		
		// Validasi: Apabila merupakan admin wilayah, Dati2 dan JP harus ada.
		if($_POST["adm_level"]==2 && (empty($_POST["adm_dati2"]["jp"]) || empty($_POST["adm_dati2"]["dt2"])))
		{$error_lvl2 = "Silakan memilih wilayah dan jenjang pendidikan admin ini.";$error++;}

		$data = array(
			':adm_nama' =>  $_POST["adm_nama"],
			':adm_email'=>	$_POST["adm_email"],
			':adm_level'=>	$_POST["adm_level"],
			':adm_dati2'=>	$o_dt2_jp,
		);
		// TINDAKAN: Mengubah admin
		if($_POST["action"] == "Ubah")
		{
			if(sql_value($connect,"SELECT count(adm_id) FROM tbl_admin WHERE adm_email=:email AND adm_id!=:id",array(':email'=>$_POST["adm_email"],':id'=>$_POST["adm_id"]))>0)
			{
				$error_email = 'Email ini sudah pernah digunakan.';
				$error++;
			}
			if($error==0)
			{
				$data[":adm_id"]=$_POST["adm_id"];
				$query="
				UPDATE tbl_admin
				SET adm_nama = :adm_nama,
				adm_email = :adm_email,
				adm_level = :adm_level,
				adm_dati2 = :adm_dati2
				WHERE adm_id = :adm_id
				";
			$st = $connect->prepare($query);
			if($st->execute($data))
				$opt=array(
					'success' =>	true,
					"text"		=>	date("H:i:s").': Data akun <b>'.$_POST["adm_nama"].'</b> berhasil diubah.'
				);
			}
		}
		// TINDAKAN: Menambah admin baru
		if($_POST["action"] == "Tambah")
		{
			if(sql_value($connect,"SELECT count(adm_id) FROM tbl_admin WHERE adm_email=:email",array(':email'=>$_POST["adm_email"]))>0)
			{
				$error_email = 'Email ini sudah pernah digunakan.';
				$error++;
			}
			if($error==0)
			{
				$data[":adm_password"]=password_hash('gtkprovntt', PASSWORD_DEFAULT);
				$data[":adm_otp"]=md5($_POST["adm_otp"]);
				$query="
				INSERT INTO tbl_admin
				(adm_nama, adm_email, adm_level, adm_password, adm_dati2, adm_otp)
				VALUES (:adm_nama, :adm_email, :adm_level, :adm_password, :adm_dati2, :adm_otp)
				";
				$st = $connect->prepare($query);
				if($st->execute($data)) $opt=array('success'=>date("H:i:s").': Data akun '.$_POST["adm_nama"].' berhasil ditambah.');
			}
		}
		if($error>0)
			$opt=array(
				'error'       =>	true,
				'error_email' =>	$error_email,
				"error_lvl2"  =>  $error_lvl2
			);
	}
	
	// TINDAKAN: Generate OTP untuk admin
	if($_POST["action"]=='generateOTP' && ($_SESSION["adm_id"]==0 || $_SESSION["adm_id"]==1))
	{
		$kodeOTP = rand(100,999).'-'.rand(100,999);
		sql_dbop($connect, "UPDATE tbl_admin SET adm_otp='".md5($kodeOTP)."' WHERE adm_id=:id",array(':id'=>$_POST["adm_id"]));
		$opt=array(
			"success" =>	true,
			"OTP"		 =>	$kodeOTP
		);
	}
	// TINDAKAN: Mengubah pengaturan akun masing-masing (pada halaman admin/akun.php)
	if($_POST["action"] == "ubah_akun")
	{
		if(sql_value($connect, "SELECT count(adm_id) FROM tbl_admin WHERE adm_email=:email AND adm_id!=:id",array(':email'=>$_POST["adm_email"],':id'=>$_SESSION["adm_id"])) != 0) {
			$error++;
			$e_email='Alamat surel ini sudah pernah digunakan oleh akun lain!';
		}
		if(strlen($_POST["adm_password"])<8) {
			$error++;
			$e_password='Kata sandi harus berisikan minimal 8 karakter.';
		}
		if(!$_POST["adm_nama"]) {
			$error++;
			$e_nama='Nama tidak boleh dibiarkan kosong!';
		}
		if($error==0)
		{
			$data = array(
				':adm_email'		=>	$_POST["adm_email"],
				':adm_password' =>	password_hash($_POST["adm_password"], PASSWORD_DEFAULT),
				':adm_id'				=>	$_SESSION["adm_id"],
				':adm_nama'		 =>	$_POST["adm_nama"]
				);
			$query = "
				UPDATE tbl_admin 
				SET adm_email = :adm_email,
				adm_password = :adm_password,
				adm_nama = :adm_nama
				WHERE adm_id = :adm_id
			";
			$st = $connect->prepare($query);
			if($st->execute($data))
			{
				$opt = array(
					"success"	=>	true,
					"text"		=>	date("H:i:s").': Data akun telah diperbarui. Silakan refresh halaman untuk melihat perubahan.'
				);
				$_SESSION["adm_email"] = $_POST["adm_email"];
				$_SESSION["adm_nama"] = $_POST["adm_nama"];
			}
		}
		else
		{
			$opt=array(
				'error'				 =>	true,
				'error_email'	 =>	$e_email,
				'error_password'=>	$e_password,
				'error_nama'		=>	$e_nama
			);
		}
	}
	echo json_encode($opt);
}