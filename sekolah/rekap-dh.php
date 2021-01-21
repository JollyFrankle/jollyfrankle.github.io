<?php
include('header.php');
$_GET["b"]=addslashes($_GET["b"]);
$_GET["t"]=addslashes($_GET["t"]);
?>
<h2 class="border-bottom pb-2">Daftar Hadir Bulanan</h2>
<form class="form-row" method="get">
  <div class="col-sm mb-3 d-flex align-items-center">
    Periode:
  </div><div class="col-8 col-sm-3 mb-3">
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
  </div><div class="col-4 col-sm-2 mb-3">
  <select class="form-control custom-select" name="t">
    <option><?=date("Y");?></option>
    <option><?=date("Y")-1;?></option>
  </select>
  </div><div class="col-sm-2 mb-3">
    <input type="submit" class="w-100 btn btn-primary text-truncate" onclick="$('#spinner_loader').show()" value="Tampilkan"/>
  </div><div class="col-sm-3 mb-3 d-flex align-items-center">
    Cetak:
    <div class="btn-group ml-2 w-100">
      <a href="action/print-dh.php?b=<?=$_GET['b']?>&t=<?=$_GET['t']?>" class="btn btn-info btn-print no_sl">A4</a>
      <a href="action/print-dh.php?b=<?=$_GET['b']?>&t=<?=$_GET['t']?>&kertas=folio" class="btn btn-info btn-print no_sl">F4</a>
    </div>
  </div>
</form>
<?php if(empty(bulan($_GET["b"])) || empty($_GET["t"])):?>
        <div class="alert alert-info" id="notset" role="alert">
          <h5 class="alert-heading">Selamat datang di halaman Rekapitulasi Daftar Hadir</h5>
          <hr class="mt-2 mb-2">
          <p class="mb-0">Silakan gunakan baris input di atas untuk memilih periode laporan, yakni bulan dan tahun. Setelah itu, klik "Tampilkan" untuk menampilkan laporan untuk periode tersebut.</p>
        </div>
</body>
</html>
<?php include('footer.php');die();endif; ?>
<div class="">
    <span class="text-nowrap mr-2"><b>H</b> - Hadir</span>
    <span class="text-nowrap mr-2"><b>A</b> - Tanpa Berita</span>
    <span class="text-nowrap mr-2"><b>S</b> - Sakit</span>
    <span class="text-nowrap mr-2"><b>I</b> - Izin (pribadi)</span>
    <span class="text-nowrap mr-2"><b>D</b> - Dinas Luar</span>
    <span class="text-nowrap mr-2"><b>C</b> - Mengambil Cuti</span>
    <span class="text-nowrap mr-2"><b>B</b> - Tugas Belajar</span>
</div>
<div class="table-responsive">
<table class="onload_ajax table table-striped table-bordered" id="trb" style="min-width:1700px">
  <thead>
    <tr>
      <th>No.</th>
      <th style="min-width:260px">Nama, NIP</th>
      <?for($count=1;$count<=date("t",strtotime($_GET["t"].'-'.$_GET["b"]));$count++)echo'<th>'.$count.'</th>';?>
            <th style="min-width:115px">Rekap</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>
<style>
<?php // Offset kolom dari kiri:
$offcol=2;
function pre_tgl($tgl) {
  return "#trb td:nth-child(".$tgl."),#trb th:nth-child(".$tgl.")";
}
// Beri background biru untuk tanggal hari ini:
if($_GET["b"]==date(m) && $_GET["t"]==date(Y))
  echo pre_tgl(date(d)+$offcol)."{background:#b8daff}";
  
// Beri background merah untuk hari minggu:
$d1 = 7 - date('N',strtotime($_GET["t"].'-'.$_GET["b"]))+3;
$dn = date('t',strtotime($_GET["t"].'-'.$_GET["b"]))+1+$offcol;
for($i=$d1;$i<=$dn;$i=$i+7)echo pre_tgl($i)."{background:#f5c6cb}";

// Beri background hijau untuk tanggal yang dimaksud (melalui GET):
if(!empty($_GET["tgl"])) echo pre_tgl($_GET["tgl"]+$offcol)."{background:#c3e6cb}";
?>
#trb td{text-align:center;vertical-align:middle;font-weight:700;width:40px;padding:.25em .5em}
#trb th{text-align:center;padding:.5em}
#trb td:nth-child(2) {text-align:left;font-weight:500;}
#trb td:first-child {font-weight:400;}
#trb td:nth-child(2), #trb th:nth-child(2) {border-right:3px solid #007bff}
#trb td:last-child, #trb th:last-child {border-left:3px solid #007bff;background:initial;}
#trb td:last-child {font-weight:500;line-height:1.15}
</style>

<?php include('footer.php'); ?>
<script>
$('[name=b]').val('<?=$_GET['b']?>');$('[name=t]').val('<?=$_GET['t']?>');
$('title').html('<?='Daftar Hadir bln. '.bulan($_GET["b"]).' th. '.$_GET["t"].' - '.$_SESSION["sek_nama"];?> (per <?=date("d/m/Y H:i:s");?>)');
$(document).on("click",".btn-print",function(event){
  event.preventDefault();
  window.location = $(this).attr("href");
});
$(document).ready(function(){
  var dataTable = $('#trb').DataTable({
    "ordering": false,
    "paging": false,
    "info": false,
    "searching": false,
    "ajax":{
      url:"action/lap-dh.php",
      type:"POST",
      data:{action:'daftar_fetch', bln:'<?=$_GET["b"];?>', thn:'<?=$_GET["t"];?>'},
      beforeSend:function(){$('#spinner_loader').show()},
      error: function(){
        $('#trb_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>')
      },
      complete: function(){
        $('#spinner_loader').fadeOut();
        $('#trb td:not(#trb td:last-child,#trb td:nth-child(2))').each(function(){
          var t2r = $(this).text();
          t2r = t2r.replace("HD",".").replace("TB","A").replace("DL","D").replace("SK","S").replace("IZ","I").replace("TG","B").replace("CT","C");
          $(this).text(t2r);
        });
      }
    }
  });
});
</script>