<?php

include('../../admin/database_connection.php');
session_start();

$error_sek_email = '';
$error_sek_password = '';
$error_captcha = '';
$error_top = '';
$error = 0;

if(empty($_POST) || (sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'") && !isset($_SESSION["adm_id"]))) {
    include('../../403.html');
    http_response_code(403);
    die();
}
if(!$_POST["sek_email"])
{
	$error_sek_email = 'Kredensial tidak diisi';
	$error++;
}

if(!$_POST["sek_password"])
{	
	$error_sek_password = 'Kata sandi tidak diisi';
	$error++;
}

if(!$_POST["g-recaptcha-response"]){
    $error_captcha = 'Silahkan centang reCAPTCHA.';
    $error++;
} 

if($error == 0)
{
	$query = "
	SELECT sek_id,sek_email,sek_password,sek_npsn,sek_otp,sek_aktif,dt_nama,dt_dati2 FROM tbl_sekolah LEFT JOIN tbl_sekdb ON sek_npsn=dt_npsn 
	WHERE sek_email = :email
	";
	$statement = $connect->prepare($query);
	if($statement->execute(array(':email'=>$_POST["sek_email"])))
	{
		if($statement->rowCount()>0)
		{
			$res = $statement->fetchAll();
			foreach($res as $row)
			{  
			    if($row["sek_aktif"]==1)
			    {
        			if(password_verify($_POST["sek_password"], $row["sek_password"]) || md5($_POST["sek_password"])==$row["sek_otp"])
        			{
        				session_regenerate_id();
        				$_SESSION["sek_id"] = $row["sek_id"];
        				$_SESSION["sek_email"] = $row["sek_email"];
        				$_SESSION["sek_npsn"] = $row["sek_npsn"];
                        $_SESSION["sek_nama"] = $row["dt_nama"];
                        $_SESSION["dati2_clean"] = dati2($row["dt_dati2"]);
                        if(md5($_POST["sek_password"])==$row["sek_otp"])
                        $redirect='akun?err=otp'; else $redirect='index';
                        sql_dbop($connect, "UPDATE tbl_sekolah SET sek_otp = NULL WHERE sek_id = '".$row["sek_id"]."'");
        			}
        			else
        			{
        				$error_sek_password = "Kata sandi/kode OTP salah";
        				$error++;
        			}
			    } else {
			        $error_top = '<div class="alert alert-danger"><b>GALAT:</b> Akun sekolah ini belum diaktifkan. Silahkan hubungi admin.</div>';
			        $error++;
			    }
			}
		}
		else
		{
			$error_sek_email = "Kredensial tidak ditemukan.";
			$error++;
		}
	}
}

if($error > 0)
{
	$output = array(
		'error'		        	=>	true,
		'error_sek_email'	    =>	$error_sek_email,
		'error_sek_password'    =>	$error_sek_password,
		'error_captcha'         =>  $error_captcha,
		'error_top'             =>  $error_top
	);
}
elseif($error==0)
{
    $secretKey = '6Lf7b7gZAAAAAP92NJfZloRw_SULYGin85H3eRey';
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretKey).'&response='.urlencode($_POST["g-recaptcha-response"]);
    $gre_r = json_decode(file_get_contents($url),true);
    if($gre_r["success"]) {
        $output = array(
    		'success'		=>	true,
    		'redirect'      =>  $redirect
    	);
    } else {
        unset($_SESSION["sek_id"]);
        unset($_SESSION["sek_nama"]);
        unset($_SESSION["sek_email"]);
        unset($_SESSION["sek_npsn"]);
        unset($_SESSION["dati2_clean"]);
        $output = array(
    		'error'		=>	true,
    		'error_sek_email'	=>	$error_sek_email,
    		'error_sek_password'=>	$error_sek_password,
    		'error_captcha'     =>  $error_captcha,
    		'error_top'         =>  '<div class="alert alert-danger"><b>GALAT:</b> Terdapat kesalahan tak terduga. Silahkan ulangi permintaan Anda.</div>'
    	);
    }
}
echo json_encode($output);
?>