<?include'header.php';?>
<style>#tbl_sres td:nth-child(3){border-left:2px solid var(--primary)}#tbl_sres td:nth-child(4){text-align:center}#tbl_sres td:last-child a{margin:.2em}#tbl_sres td:last-child{padding:.1em .55em}#tbl_sres td{padding: .2em .4em;vertical-align:middle}#tbl_sres td:first-child{padding-left:2rem}.dtr-details{width:100%;columns:2}@media screen and (max-width:991px){.dtr-details{columns:1}}.dtr-data div{display:inline-block;margin-right:.5rem}</style>
<h2 class="border-bottom pb-2">Pencarian GTK</h2>
<div class="alert alert-info">
  <p><b>INFORMASI:</b> Anda dapat mencari GTK melalui identitas berupa: <b>nama, NIP, NUPTK, tempat lahir, kualifikasi, atau mata pelajaran</b>. Tersedia tindakan untuk membuat PAK dari PNS bersangkutan, atau melihat daftar GTK pada sekolah (klik pada tindakan ini apabila Anda ingin untuk kemudian mengubah, melihat laporan kehadiran, atau menghapus GTK).</p>
  <form class="form-row" method="POST">
    <label for="gsearch" class="mb-3 d-flex align-items-center col-4 col-md-3">Nama GTK:</label>
    <div class="col-8 col-md-7 mb-3">
      <input type="text" class="form-control" id="gsearch" autocomplete="off" placeholder="Identitas GTK yang ingin dicari"/>
    </div>
    <div class="col-12 col-md-2 mb-3">
      <input type="submit" id="gsbtn" class="btn btn-primary btn-block" value="Cari"/>
    </div>
  </form>
</div>
<table class="table table-bordered table-hover w-100 overflow-hidden" style="visibility:hidden" id="tbl_sres">
  <thead class="bg-light">
    <tr>
      <th style="width:200px"><span class="text-nowrap">Nama, NIP, NUPTK</span></th>
      <th style="width:150px">Sekolah</th>
      <th style="width:150px">Tempat, Tanggal Lahir</th>
      <th style="width:50px">Jenis<br>Kelamin</th>
      <th style="width:100px">Pend. Terakhir</th>
      <th style="width:100px">Status Kepegawaian</th>
      <th style="width:100px">Sertifikasi</th>
      <th style="width:70px">Gol / Ruang</th>
      <th style="width:100px">TMT<br>Pangkat Terakhir</th>
      <th style="width:100px">Tanggal SK<br>Berkala Terakhir</th>
      <th style="width:70px">Jenis GTK</th>
      <th style="width:120px">Mata Pelajaran</th>
      <th style="width:100px">Mengajar<br>di Kelas</th>
      <th style="width:100px">Status di Dapodik</th>
      <th style="min-width:145px">Tindakan</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<?include'footer.php';?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script>
  var dataTable = $('#tbl_sres').DataTable({
    "deferLoading": 0,
    "responsive":true, "paging":false, "ordering":false, "info":false, "searching":false, "serverSide":true,
    "ajax":{
      url:"action/daftar_gtk.php",
      type:"POST",
      data:function(d){
        d.action="fetch";
        d.type="search";
        d.s_value=$("#gsearch").val();
      },
      beforeSend:function(){$('#spinner_loader').show();$('#gsbtn,#gsearch').attr("disabled",true)},
      error: function(){},
      complete: function(){
        $('#spinner_loader').fadeOut();
        $("#tbl_sres").css("visibility","visible");
        $('#gsbtn,#gsearch').attr("disabled",false);
        topbar(1,"Pencarian berhasil. Ditemukan <strong>"+dataTable.rows().count()+" hasil</strong> untuk <strong>"+$("#gsearch").val()+"</strong>.");
        if(dataTable.rows().count()==0) $("#tbl_sres").css("visibility","hidden");
      }
    }
  })
$("form").on("submit",function(e){
  e.preventDefault();
  if($("#gsearch").val()=='') topbar(0,"Silakan masukkan data yang ingin dicari.");
  else if($("#gsearch").val().length<3) topbar(0,"Silakan masukkan setidaknya 3 huruf.");
  else {dataTable.ajax.reload();$('#console').html('')}
});
</script>