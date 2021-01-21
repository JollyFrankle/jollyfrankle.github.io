<?php
include('../admin/database_connection.php');
session_start();
$mm=sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'");
if($mm==1 && !isset($_SESSION["adm_id"])) : ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
<title>Situs Dalam Perawatan - <?=date("d/m/Y H:i:s");?></title>
<main class="d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div class="container">
        <div class="card card-body text-center bg-primary text-white">
            <h1 class="display-3">Mohon maaf.</h1>
            <p class="lead">Situs Sistem Presensi Daring sedang dalam perawatan.</p>
        </div>
    </div>
</main>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167603452-2"></script>
<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-167603452-2');</script>
<?die();endif;
if(isset($_SESSION["sek_id"]))
{header('location:index');}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <title>Buat Akun Sekolah <?php echo $title;?></title>
  <meta charset="utf-8">
  <link rel="dns-prefetch" href="https://stackpath.bootstrapcdn.com/">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com/">
  <link rel="icon" href="../storage/media/icon.png" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <noscript><meta http-equiv="refresh" content="0; url=no-js" /></noscript>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
</head>
<?if($mm==1 && isset($_SESSION["adm_id"])):?>
<nav class="navbar fixed-bottom bg-dark text-white small">
    <div class="container">Ini adalah tampilan situs apabila Anda menonaktifkan mode maintenance.</div>
</nav>
<?endif;?>
<body class="d-flex align-items-center" style="min-height:100vh;background:url(../storage/media/cover2.jpg) center center no-repeat fixed">
<div class="container">
  <div class="row">
    <div class="col-md-4 p-0 my-4" style="min-width:344px;margin:auto">
      <div class="card card-body">
        <div class="text-center mb-3">
          <h5 class="display-4 text-center border-bottom">Pendaftaran</h5>
          <h6 class="text-secondary text-center">AKUN SEKOLAH BARU</h6>
        </div>
        <?if(sql_value($connect, "SELECT val1 FROM options WHERE set_var='registrasi'")==1){?>
        <form method="post" id="form_pendaftaran" autocomplete="off">
          <div id="msg"><div class="alert alert-info mb-3 small"><b>PETUNJUK:</b> Email dan password yang telah ditetapkan akan digunakan untuk masuk akun sekolah. Sekolah hanya dapat didaftarkan sebanyak 1 (satu) kali, divalidasi melalui NPSN yang dimasukkan.</div></div>
          <div class="mb-3">
            <input type="text" class="form-control" id="sek_npsn" name="sek_npsn" pattern="\d*" maxlength="8" placeholder="NPSN"/>
            <div class="text-danger small" id="error_npsn"></div>
          </div>
          <div class="mb-3">
            <input type="email" class="form-control" id="sek_email" name="sek_email" placeholder="Alamat email/surel (untuk login)"/>
            <div id="error_email" class="text-danger small"></div>
          </div>
          <div class="form-row">
            <div class="col-md-6 mb-3">
              <input type="password" class="form-control" name="sek_password" id="sek_password" placeholder="Kata sandi"/>
            </div><div class="col-md-6 mb-3">
              <input type="password" class="form-control" name="pass_confirm" id="pass_confirm" placeholder="Konfirmasi"/>
            </div><div class="col-md-12 mb-3 text-danger small" id="error_sek_password"></div>
          </div>
          <div class="custom-control custom-checkbox mb-3">
            <input type="checkbox" id="persetujuan" class="custom-control-input" required/>
            <label for="persetujuan" class="custom-control-label text-justify">Saya menyatakan dengan sesungguhnya bahwa saya adalah penanggungjawab data GTK di sekolah.</label>
          </div>
          <div class="form-group text-center">
            <div class="g-recaptcha border rounded overflow-hidden" style="display:inline-block;width:302px;height:76px" data-sitekey="6Lf7b7gZAAAAAEKSaqaSEe-XZYYGvA9Vb1bqkWrs"></div>
            <div id="error_captcha" class="text-danger small"></div>
          </div>
          <input type="submit" class="btn btn-info btn-block" style="font-weight:500" name="action" id="buat_akun" value="DAFTARKAN AKUN"/>
          <a class="d-block small mt-2 text-center" href="login">Masuk akun</a>
        </form>
        <?}else{?>
        <h4 class="text-center">Mohon maaf.</h4>
        <div class="text-center">Pendaftaran akun baru untuk saat ini ditutup.<br>
        <a class="d-block small mt-2 text-center" href="login">Masuk akun</a></div>
        <?}?>
        <div class="discontinued border rounded" style="display:none">
          <h5 class="m-0 font-weight-bold p-2 border-bottom" id="apology"></h5>
          <p class="m-0 p-2" id="reason"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>.g-recaptcha>div:first-child{margin:-1px}</style>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
<script>
if (window.document.documentMode) {
  $('form').html('');
  $('.discontinued').fadeIn().addClass('text-danger');
  $('#apology').text('Mohon maaf!');
  $('#reason').text('Aplikasi ini tidak dapat diakses karena masalah kompatibilitas dan keamanan. Silakan gunakan browser lain seperti Google Chrome, Opera, Microsoft Edge, Safari, atau Mozila Firefox.');
}
$('form').on('submit', function(event){
	event.preventDefault();
  if($('#g-recaptcha-response').val()=='') $('#error_captcha').text('Silahkan centang reCAPTCHA.');
  else $('#error_captcha').text('');
  if($('#sek_email').val()=='') $('#error_email').text('Alamat email/surel tidak diisi.');
  else $('#error_email').text('');
  if($('#sek_npsn').val()=='') $('#error_npsn').text('NPSN belum diisi.');
  else $('#error_npsn').text('');
  if($('#sek_password').val().length < 8) $('#error_sek_password').text('Kata sandi minimal 8 karakter.');
  else $('#error_sek_password').text('');
  if($('#sek_password').val()!=$('#pass_confirm').val()) $('#error_sek_password').text('Kata sandi dan konfirmasinya tidak sama.');
  else $('#error_sek_password').text('');
  if($('#error_captcha,#error_email,#error_sek_password,#error_npsn').text()=='')
	$.ajax({
		url:"action/sek_daftar.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
			$('#buat_akun').val('MENGIRIM...').prop('disabled', true);
		},
		success:function(data)
		{
			if(data.success)
			{
				$('#msg').html(data.success);
				$('#form_pendaftaran')[0].reset();
				$('#error_captcha,#error_email,#error_sek_password,#error_npsn').text('');
				$('#buat_akun').val('MENUNGGU PERSETUJUAN').prop('disabled', true);
				$('input').prop('disabled',true);
			}
			if(data.error)
			{
			  $('#buat_akun').val('MOHON TUNGGU 5 DETIK');
        $('#buat_akun').delay(5000).queue(function(){$(this).val('DAFTARKAN AKUN').prop('disabled',false).dequeue()});
        $('#msg').html('<div class="alert alert-danger"><b>GALAT:</b> Terdapat kesalahan. Silahkan periksa ulang bagian yang ditunjukkan.</div>')
        $('#error_npsn').text(data.error_npsn);
        $('#error_email').text(data.error_email);
        $('#error_captcha').text(data.error_captcha);
			}
		},
    error:function(){
      alert("Server sedang sibuk. Silahkan coba ulang dalam beberapa saat.");
      $('#buat_akun').val('DAFTARKAN AKUN').prop('disabled', false);
    }
	})
});
</script>
<script src='https://www.google.com/recaptcha/api.js?hl=id' async defer></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167603452-2"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-167603452-2');</script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700&display=swap">
<style>body{font-family:Roboto,sans-serif}</style>