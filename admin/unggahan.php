<?include'header.php';if($_SESSION["adm_level"]==3){?>
<div class="jumbotron bg-danger">
	<div class="container text-light">
		<h1 class="display-4">GALAT!</h1>
		<p class="lead">Halaman ini tidak dapat dimuat!</p>
	</div>
</div>
<?include('footer.php');die();}?>
<div class="pb-2 mb-2 border-bottom d-block d-md-flex justify-content-between align-items-end">
	<h2 class="m-0">Unggahan Berkas Sekolah</h2>
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
<?foreach($_SESSION["adm_dati2"] as $ds_ad2)echo'<option value='.$ds_ad2.'>'.dati2($ds_ad2).'</option>';?>
				</select>
				<select class="form-control custom-select border-dark" name="tipe">
					<option value="">Semua Jenjang Pendidikan</option>
<?function jp_lengkap($jp){if($jp=='SMA')return'Sekolah Menengah Atas'; if($jp=='SMK')return'Sekolah Menengah Kejuruan'; if($jp=='SLB')return'Pendidikan Khusus & Layanan Khusus';}
foreach($_SESSION["adm_jp"] as $ds)echo'<option value='.$ds.'>'.jp_lengkap($ds).'</option>';?>
				</select>
<?}?>
			<div class="input-group-append">
				<input type="submit" class="btn btn-outline-dark btn-block btn-sm" value="Saring" onclick="$('#spinner_loader').show();"/>
		 </div>
		</div>
	</form>
</div>
<table id="tbl_unggahan" class="table table-hover table-bordered onload_ajax w-100">
	<thead class="bg-light">
		<tr>
			<th>Nama Sekolah</th>
			<th>Jenis</th>
			<th>Deskripsi</th>
			<th>Periode</th>
			<th>Diunggah Pada</th>
			<th>Tindakan</th>
		</tr>
	</thead>
</table>
<div class="modal fade" id="deleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Hapus Permanen Berkas</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body text-center">
				<h3>Apakah Anda yakin ingin menghapus berkas ini?</h3>
				<p class="m-0"><b>PENTING:</b> Hanya hapus berkas apabila berkas sudah tidak diperlukan lagi.</p>
			</div>
			<div class="modal-footer">
				<button type="button" name="ConfHapus" id="ConfHapus" class="btn btn-danger btn-sm">Hapus</button>
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>
<style>
#tbl_unggahan td:last-child button, #tbl_unggahan td:last-child a{margin:.2em}#tbl_unggahan td:last-child{padding:.75em}
</style>
<?include'footer.php';?>
<script>
<?$_GET["dati2"] = addslashes($_GET["dati2"]); $_GET["tipe"] = addslashes($_GET["tipe"]);?>
$('[name="dati2"]').val('<?=$_GET["dati2"];?>');
$('[name="tipe"]').val('<?=$_GET["tipe"];?>');
$(document).ready(function(){
	var dataTable = $('#tbl_unggahan').DataTable({
		"processing": false,
		"lengthMenu": [[10, 20, 30], [10, 20, 30]],
		"order": [[4,"desc"]],
		"scrollX": true,
		"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
		},
		"ajax":{
			url:"action/unggahan.php",
			type:"POST",
			data:{action:'fetch_ung',dati2:'<?=$_GET["dati2"];?>',tipe:'<?=$_GET["tipe"];?>'},
			dataType:"json",
			beforeSend:function(){$('#spinner_loader').show();},
			error: function(){
				$('#tbl_unggahan_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
			},
			complete: function(){
					$('#spinner_loader').fadeOut();
			}
		}
	});
	$(document).on('click', '.b_unghps', function(){
		ung_nama = $(this).attr('data-nama');
		$('#deleteModal').modal('show');
	});

	$('#ConfHapus').click(function(){
		$('#spinner_loader').show();
		$.ajax({
			url:"action/unggahan.php",
			method:"POST",
			data:{ung_nama:ung_nama, action:"hapus_berkas"},
			dataType:"json",
			success:function(data)
			{
				if(data.success) topbar(1,data.text);
				if(data.error) topbar(0,data.text);
				$('#deleteModal').modal('hide');
				dataTable.ajax.reload();
			},
			complete:function(){
				$('#spinner_loader').fadeOut();
			}
		})
	});

});
</script>