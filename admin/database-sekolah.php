<?include'header.php';?>
<h2 class="border-bottom">Sekolah di Dapodik</h2>

<table class="onload_ajax table table-hover table-bordered" id="tbl_sekdb">
  <thead class="bg-light">
    <tr>
      <th>Kabupaten
        <select class="form-control custom-select">
          <option value="" selected>Semua Kabupaten/Kota</option>
          <option>Kabupaten Alor</option>
          <option>Kabupaten Belu</option>
          <option>Kabupaten Ende</option>
          <option>Kabupaten Flores Timur</option>
          <option>Kabupaten Kupang</option>
          <option>Kabupaten Lembata</option>
          <option>Kabupaten Malaka</option>
          <option>Kabupaten Manggarai Barat</option>
          <option>Kabupaten Manggarai Timur</option>
          <option>Kabupaten Manggarai</option>
          <option>Kabupaten Nagekeo</option>
          <option>Kabupaten Ngada</option>
          <option>Kabupaten Rote Ndao</option>
          <option>Kabupaten Sabu Raijua</option>
          <option>Kabupaten Sikka</option>
          <option>Kabupaten Sumba Barat Daya</option>
          <option>Kabupaten Sumba Barat</option>
          <option>Kabupaten Sumba Tengah</option>
          <option>Kabupaten Sumba Timur</option>
          <option>Kabupaten Timor Tengah Selatan</option>
          <option>Kabupaten Timor Tengah Utara</option>
          <option>Kota Kupang</option>
        </select>
      </th>
      <th>Kecamatan<input class="form-control"/></th>
      <th>Status
        <select class="form-control custom-select">
          <option value="" selected>Semua JP</option>
          <option>SMA Negeri</option>
          <option>SMA Swasta</option>
          <option>SMK Negeri</option>
          <option>SMK Swasta</option>
          <option>SLB Negeri</option>
          <option>SLB Swasta</option>
        </select>
      </th>
      <th>NPSN<input class="form-control"/></th>
      <th>Nama<input class="form-control"/></th>
      <th>Terakhir tarik data</th>
      <th>Tindakan</th>
    </tr>
  </thead>
</table>
<?include'footer.php';?>
<script>
$(document).ready(function(){
  var dataTable = $('#tbl_sekdb').DataTable({
    "processing": false,
    "ordering":false,
    "order":[[1,"asc"]],
    "scrollX":true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
    },
    initComplete: function () {
            this.api().columns().every( function () {
                var that = this;
                $( 'input,select', this.header() ).on( 'change clear input', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        },
    "ajax":{
      url:"action/daftar_sekolah.php",
      type:"POST",
      data:{action:'fetch_dbdapodik', dati2:'<?=$_GET["dati2"];?>', tipe:'<?=$_GET["tipe"];?>'},
      dataType:"json",
      beforeSend:function(){$('#spinner_loader').show();},
      error: function(){
        $('#tbl_sekdb_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
      },
      complete: function(){
          $('#spinner_loader').fadeOut();
          $('table th').addClass('input-group-sm');
      }
    }
  });
});
</script>