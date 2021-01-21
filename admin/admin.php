<?php
include('header.php'); if(!($_SESSION["adm_level"]==0 || $_SESSION["adm_level"]==1)) {
?>
<div class="jumbotron bg-danger">
  <div class="container text-light">
    <h1 class="display-4">GALAT!</h1>
    <p class="lead">Halaman ini tidak dapat dimuat!</p>
  </div>
</div>
<?include 'footer.php';die();}?>
<div class="pb-2 mb-2 border-bottom d-block d-md-flex justify-content-between align-items-end">
    <h2 class="m-0">Daftar Pengelola/Admin</h2>
    <div class="btn-toolbar mt-2 mt-md-0 btn-group">
      <button id="AddAcc" class="btn btn-sm btn-outline-dark">Tambah Akun Admin</button>
    </div>
</div>
<div class="alert alert-info">
    <p><b>INFORMASI:</b> Admin dinas dapat menambah, mengubah, dan mengenerate OTP untuk kategori admin dinas dan admin tingkat II. <b>Fitur penghapusan admin untuk saat ini dinonaktifkan</b>.</p>
    <button id="dtreload" class="btn btn-outline-primary btn-sm">Reload tabel</button>
</div>
  		<div class="table-responsive">
        <table class="onload_ajax table table-hover" id="dft_admin">
          <thead class="bg-light">
            <tr>
              <th>Pemilik Akun</th>
              <th>Status</th>
              <th>Aktivitas terakhir</th>
              <th>Tindakan</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
  		</div>
<style>#dft_admin td:last-child .btn {margin:.2em}#dft_admin td:last-child {padding:.3em .55em}#dft_admin td {vertical-align:middle}</style>
</body>
</html>

<div class="modal fade" id="OTPrequest">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Generate OTP untuk login</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p class="text-center"><b>PERHATIAN!</b> Anda akan mengenerate kode OTP untuk akun <b id="otp_namaadmin"></b>. Silakan gunakan fitur ini <b>hanya apabila akun tersebut meminta</b>.</p>
        <div class="text-center">
            <kbd class="d-inline-block h1 px-3 py-2 mb-4" id="kodeOTP">***-***</kbd>
            <button class="btn btn-primary btn-block" id="generateOTP" sekolah_id="">Generate OTP</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="EditAcc">
  <div class="modal-dialog">
    <form class="modal-content" id="AccForm">
      <div class="modal-header">
        <h5 class="modal-title" id="EditAccH5">Tambah/Ubah Informasi Akun</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <label for="adm_nama" class="col-4">Nama pemilik:</label>
          <div class="col-8">
            <input type="text" maxlength="30" name="adm_nama" id="adm_nama" required class="form-control"/>
          </div>
          <div class="col-12 mb-3"></div>
          
          <label for="adm_email" class="col-4">Alamat email:</label>
          <div class="col-8">
            <input type="email" name="adm_email" id="adm_email" required class="form-control"/>
          </div>
          <div class="col-12 mb-3 small text-danger" id="error_email"></div>
          
          <label class="col-4 otp4form" for="adm_otp">OTP (login pertama)</label>
          <div class="col-8 align-contents-between otp4form" style="display:flex">
            <input type="text" class="form-control" readonly name="adm_otp" id="adm_otp" onfocus="this.select()"/>
            <button type="button" class="btn btn-info ml-2 text-nowrap" id="genOTPform">Buat OTP</button>
          </div>
          <div class="col-12" id="err_firstotp"></div>
          <div class="col-12 mb-3 small text-muted otp4form">Silakan generate OTP untuk pengguna ini gunakan dalam login pertamanya. Setelah berhasil login, pengguna ybs diminta segera membuatkan kata sandi.</div>
          
          <label for="adm_level" class="col-4">Kemampuan akun:</label>
          <div class="col-8">
            <select class="form-control custom-select" required id="adm_level" name="adm_level">
              <option value="1">Admin Dinas</option>
              <option value="2">Admin Wilayah</option>
              <option value="3">Pengakses Presensi</option>
            </select>
          </div>
          <div class="col-12 small text-primary mb-3" id="info_level"></div>
          
        </div>
        <div class="test-danger small" id="error_lvl2"></div>
        <label for="adm_jp" class="dati2sel">Jenjang pendidikan:</label>
        <select class="form-control custom-select mb-3 dati2sel" multiple name="adm_dati2[jp][]" id="adm_jp">
            <option value="SMA">Sekolah Menengah Atas</option>
            <option value="SMK">Sekolah Menengah Kejuruan</option>
            <option value="SLB">Sekolah Luar Biasa</option>
        </select>
        <label for="adm_dati2" class="dati2sel">Daerah tanggungjawab:</label>
        <div class="small text-muted dati2sel">Gunakan <kbd>CTRL</kbd> untuk memilih satu-per-satu wilayah, atau gunakan <kbd>SHIFT</kbd> untuk memilih rentangan wilayah tertentu.</div>
        <select class="form-control custom-select mb-3 dati2sel" multiple name="adm_dati2[dt2][]" id="adm_dati2" style="height:15em">
          <optgroup label="Daratan Timor (selatan ke utara)">
            <option value="60">Kota Kupang</option>
            <option value="01">Kabupaten Kupang</option>
            <option value="03">Kabupaten Timor Tengah Selatan</option>
            <option value="04">Kabupaten Timor Tengah Utara</option>
            <option value="05">Kabupaten Belu</option>
            <option value="22">Kabupaten Malaka</option>
          </optgroup>
          <optgroup label="Daratan Flores (barat ke timur)">
            <option value="16">Kabupaten Manggarai Barat</option>
            <option value="11">Kabupaten Manggarai</option>
            <option value="20">Kabupaten Manggarai Timur</option>
            <option value="10">Kabupaten Ngada</option>
            <option value="17">Kabupaten Nagekeo</option>
            <option value="09">Kabupaten Ende</option>
            <option value="08">Kabupaten Sikka</option>
            <option value="07">Kabupaten Flores Timur</option>
            <option value="14">Kabupaten Lembata</option>
          </optgroup>
          <optgroup label="Daratan Sumba (timur ke barat)">
            <option value="12">Kabupaten Sumba Timur</option>
            <option value="18">Kabupaten Sumba Tengah</option>
            <option value="13">Kabupaten Sumba Barat</option>
            <option value="19">Kabupaten Sumba Barat Daya</option>
          </optgroup>
          <optgroup label="NTT Kepulauan (utara ke selatan)">
            <option value="06">Kabupaten Alor</option>
            <option value="15">Kabupaten Rote Ndao</option>
            <option value="21">Kabupaten Sabu Raijua</option>
          </optgroup>
        </select>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="action" id="EditAccAction"/>
        <input type="hidden" name="adm_id" id="adm_id"/>
        <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">Batal</button>
        <input type="submit" id="EditAccSubmit" class="btn btn-primary btn-sm"/>
      </div>
    </form>
  </div>
</div>
<style>#EditAcc label.col-4{display:flex;align-items:center;margin:0}.otp4form</style>
<?php include('footer.php');?>
<script>
/*$('#adm_dati2').on("change",function(){
    $('#info_level').text($('#adm_dati2').val());
});*/
function validate(){
    if($('#adm_level').val()==2) $('.dati2sel').slideDown();else $('.dati2sel').slideUp();
    if($('#adm_level').val()==1) $('#info_level').text('Pengguna ini memiliki akses ke setiap sekolah pada setiap wilayah, serta dapat membuat OTP (one-time pin) untuk sekolah yang mengalami kesusahan login.');
    if($('#adm_level').val()==2) $('#info_level').text('Pengguna ini memiliki akses ke setiap sekolah pada setiap wilayah yang diizinkan (atur di bawah). Pengguna ini tidak memiliki hak membuat OTP untuk sekolah yang membutuhkan.')
    if($('#adm_level').val()==3) $('#info_level').text('Pengguna ini hanya memiliki hak mengakses dan mengunduh presensi yang diunggah sekolah.');
}
function rand3(){
    return Math.floor(Math.random()*(999-100))+100;
}
function resetFormAcc(){
    $('#AccForm')[0].reset();
    $('#error_email,#error_lvl2').text('');
}
$('#AccForm').on("change",function(){
    validate();
});
$(document).on("click",'#genOTPform',function(){
    $('#adm_otp').val(rand3()+'-'+rand3());
});
$(document).ready(function(){
  var dataTable = $('#dft_admin').DataTable({
    "processing": false,
    "lengthMenu": [[10, 20, 30], [10, 20, 30]],
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
    },
    "ajax":{
      url:"action/admin.php",
      type:"POST",
      data:{action:'fetch'},
      dataType:"json",
      beforeSend:function(){$('#spinner_loader').show();},
      error: function(){
        $('#dft_admin_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
      },
      complete: function(){
          $('#spinner_loader').fadeOut();
      }
    }
  });

$(document).on('click', '#dtreload', function() {
    dataTable.ajax.reload();
    $('#spinner_loader').show();
    topbar(1,"Tabel telah direload");
});

$('#AccForm').on('submit', function(event){
	event.preventDefault();
	if($('#EditAccAction').val()=='Tambah' && $('#adm_otp').val()=='')
	$('#err_firstotp').html('<div class="alert alert-danger">Silakan buatkan kode OTP.</div>');
	else $.ajax({
		url:"action/admin.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
      $('#spinner_loader').show();
			$('#EditAccSubmit').val('Menyimpan...').attr("disabled",true);
		},
		success:function(data)
		{
			if(data.success)
			{
				topbar(1,data.text);
				$('#EditAcc').modal('hide');
				resetFormAcc();
				dataTable.ajax.reload();
			}
			if(data.error)
			{
        $('#error_email').text(data.error_email);
        $('#error_lvl2').text(data.error_lvl2);
			}
		},
    complete:function(){
      $('#spinner_loader').fadeOut();
      $('#EditAccSubmit').attr('disabled', false).val($('#EditAccAction').val());
    }
	})
});

  $(document).on('click', '.otp_adm', function(){
      $('#otp_namaadmin').text($(this).attr('data-admnama'));
      $('#generateOTP').attr('data-admid', $(this).attr('data-admid'));
      $('#kodeOTP').text('***-***');
      $('#OTPrequest').modal('show');
  });
  
  $(document).on('click', '#generateOTP', function(){
    $('#spinner_loader').show();
    $(this).prop('disabled',true);
    $.ajax({
      url:"action/admin.php",
      method:"POST",
      data:{action:'generateOTP', adm_id:$(this).attr('data-admid')},
      dataType:"json",
      success:function(data)
      {
        if(data.success) $('#kodeOTP').text(data.OTP);
        if(data.error) topbar(0,data.text);
      },
      complete:function(){
        $('#spinner_loader').fadeOut();
        $('#generateOTP').prop('disabled',false);
      }
    })
  });
  $(document).on("click","#AddAcc",function(){
      $('#EditAccH5').text('Tambah Akun Pengelola');
      $('#EditAccSubmit,#EditAccAction').val('Tambah');
      $('#EditAcc').modal('show');
      resetFormAcc();
      $('.otp4form').show();
  });
  var adm_id = '';

  $(document).on('click', '.edit_akun', function(){
    $('#spinner_loader').show();
    resetFormAcc();
    adm_id = $(this).attr('data-admid');
    $.ajax({
      url:"action/admin.php",
      method:"POST",
      data:{action:'fetch_accinfo', adm_id:adm_id},
      dataType:"json",
      success:function(x)
      {
        if(x.adm_dati2)wil=JSON.parse(x.adm_dati2);
        $('#spinner_loader').fadeOut();
        $('#adm_id').val(adm_id);
        $('#adm_nama').val(x.adm_nama);
        $('#adm_email').val(x.adm_email);
        $('#adm_level').val(x.adm_level);
        if(x.adm_dati2)$('#adm_dati2').val(wil.dt2);
        if(x.adm_dati2)$('#adm_jp').val(wil.jp);
        $('.otp4form').hide();
        $('#EditAccH5').text('Ubah Akun Pengelola');
        $('#EditAccSubmit,#EditAccAction').val('Ubah');
        $('#EditAcc').modal('show');
        validate();
      },
    })
  });
});
</script>