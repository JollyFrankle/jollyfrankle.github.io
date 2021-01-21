<?php
include('header.php');
?>
<h2 class="border-bottom">Laporan Bulanan</h2>
<form method="post" id="sek_lb_form">
  <div class="form-row">
    <div class="col-sm-2 mb-3 d-flex align-items-center">
      Periode:
    </div>
    <div class="col-sm-4 col-8 mb-3">
    <select class="form-control custom-select" name="lb_periode[]" id="sek_lb_bulan">
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
    </div><div class="col-sm-2 col-4 mb-3">
    <select class="form-control custom-select" name="lb_periode[]" id="sek_lb_tahun">
      <option value="<?=date("Y");?>"><?=date("Y");?></option>
      <option value="<?=date("Y")-1;?>"><?=date("Y")-1;?></option>
    </select>
    <input type="hidden" name="action" value="update_lb"/>
    </div><div class="col-sm-4 mb-3">
      <div class="btn-group w-100">
        <input type="submit" class="btn btn-primary text-truncate" id="update_lb" value="Perbarui"/>
        <button type="button" id="tmbh_gtk" class="btn btn-success">Tambah GTK</button>
      </div>
    </div>
  </div>
</form>
<table class="table table-bordered table-hover onload_ajax" style="min-width:2000px" id="tbl_dft_guru">
  <thead class="bg-light">
    <tr>
      <th colspan="5">Identitas Pribadi</th>
      <th colspan="5">Kepegawaian</th>
      <th colspan="3">Jabatan di Sekolah</th>
      <th style="width:100px" rowspan="2">Status di Dapodik</th>
      <th style="min-width:135px" rowspan="2">Tindakan</th>
    </tr>
    <tr>
      <th style="width:40px">No.</th>
      <th style="width:250px">Nama, NIP, NUPTK</th>
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
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<style>#tbl_dft_guru td:nth-child(4){text-align:center}#tbl_dft_guru td:last-child button{margin:.2em}#tbl_dft_guru td:last-child{padding:.1em .55em}#tbl_dft_guru td{padding: .2em .4em;vertical-align:middle}#tbl_dft_guru td:first-child{text-align:center}#form_gtk label{white-space:nowrap;margin: .5em 1em 0 0}#form_gtk option[disabled]{font-weight:500;font-style:italic;background:#ddd}.fg{margin-bottom:1rem;display:flex}
</style>

<div class="modal fade" id="formModal">
  <div class="modal-dialog modal-lg">
  	<form method="post" id="form_gtk" autocomplete="off">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_title"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body row">
          <div class="col-lg-6">
            <h6 class="border-bottom">Identitas Pribadi</h6>
            <div class="d-flex justify-content-between">
                <label for="gtk_no" class="m-0 w-25 mr-1 text-nowrap">No. Absen</label>
                <label for="gtk_nama" class="m-0 w-75 ml-1 text-nowrap">Nama GTK <small class="text-muted">(maks. 50 karakter)</small></label>
            </div>
            <div class="fg justify-content-between">
              <input type="number" name="gtk_no" id="gtk_no" class="form-control w-25 mr-1" min=1 max=255 required />
              <input type="text" name="gtk_nama" id="gtk_nama" class="form-control w-75 ml-1" maxlength=50 required />
            </div>
            <div class="fg">
              <label for="gtk_nip">NIP</label>
              <input type="text" name="gtk_nip" id="gtk_nip" class="form-control" pattern="\d*" maxlength=18 placeholder="18 digit" />
            </div>
            <div class="fg">
              <label for="gtk_nuptk">NUPTK</label>
              <input type="text" name="gtk_nuptk" id="gtk_nuptk" class="form-control" pattern="\d*" maxlength=16 placeholder="16 digit" />
            </div>
            <label for="ttl_tempat" class="m-0">Tempat, tanggal lahir</label>
            <div class="fg justify-content-between">
                <input type="text" class="form-control mr-1" name="gtk_ttl[]" pattern="[A-Za-z\s]+" placeholder="Tempat lahir" id="ttl_tempat"/>
                <input type="date" class="form-control ml-1" name="gtk_ttl[]" id="ttl_tgl"/>
            </div>
            <div class="fg">
              <label for="gtk_jk">Jenis kelamin</label>
              <select class="form-control custom-select" name="gtk_jk" id="gtk_jk" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
            <label for="kual_pt" class="m-0">Pendidikan terakhir</label>
            <div class="fg justify-content-between">
              <select class="form-control custom-select w-25 mr-1" name="gtk_kual[]" id="kual_pt" required>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="D1">D-I</option>
                <option value="D2">D-II</option>
                <option value="D3">D-III</option>
                <option value="D4">D-IV</option>
                <option value="S1">S-1</option>
                <option value="S2">S-2</option>
                <option value="S3">S-3</option>
              </select>
              <input type="text" name="gtk_kual[]" id="kual_ps" class="form-control w-75 ml-1" maxlength=50 required />
            </div>
            <h6 class="border-bottom">Data Pokok Pendidikan</h6>
            <div class="fg">
              <label for="dapodik">Status di Dapodik</label>
              <select class="form-control custom-select" name="dapodik" id="dapodik" required>
                <option value="IND">Induk</option>
                <option value="NON">Non-Induk</option>
                <option value="BLM">Belum Terdaftar</option>
              </select>
            </div>
            <div class="fg">
              <label for="sek_induk">Sekolah induk</label>
              <input type="text" name="sek_induk" id="sek_induk" class="form-control" maxlength=30/>
            </div>
          </div>
          <div class="col-lg-6">
            <h6 class="border-bottom">Kepegawaian</h6>
            <div class="fg">
              <label for="statpeg">Status Kepegawaian</label>
              <select class="form-control custom-select" name="statpeg" id="statpeg" required>
                <option value="PNS">Pegawai Negeri Sipil</option>
                <option value="KON">Kontrak Provinsi</option>
                <option value="KAB">Kontrak Kabupaten/Kota</option>
                <option value="GTY">Guru Tetap Yayasan</option>
                <option value="GTT">Guru Tidak Tetap Yayasan</option>
                <option value="HON">Honor Komite</option>
              </select>
            </div>
            <div class="d-flex justify-content-between">
              <label for="gtk_gol" class="m-0 w-50 mr-1">Gol/Ruang</label>
              <label for="gtk_sert" class="m-0 w-50 ml-1">Sertifikasi</label>
            </div>
            <div class="fg justify-content-between">
              <select class="form-control custom-select mr-1" name="gtk_gol" id="gtk_gol" required>
                <optgroup label="II/a - II/d">
                  <option>II / a</option>
                  <option>II / b</option>
                  <option>II / c</option>
                  <option>II / d</option>
                </optgroup>
                <optgroup label="III/a - III/d">
                  <option>III / a</option>
                  <option>III / b</option>
                  <option>III / c</option>
                  <option>III / d</option>
                </optgroup>
                <optgroup label="IV/a - IV/d">
                  <option>IV / a</option>
                  <option>IV / b</option>
                  <option>IV / c</option>
                  <option>IV / d</option>
                </optgroup>
              </select>
              <select class="form-control custom-select ml-1" name="gtk_sert" id="gtk_sert" required>
                <option value="1">Sudah</option>
                <option value="0">Belum</option>
              </select>
            </div>
            <div class="fg">
              <label for="gtk_sk_ber" style="min-width:125px;">Tgl SK Berkala <abbr title="terakhir">tkh</abbr></label>
              <input type="date" name="gtk_sk_ber" id="gtk_sk_ber" class="form-control"/>
            </div>
            <div class="fg">
              <label for="gtk_tmtpt" style="min-width:125px;">TMT Pgkt Terakhir</label>
              <input type="date" name="gtk_tmtpt" id="gtk_tmtpt" class="form-control"/>
            </div>
            <div class="fg">
              <label for="gtk_karpeg">No. Seri Karpeg</label>
              <input type="text" name="gtk_karpeg" id="gtk_karpeg" class="form-control" maxlength=12/>
            </div>
            <div class="fg" id="pen_bantuan">
              <label for="tamsil" id="label_tamsil"></label>
              <select class="form-control custom-select" name="tamsil" id="tamsil">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
            <div class="justify-content-between gaji_input" style="display:flex">
                <label for="gaji_bos" class="m-0 w-50 mr-1">Gaji BOS</label>
                <label for="gaji_tmb" class="m-0 w-50 ml-1" id="label_gaji_tmb"></label>
            </div>
            <div class="fg justify-content-between gaji_input">
                <input type="number" class="form-control mr-1" id="gaji_bos" min=0 step=1 name="gtk_gaji[]"/>
                <input type="number" class="form-control ml-1" id="gaji_tmb" min=0 step=1 name="gtk_gaji[]"/>
            </div>
            <h6 class="border-bottom">Jabatan di Sekolah</h6>
            <div class="d-flex">
              <label for="gtk_jenis">Jenis GTK</label>
              <select class="form-control custom-select" name="gtk_jenis[]" id="gtk_jenis" required>
                <option value="Guru">Guru</option>
                <option value="Tendik">Tendik</option>
                <option value="Kepsek">Kepsek</option>
              </select>
            </div>
            <div id="error_jenis"class="text-danger small mb-3"></div>
            <div id="nokepsek">
            <div class="fg">
              <label for="mapel">Mapel asuhan</label>
              <input type="text" name="gtk_jenis[]" id="mapel" class="form-control" maxlength=30 required/>
            </div>
            <div class="fg">
              <label for="gtk_kelas">Kelas didikan</label>
              <input type="text" name="gtk_jenis[]" id="gtk_kelas" class="form-control" maxlength=30 required/>
            </div>
            </div>
            <div class="fg" style="display:none" id="tmt_kepsek">
              <label for="gtk_tmt_kepsek">TMT Kepala Sekolah</label>
              <input type="date" name="gtk_jenis[]" id="gtk_tmt_kepsek" class="form-control overflow-hidden" placeholder="yyyy-mm-dd" required/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" name="gtk_id" id="gtk_id"/><input type="hidden" name="action" id="action"/>
        	<input type="submit" id="button_action" class="btn btn-primary btn-sm"/>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
        </div>
      </div>
  </form>
  </div>
</div>

<div class="modal fade" id="laporanModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Laporan Kehadiran GTK</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="fg" id="alert_lki">
            <div class="form-row">
                <label for="laporan_namagtk" class="col-4 d-flex align-items-center mb-3">Nama GTK</label>
                <div class="col-8 mb-3"><input type="text" readonly class="form-control" id="laporan_namagtk"/></div>
                <label for="tgl_awal" class="col-4 d-flex align-items-center m-0">Tanggal awal</label>
                <div class="col-8"><input type="date" id="tgl_awal" class="form-control"/></div>
                <div class="col-12 mb-3"><div id="error_tgl_awal" class="text-danger small"></div></div>
                <label for="tgl_akhir" class="col-4 d-flex align-items-center m-0">Tanggal akhir</label>
                <div class="col-8"><input type="date" id="tgl_akhir" class="form-control"/></div>
                <div class="col-12 mb-3"><div id="error_tgl_akhir" class="text-danger small"></div></div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm mb-3">
                <button id="tmpl_lap" class="btn btn-primary btn-block">Tampilkan</button>
            </div>
            <div class="col-sm mb-3">
                <button id="reset_lap" class="btn btn-success btn-block">Reset</button>
            </div>
            <div class="col-sm mb-3">
                <button class="btn btn-danger btn-block" data-dismiss="modal">Tutup</button>
            </div>
        </div>
        <div class="lki card m-0 border-primary" style="display:none">
          <div class="card-header p-3 bg-primary">
              <h5 class="card-title m-0 text-white text-truncate" id="lki_nama"></h5>
              <div class="text-light small"><span id="lki_nip"></span><span id="lki_nuptk"></span></div>
          </div>
          <div class="card-body p-3">
            <div class="px-3 py-1 mb-3 small bg-secondary text-light text-center rounded">
              <div class="text-truncate">Periode: <span id="lki_awal"></span> – <span id="lki_akhir"></span></div>
                <div class="text-truncate">Laporan per <span id="lki_now"></span></div>
            </div>
            <h6>Persentase Presensi</h6>
            <div id="lki_bar" class="mb-3"></div>
            <h6>Rekapitulasi Presensi</h6>
            <ul class="list-group mb-2 lki_rekap">
              <li class="list-group-item bg-info border-info text-light font-weight-bold">
                Jumlah hari belajar efektif
                <span class="badge badge-light" id="lki_total">14</span>
              </li>
            </ul>
            <ul class="list-group mb-3 lki_rekap">
              <li class="list-group-item">
                Jumlah kehadiran
                <span class="badge badge-dark" id="lki_hd"></span>
              </li>
              <li class="list-group-item">
                Jumlah ketidakhadiran tanpa keterangan
                <span class="badge badge-dark" id="lki_tk"></span>
              </li>
              <li class="list-group-item">
                Jumlah ketidakhadiran karena izin
                <span class="badge badge-dark" id="lki_iz"></span>
              </li>
              <li class="list-group-item">
                Jumlah ketidakhadiran karena sakit
                <span class="badge badge-dark" id="lki_sk"></span>
              </li>
              <li class="list-group-item">
                Jumlah ketidakhadiran karena dinas luar
                <span class="badge badge-dark" id="lki_dl"></span>
              </li>
              <li class="list-group-item">
                Jumlah ketidakhadiran karena cuti
                <span class="badge badge-dark" id="lki_ct"></span>
              </li>
              <li class="list-group-item">
                Jumlah ketidakhadiran karena tugas belajar
                <span class="badge badge-dark" id="lki_tg"></span>
              </li>
            </ul>
            <h6>Distribusi Presensi</h6>
            <div id="lki_table"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Penghapusan Data GTK</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h5>Apakah Anda yakin ingin menghapus GTK ini?</h5>
        <p class="mb-2">Anda akan menghapus GTK atas nama:</p>
        <h5 class="border-top border-bottom py-2" id="modal_hps_nama"></h5>
        <p class="m-0">Setelah datanya dihapus, maka data tersebut tidak dapat dipulihkan kembali.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="conf_hapus" class="btn btn-danger btn-sm">Hapus</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
<style>.tr-selected{background:#b8daff!important}.lki_rekap li {display: flex;justify-content: space-between;align-items: center;border-color: var(--dark);}.lki_rekap li .badge{font-size:1rem;margin:-.5rem 0}</style>
<?php include('footer.php');
$jd1 = json_decode(sql_value($connect, "SELECT sek_lb_periode FROM tbl_sekolah WHERE sek_id='".$_SESSION["sek_id"]."'"));?>
<script>
$(document).ready(function(){
    $('#form_gtk select, #form_gtk input').addClass('overflow-hidden');
    $('#sek_lb_bulan').val('<?=$jd1[0];?>');
    $('#sek_lb_tahun').val('<?=$jd1[1];?>');
  var dataTable = $('#tbl_dft_guru').DataTable({
    "scrollX": true,
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
    },
    "ajax":{
      url:"action/daftar_gtk.php",
      type:"POST",
      data:{action:'fetch'},
      beforeSend:function(){$('#spinner_loader').show();},
      error: function(){
        $('#tbl_dft_guru_wrapper').html('<div class="bg-dark text-white p-5" align="center"><h1>Server sibuk.</h1>Coba ulang dalam beberapa saat.</div>');
      },
      complete: function(){$('#spinner_loader').fadeOut()}
    }
  });

function clear_field(){
	$('#form_gtk')[0].reset();
	$('#form_gtk select').val('');
}

$('#tmbh_gtk').click(function(){
  $('#modal_title').text('Tambah GTK');
  $('#button_action, #action').val('Tambah');
  $('#formModal').modal('show');
  clear_field();
  validate();
});

function validate(){
  $('#error_jenis').text(null);
  kual1=$('#kual_pt').val();
  statpeg=$('#statpeg').val();
  // Kualifikasi GTK:
  if (kual1=="SD" || kual1=="SMP" || kual1=="SMA") $('#kual_ps').val(null).prop('disabled', true);
  else $('#kual_ps').prop('disabled', false);
  // Jenis GTK:
  if ($('#gtk_jenis').val()=="Guru") $('#mapel, #gtk_kelas').prop('disabled', false);
  else $('#mapel, #gtk_kelas').val(null).prop('disabled', true);
  if ($('#gtk_jenis').val()=="Kepsek"){
    $('#tmt_kepsek').slideDown().addClass('d-flex');
    $('#gtk_tmt_kepsek').prop('disabled',false);
    $('#nokepsek').slideUp();
  } else {
    $('#tmt_kepsek').slideUp().removeClass('d-flex');
    $('#gtk_tmt_kepsek').val(null).prop('disabled',true);
    $('#nokepsek').slideDown();
  }
  // Status Kepegawaian:
  if(statpeg=="PNS") $("#gtk_nip, #gtk_sk_ber, #gtk_gol, #gtk_karpeg, #gtk_tmtpt").prop('disabled',false);
  else $("#gtk_nip, #gtk_sk_ber, #gtk_gol, #gtk_karpeg, #gtk_tmtpt").val(null).prop('disabled',true);
  if (statpeg=="HON" || statpeg=="GTT") {
    $("#tamsil").prop('disabled', false);
    $("#pen_bantuan,.gaji_input").slideDown();
    $("#label_tamsil").text("Penerima Tamsil?");
    $('#gaji_bos,#gaji_tmb').prop('disabled',false);
    if(statpeg=="HON") $('#label_gaji_tmb').text('Gaji Komite');
    if(statpeg=="GTT") $('#label_gaji_tmb').text('Gaji Yayasan');
  } else if (statpeg=='PNS' && $('#gtk_sert').val()=='0') {
    $("#tamsil").prop('disabled', false);
    $("#pen_bantuan").slideDown();
    $("#label_tamsil").text("Penerima Tunj. Non-sertif?");
    $('#gaji_bos,#gaji_tmb').prop('disabled',true);
    $('.gaji_input').slideUp();
  } else {
    $("#tamsil").val(0).prop('disabled', true);
    $("#pen_bantuan,.gaji_input").slideUp();
    $('#gaji_bos,#gaji_tmb').prop('disabled',true);
  }
  // Status di Dapodik:
  if($('#dapodik').val() == "BLM") $("#gtk_nuptk").val(null).prop('disabled', true);
  else $("#gtk_nuptk").prop('disabled', false);
  if($('#dapodik').val() == "NON") $("#sek_induk").prop('disabled', false);
  else $("#sek_induk").val('<?=$_SESSION["sek_nama"];?>').prop('disabled', true);
}
$("#form_gtk").change(function(){validate();});
$('#form_gtk').on('submit', function(event){
	event.preventDefault();
	$('#spinner_loader').show();
	$.ajax({
		url:"action/gtk_single.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
			$('#button_action').val('Menyimpan...').prop('disabled', true);
		},
		success:function(data)
		{
			$('#button_action').val('Ulangi').prop('disabled', false);
			if(data.success)
			{
				topbar(1,data.text);
				clear_field();
				$('#formModal').modal('hide');
				dataTable.ajax.reload();
			}
			if(data.error)
			{
		    $('#spinner_loader').fadeOut();
		    $('#error_jenis').text(data.error_jenis);
			}
		},
		error:function(){
      $('#button_action').prop('disabled', false).val('Ulangi');
		}
	})
});

  $('#sek_lb_form').on('submit',function(event){
    event.preventDefault();
    $('#spinner_loader').show();
    $.ajax({
      url:"action/daftar_gtk.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      success:function(data)
      {
        $('#spinner_loader').fadeOut();
        if(data.success) topbar(1,data.text);
        if(data.error) topbar(0,data.text);
      }
    })
  });

  var gtk_id = '';
  $(document).on('click', '.btn_ubah', function(){
    $('#spinner_loader').show();
    gtk_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"action/gtk_single.php",
      method:"POST",
      data:{action:'edit_fetch', gtk_id:gtk_id},
      dataType:"json",
      success:function(x)
      {
        $('#spinner_loader').fadeOut();
        $.each(x,function(par,val){$('#'+par).val(val)});
        $('#modal_title').text('Ubah Data GTK');
        $('#button_action, #action').val('Ubah');
        $('#formModal').modal('show');
        validate();
      }
    })
  });

  $(document).on('click', '.btn_hps', function(){
    gtk_id = $(this).attr('id');
    data_gtk_nama = $(this).attr('nama_gtk');
    $('#deleteModal').modal('show');
    $('#modal_hps_nama').text($(this).attr('nama_gtk'));
  });

  $('#conf_hapus').click(function(){
    $('#spinner_loader').show();
    $.ajax({
      url:"action/gtk_single.php",
      method:"POST",
      data:{gtk_id:gtk_id, gtk_nama:data_gtk_nama, action:"delete"},
      dataType: "json",
      success:function(data)
      {
        if(data.success) topbar(1,data.text);
        if(data.error) topbar(0,data.text);
        $('#deleteModal').modal('hide');
        dataTable.ajax.reload();
      }
    })
  });

  $(document).on('click', '.btn_lap', function(){
    std_id = $(this).attr('id');
    $('#laporan_namagtk').val($(this).siblings('.btn_hps').attr('nama_gtk'));
    $('#laporanModal').modal('show');
  });
  $('#tmpl_lap').click(function(){
      if($('#tgl_awal').val() =='') {$('#error_tgl_awal').text('Silahkan mengisi tanggal awal laporan');} else {$('#error_tgl_awal').text('')}
      if($('#tgl_akhir').val() =='') {$('#error_tgl_akhir').text('Silahkan mengisi tanggal akhir laporan');} else {$('#error_tgl_akhir').text('')}
      if($('#tgl_awal').val() !=='' && $('#tgl_akhir').val() !=='') {
          $('#spinner_loader').show();
        $.ajax({
          url:"action/gtk_single.php",
          method:"POST",
          data:{gtk_id:std_id, awal:$('#tgl_awal').val(), akhir:$('#tgl_akhir').val(), action:"kehadiran"},
          dataType:"json",
          success:function(data)
          {
            $('#spinner_loader').fadeOut();
            $('[id^="lki_"]').text('');
            $('.lki').slideDown();
            $('#alert_lki').slideUp();
            $.each(data,function(par,val){$('#lki_'+par).html(val);});
          }
        })
      }
  });
  
  $('#reset_lap').click(function() {
    $('.lki').slideUp();
    $('#alert_lki').slideDown();
  });
  $('#laporanModal').on('hide.bs.modal', function () {
    $('[id^="lki_"]').text('');
    $('.lki').slideUp();
    $('#alert_lki').slideDown();
  });
  $(document).on("click", "#tbl_dft_guru tbody tr",function(){
    $(this).toggleClass("tr-selected");
  });
});
</script>