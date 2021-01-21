<?include'header.php';?>
<style>.btn-right{display:flex;height:2em;width:2em;padding:0;border:0}.btn-right::before{content:url("data:image/svg+xml,%3Csvg width='2em' height='2em' viewBox='0 0 16 16' fill='%23212529' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z'/%3E%3Cpath fill-rule='evenodd' d='M4 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5A.5.5 0 0 0 4 8z'/%3E%3C/svg%3E")}.btn-right:hover::before,.btn-right:focus::before{content:url("data:image/svg+xml,%3Csvg width='2em' height='2em' viewBox='0 0 16 16' fill='%23007bff' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 8.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z'/%3E%3C/svg%3E")}.dsb_menu{display:flex;align-items:center;justify-content:space-between;padding:1rem}</style>
<h2 class="border-bottom pb-2">Dashboard</h2>
<div class="alert alert-info">
	<b>INFORMASI:</b> Data usia GTK diperbarui setiap pukul 00.00 UTC (08.00 WITA). Untuk melihat daftar sekolah yang sudah memiliki akun di SISTER, silakan kunjungi <a class="alert-link" href="daftar-sekolah">daftar sekolah terdaftar</a>.
</div>
<h4 class="border-bottom pb-1">Jumlah GTK berdasarkan usia</h4>
<canvas id="ChartUsiaGTK" width=400 height=200></canvas>
<div class="row mt-3">
	<div class="col-md-6 mb-3">
		<div class="card h-100">
			<div class="dsb_menu">
				<div>
					<h5 class="m-0">Daftar Sekolah Terdaftar</h5>
					<p class="text-muted m-0">Sekolah yang sudah memiliki akun di SISTER.</p>
				</div>
				<a href="daftar-sekolah" class="btn btn-right"></a>
			</div>
			<div class="dsb_menu border-top">
				<div>
					<h5 class="m-0">Daftar Sekolah di Dapodik</h5>
					<p class="text-muted m-0">Daftar seluruh sekolah di NTT (data Dapodik).</p>
				</div>
				<a href="database-sekolah" class="btn btn-right"></a>
			</div>
			<div class="dsb_menu border-top">
				<div>
					<h5 class="m-0">Pencarian GTK</h5>
					<p class="text-muted m-0">Pencarian GTK berdasarkan nama, NIP, NUPTK, kualifikasi, dan mata pelajaran.</p>
				</div>
				<a href="gtk_search" class="btn btn-right"></a>
			</div>
			<div class="dsb_menu border-top">
				<div>
					<h5 class="m-0">Daftar Tunggu PAK</h5>
					<p class="text-muted m-0">Formulir PAK yang Anda simpan untuk disunting/digunakan di kemudian hari.</p>
				</div>
				<a href="pak-list" class="btn btn-right"></a>
			</div>
			<div class="dsb_menu border-top border-bottom mb-2">
				<div>
					<h5 class="m-0">Daftar Unggahan Sekolah</h5>
					<p class="text-muted m-0">Daftar unggahan berkas oleh setiap sekolah.</p>
				</div>
				<a href="unggahan" class="btn btn-right"></a>
			</div>

			<div class="dsb_menu border-top">
				<div>
					<h5 class="m-0">Rekap Daftar Hadir</h5>
					<p class="text-muted m-0">Rekap bulanan daftar hadir GTK</p>
				</div>
				<a href="rekap-lb" class="btn btn-right"></a>
			</div>
			<div class="dsb_menu border-top border-bottom mb-2">
				<div>
					<h5 class="m-0">Pusat Unggahan</h5>
					<p class="text-muted m-0">Unggah berkas yang dibutuhkan</p>
				</div>
				<a href="unggahan" class="btn btn-right"></a>
			</div>
		
			<div class="dsb_menu border-top">
				<div>
					<h5 class="m-0">Panduan Penggunaan</h5>
					<p class="text-muted m-0">Tampilkan cara menggunakan aplikasi</p>
				</div>
				<a href="" class="no_sl btn btn-right"></a>
			</div>
			<div class="dsb_menu border-top">
				<div>
					<h5 class="m-0">Sister DevBlog</h5>
					<p class="text-muted m-0">Berbagai perubahan aplikasi, serta ucapan terima kasih</p>
				</div>
				<a href="version" class="btn btn-right"></a>
			</div>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<canvas id="ChartPNS_nPNS" width="400" height="200"></canvas>
		<div class="text-center small mb-3">Perbandingan PNS dan Non-PNS</div>
		<canvas id="ChartL_P" width="400" height="200"></canvas>
		<div class="text-center small mb-3">Perbandingan Laki-laki dan Perempuan</div>
	</div>
</div>

<h4 class="border-bottom pb-1">Prototype</h4>
<div class="alert alert-info">
	<b>INFORMASI:</b> Komponen-komponen di bawah ini masih dalam pengembangan tahap awal-akhir, dan belum dijamin berjalan 100% sesuai keinginan. Silakan mencoba berbagai fitur berikut dan melaporkan ke webmaster apabila ada fitur yang tidak berjalan dengan sesuai.
</div>
<div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
	<a href="pak-list" class="text-success">Daftar PAK Tersimpan (Daftar Tunggu PAK)</a>
	<div class="progress w-50">
		<div class="progress-bar bg-primary" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:90%">90%</div>
	</div>
</div>
<div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
	<a href="" class="text-success">Dashboard</a>
	<div class="progress w-50">
		<div class="progress-bar bg-primary" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width:85%">85%</div>
	</div>
</div>
<div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
	<a href="gtk_search" class="text-success">Pencarian GTK</a>
	<div class="progress w-50">
		<div class="progress-bar bg-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">100%</div>
	</div>
</div>

<?include'footer.php';?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
var C_all = document.getElementById('ChartUsiaGTK').getContext('2d');
var C_PNS_nPNS = document.getElementById('ChartPNS_nPNS').getContext('2d');
var C_L_P = document.getElementById('ChartL_P').getContext('2d');
var data = JSON.parse($.ajax({
	url:	'action/dashboard.php',
	dataType: "json", 
	async: false
}).responseText);
var ChartUsiaGTK = new Chart(C_all, {
	type: 'line',
	data: {
		labels: JSON.parse(data.usia_rentang),
		datasets: [{
			label: 'Jumlah GTK',
			data: JSON.parse(data.usia_jumlah),
			backgroundColor: 'rgba(0,123,255,.2)',
			borderColor: 'rgba(0,123,255,1)',
			pointBorderColor: 'rgba(0,123,255,1)',
			borderWidth: 1
		}]
	},
	options: {
		tooltips: {
			enabled: true,
			mode: 'single',
			callbacks: {
				title: function(tooltipItems, data) { 
					return 'Usia '+tooltipItems[0].xLabel+' tahun:';
				}
			}
		},
		scales:{yAxes:[{ticks: {beginAtZero: true}}]}
	}
});
var ChartPNS_nPNS = new Chart(C_PNS_nPNS, {
	type: 'line',
	data: {
		labels: JSON.parse(data.usia_rentang),
		datasets: [{
			label: 'GTK PNS',
			data: JSON.parse(data.SP_PNS),
			backgroundColor: 'rgba(0,0,0,0)',
			borderColor: 'rgba(0,123,255,1)',
			pointBorderColor: 'rgba(0,123,255,1)',
			borderWidth: 1
		},{
			label: 'GTK Non-PNS',
			data: JSON.parse(data.SP_NPNS),
			backgroundColor: 'rgba(0,0,0,0)',
			borderColor: 'rgba(220,53,69,1)',
			pointBorderColor: 'rgba(220,53,69,1)',
			borderWidth: 1
		}
		]
	},
	options: {
		tooltips: {
			enabled: true,
			mode: 'single',
			callbacks: {
				title: function(tooltipItems, data) { 
					return 'Usia '+tooltipItems[0].xLabel+' tahun:';
				}
			}
		},
		scales:{yAxes:[{ticks: {beginAtZero: true}}]}
	}
});
var ChartL_P = new Chart(C_L_P, {
	type: 'line',
	data: {
		labels: JSON.parse(data.usia_rentang),
		datasets: [{
			label: 'Laki-laki',
			data: JSON.parse(data.JK_L),
			backgroundColor: 'rgba(0,0,0,0)',
			borderColor: 'rgba(0,123,255,1)',
			pointBorderColor: 'rgba(0,123,255,1)',
			borderWidth: 1
		},{
			label: 'Perempuan',
			data: JSON.parse(data.JK_P),
			backgroundColor: 'rgba(0,0,0,0)',
			borderColor: 'rgba(220,53,69,1)',
			pointBorderColor: 'rgba(220,53,69,1)',
			borderWidth: 1
		}
		]
	},
	options: {
		tooltips: {
			enabled: true,
			mode: 'single',
			callbacks: {
				title: function(tooltipItems, data) { 
					return 'Usia '+tooltipItems[0].xLabel+' tahun:';
				}
			}
		},
		scales:{yAxes:[{ticks: {beginAtZero: true}}]}
	}
});
</script>