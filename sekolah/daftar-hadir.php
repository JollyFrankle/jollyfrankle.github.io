<?php include('header.php');?>
<h2 class="pb-2 border-bottom">Unggah Daftar Hadir</h2>
<form method="post" id="tmb_laporan">
<div class="form-row align-items-center">
  <div class="col-md-9 col-12 mb-3 d-flex justify-content-between align-items-center">
    <label for="s_tgl" class="mb-0">Tanggal:</label>
    <input type="date" id="s_tgl" name="dfhd_tgl" class="form-control mx-1 mx-md-2"/>
    <button type="button" id="CariDataGTK" class="btn btn-primary text-truncate" style="width:120px">Cari GTK</button>
  </div>
	<div class="col-md-3 mb-3">
		<button type="button" onclick="$('#reportModal').modal('show');" class="btn btn-outline-success btn-block">Rekap Kehadiran</button>
	</div>
</div>
<div class="alert alert-info">
	<b>INFORMASI:</b> Silakan pilih tanggal presensi di atas, klik "Cari GTK" untuk mendapatkan daftar GTK, kemudian isi presensi GTK di bawah <b>sesuai dengan kondisi sebenarnya</b>. Memilih "hadir" atau "tanpa keterangan", maka keterangan tidak perlu diisi, namun selain itu, keterangan wajib diisi. Mohon diperhatikan: Presensi yang telah diunggah untuk tanggal yang telah ditentukan tidak dapat diubah lagi. Pastikan presensi yang Anda isi sudah benar sebelum Anda mengunggahnya.
</div>
<div class="table-responsive" id="form_tmbh">
	<table class="table table-hover table-bordered w-100 d-none" style="min-width:600px">
		<thead class="bg-light">
			<tr>
				<th width=40>No.</th>
				<th width=300>Nama, NIP</th>
				<th width=150>Kehadiran</th>
				<th width=250>Keterangan</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<div class="align-items-center justify-content-between p-2 d-flex border rounded bg-white" style="position:sticky;bottom:0">
	<span id="error_bawah" class="text-muted">Pastikan data presensi sudah valid.</span>
	<input type="hidden" name="action" value="unggah_baru"/>
	<input type="submit" id="SubmitPres" class="btn btn-primary btn-sm" value="Unggah" disabled/>
</div>
</form>
<style>
#form_tmbh td, #tbl_dh td{vertical-align:middle;padding: .5em .75em}#form_tmbh td:first-child{text-align:center;}#tbl_dh_wrapper .row:first-child{display: none}
</style>

<div class="modal fade" id="reportModal">
	<div class="modal-dialog">
		<form class="modal-content" action="rekap-dh" onsubmit="$('#spinner_loader').show()" method="get">
			<div class="modal-header">
				<h5 class="modal-title">Rekapitulasi Daftar Hadir Bulanan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<div class="form-group">
					<div class="row mb-3">
						<div class="col-4 d-flex align-items-center">Bulan</div>
						<div class="col-8">
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
					</div>
					<div class="row mb-3">
						<div class="col-4 d-flex align-items-center">Tahun</div>
						<div class="col-8">
							<select class="form-control custom-select" name="t">
								<option><?=date("Y");?></option>
								<option><?=date("Y")-1;?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="alert alert-info mb-0" role="alert">
						Untuk info lebih detil mengenai status kehadiran seorang GTK, Silakan gunakan fitur "Laporan Kehadiran" yang dapat Anda temui pada kolom "Tindakan" di sebelah kanan setiap GTK di halaman <a class="alert-link" href="daftar-gtk">Laporan Bulanan</a>.
				</div>
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-success btn-sm" value="Tampilkan"/>
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
			</div>
		</form>
	</div>
</div>
<!--<tr><td>22<input type=hidden name='gtk_id[]' value=75 /></td><td><div style='font-weight:500'>Yohanes Fransiskus Jiralonga, S.Pd.</div>NIP. 197710151998071001</td><td><select class='ketdfhd' gtkid='75' name='status_75'></select></td><td><input class='ket-field' id='ket_75' name='ket_75'/></td></tr>-->
<?php include('footer.php'); ?>
<script>
<?if($error){?>topbar(0,"<?=date("H:i:s: ").$err_text;?>");<?}?>
$('[name="b"]').val('<?=date(m);?>');
function LengkapiElemenPresensiAJAX()
{
  // Untuk status kehadiran:
  $(".ketdfhd").each(function(){
    $(this).addClass("form-control custom-select").html('<option value="HD" selected>Hadir</option><option value="IZ">Izin</option><option value="SK">Sakit</option><option value="TB">Tanpa berita</option><option value="DL">Dinas luar</option><option value="CT">Cuti</option><option value="TG">Tgs belajar</option>');
  });
  // Untuk keterangan kehadiran:
  $(".ket-field").each(function(){
    $(this).addClass("form-control").attr("type","text").attr("maxlength",50).attr("placeholder","maks. 50 karakter").attr("disabled",true).attr("required",true);
  });
  // Validasi status dan keterangan kehadiran:
  $('.ketdfhd').each(function(){
    $(this).on("change",function(){
  		if($(this).val()!='HD' && $(this).val()!='TB') {
  			$('#ket_'+$(this).attr('gtkid')).prop('disabled',false);
  		} else {$('#ket_'+$(this).attr('gtkid')).prop('disabled',true).val('');}
    });
  });
}
function interpretJSONdata(x)
{
  if(x[3]) nip="<div class='small'>NIP. "+x[3]+"</div>"; else nip="";
  $("#form_tmbh table tbody").append("<tr><td>"+x[1]+"<input type=hidden name='gtk_id[]' value="+x[0]+" /></td><td><div style='font-weight:500'>"+x[2]+"</div>"+nip+"</td><td><select class='ketdfhd' gtkid="+x[0]+" name='status_"+x[0]+"'></select></td><td><input class='ket-field' id='ket_"+x[0]+"' name='ket_"+x[0]+"'/></td></tr>");
}
function clear_field()
{
  $("#tmb_laporan")[0].reset();
  $("#CariDataGTK, #s_tgl").attr("disabled",false).attr("readonly",false);
  $("#form_tmbh table").addClass("d-none");
  $("#form_tmbh table tbody").html("");
}

$("#CariDataGTK").on("click",function(){
  if($("#s_tgl").val()=="")
  {
    topbar(0,"Tanggal belum diisi. Silakan diisi kemudian klik \"Cari GTK\"..");
    $('#s_tgl').addClass('is-invalid');
  }
  else $.ajax({
		url:"action/daftar_hadir.php",
		method:"POST",
		dataType:"json",
		data:{action:"fetch",tanggal:$("#s_tgl").val()},
		beforeSend:function(){
			$('#spinner_loader').show();
      $("#CariDataGTK, #s_tgl").attr("disabled",true);
		},
		success:function(data)
		{
			$('#spinner_loader').fadeOut();
			if(data.success)
			{
				topbar(1,data.text);
				JSONdata=data.data;
				//$("#form_tmbh table tbody").html(data.tbody); OBSOLETE
				JSONdata.forEach(interpretJSONdata);
				$("#form_tmbh table").removeClass("d-none");
				$("#s_tgl").attr("disabled",false).attr("readonly",true).removeClass("is-invalid");
				LengkapiElemenPresensiAJAX();
				$("#error_bawah").html("Tanggal Presensi: <strong>"+data.tanggal+"</strong>")
				$("#SubmitPres").attr("disabled",false).val("Unggah");
			}
			if(data.error)
			{
			  topbar(0,data.text);
			  $("#form_tmbh table").addClass("d-none");
			  $("#CariDataGTK, #s_tgl").attr("disabled",false);
			}
		},
		error:function()
		{
		  $("#CariDataGTK, #s_tgl").attr("disabled",false);
		}
	});
});
$('#tmb_laporan').on('submit', function(event){
	event.preventDefault();
	if($('#s_tgl').val()=='')
	{
		topbar(0,"Tanggal belum diisi. Silakan diisi kemudian klik \"Cari GTK\"..");
		$('#s_tgl').addClass('is-invalid');
	}
	else $.ajax({
		url:"action/daftar_hadir.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
			$('#spinner_loader').show();
			$('#s_tgl').removeClass('is-invalid');
			$('#SubmitPres').val('Mengirim...').attr('disabled', true);
		},
		success:function(data)
		{
			$('#spinner_loader').fadeOut();
			if(data.success)
			{
				topbar(1,data.text);
				$("#error_bawah").html("Presensi Anda telah diunggah. <a href="+data.checklink+">Silakan cek di sini</a>.");
				$('#SubmitPres').val('Unggah');
				clear_field();
			}
			if(data.error)
			{
			  topbar(0,data.text);
				$('#error_bawah').html('Terjadi kesalahan! Silakan cek log di atas.');
				$('#SubmitPres').attr('disabled', false).val('Unggah');
			}
		},
		error:function()
		{
		  $('#SubmitPres').attr('disabled', false).val('Unggah');
		}
	});
});
</script>