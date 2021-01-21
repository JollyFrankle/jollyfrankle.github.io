<?include 'header.php';?>
<div class="pb-2 mb-2 border-bottom d-block d-md-flex justify-content-between align-items-end">
    <h2 class="m-0">Daftar Formulir PAK Tersimpan (BETA)</h2>
    <div class="btn-toolbar mt-2 mt-md-0 btn-group" role="group" aria-label="Tindakan">
        <a href="pak" class="btn btn-outline-dark btn-sm">Tambah (Format Kosong)</a>
        <a href="gtk_search" class="btn btn-outline-dark btn-sm">Tambah (Pencarian GTK)</a>
    </div>
</div>
<div class="alert alert-info">
  <b>PERHATIAN:</b> Bagian ini sudah dinyatakan selesai, <b>namun belum diuji coba secara keseluruhan</b>.
</div>
<table class="onload_ajax table table-bordered table-hover" id="dft_pak">
  <thead class="bg-light">
    <tr>
      <th style="width:250px">Nama Pegawai</th>
      <th style="width:200px">TMT & Masa Kerja</th>
      <th>Aktivitas terakhir</th>
      <th style="min-width:200px">Tindakan</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<div class="modal fade" id="HapusDataModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Permanen Data PAK</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <h3>Apakah Anda yakin ingin menghapus data ini?</h3>
        <p class="m-0">Setelah dihapus, Anda tidak dapat memulihkan kembali data ini.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="ConfHapus" class="btn btn-danger btn-sm">Hapus</button>
        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
<style>#dft_pak td:last-child .btn {margin:.2em}#dft_pak td:last-child {padding:.3em .55em}#dft_pak td {vertical-align:middle}</style>
<?include 'footer.php';?>
<script>
var dataTable = $('#dft_pak').DataTable({
  "processing": false,
  "order": [[2,"desc"]],
  "scrollX": true,
  "language": {
      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
  },
  "ajax":{
    url:"action/pak.php",
    type:"POST",
    data:{action:'fetch_list'},
    dataType:"json",
    beforeSend:function(){$('#spinner_loader').show();},
    error: function(){
      $('#dft_pak_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
    },
    complete: function(){
        $('#spinner_loader').fadeOut();
    }
  }
});
$(document).on("click",".hps_pak",function(){
  $('#HapusDataModal').modal('show');
  $("#ConfHapus").attr("data-id",$(this).attr("data-id"));
});
$(document).on("click","#ConfHapus",function(){
  $('#spinner_loader').show();
  $.ajax({
    url:"action/pak.php",
    method:"POST",
    data:{pak_id:$(this).attr("data-id"), action:"hapus"},
    dataType:"json",
    success:function(x)
    {
      $('#spinner_loader').fadeOut();
      if(x.success)
      {
        topbar(1,x.text);
        $('#ConfHapus').attr("data-id","");
        $('#HapusDataModal').modal("hide");
        dataTable.ajax.reload();
      }
      if(x.error) topbar(0,x.text);
    }
  })
});
</script>