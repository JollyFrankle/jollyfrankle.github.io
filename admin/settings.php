<?php include('header.php');
if($_SESSION["adm_level"]!=0 && $_SESSION["adm_level"]!=1){?>
<div class="jumbotron bg-danger">
	<div class="container text-light">
		<h1 class="display-4">GALAT!</h1>
		<p class="lead">Halaman ini tidak dapat dimuat!</p>
	</div>
</div>
<?include'footer.php';die();}?>
<h2 class="pb-2 border-bottom">Pengaturan Situs</h2>
<div class="row">
	<div class="col-md-6 mb-3">
		<h4 class="border-bottom pb-1">Pengaturan Dasar</h4>
		<div class="d-flex justify-content-between fg mb-3">
			<label>Sedang Perawatan?</label>
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-outline-danger btn-sm w-50">
					<input type="radio" class="set" name="maintenance" id="mtc0" value="0"/>MATI
				</label>
				<label class="btn btn-outline-primary btn-sm w-50">
					<input type="radio" class="set" name="maintenance" id="mtc1" value="1"/>HIDUP
				</label>
			</div>
		</div>
		<div class="d-flex justify-content-between fg mb-3">
			<label>Hidupkan notifikasi</label>
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-outline-danger btn-sm w-50">
					<input type="radio" class="set" name="pengst" id="peng0" value="0"/>MATI
				</label>
				<label class="btn btn-outline-primary btn-sm w-50">
					<input type="radio" class="set" name="pengst" id="peng1" value="1"/>HIDUP
				</label>
			</div>
		</div>
		<div class="d-flex justify-content-between fg mb-3">
			<label>Pendaftaran akun sekolah</label>
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-outline-danger btn-sm w-50">
					<input type="radio" class="set" name="regisakun" id="reg0" value="0"/>MATI
				</label>
				<label class="btn btn-outline-primary btn-sm w-50">
					<input type="radio" class="set" name="regisakun" id="reg1" value="1"/>HIDUP
				</label>
			</div>
		</div>
		<div class="d-flex justify-content-between fg mb-3">
			<label>Pengunggahan berkas/dokumen</label>
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-outline-danger btn-sm w-50">
					<input type="radio" class="set" name="unggahberkas" id="ung0" value="0"/>MATI
				</label>
				<label class="btn btn-outline-primary btn-sm w-50">
					<input type="radio" class="set" name="unggahberkas" id="ung1" value="1"/>HIDUP
				</label>
			</div>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<h4 class="pb-1 border-bottom">Statistik Database</h4>
		<table class="w-100 table-hover">
			<thead class="bg-light border-bottom">
			<tr>
				<th>Kriteria</th>
				<th>Jlh.</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>Jumlah record GTK</td>
				<td><?=sql_value($connect,'SELECT count(gtk_id) FROM tbl_gtk');?></td>
			</tr>
			<tr>
				<td>ID GTK tertinggi</td>
				<td><?=sql_value($connect,'SELECT max(gtk_id) FROM tbl_gtk');?></td>
			</tr>
			<tr class="border-top">
				<td>Jumlah record Daftar Hadir</td>
				<td><?=sql_value($connect,'SELECT count(dfhd_id) FROM tbl_dft_hadir');?></td>
			</tr>
			<tr>
				<td>ID Daftar Hadir tertinggi</td>
				<td><?=sql_value($connect,'SELECT max(dfhd_id) FROM tbl_dft_hadir');?></td>
			</tr>
			<tr class="border-top">
				<td>Jumlah sekolah terdaftar</td>
				<td><?=sql_value($connect,'SELECT count(sek_id) FROM tbl_sekolah');?></td>
			</tr>
			<tr>
				<td>Jumlah sekolah terdaftar akun aktif</td>
				<td><?=sql_value($connect,'SELECT count(sek_id) FROM tbl_sekolah WHERE sek_aktif=1');?></td>
			</tr>
			<tr>
				<td>Jumlah sekolah terdaftar akun nonaktif</td>
				<td><?=sql_value($connect,'SELECT count(sek_id) FROM tbl_sekolah WHERE sek_aktif!=1');?></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6 mb-3">
		<h4 class="border-bottom pb-1">Teks Notifikasi (di dashboard)</h4>
		<form method="post" id="pengkal">
			<div class="fg d-flex justify-content-between mb-2">
				<label>Isi pesan notifikasi:</label>
				<input type="submit" class="btn btn-outline-primary btn-sm" value="PERBARUI"/>
			</div>
			<div class="fg d-flex justify-content-between mb-2">
				<label>Judul:</label>
				<input type="text" maxlength="50" class="ml-2 form-control" name="peng[judul]"/>
			</div>
			<textarea name="peng[konten]" id="notif" class="form-control mb-3" rows=5>
					<?=str_replace("'","\'",sql_value($connect, "SELECT txt2 FROM options WHERE set_var='pengumuman'"));?>
			</textarea>
		</form>
		<h6>Preview teks:</h6>
		<div class="alert alert-info mb-0">
				<div id="peng_preview">Belum tersedia. <a id="renderprev" href="javascript:void(0);" class="no_sl">Klik di sini untuk memuat preview</a>.</div>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<h4 class="border-bottom pb-1">Kredensial Utama</h4>
		<form method="post" id="kadis">
			<h6 class="d-flex justify-content-between align-items-center">
				Kepala Dinas Pendidikan & Kebudayaan
				<input type="submit" class="btn btn-outline-primary btn-sm" value="SIMPAN"/>
			</h6>
			<div class="fg d-flex justify-content-between mb-2">
				<label class="w-25">Nama:</label>
				<input type="text" maxlength="50" class="w-75 form-control" name="kadis[nama]"/>
			</div>
			<div class="fg d-flex justify-content-between mb-2">
				<label class="w-25">NIP:</label>
				<input type="text" maxlength="50" class="w-75 form-control" name="kadis[nip]"/>
			</div>
			<div class="fg d-flex justify-content-between mb-2">
				<label class="w-25">Pangkat/Gol:</label>
				<input type="text" maxlength="50" class="w-75 form-control" name="kadis[gol]"/>
			</div>
		</form>
	</div>
</div>
<style>.fg>label{display:flex;margin:0;align-items:center}.fg>.btn-group-toggle{width:120px}#peng_preview ul,#peng_preview ol{padding-left: 1.3rem;}
</style>
<?php include('footer.php');?>
<script src="https://cdn.tiny.cloud/1/8nsic3ymoiny2q1d90lynqizdrpdhoaah8rtyffkek6rxygn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
	selector: '#notif',
	inline_styles : true,
	plugins: 'link lists',
	menubar: false,
	height : "480",
	toolbar: 'undo redo | forecolor link |	bold italic | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent',
	content_css : "https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap,https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css",
	content_style: "body{font-family:Roboto;padding:1rem}ol,ul{padding-left: 1.3rem}"
});
$('#mtc<?=sql_value($connect, "SELECT val1 FROM options WHERE set_var='maintenance'");?>').prop('checked',true);
$('#peng<?=sql_value($connect, "SELECT val1 FROM options WHERE set_var='pengumuman'");?>').prop('checked',true);
$('#reg<?=sql_value($connect, "SELECT val1 FROM options WHERE set_var='registrasi'");?>').prop('checked',true);
$('#ung<?=sql_value($connect, "SELECT val1 FROM options WHERE set_var='unggah_berkas'");?>').prop('checked',true);
//$('[name="peng[konten]"]').val('');
$('[name="peng[judul]"]').val('<?=sql_value($connect, "SELECT txt FROM options WHERE set_var='pengumuman'");?>');
<?$kadis=json_decode(sql_value($connect, "SELECT txt FROM options WHERE set_var='kadis'"),true);?>
$('[name="kadis[nama]"]').val('<?=$kadis["nama"];?>');
$('[name="kadis[nip]"]').val('<?=$kadis["nip"];?>');
$('[name="kadis[gol]"]').val('<?=$kadis["gol"];?>');

function peng_preview(){
	var etext=tinymce.activeEditor.getContent();
	$('#notif').val(etext);
	$('#peng_preview').html('<h4>'+$('[name="peng[judul]"]').val()+'</h4>'+etext+'<em class="text-right d-block">Terima kasih.</em>');
}
$(document).ready(function(){
	tinymce.activeEditor.on("input keyup change",function(){
		peng_preview();
	});
});
$(document).on("click","#renderprev",function(){
	peng_preview();
});
$('[name="peng[judul]"]').on("input",function(){
	peng_preview();
});
$('form').on("submit",function(event){
	event.preventDefault();
	$('#spinner_loader').show();
	$.ajax({
		url:"action/settings.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		success:function(data){
			if(data.success) topbar(1,data.text);
			if(data.error) topbar(0,data.text);
			$('#spinner_loader').fadeOut();
		}
	});
});
$('.set').on("change",function(){
	$('#spinner_loader').show();
	$(this).blur();
	$.ajax({
		url:"action/settings.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		success:function(data){
			if(data.success) topbar(1,data.text);
			if(data.error) topbar(0,data.text);
			$('#spinner_loader').fadeOut();
		}
	});
});
</script>