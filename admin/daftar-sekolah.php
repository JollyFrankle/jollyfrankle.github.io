<?php include('header.php'); ?>

<div class="pb-2 mb-2 border-bottom d-block d-md-flex justify-content-between align-items-end">
	<h2 class="m-0">Sekolah Terdaftar</h2>
	<form method="get" class="btn-toolbar mt-2 mt-md-0 btn-group">
		<div class="input-group input-group-sm">
<? if($_SESSION["adm_level"]!=2){?>
			<select class="form-control custom-select border-dark" name="dati2">
				<option value="">Semua Kabupaten/Kota</option>
				<option value="06">Kabupaten Alor</option>
				<option value="05">Kabupaten Belu</option>
				<option value="09">Kabupaten Ende</option>
				<option value="07">Kabupaten Flores Timur</option>
				<option value="01">Kabupaten Kupang</option>
				<option value="14">Kabupaten Lembata</option>
				<option value="22">Kabupaten Malaka</option>
				<option value="16">Kabupaten Manggarai Barat</option>
				<option value="20">Kabupaten Manggarai Timur</option>
				<option value="11">Kabupaten Manggarai</option>
				<option value="17">Kabupaten Nagekeo</option>
				<option value="10">Kabupaten Ngada</option>
				<option value="15">Kabupaten Rote Ndao</option>
				<option value="21">Kabupaten Sabu Raijua</option>
				<option value="08">Kabupaten Sikka</option>
				<option value="19">Kabupaten Sumba Barat Daya</option>
				<option value="13">Kabupaten Sumba Barat</option>
				<option value="18">Kabupaten Sumba Tengah</option>
				<option value="12">Kabupaten Sumba Timur</option>
				<option value="03">Kabupaten Timor Tengah Selatan</option>
				<option value="04">Kabupaten Timor Tengah Utara</option>
				<option value="60">Kota Kupang</option>
			</select>
			<select class="form-control custom-select border-dark" name="tipe">
				<option value="">Semua Jenjang Pendidikan</option>
				<option value="SMA">Sekolah Menengah Atas</option>
				<option value="SMK">Sekolah Menengah Kejuruan</option>
				<option value="SLB">Pendidikan Khusus & Layanan Khusus</option>
			</select>
<?}else{?>
			<select class="form-control custom-select border-dark" name="dati2">
				<option value="">Semua Kabupaten/Kota</option>
        <?foreach($_SESSION["adm_dati2"] as $ds)echo'<option value='.$ds.'>'.dati2($ds).'</option>';?>
			</select>
			<select class="form-control custom-select border-dark" name="tipe">
				<option value="">Semua Jenjang Pendidikan</option>
        <?function jp_lengkap($jp){
if($jp=='SMA')return'Sekolah Menengah Atas'; if($jp=='SMK')return'Sekolah Menengah Kejuruan'; if($jp=='SLB')return'Pendidikan Khusus & Layanan Khusus';}foreach($_SESSION["adm_jp"] as $ds)echo'<option value='.$ds.'>'.jp_lengkap($ds).'</option>';?>
			</select>
<?}?>
			<select class="form-control custom-select border-dark" name="aktif">
				<option value="">Semua Status Akun</option>
				<option value="2">Akun sudah aktif</option>
				<option value="1">Akun belum aktif</option>
			</select>
			<div class="input-group-append">
				<input type="submit" class="btn btn-outline-dark btn-block btn-sm" value="Saring" onclick="$('#spinner_loader').show();"/>
			</div>
		</div>
	</form>
</div>

<div class="alert alert-info">
	<p><b>INFORMASI:</b> Penyaringan data (<i>filter</i>) tersedia di sebelah kanan judul halaman (pada desktop), atau di bawah judul halaman (pada ponsel). Silahkan gunakan fitur tersebut untuk menampilkan data khusus dari sebuah kabupaten/kota, jenjang pendidikan, serta status keaktifan akun.</p>
	<p>Anda dapat melihat rincian lengkap mengenai sekolah dengan menekan tombol "Info sekolah" di sebelah kanan setiap sekolah, di kolom "tindakan".</p>
	<button id="dtreload" class="btn btn-outline-primary btn-sm">Reload tabel</button>
</div>
<table class="onload_ajax table table-bordered table-hover" id="daftar_sekolah">
	<thead class="bg-light">
		<tr>
			<th style="width:250px">Nama Sekolah</th>
			<th style="width:200px">Kabupaten/Kota</th>
			<th style="min-width:150px">Jml. GTK</th>
			<th>Aktivitas terakhir</th>
			<th style="min-width:200px">Tindakan</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<style>#daftar_sekolah td:last-child .btn {margin:.2em}#daftar_sekolah td:last-child {padding:.3em .55em}#daftar_sekolah td {vertical-align:middle}</style>

<div class="modal fade" id="OTPrequest">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Generate OTP untuk login</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p class="text-center"><b>PERHATIAN!</b> Anda akan mengenerate kode OTP untuk akun <b id="otp_namasekolah"></b>. Silahkan gunakan fitur ini <b>hanya apabila akun tersebut meminta</b>.</p>
				<div class="text-center">
					<kbd class="d-inline-block h1 px-3 py-2 mb-4 user-select-all" id="kodeOTP">***-***</kbd>
					<button class="btn btn-outline-info btn-sm" id="copyOTP">Salin</button>
					<button class="btn btn-primary btn-block" id="generateOTP" data-npsn="">Generate OTP</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="deleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Hapus Permanen Sekolah & GTK</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body text-center">
				<h3>Apakah Anda yakin ingin menghapus sekolah ini?</h3>
				<p class="m-0">Apabila Anda telah menghapus data sekolah, maka data tersebut tidak akan dapat dipulihkan kembali.</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="conf_hps" class="btn btn-danger btn-sm">Hapus</button>
				<button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="RekapBulanan">
	<div class="modal-dialog">
		<form class="modal-content" method="get" action="rekap-dh">
			<div class="modal-header">
				<h5 class="modal-title">Laporan Kehadiran</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="alert alert-info" role="alert">
						Anda akan diarahkan ke halaman Rekapitulasi Kehadiran. Silahkan masukkan periode laporan. Setelah itu silahkan klik "Tampilkan Laporan". Anda kemudian dapat memilih untuk mengekspor, mencetak, atau melihat jumlah kehadiran per GTK.
				</div>
					<div class="form-row">
						<div class="col-4 d-flex align-items-center mb-3">NPSN</div>
						<div class="col-8 mb-3">
								<input type="text" class="form-control" readonly id="rekap_sek_npsn" name="npsn"/>
						</div>
						<div class="col-4 d-flex align-items-center mb-3">Bulan</div>
						<div class="col-8 mb-3">
							<select class="form-control custom-select" name="b">
								<option value="01">Januari</option>
								<option value="02">Februari</option>
								<option value="03">Maret</option>
								<option value="04">April</option>
								<option value="05">Mei</option>
								<option value="06">Juni</option>
								<option value="07">Juli</option>
								<option value="08">Agustus</option>
								<option value="09">September</option>
								<option value="10">Oktober</option>
								<option value="11">November</option>
								<option value="12">Desember</option>
							</select>
						</div>
						<div class="col-4 d-flex align-items-center">Tahun</div>
						<div class="col-8">
								<select class="form-control custom-select" name="t">
										<option><?=date('Y');?></option>
										<option><?=date('Y')-1;?></option>
								</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-success btn-sm" onclick="$('#spinner_loader').show();" value="Tampilkan"/>
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
			</div>
		</form>
	</div>
</div>
<?php include('footer.php');?>
<script>
var dati2='<?=addslashes($_GET["dati2"]);?>';
var tipe ='<?=addslashes($_GET["tipe"]);?>';
var aktif='<?=addslashes($_GET["aktif"]);?>';
$('[name="dati2"]').val(dati2);
$('[name="tipe"]').val(tipe);
$('[name="aktif"]').val(aktif);
$('[name="b"]').val('<?=date("m");?>');
$('[name="t"]').val('<?=date("Y");?>');
$(document).ready(function(){
	var dataTable = $('#daftar_sekolah').DataTable({
		"processing": false,
		"order": [[3,"desc"]],
		"scrollX": true,
		"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
		},
		"ajax":{
			url:"action/daftar_sekolah.php",
			type:"POST",
			data:{action:'fetch_terdaftar', dati2:dati2, tipe:tipe, aktif:aktif},
			dataType:"json",
			beforeSend:function(){$('#spinner_loader').show();},
			error: function(){
				$('#daftar_sekolah_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
			},
			complete: function(){
					$('#spinner_loader').fadeOut();
			}
		}
	});

$(document).on('click', '#dtreload', function() {
	dataTable.ajax.reload();
	$('#spinner_loader').show();
	topbar(1,"Tabel telah dimuat ulang.");
});

	$(document).on('click', '.otp_sek', function(){
		$('#otp_namasekolah').text($(this).attr('nama_sekolah'));
		$('#generateOTP').attr('data-npsn', $(this).attr('id'));
		$('#kodeOTP').text('***-***');
		$('#OTPrequest').modal('show');
	});
	
	$(document).on("click", "#copyOTP", function(){
        $("#kodeOTP").select();
        $("#kodeOTP").copyText.setSelectionRange(0,7);
        document.execCommand("copy");
        alert("Kode OTP berhasil disalin.");
	});
	$(document).on('click', '#generateOTP', function(){
		$('#spinner_loader').show();
		$(this).prop('disabled',true);
		npsn = $(this).attr('data-npsn');
		$.ajax({
			url:"action/daftar_sekolah.php",
			method:"POST",
			data:{action:'generateOTP', npsn:npsn},
			dataType:"json",
			success:function(data)
			{
				$('#spinner_loader').fadeOut();
				if(data.success) $('#kodeOTP').text(data.otp);
			},
			complete:function()
			{
				$('#generateOTP').prop('disabled',false);
			}
		})
	});
	var npsn = '';
	$(document).on('change', '.edit_aktif', function(){
		$('#spinner_loader').show();
		npsn = $(this).attr('data-npsn');
		sek_aktif = $(this).val();
		$(this).prop('disabled',true).prop('disabled',false);
		$.ajax({
			url:"action/daftar_sekolah.php",
			method:"POST",
			data:{action:'edit_sek_aktif', npsn:npsn, sek_aktif:sek_aktif},
			dataType:"json",
			success:function(data)
			{
				$('#spinner_loader').fadeOut();
				if(data.success) topbar(1,data.text);
				if(data.error) topbar(0,data.text);
			},
		})
	});
	
	npsn = '';
// hapus_sek
	$(document).on('click', '.hapus_sek', function(){
		$('#conf_hps').attr("data-npsn",$(this).attr('data-npsn'));
		$('#deleteModal').modal('show');
	});

	$('#conf_hps').click(function(){
		$('#spinner_loader').show();
		$.ajax({
			url:"action/daftar_sekolah.php",
			method:"POST",
			data:{npsn:$(this).attr("data-npsn"), action:"hapus"},
			dataType:"json",
			success:function(data)
			{
				$('#spinner_loader').fadeOut();
				if(data.success) topbar(1,data.text);
				if(data.error) topbar(0,data.text);
				$('#deleteModal').modal('hide');
				$('#conf_hps').attr("data-npsn",'');
				dataTable.ajax.reload();
			}
		})
	});
	$(document).on('click', '.rekap', function(){
		$('#RekapBulanan').modal('show');
		$('#rekap_sek_npsn').val($(this).attr('data-npsn'));
	});
});
</script>