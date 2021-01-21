<?php include('header.php');?>
<div class="pb-2 mb-2 border-bottom d-block d-md-flex justify-content-between align-items-end">
	<h2 class="m-0">Data Sekolah</h2>
</div>
<form method="post" autocomplete="off" id="form_sekolah">
	<div class="row">
		<label class="col-sm-4 mb-3 font-weight-bold" for="sek_nama">
			Nama Sekolah
		</label>
		<div class="col-sm-8 input-group-lg mb-3">
			<input class="form-control-plaintext font-weight-bold" type="text" id="sek_nama" readonly/>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<h5 class="border-bottom mb-3">Identitas Sekolah</h5>
			<div class="form-row">
				<label class="col-4" for="sek_npsn">
					NPSN
				</label>
				<div class="col-8">
					<input class="form-control" type="text" id="sek_npsn" readonly/>
				</div>
				<label class="col-4" for="sek_tipe">
					Jenjang Pendidikan
				</label>
				<div class="col-8">
					<input type="text" readonly id="sek_tipe" class="form-control"/>
				</div>
				<label class="col-4" for="sek_dati2">
					Kabupaten
				</label>
				<div class="col-8">
					<input type="text" readonly id="sek_dati2" class="form-control"/>
				</div>
				<label class="col-4" for="sek_dati3">
					Kecamatan
				</label>
				<div class="col-8">
					<input type="text" readonly id="sek_dati3" class="form-control"/>
				</div>
				<!--<label class="col-4" for="sek_alamat">
					Alamat Sekolah
				</label>
				<div class="col-8">
					<textarea rows=3 class="form-control" name="sek_alamat" id="sek_alamat" maxlength="150" required placeholder="Jalan, RT/RW, Dusun, Desa/Kelurahan, Kecamatan - Kode Pos"></textarea>
				</div>-->
			</div>
		</div>
		<div class="col-md-6">
			<h5 class="border-bottom mb-3">Pembagian Rombongan Belajar</h5>
			<div class="form-row">
				<div class="col-9 mb-3">
					<input class="form-control" type="text" name="sek_rombel[0][jur]" id="pem_1" maxlength="50" placeholder="Jurusan" title="Maks. 50 huruf"/>
				</div>
				<div class="col-3 mb-3">
					<input class="form-control" type="number" name="sek_rombel[0][rb]" id="rom_1" min="0" max="99" placeholder="Jlh. Rombel"/>
				</div>
				<div class="col-9 mb-3">
					<input class="form-control" type="text" name="sek_rombel[1][jur]" id="pem_2" maxlength="50" placeholder="Jurusan" title="Maks. 50 huruf"/>
				</div>
				<div class="col-3 mb-3">
					<input class="form-control" type="number" name="sek_rombel[1][rb]" id="rom_2" min="0" max="99" placeholder="Jlh. Rombel"/>
				</div>
				<div class="col-9 mb-3">
					<input class="form-control" type="text" name="sek_rombel[2][jur]" id="pem_3" maxlength="50" placeholder="Jurusan" title="Maks. 50 huruf"/>
				</div>
				<div class="col-3 mb-3">
					<input class="form-control" type="number" name="sek_rombel[2][rb]" id="rom_3" min="0" max="99" placeholder="Jlh. Rombel"/>
				</div>
				<div class="col-9 mb-3">
					<input class="form-control" type="text" name="sek_rombel[3][jur]" id="pem_4" maxlength="50" placeholder="Jurusan" title="Maks. 50 huruf"/>
				</div>
				<div class="col-3 mb-3">
					<input class="form-control" type="number" name="sek_rombel[3][rb]" id="rom_4" min="0" max="99" placeholder="Jlh. Rombel"/>
				</div>
				<div class="col-9 mb-3">
					<input class="form-control" type="text" name="sek_rombel[4][jur]" id="pem_5" maxlength="50" placeholder="Jurusan" title="Maks. 50 huruf"/>
				</div>
				<div class="col-3 mb-3">
					<input class="form-control" type="number" name="sek_rombel[4][rb]" id="rom_5" min="0" max="99" placeholder="Jlh. Rombel"/>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="action" value="ubah_infosek"/>
	<div class="d-flex align-items-center justify-content-between p-2 border rounded">
		<div class="text-muted small">Silahkan klik "Perbarui" untuk perbarui data.</div>
		<div class="text-nowrap">
			<input type="submit" name="update" id="update" class="btn btn-primary btn-sm" value="Perbarui Data"/>
		</div>
	</div>
</form>

<style>#form_sekolah .col-8, #form_sekolah label{margin-bottom:1em;display:flex;align-items:center}</style>
<?php include('footer.php');?>
<script>
<?$data = array(":npsn"=>$_SESSION["sek_npsn"]);
  $st = $connect->prepare("SELECT dt_jp,dt_dati3,dt_status FROM tbl_sekdb WHERE dt_npsn=:npsn");
	$st->execute($data);
	$result = $st->fetchAll();
	foreach ($result as $r1) {?>
$('#sek_nama').val('<?=$_SESSION["sek_nama"];?>');
$('#sek_npsn').val('<?=$_SESSION["sek_npsn"];?>');
$('#sek_tipe').val('<?=$r1["dt_jp"].' '.$r1["dt_status"];?>');
$('#sek_dati2').val('<?=$_SESSION["dati2_clean"];?>');
$('#sek_dati3').val('<?=$r1["dt_dati3"];?>');
if ("<?=$r1["dt_jp"];?>"=="SMA") {
$('#pem_1, #pem_2, #pem_3').prop('readonly', true);
$('#rom_1, #rom_2, #rom_3').prop('readonly', false);
$('#pem_4, #pem_5, #rom_4, #rom_5').val('').prop('readonly', true).hide();
}
<?}?>
<?$st = $connect->prepare("SELECT sek_rombel FROM tbl_sekolah WHERE sek_npsn=:npsn");
	$st->execute($data);
	$result = $st->fetchAll();
	foreach ($result as $r2) {?>
var sek_rb = JSON.parse('<?if(empty($r2["sek_rombel"])) echo '[{"jur":"","rb":""},{"jur":"","rb":""},{"jur":"","rb":""},{"jur":"","rb":""},{"jur":"","rb":""}]'; else echo str_replace("'","\'",$r2["sek_rombel"]);?>');
$('#pem_1').val(sek_rb[0].jur); $('#rom_1').val(sek_rb[0].rb);
$('#pem_2').val(sek_rb[1].jur); $('#rom_2').val(sek_rb[1].rb);
$('#pem_3').val(sek_rb[2].jur); $('#rom_3').val(sek_rb[2].rb);
$('#pem_4').val(sek_rb[3].jur); $('#rom_4').val(sek_rb[3].rb);
$('#pem_5').val(sek_rb[4].jur); $('#rom_5').val(sek_rb[4].rb);
<?}?>
$('#form_sekolah').on('submit', function(event){
	event.preventDefault();
	$('#spinner_loader').show();
	$.ajax({
		url:"action/sekolah.php",
		data:$(this).serialize(),
		method:"POST",
		dataType:"json",
		beforeSend:function(){
			$('#update').val('Menyimpan...').attr('disabled', true);
		},
		success:function(data)
		{
			if(data.success) topbar(1,data.text);
			if(data.error) topbar(0,data.text);
		},
		complete:function(){
			$('#update').attr('disabled', false).val('Perbarui data');
			$('#spinner_loader').fadeOut();
		}
	})
});
if ($("#sek_tipe").val()=="SMA Negeri" || $("#sek_tipe").val()=="SMA Swasta") {
	$('#pem_1').val('Matematika dan Ilmu Pengetahuan Alam');
	$('#pem_2').val('Ilmu-Ilmu Sosial');
	$('#pem_3').val('Ilmu Bahasa dan Budaya');
	$('#pem_1, #pem_2, #pem_3').prop('readonly', true);
	$('#rom_1, #rom_2, #rom_3').prop('readonly', false);
	$('#pem_4, #pem_5, #rom_4, #rom_5').val('').prop('readonly', true).slideUp();
} else {
	$('#pem_1, #pem_2, #pem_3, #pem_4, #pem_5, #rom_1, #rom_2, #rom_3, #rom_4, #rom_5').prop('readonly', false);
}
</script>