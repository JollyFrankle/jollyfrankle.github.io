<?php
session_start();
if(!isset($_SESSION["sek_id"])){
		include('login.php');
		die();
}
include('../admin/database_connection.php');
if(sql_value($connect, "SELECT sek_aktif AS r FROM tbl_sekolah WHERE sek_id='".$_SESSION["sek_id"]."'")==0) {
		header("location:login.php?action=logout");
}
$mm=sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'");
if($mm==1 && !isset($_SESSION["adm_id"])){?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
<title>Situs Dalam Perawatan - <?=date("d/m/Y H:i:s");?></title>
<main class="d-flex align-items-center justify-content-center bg-light" style="min-height:100vh">
	<div class="container">
		<div class="card card-body text-center bg-primary text-white">
			<h1 class="display-3">Mohon maaf.</h1>
			<p class="lead">Situs Sistem Presensi Daring sedang dalam perawatan.</p>
		</div>
	</div>
</main>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167603452-2"></script>
<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-167603452-2');</script>
<?die();}?>
<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
	<title><?=$_SESSION["sek_nama"].' '.$title;?></title>
	<meta charset="utf-8">
	<meta name="theme-color" content="#343a40">
	<meta name="description" content="Sistem Presensi Daring untuk seluruh sekolah di Nusa Tenggara Timur">
	<base href="https://<?=$_SESSION["url"];?>sekolah/">
	<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com/">
	<link rel="icon" href="../storage/media/icon.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<noscript><meta http-equiv="refresh" content="0; url=../no-js.html"/></noscript>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css" media="none" onload="if(media!='all')media='all'">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" onload="if(media!='all')media='all'">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" onload="if(media!='all')media='all'">
<style>
body{font-family:Roboto,sans-serif}
#console>.alert>.close{padding:.25rem;width:2rem}.sidebar{position:fixed;top:3em;bottom:0;left:0;z-index:100;overflow-y:auto;user-select:none}.navbar-brand{padding-top:.75rem;padding-bottom:.75rem;font-size:1rem;background-color:rgba(0,0,0,.25)}.nav-item{width:100%}.sidebar .nav-link{color:#343a40;font-weight:500;border-right:3px solid transparent;padding:.4rem 1rem;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;cursor:pointer}.sidebar .nav-link:hover{color:#007bff}.sidebar .nav-link.active{color:#007bff;border-color:#007bff;background:#fff;pointer-events:none}.nav-link svg{width:1rem;margin-right:.25rem;padding-bottom:4px}.navbar .navbar-toggler{top:.25rem;right:1rem}@media screen and (max-width:767px){.sidebar .nav-link{padding:.6rem 1rem}}#console>.alert{animation:shake .75s cubic-bezier(.36,.07,.19,.97);margin:0;width:100%;max-height:3em;overflow-y:auto;padding:.5em 1em}@keyframes shake{10%,90%{transform:translateX(-1px)}20%,80%{transform:translateX(3px)}30%,50%,70%{transform:translateX(-6px)}40%,60%{transform:translateX(6px)}}.loader{position:relative;margin:0 auto;width:80px}.circular{animation:sl_rotate 1.2s linear infinite;height:100%;transform-origin:center center;width:100%;margin:auto}.path{stroke-dasharray:1,200;stroke-dashoffset:0;animation:sl_dash 1.2s ease-in-out infinite;stroke-linecap:round}@keyframes sl_rotate{100%{transform:rotate(360deg)}}@keyframes sl_dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.pt-48{padding-top:48px}@media print{#sidebarMenu{display:none!important}.navbar.fixed-top{display:flex!important}.print-full{flex:0 0 100%;max-width:100%}}
</style>
</head>
<body>
<?if($mm==1 && isset($_SESSION["adm_id"])):?>
<nav class="navbar fixed-bottom bg-dark text-white small">
		<div class="container">Ini adalah tampilan situs apabila Anda menonaktifkan mode maintenance.</div>
</nav>
<?endif;?>
<div class="pt-48"></div>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
	<a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3 d-flex" style="line-height:1.1" href="index">
		<img src="../storage/media/icon.png" height=30 style="margin:-3px 0" class="mr-1 d-block" alt="Logo">
		<div style="margin:-3px 0" class="text-truncate d-flex align-items-center">
			<h1 class="mb-0 h3">SISTER</h1>
		</div>
	</a>
	<button class="navbar-toggler position-absolute d-md-none" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Navigasi">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div id="console" class="w-100 d-flex align-items-center"></div>
	<a class="d-md-block d-none btn btn-danger btn-sm m-2 text-nowrap" href="login?action=logout">
<svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1zm-7-1a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7zm0-3a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/></svg>
			Log Keluar
	</a>
</nav>

<div class="justify-content-center align-items-center position-fixed" style="width:100vw;height:100vh;background:rgba(0,0,0,.5);top:0;left:0;display:flex;z-index:1080;user-select:none" id="spinner_loader">
		<div class="text-center text-white font-weight-bold">
	<div class="loader">
		<svg class="circular" viewBox="25 25 50 50" >
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke="#fff" stroke-width="3" stroke-miterlimit="10"/>
		</svg>
	</div>
		Memuat...
		</div>
</div>

<div class="container-fluid">
	<div class="row">
		<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse p-0" style="">
			<div class="sidebar-sticky pt-3">
				<ul class="nav flex-column">
					<li class="nav-item px-3 pb-3 border-bottom mb-3">
						<p class="font-weight-bold m-0 border-bottom"><?php echo $_SESSION["sek_nama"];?></p>
						<p class="m-0 small">NPSN <?php echo $_SESSION["sek_npsn"];?></p>
						<p class="m-0 small border-bottom"><?php echo $_SESSION["dati2_clean"];?></p>
						<p class="small text-truncate">Email: <?php echo $_SESSION["sek_email"];?></p>
						<div class="btn-group w-100" role="group" aria-label="Tindakan Akun">
							<a href="login?action=logout" class="btn btn-outline-danger btn-sm">Log Keluar</a>
							<a href="akun" class="btn btn-outline-primary btn-sm navmenu">Akun</a>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="index">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M15 2H1v12h14V2zM1 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H1z"/><path fill-rule="evenodd" d="M7.5 14V2h1v12h-1zm0-8H1V5h6.5v1zm7.5 5H8.5v-1H15v1z"/></svg>
							Dashboard
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="daftar-gtk">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9H3V8h10v1h-3v2h3v1h-3v2H9v-2H6v2H5v-2H3v-1h2V9zm1 0v2h3V9H6z"/><path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/><path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/></svg>
							Laporan Bulanan
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="daftar-hadir">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/></svg>
							Daftar Hadir
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="data-sekolah">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1H2a2 2 0 0 1 2-2z"/><path d="M2 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H2zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H2zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H2z"/><path fill-rule="evenodd" d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/></svg>
							Data Sekolah
						</a>
					</li>
				</ul>

				<h6 class="px-3 mt-3 mb-1 text-muted">Unduh dan Unggah</h6>
				<ul class="nav flex-column mb-2">
					<li class="nav-item">
						<span class="nav-link" href="rekap-dh" data-toggle="collapse" data-target="#rekapMenu" aria-controls="rekapMenu">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
							Rekap Daftar Hadir
						</span>
						<div id="rekapMenu" style="height:0;overflow:hidden;padding-left:2.5em" class="bg-white">
								<a href="rekap-dh?b=<?php echo date(m).'&t='.date(Y);?>">Bulan ini</a><br>
								<a href="rekap-dh?b=<?php echo date(m, strtotime("-1 month")).'&t='.date(Y);?>">Bulan lalu</a>
						</div>
					</li>
					<li class="nav-item">
						<a href="unggahan" class="nav-link">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/><path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
							Pusat Unggahan
						</a>
					</li>
				</ul>
				
				<h6 class="px-3 mt-3 mb-1 text-muted">Info Aplikasi</h6>
				<ul class="nav flex-column mb-2">
					<li class="nav-item">
						<a class="nav-link disabled">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M5.25 6.033h1.32c0-.781.458-1.384 1.36-1.384.685 0 1.313.343 1.313 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.007.463h1.307v-.355c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.326 0-2.786.647-2.754 2.533zm1.562 5.516c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/></svg>
							Panduan
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="version">
<svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/> <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
	<circle cx="8" cy="4.5" r="1"/></svg>
							Riwayat Versi
						</a>
					</li>
				</ul>

				<div class="p-3 small text-muted mt-3 border-top" align="center">
					© Jolly Frankle<br>
					2020-2021
				</div>
			</div>
		</nav>
		<main class="print-full col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4" id="content">