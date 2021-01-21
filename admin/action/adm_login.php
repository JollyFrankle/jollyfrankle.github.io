<?php

include('../database_connection.php');
session_start();

$error_adm_email = '';
$error_adm_password = '';
$error_captcha = '';
$error_top = '';
$error = 0;

function adm_level_text($l) {
    if($l==0) return 'Super Admin';
    if($l==1) return 'Admin Dinas';
    if($l==2) return 'Pengelola Wilayah';
    if($l==3) return 'Bagian Keuangan';
}

if(empty($_POST)) {
    include('../403.html');
    http_response_code(403);
    die();
}
if(!$_POST["adm_email"])
{
	$error_adm_email = 'Kredensial tidak diisi';
	$error++;
}

if(!$_POST["adm_password"])
{	
	$error_adm_password = 'Kata sandi tidak diisi';
	$error++;
}

if(!$_POST["g-recaptcha-response"]){
    $error_captcha = 'Silahkan centang reCAPTCHA.';
    $error++;
} 

if($error == 0)
{
	$query = "
	SELECT * FROM tbl_admin 
	WHERE adm_email = :email
	";
	$statement = $connect->prepare($query);
	if($statement->execute(array(':email'=>$_POST["adm_email"])))
	{
		if($statement->rowCount()==1)
		{
			$res = $statement->fetchAll();
			foreach($res as $row)
			{  
        			if(password_verify($_POST["adm_password"], $row["adm_password"]) || md5($_POST["adm_password"])==$row["adm_otp"])
        			{
        				session_regenerate_id();
        				if($row["adm_level"]==2){
            				$kab='<div>Penanggungjawab:</div><ul class="pl-3 pb-1 mb-1 border-bottom">';
            				foreach(json_decode($row["adm_dati2"],true)["jp"] as $ad2)$kab.='<li>'.$ad2.'</li>';
            				$kab.='</ul><div>Wilayah:</div><ul class="pl-3 pb-1 mb-1 border-bottom">';
            				foreach(json_decode($row["adm_dati2"],true)["dt2"] as $ad2)$kab.='<li>'.dati2($ad2).'</li>';
            				$kab.='</ul>';
        				} else $kab='';
        				$_SESSION["adm_id"] = $row["adm_id"];
        				$_SESSION["adm_nama"] = $row["adm_nama"];
        				$_SESSION["adm_email"] = $row["adm_email"];
        				$_SESSION["adm_level"] = $row["adm_level"];
        				$_SESSION["adm_dati2"] = json_decode($row["adm_dati2"],true)["dt2"];
        				$_SESSION["adm_jp"] = json_decode($row["adm_dati2"],true)["jp"];
                        $_SESSION["adm_dati2c"] = adm_level_text($row["adm_level"]).$kab;
                        if(md5($_POST["adm_password"])==$row["adm_otp"])
                        $redirect='akun?err=otp'; else $redirect='index';
                        sql_dbop($connect, "UPDATE tbl_admin SET adm_otp = NULL WHERE adm_id = '".$row["adm_id"]."'");
        			}
        			else
        			{
        				$error_adm_password = "Kata sandi salah";
        				$error++;
        			}
			}
		}
		else
		{
			$error_adm_email = "Kredensial tidak ditemukan.";
			$error++;
		}
	}
}

if($error > 0)
{
	$output = array(
		'error'		        	=>	true,
		'error_adm_email'	    =>	$error_adm_email,
		'error_adm_password'    =>	$error_adm_password,
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
    		'success'	=>	true,
    		'redirect'  =>  $redirect
    	);
    } else {
        unset($_SESSION["adm_id"]);
        unset($_SESSION["adm_nama"]);
        unset($_SESSION["adm_email"]);
        unset($_SESSION["adm_level"]);
        unset($_SESSION["adm_dati2"]);
        unset($_SESSION["adm_dati2c"]);
        unset($_SESSION["adm_jp"]);
        $output = array(
    		'error'		=>	true,
    		'error_adm_email'	=>	$error_adm_email,
    		'error_adm_password'=>	$error_adm_password,
    		'error_captcha'     =>  $error_captcha,
    		'error_top'         =>  '<div class="alert alert-danger"><b>GALAT:</b> Terdapat kesalahan tak terduga. Silahkan ulangi permintaan Anda.</div>'
    	);
    }
}

echo json_encode($output);

?>