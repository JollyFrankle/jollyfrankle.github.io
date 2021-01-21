<?php 

include('../../admin/database_connection.php');
session_start();

if(empty($_POST) || (sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'") && !isset($_SESSION["adm_id"]))) {
    include('../../403.html');
    http_response_code(403);
    die();
}
    $error_email = '';
    $error_npsn = '';
    $error_captcha='';
    if(sql_value($connect, "SELECT count(sek_id) FROM tbl_sekolah WHERE sek_email=:email",array(':email'=>$_POST["sek_email"]))>0) {
  	    $error_email = "Alamat email/surel sudah pernah digunakan.";
  	    $error++;
  	}
  	if(sql_value($connect, "SELECT count(sek_id) FROM tbl_sekolah WHERE sek_npsn=:npsn",array(':npsn'=>$_POST["sek_npsn"]))>0) {
  	    $error_npsn = "NPSN sudah pernah terdaftar di sistem.";
  	    $error++;
  	}
  	if(sql_value($connect, "SELECT count(dt_id) FROM tbl_sekdb WHERE dt_npsn=:npsn",array(':npsn'=>$_POST["sek_npsn"]))==0) {
  	    $error_npsn = "NPSN tidak ditemukan di basis data.";
  	    $error++;
  	}
    if(!$_POST["g-recaptcha-response"]){
        $error_captcha = 'Silahkan centang reCAPTCHA.';
        $error++;
    }

if($error == 0) {
	$data = array(
		':sek_npsn'     =>	$_POST["sek_npsn"],
		':sek_email'    =>  $_POST["sek_email"],
		':sek_password' =>  password_hash($_POST["sek_password"], PASSWORD_DEFAULT),
		':sek_aktif'    =>  false
	);
	$query = "
	INSERT INTO tbl_sekolah 
	(sek_npsn, sek_email, sek_password, sek_aktif) 
	VALUES (:sek_npsn, :sek_email, :sek_password, :sek_aktif) 
	";
}
    if($error > 0)
		{
			$output = array(
				'error'				=>	true,
				'error_email'	    =>	$error_email,
				'error_npsn'        =>  $error_npsn,
				'error_captcha'     =>  $error_captcha
			);
		}
	elseif($error == 0){
	    $sk="6Lf7b7gZAAAAAP92NJfZloRw_SULYGin85H3eRey";
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($sk).'&response='.urlencode($_POST["g-recaptcha-response"]);
        $gre_r = json_decode(file_get_contents($url),true);
        if($gre_r["success"]) {
            $statement = $connect->prepare($query);
    	    if($statement->execute($data))
    	    {
    		    $output = array(
    		    	'success'		=>	'<div class="alert alert-primary"><b>INFO:</b> Pembuatan akun berhasil. Silahkan hubungi administrator untuk mengaktifkan akun Anda.</div>',
    	    	);
        	}
        } else {
            $output = array(
                'error'         =>  true,
                'error_captcha' =>  'Terjadi kesalahan tak terduga. Silahkan reload halaman.'
            );
        }
    }
echo json_encode($output);

?>