<?php
include('../admin/database_connection.php');
session_start();
$_SESSION["url"]=preg_replace('/sekolah[\/\w\W]+/', '', $_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
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
if($_GET["action"]=='logout' && isset($_SESSION["sek_id"])) {
    unset($_SESSION["sek_id"]);
    unset($_SESSION["sek_nama"]);
    unset($_SESSION["sek_email"]);
    unset($_SESSION["sek_npsn"]);
    unset($_SESSION["dati2_clean"]);
} elseif(isset($_SESSION["sek_id"]))
{header('location:index');}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <title>Masuk <?php echo $title;?></title>
  <meta charset="utf-8">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com/">
  <link rel="icon" href="../storage/media/icon.png" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
<noscript class="col-md-4 m-auto card card-body">
    <h2 class="border-bottom">Mohon maaf!</h2>
    <p class="m-0">JavaScript Anda tidak diaktifkan. Silakan diaktifkan, kemudian reload halaman ini.</p>
</noscript>
    <div class="col-md-4 p-0" id="main_form" style="min-width:344px;margin-left:auto;display:none">
      <div class="card">
        <div class="card-body">
          <h5 class="display-4 text-center border-bottom">Masuk Akun</h5>
          <h6 class="text-secondary text-center">BAGI SEKOLAH</h6>
          <form method="post" id="login_sek">
            <div id="error_top" class="mb-3 small">
                <div class="alert alert-info"><b>PETUNJUK:</b> Isikan alamat surel, kata sandi, serta centang reCAPTCHA.</div>
            </div>
            <div class="input-group">
                <label for="sek_email" class="sr-only">Alamat email</label>
                <label class="input-group-prepend mb-0" for="sek_email">
                  <div class="input-group-text">
<svg style="margin:-.25em" width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-at" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"/>
</svg>
                  </div>
                </label>
              <input type="email" name="sek_email" id="sek_email" class="form-control" placeholder="Alamat email/surel" />
            </div>
            <div id="error_sek_email" class="text-danger small mb-3 text-center"></div>
            <div class="input-group">
                <label for="sek_password" class="sr-only">Kata sandi</label>
                <label class="input-group-prepend mb-0" for="sek_password">
                  <div class="input-group-text">
<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-asterisk" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
</svg>
                  </div>
                </label>
              <input type="password" name="sek_password" id="sek_password" class="form-control" placeholder="Kata sandi/OTP"/>
            </div>
            <div id="error_sek_password" class="text-danger small text-center"></div>
            <div id="pwdinfo" class="text-primary small mb-3 text-justify"></div>
            <div class="form-group text-center">
                <div class="g-recaptcha border rounded overflow-hidden" style="display:inline-block;width:302px;height:76px" data-sitekey="6Lf7b7gZAAAAAEKSaqaSEe-XZYYGvA9Vb1bqkWrs"></div>
                <div id="error_captcha" class="text-danger small"></div>
            </div>
            <div class="text-center">
                <input type="submit" id="kirim_btn" class="btn btn-primary btn-block" style="font-weight:500" value="MASUK" />
                <a class="d-block small my-2" href="daftar">Daftarkan Akun Sekolah</a>
                <hr class="my-1"/>
                <a href="../admin/login" class="badge badge-secondary">Login Admin</a>
                <div class="text-muted small">Lupa kata sandi? Silakan hubungi admin dinas untuk meminta kode OTP (one-time pin).</div>
            </div>
          </form>
          <div class="discontinued border rounded" style="display:none">
              <h5 class="m-0 font-weight-bold p-2 border-bottom" id="apology"></h5>
              <p class="m-0 p-2" id="reason"></p>
          </div>
        </div>
      </div>
    </div>
    </div>
</div>

</body>
</html>

<style>.input-group-text {border-radius: .25rem 0 0 .25rem !important;}.g-recaptcha>div:first-child{margin:-1px}</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
<script>
if (window.document.documentMode) {
  $('#login_sek').html('');
  $('.discontinued').fadeIn().addClass('text-danger');
  $('#apology').text('Mohon maaf!')
  $('#reason').text('Aplikasi ini tidak dapat diakses karena masalah kompatibilitas dan keamanan. Silakan gunakan browser lain seperti Google Chrome, Opera, Microsoft Edge, Safari, atau Mozila Firefox.')
}
$('#main_form').show();
$('#sek_password').on("input",function(){
    if(/^\d{3}-\d{3}$/.test($(this).val()))$('#pwdinfo').text('Tampaknya Anda berusaha masuk menggunakan OTP. OTP hanya dapat digunakan sebanyak 1 (satu) kali. Apabila Anda berhasil masuk, Anda perlu segera memperbarui kata sandi.'); else $('#pwdinfo').text('');
});
$(document).ready(function(){
  $('#login_sek').on('submit', function(event){
    event.preventDefault();
    $('#error_captcha, #error_sek_email, #error_sek_password').text('')
    if(($('#g-recaptcha-response').val()=='') || ($('#sek_email').val()=='') || ($('#sek_password').val()=='')) 
    {
        if($('#g-recaptcha-response').val()=='')
            {$('#error_captcha').text('Silakan centang reCAPTCHA.');}
        if($('#sek_email').val()=='')
            {$('#error_sek_email').text('Kredensial tidak diisi.');}
        if($('#sek_password').val()=='')
            {$('#error_sek_password').text('Kata sandi tidak diisi.');}
    } else {
    $.ajax({
      url:"action/sek_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#kirim_btn').prop('disabled', true);
      },
      success:function(x)
      {
        if(x.success)
        {
          location.href = x.redirect;
          $('#kirim_btn').val('MEMUAT HALAMAN...').prop('disabled', true);
          $('#login_sek input').prop('disabled', true);
        }
        if(x.error)
        {
          $('#kirim_btn').prop('disabled', false);
          $('#error_sek_email, #error_sek_password, #error_captcha').text('');
          if(x.error_top) $('#error_top').html(x.error_top);
          else $('#error_top').html('<div class="alert alert-info"><b>PETUNJUK:</b> Isikan alamat surel, kata sandi, serta centang reCAPTCHA.</div>');
          $('#error_sek_email').text(x.error_sek_email);
          $('#error_sek_password').text(x.error_sek_password);
          $('#error_captcha').text(x.error_captcha);
        }
      },
      error:function(){
          alert("Server sedang sibuk. Silakan coba ulang dalam beberapa saat.");
          $('#kirim_btn').val('MASUK').prop('disabled', false);
      }
    });
    }
  });
});
</script>
<script src='https://www.google.com/recaptcha/api.js?hl=id' async defer></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167603452-2"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-167603452-2');</script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700&display=swap">
<style>body{font-family:Roboto}</style>