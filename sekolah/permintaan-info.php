<?php
include('../404.html');
http_response_code(404);die();
?>

<div class="pb-2 mb-3 border-bottom d-block d-md-flex justify-content-between align-items-end">
    <h2 class="m-0">Permintaan Informasi</h2>
    <div class="btn-toolbar mt-2 mt-md-0 btn-group" role="group" aria-label="Tindakan">
        <a href="#sec1" class="no_sl btn btn-outline-dark btn-sm">Tambah</a>
        <a href="#sec2" class="no_sl btn btn-outline-dark btn-sm">Daftar</a>
    </div>
</div>

<h4 class="border-bottom pb-1" id="sec1">Tambah Pertanyaan</h4>
<form method="post" id="tmb_buku_tamu">
<div class="form-group row">
    <label class="col-sm-3 mb-3 mt-1" for="bt_tipe">Tipe Pertanyaan</label>
    <div class="col-sm-9 mb-3">
      <select id="bt_tipe" name="bt_tipe" class="form-control custom-select" required>
        <option value="Umum">Umum</option>
        <option value="Sistem">Sistem</option>
        <option value="Khusus">Khusus</option>
        <option value="Rahasia">Rahasia</option>
      </select>
    </div>
    <label class="col-sm-3 mb-2 mt-1" for="bt_tanya">Pertanyaan</label>
    <div class="col-sm-9 mb-2">
        <textarea id="bt_tanya" name="bt_tanya" class="form-control" style="min-height:200px;" required></textarea>
    </div>
</div>
<input type="hidden" id="action" name="action" value="tambah"/>
<div class="align-items-center justify-content-between p-2 d-flex border rounded mb-3">
    <div class="text-muted small">Pertanyaan dengan tipe Umum dan Teknis akan ditampilkan ke publik setelah dijawab.<br>Pertanyaan dengan tipe Khusus dan Rahasia hanya akan ditampilkan kepada Anda setelah dijawab.</div>
    <input type="submit" id="submit_btn" name="submit_btn" class="btn btn-primary btn-sm" value="Tambah"/>
</div>
</form>

<div id="bt_tanya_appear"></div>

<h4 class="border-bottom pb-1" id="sec2">Daftar Pertanyaan</h4>
<div class="table-responsive rounded">
  <table class="w-100 onload_ajax" id="tbl_pertanyaan">
    <thead>
        <th class="d-none"></th>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<?php include('footer.php');?>
<script>
$("#bt_tanya").keyup(function(){
    $('#bt_tanya_appear').html($('#bt_tanya').val())
});

$(document).ready(function(){
  var dataTable = $('#tbl_pertanyaan').DataTable({
    "processing":true,
    "serverSide":true,
    "ordering": false,
    "lengthMenu": [[10, 20, 30], [10, 20, 30]],
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
    },
    "ajax":{
      url:"action/buku_tamu.php",
      type:"POST",
      data:{action:'fetch'},
      beforeSend:function(jqXHR){$('#spinner_loader').show();},
      error: function(jqXHR, textStatus, errorThrown){
      if (jqXHR.status != 0){
            $('#tbl_dh_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>')
            alert('Server sedang sibuk. Silahkan muat ulang halaman ini dalam beberapa saat ke depan.');
        }},
      complete: function(jqXHR){$('#spinner_loader').fadeOut()}
    }
  });
  
$('#tmb_buku_tamu').on('submit', function(event){
	event.preventDefault();
	$('#spinner_loader').show();
	$.ajax({
		url:"action/buku_tamu.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
			$('#submit_btn').val('Menyimpan...');
			$('#submit_btn').attr('disabled', true);
		},
		success:function(data)
		{
			$('#submit_btn').attr('disabled', false);
			$('#submit_btn').val('Tambah');
			if(data.success)
			{
				$('#console').html('<div class="alert alert-success small">'+data.success+'</div>');
				$('#tmb_buku_tamu')[0].reset();
				dataTable.ajax.reload();
			}
		}
	})
});
});
</script>