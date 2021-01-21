<?php
include('header.php');
// PEREMAJAAN: 100%, TAB: 100%
$data=array(":npsn"=>$_SESSION["sek_npsn"]);
$query = "
SELECT
	COUNT(gtk_id) AS JLH,
	IFNULL(SUM(IF(gtk_jk='L',1,0)),0) AS JK_L,
	IFNULL(SUM(IF(gtk_jk='P',1,0)),0) AS JK_P,
	IFNULL(SUM(IF(statpeg='PNS',1,0)),0) AS PNS,
	IFNULL(SUM(IF(statpeg!='PNS',1,0)),0) AS NPNS,
	IFNULL(SUM(IF(JSON_UNQUOTE(JSON_EXTRACT(gtk_ttl,'$[1]')) BETWEEN '".date('Y-m-d', strtotime("-60 years"))."' AND '".date('Y-m-d', strtotime("-55 years"))."',1,0)),0) AS THN5,
	IFNULL(SUM(IF(JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[0]'))='Guru',1,0)),0) AS GURU,
	IFNULL(SUM(IF(JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[0]'))='Tendik',1,0)),0) AS TENDIK,
	IFNULL(SUM(IF(dapodik='IND',1,0)),0) AS DP_IND,
	IFNULL(SUM(IF(dapodik='NON',1,0)),0) AS DP_NON,
	IFNULL(SUM(IF(dapodik='BLM',1,0)),0) AS DP_BLM
FROM tbl_gtk WHERE gtk_npsn=:npsn
";
$st = $connect->prepare($query);
$st->execute($data);
$res = $st->fetchAll();
foreach($res as $r)
{
	$d1	 = $r["JLH"];
	$d2a = $r["JK_L"];
	$d2b = $r["JK_P"];
	$d3a = $r["PNS"];
	$d3b = $r["NPNS"];
	$d4	 = $r["THN5"];
	$d5a = $r["GURU"];
	$d5b = $r["TENDIK"];
	$d6a = $r["DP_IND"];
	$d6b = $r["DP_NON"];
	$d6c = $r["DP_BLM"];
} ?>
<style>
.tbl-pns td, .tbl-pns th{padding:.25em .5em;vertical-align:middle;text-align:center}.tbl-pns td:nth-child(2), .tbl-pns td:nth-child(5){text-align:left}.tbl-pns td:nth-child(6){white-space:nowrap}.stats .col-6{height:140px;text-align:center;line-height:1.1}.stats small, .stats num{display:flex;align-items:center;justify-content:center;cursor:default}.stats num{font-weight:500;font-size:2em;height:95px;white-space:nowrap;text-shadow:0 0 1em #000;transition:.3s}.stats num:hover{font-size:3em;margin:0 -1em;}.stats small{background:#dee2e6;height:45px;margin: 0 -15px;padding: 0 15px;color:#343a40!important;font-size:.875em}.tbl-info td:nth-child(2){font-weight:500}.btn-right{display:flex;height:2em;width:2em;padding:0;border:0}.btn-right::before{content:url("data:image/svg+xml,%3Csvg width='2em' height='2em' viewBox='0 0 16 16' fill='%23212529' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z'/%3E%3Cpath fill-rule='evenodd' d='M4 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5A.5.5 0 0 0 4 8z'/%3E%3C/svg%3E")}.btn-right:hover::before,.btn-right:focus::before{content:url("data:image/svg+xml,%3Csvg width='2em' height='2em' viewBox='0 0 16 16' fill='%23007bff' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 8.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z'/%3E%3C/svg%3E")}.dsbl-color{width:1rem;height:1rem;border-radius:.25rem;border:1px solid #6c757d}.dsbl-text{margin:-4px 0 2px .25rem}.dsbl{display:flex;padding-top:3px;width:50%;margin-right:.25rem}@media screen and (max-width:575.98px){.dsbl{width:100%}}#pengumuman ul,#pengumuman ol{padding-left: 1.3rem;}
</style>

<h2 class="pb-2 border-bottom">Dashboard</h2>
<?if(sql_value($connect, "SELECT val1 FROM options WHERE set_var='pengumuman'")==1)
{?>
	<section class="alert alert-info" id="pengumuman">
		<h4><?=sql_value($connect, "SELECT txt FROM options WHERE set_var='pengumuman'");?></h4>
		<?=sql_value($connect, "SELECT txt2 FROM options WHERE set_var='pengumuman'");?>
		<em class="text-right d-block">Terima kasih.</em>
	</section>
<?}?>
<div class="row rounded overflow-hidden stats m-0 text-light">
		<div class="col-md-2 col-6 bg-primary"><num><?= $d1;?></num><small>Jumlah GTK di Sekolah</small></div>
		<div class="col-md-2 col-6 bg-success"><num><?= $d2a.' : '.$d2b;?></num><small>L : P</small></div>
		<div class="col-md-2 col-6 bg-info"><num><?= $d3a.' : '.$d3b;?></num><small>PNS : Non-PNS</small></div>
		<div class="col-md-2 col-6 bg-danger"><num><?= $d4;?></num><small>Pensiun 5 tahun ke depan</small></div>
		<div class="col-md-2 col-6 bg-warning"><num><?= $d5a.' : '.$d5b;?></num><small>Guru : Tendik</small></div>
		<div class="col-md-2 col-6 bg-secondary"><num><?= $d6a.':'.$d6b.':'.$d6c;?></num><small>Induk : Non Induk : Belum Terdaftar</small></div>
</div>

<div class="row mt-4">
	<div class="col-md-6 mb-3">
	 <div class="card h-100">
		<div class="d-flex justify-content-between align-items-center p-3">
			<div>
				<h5 class="m-0">Laporan Bulanan</h5>
				<p class="text-muted m-0">Rincian data mengenai GTK</p>
			</div>
			<a href="daftar-gtk" class="btn btn-right"></a>
		</div>
		<div class="d-flex justify-content-between align-items-center p-3 border-top">
			<div>
				<h5 class="m-0">Presensi Daring</h5>
				<p class="text-muted m-0">Unggah daftar hadir harian</p>
			</div>
		<a href="daftar-hadir" class="btn btn-right"></a>
		</div>
		<div class="d-flex justify-content-between align-items-center p-3 border-top border-bottom mb-2">
			<div>
				<h5 class="m-0">Data Sekolah</h5>
				<p class="text-muted m-0">Data singkat pendidikan</p>
			</div>
			<a href="data-sekolah" class="btn btn-right"></a>
		</div>

		<div class="d-flex justify-content-between align-items-center p-3 border-top">
			<div>
				<h5 class="m-0">Rekap Daftar Hadir</h5>
				<p class="text-muted m-0">Rekap bulanan daftar hadir GTK</p>
			</div>
			<a href="rekap-lb" class="btn btn-right"></a>
		</div>
		<div class="d-flex justify-content-between align-items-center p-3 border-top border-bottom mb-2">
			<div>
				<h5 class="m-0">Pusat Unggahan</h5>
				<p class="text-muted m-0">Unggah berkas yang dibutuhkan</p>
			</div>
			<a href="unggahan" class="btn btn-right"></a>
		</div>
		
		<div class="d-flex justify-content-between align-items-center p-3 border-top">
			<div>
				<h5 class="m-0">Panduan Penggunaan</h5>
				<p class="text-muted m-0">Tampilkan cara menggunakan aplikasi</p>
			</div>
			<a href="" class="no_sl btn btn-right"></a>
		</div>
		<div class="d-flex justify-content-between align-items-center p-3 border-top">
			<div>
				<h5 class="m-0">Tentang Aplikasi</h5>
				<p class="text-muted m-0">Berbagai perubahan aplikasi, serta ucapan terima kasih</p>
			</div>
			<a href="version" class="btn btn-right"></a>
		</div>
	 </div>
	</div>
	<div class="col-md-6 mb-3">
		<div class="card h-100">
			<h5 class="card-header px-3">Informasi</h5>
			<div class="card-body p-3">
				<h6>Informasi Sekolah</h6>
				<table class="table table-hover tbl-info" style="width: calc(100% + 1.5rem); margin: 0 -.75rem;">
					<tr>
						<td style="width:40%">Nama Sekolah</td>
						<td style="width:60%"><?=$_SESSION["sek_nama"];?></td>
					</tr>
					<tr>
						<td>NPSN</td>
						<td><?=$_SESSION["sek_npsn"];?></td>
					</tr>
					<tr>
						<td>Kecamatan</td>
						<td><?=sql_value($connect, "SELECT dt_dati3 FROM tbl_sekdb WHERE dt_npsn=:npsn",$data);?></td>
					</tr>
					<tr>
						<td>Kabupaten/Kota</td>
						<td><?=dati2(sql_value($connect, "SELECT dt_dati2 FROM tbl_sekdb WHERE dt_npsn=:npsn",$data));?></td>
					</tr>
					<tr>
						<td>Kepala Sekolah</td>
						<td><?$kepsek=sql_value($connect, "SELECT gtk_nama FROM tbl_gtk WHERE gtk_npsn = :npsn AND JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[0]'))='Kepsek'",$data);echo filter_xsshtml($kepsek);if(!$kepsek)echo '<style>#kepsek-tgl{display:none}</style>';?>
						    <div id="kepsek-tgl" class="small text-muted">Menjabat sejak <?=date("d/m/Y", strtotime(sql_value($connect, "SELECT JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[1]')) FROM tbl_gtk WHERE gtk_npsn = :npsn AND JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[0]'))='Kepsek'",$data)));?></div></td>
					</tr>
					<tr>
						<td>Perubahan terbaru</td>
						<td><?=sql_value($connect, "SELECT sek_lastmod FROM tbl_sekolah WHERE sek_npsn=:npsn",$data);?></td>
					</tr>
					<tr class="border-bottom">
						<td>Jumlah Hari Presensi<div class="small text-muted">Sepanjang bulan ini</div></td>
						<td><?=sql_value($connect, "SELECT count(distinct(dfhd_tgl)) FROM tbl_dft_hadir WHERE dfhd_npsn = :npsn AND dfhd_tgl BETWEEN '".date("Y-m")."-01' AND '".date("Y-m")."-31'",$data);?> hari</td>
					</tr>
				</table>
				<h6 class="mt-3">Informasi Aplikasi</h6>
				<table class="table table-hover tbl-info" style="width: calc(100% + 1.5rem); margin: 0 -.75rem;">
					<tr>
						<td style="width:40%">Versi aplikasi</td>
						<td style="width:60%">Beta 4.0.0 (2101H1)</td>
					</tr>
					<tr class="border-bottom">
						<td>Terakhir diperbarui</td>
						<td>4 Januari 2020</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<h4 id="sekilaspns" class="pb-2 border-bottom">Sekilas GTK Pegawai Negeri Sipil</h4>
<div class="row">
	<div class="col-lg-6 tmtpt_tbl mb-3">
		<h5>Daftar Usulan Kenaikan Pangkat GTK</h5>
		<div class="border rounded p-2 mb-2">
			<div class="d-md-flex d-block">
				<div class="dsbl">
					<div class="dsbl-color" style="background:#c3e6cb"></div>
					<div class="dsbl-text">lebih dari 6 bulan</div>
				</div>
				<div class="dsbl">
					<div class="dsbl-color" style="background:#ffeeba"></div>
					<div class="dsbl-text">antara 5 dan 6 bulan</div>
				</div>
			</div>
			<div class="d-md-flex d-block">
				<div class="dsbl">
					<div class="dsbl-color" style="background:#f5c6cb"></div>
					<div class="dsbl-text">antara 0 dan 5 bulan</div>
				</div>
				<div class="dsbl">
					<div class="dsbl-color" style="background:#c6c8ca"></div>
					<div class="dsbl-text">melebihi waktu</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table m-0 w-100 tbl-pns" style="overflow:auto;min-width:510px;font-size:.875em;table-layout: fixed">
				<thead>
					<tr>
						<th style="width:40px">No.</th>
						<th style="width:200px">Nama, NIP</th>
						<th style="width:80px">Tgl. SK Terakhir</th>
						<th style="width:80px">Tgl. Target Kenaikan</th>
						<th style="width:50px">Sisa waktu</th>
						<th style="width:60px">Naik ke</th>
					</tr>
				</thead>
				<tbody>
<?php
$query = "
	SELECT gtk_no,gtk_nama,gtk_nip,gtk_tmtpt,gtk_gol FROM tbl_gtk
	WHERE gtk_npsn = :npsn
	AND statpeg = 'PNS'
	AND LEFT(gtk_gol, 2) <> 'IV'
	AND gtk_tmtpt IS NOT NULL
	ORDER BY gtk_tmtpt ASC
	LIMIT 10
";
$st = $connect->prepare($query);
$st->execute($data);
$res = $st->fetchAll();
if($st->rowCount()==0) {$skter_empty=true;echo '<style>.tmtpt_tbl{display:none}</style>';}
foreach($res as &$r)
{
    $r=array_map("filter_xsshtml",$r);
	$tgl_4y		 = $r["gtk_tmtpt"].' +4 years';
	$p_nip			= '<div class="text-nowrap" style="font-size:12px">NIP. '.$r["gtk_nip"].'</div>';
	$p_terakhir = date_format(date_create($r["gtk_tmtpt"]),"d/m/Y");
	$p_target	 = date('d/m/Y', strtotime($tgl_4y));
	$p_sisa		 = selisihTgl($tgl_4y, date(), '%y th<br>%m bl<br>%d hr');
	if(selisihTgl($tgl_4y, date())>180) $ind="#c3e6cb";
	if(selisihTgl($tgl_4y, date())<181) $ind="#ffeeba";
	if(selisihTgl($tgl_4y, date())<150) $ind="#f5c6cb";
	if(selisihTgl($tgl_4y, date())== 0){$ind="#c6c8ca"; $p_sisa='-';}
	if($r["gtk_gol"]=='II / a')$naik='II / b';
	if($r["gtk_gol"]=='II / b')$naik='II / c';
	if($r["gtk_gol"]=='II / c')$naik='II / d';
	if($r["gtk_gol"]=='II / d')$naik='III / a';
	if($r["gtk_gol"]=='III / a')$naik='III / b';
	if($r["gtk_gol"]=='III / b')$naik='III / c';
	if($r["gtk_gol"]=='III / c')$naik='III / d';
	if($r["gtk_gol"]=='III / d')$naik='IV / a';
?>
					<tr style="background:<?= $ind;?>">
						<td><?= $r["gtk_no"];?></td>
						<td><?= '<div class="text-truncate m-0" style="font-weight:500">'.$r["gtk_nama"].'</div>'.$p_nip;?></td>
						<td><?= $p_terakhir;?></td>
						<td><?= $p_target;?></td>
						<td class="small" style="line-height:1.2"><?= $p_sisa;?></td>
						<td><?= $naik;?></td>
					</tr>
<?}unset($r);?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-lg-6 sk_ber_tbl mb-3">
	<h5>Daftar Usulan SK Berkala GTK</h5>
		<div class="border rounded p-2 mb-2">
			<div class="d-md-flex d-block">
				<div class="dsbl">
					<div class="dsbl-color" style="background:#c3e6cb"></div>
					<div class="dsbl-text">lebih dari 2 bulan</div>
				</div>
				<div class="dsbl">
					<div class="dsbl-color" style="background:#ffeeba"></div>
					<div class="dsbl-text">antara 1 dan 2 bulan</div>
				</div>
			</div>
			<div class="d-md-flex d-block">
				<div class="dsbl">
					<div class="dsbl-color" style="background:#f5c6cb"></div>
					<div class="dsbl-text">antara 0 dan 1 bulan</div>
				</div>
				<div class="dsbl">
					<div class="dsbl-color" style="background:#c6c8ca"></div>
					<div class="dsbl-text">melebihi waktu</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table m-0 w-100 tbl-pns" style="overflow:auto;min-width:510px;font-size:.875em;table-layout: fixed">
				<thead>
					<tr>
						<th style="width:40px">No.</th>
						<th style="width:200px">Nama, NIP</th>
						<th style="width:80px">Tgl. SK Terakhir</th>
						<th style="width:80px">Tgl. Target Pembaruan</th>
						<th style="width:50px">Sisa waktu</th>
					</tr>
				</thead>
				<tbody>
<?php
$query = "
	SELECT gtk_no,gtk_nama,gtk_nip,gtk_sk_ber FROM tbl_gtk
	WHERE gtk_npsn = :npsn
	AND statpeg = 'PNS'
	AND gtk_sk_ber IS NOT NULL
	ORDER BY gtk_sk_ber ASC
	LIMIT 10
";
$st = $connect->prepare($query);
$st->execute($data);
$res = $st->fetchAll();
if($st->rowCount()==0){$skber_empty=true;echo '<style>.sk_ber_tbl{display:none}</style>';}
foreach($res as &$r)
{
    $r=array_map("filter_xsshtml",$r);
	$tgl_2y		 = $r["gtk_sk_ber"].' +2 years';
	$p_nip			= '<div class="text-nowrap" style="font-size:12px">NIP. '.$r["gtk_nip"].'</div>';
	$p_terakhir = date_format(date_create($r["gtk_sk_ber"]),"d/m/Y");
	$p_target	 = date('d/m/Y', strtotime($r["gtk_sk_ber"]. '+2 years'));
	$p_sisa		 = selisihTgl($tgl_2y, date(), '%y th<br>%m bl<br>%d hr');
	if(selisihTgl($tgl_2y, date())>90) $ind="#c3e6cb";
	if(selisihTgl($tgl_2y, date())<91) $ind="#ffeeba";
	if(selisihTgl($tgl_2y, date())<30) $ind="#f5c6cb";
	if(selisihTgl($tgl_2y, date())==0){$ind="#c6c8ca"; $p_sisa='-';}
?>
					<tr style="background:<?php echo $ind;?>">
						<td><?php echo $r["gtk_no"];?></td>
						<td><?php echo '<div class="text-truncate m-0" style="font-weight:500">'.$r["gtk_nama"].'</div>'.$p_nip;?></td>
						<td><?php echo $p_terakhir;?></td>
						<td><?php echo $p_target;?></td>
						<td class="small" style="line-height:1.2"><?php echo $p_sisa;?></td>
					</tr>
<?}unset($r);?>
				</tbody>
			</table>
		</div>
	</div>
<?if($skter_empty && $skber_empty)echo '<style>#sekilaspns{display:none}</style>';?>
</div>
<?php include('footer.php'); ?>