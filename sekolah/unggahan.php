<?php
include('header.php');

if($_POST["action"] == "unggah_berkas")
{
		$success = '';
		if($_FILES["ung_file"]["name"] != '')
	{
		$file_name = $_FILES["ung_file"]["name"];
		$tmp_name = $_FILES["ung_file"]["tmp_name"];
		$ext_array = explode(".", $file_name);
		$extension = strtolower(end($ext_array));
		$allowed_extension = array('pdf');
		if(!in_array($extension, $allowed_extension))
		{
			$error_file = date("H:i:s").': File tidak sesuai ketentuan (PDF). Silakan unggah ulang.';
			$error++;
		}
		else
		{
			$ung_file = $_SESSION["sek_npsn"].'_'.$_POST["ung_tipe"].'_'.$_POST["p_bulan"].'-'.$_POST["p_tahun"].'+'.hash("sha256",rand()).'.'.$extension;
			
			$upload_path = '../storage/uploads/' . $ung_file;
			move_uploaded_file($tmp_name, $upload_path);
		}
	}
	if($error == 0) {
	$data = array(
	':ung_npsn'		 =>	$_SESSION["sek_npsn"],
	':ung_tipe'		 =>	$_POST["ung_tipe"],
	':ung_file'		 =>	$ung_file,
	':ung_desc'		 =>	$_POST["ung_desc"],
	':ung_periode'	=>	$_POST["p_bulan"].' '.$_POST["p_tahun"],
	':ung_tgl'			=>	date("Y-m-d H:i:s")
	);
	$query = "
	INSERT INTO tbl_unggahan 
	(ung_npsn, ung_tipe, ung_file, ung_desc, ung_periode, ung_tgl) 
	VALUES (:ung_npsn, :ung_tipe, :ung_file, :ung_desc, :ung_periode, :ung_tgl)
	";
	$statement = $connect->prepare($query);
	if($statement->execute($data)){
			$success = '<div class="alert alert-success small">'.date("H:i:s").': Berkas telah diunggah.</div>';
	}
	}
		if($error > 0) {
				$success = '<div class="alert alert-danger small">'.date("H:i:s").': Extension berkas tidak sah!</div>';
		}
	
}
?>
<div class="pb-2 mb-3 border-bottom d-block d-md-flex justify-content-between align-items-end">
	<h2 class="m-0">Pusat Unggahan</h2>
	<div class="btn-toolbar mt-2 mt-md-0 btn-group" role="group" aria-label="Tindakan">
		<a href="#sec1" class="no_sl btn btn-outline-dark btn-sm">Unggah</a>
		<a href="#sec2" class="no_sl btn btn-outline-dark btn-sm">Daftar</a>
	</div>
</div>

<h4 id="sec1" class="m-0 pb-1 border-bottom mb-3">Unggah Dokumen Baru</h4>
<?if(sql_value($connect, "SELECT val1 FROM options WHERE set_var='unggah_berkas'")==1){?>
<form method="post" id="form_ung" enctype="multipart/form-data" onsubmit="$('#spinner_loader').show();">
	<div class="row">
		<label class="col-4" for="ung_tipe">Jenis Unggahan</label>
		<div class="col-8">
			<select id="ung_tipe" name="ung_tipe" class="form-control custom-select" required>
				<option value="PDH">Pertanggungjawaban Daftar Hadir</option>
				<option value="NON">Berkas lain</option>
			</select>
		</div>
		<label class="col-4" for="ung_periode">Periode</label>
		<div class="col-8">
			<div class="row" style="width:calc(100% + 2em);">
				<div class="col-6">
					<select class="form-control custom-select" name="p_bulan" required>
						<option value="Januari">Januari</option>
						<option value="Februari">Februari</option>
						<option value="Maret">Maret</option>
						<option value="April">April</option>
						<option value="Mei">Mei</option>
						<option value="Juni">Juni</option>
						<option value="Juli">Juli</option>
						<option value="Agustus">Agustus</option>
						<option value="September">September</option>
						<option value="Oktober">Oktober</option>
						<option value="November">November</option>
						<option value="Desember">Desember</option>
					</select>
				</div>
				<div class="col-6">
					<select class="form-control custom-select" name="p_tahun" required>
						<option value="<?=date("Y");?>"><?=date("Y");?></option>
						<option value="<?=date("Y")-1;?>"><?=date("Y")-1;?></option>
					</select>
				</div>
			</div>
		</div>
		<label class="col-4" for="ung_desc">Deskripsi Berkas</label>
		<div class="col-8">
			<textarea class="form-control" name="ung_desc" id="ung_desc" maxlength="100" placeholder="Apabila tidak ada informasi tambahan, silahkan dikosongkan"></textarea>
		</div>
	</div>
	<div class="input-group mb-3">
		<div class="custom-file">
			<input type="file" class="custom-file-input" id="ung_file" name="ung_file" aria-describedby="desc_ung_file" required accept=".pdf">
			<label class="custom-file-label text-truncate" style="padding-right:4em;" for="ung_file">Klik di sini untuk mengunggah berkas</label>
		</div>
	</div>
	<input type="hidden" name="action" value="unggah_berkas"/>
	<div class="align-items-center justify-content-between p-2 d-flex border rounded mb-3">
		<div class="text-muted">Pastikan Berkas Anda unggah dalam bentuk <b>.PDF</b>, ukuran maksimal <b>250kB</b></div>
		<input type="submit" id="tombol_unggah" class="btn btn-primary btn-sm" value="Unggah Berkas"/>
	</div>
</form>
<?}else{?>
<div class="bg-secondary text-light p-5 text-center mb-4 rounded">
	<h4 class="display-4">Mohon maaf.</h4>
	<p class="lead">Fitur pengunggahan berkas untuk saat ini dinonaktifkan.</p>
	<input type="hidden" id="ung_file"/>
</div>
<?}?>

<h4 id="sec2" class="border-bottom pb-1">Daftar Unggahan</h4>
<table class="table table-bordered table-hover onload_ajax" id="tbl_dft_unggahan">
	<thead class="bg-light">
		<tr>
			<th>Jenis</th>
			<th>Deskripsi</th>
			<th>Periode</th>
			<th>Diunggah Pada</th>
			<th>Tindakan</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<style>.custom-file-label::after{content:"Pilih"!important;}#form_ung .col-8, #form_ung label{margin-bottom:1em;display:flex;align-items:center}#tbl_dft_unggahan td:last-child button, #tbl_dft_unggahan td:last-child a{margin:.2em}#tbl_dft_unggahan td:last-child{padding:.75em}
</style>

<div class="modal fade" id="deleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Hapus Permanen Berkas</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body text-center">
				<h3>Apakah Anda yakin ingin menghapus berkas ini?</h3>
				<p class="m-0">Silahkan hapus berkas apabila Anda ingin mengunggah berkas yang berbeda.</p>
			</div>
			<div class="modal-footer">
				<button type="button" name="ok_button" id="ok_button" class="btn btn-danger btn-sm">Hapus</button>
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php');?>
<script>
var fileup = document.getElementById("ung_file");
fileup.onchange = function() {
	if($(this).val()!='') {
		if(this.files[0].size > 256000) {
			topbar("info","Ukuran berkas terlalu besar. <b>Ukuran maksimal adalah 250kB</b>.");
			$(this).val('');
		} else $('#console').html('');
	} else $('#console').html('');
};

$(".custom-file-input").on("change", function() {
	var fileName = $(this).val().split("\\").pop();
	if(fileName!=''){
	$(this).siblings(".custom-file-label").addClass("selected").html('<code>'+fileName+'</code>');
	} else {$(this).siblings(".custom-file-label").html('Klik di sini untuk mengunggah berkas')}
});
if ( window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
$(document).ready(function(){
	var dataTable = $('#tbl_dft_unggahan').DataTable({
		"lengthMenu": [[10,20,30], [10,20,30]],
		"order": [3,"desc"],
		"scrollX": true,
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
		},
		"ajax":{
			url:"action/sekolah.php",
			method:"POST",
			data:{action:"fetch_ung"},
			beforeSend:function(){$('#spinner_loader').show();},
			error: function(){
				$('#tbl_dft_unggahan_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
			},
			complete:function(){$('#spinner_loader').fadeOut();}
		}
	});
	$(document).on('click', '.b_unghps', function(){
		ung_nama = $(this).attr('data-ung_nama');
		$('#deleteModal').modal('show');
	});

	$('#ok_button').click(function(){
		$('#spinner_loader').show();
		$.ajax({
			url:"action/sekolah.php",
			method:"POST",
			data:{ung_nama:ung_nama, action:"ung_hps"},
			dataType:"json",
			success:function(data)
			{
				if(data.success) topbar(1,data.text);
				if(data.error) topbar(0,data.text);
				$('#deleteModal').modal('hide');
				dataTable.ajax.reload();
			}
		})
	});

$('#console').html('<?=$success;?>');
$('#form_ung select').val('');
});
</script>