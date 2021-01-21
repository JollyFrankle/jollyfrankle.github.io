<?php include('header.php');?>
<div class="pb-2 mb-3 border-bottom d-block d-md-flex justify-content-between align-items-end">
		<h2 class="m-0">Manajemen Akun</h2>
</div>
<?if($_GET["err"]=="otp"){?>
<div class="alert alert-danger alertotp" role="alert">
		<b>PENTING:</b> Anda diketahui masuk akun menggunakan <b>kode one-time pin (OTP)</b>. Kode tersebut hanya berlaku satu kali. Silakan perbarui kata sandi Anda di bawah agar Anda tidak kehilangan akses ke akun Anda.
</div>
<?}?>
<form class="container m-auto px-0" method="post" autocomplete="off" id="form_akun" style="max-width:480px">
	<div class="alert alert-info"><b>PERHATIAN:</b> Sebelum Anda klik "Perbarui", pastikan Anda mengisi semua kotak di bawah ini. Apabila Anda hanya ingin mengubah alamat email, Anda juga perlu memasukkan kata sandi lama dan konfirmasinya. Kata sandi minimal 8 huruf.</div>
	<div class="row">
		<label class="col-4" for="adm_nama">
			Nama pemilik akun
		</label>
		<div class="col-8">
			<input autocomplete="off" class="form-control" type="text" name="adm_nama" id="adm_nama" value="<?=$_SESSION["adm_nama"];?>" required />
		</div>
		<div class="text-danger small mb-3 col-12" id="error_nama"></div>
		<label class="col-4" for="curr_email">
				Alamat email saat ini
		</label>
		<div class="col-8">
				<input class="form-control" id="curr_email" readonly value="<?php echo $_SESSION["adm_email"];?>" />
		</div>
		<div class="col-12 small text-muted mb-3">Di atas merupakan alamat email yang saat ini Anda gunakan untuk masuk ke sistem. Beberapa browser akan secara otomatis mengisi alamat email di bawah, tetapi mungkin berbeda dengan alamat email di atas. Apabila Anda tidak bermaksud mengubah alamat email, silakan salin alamat email di atas dan tempelkan ke kotak input "Alamat email" di bawah ini.</div>
		<label class="col-4" for="adm_email">
			Alamat email
		</label>
		<div class="col-8">
			<input autocomplete="off" class="form-control" type="text" name="adm_email" id="adm_email" value="<?php echo $_SESSION["adm_email"];?>"/>
		</div>
		<div class="col-12 small text-muted">Pastikan Anda menggunakan alamat email yang benar dan aktif agar dapat dihubungi di kemudian hari.</div>
		<div class="text-danger small mb-3 col-12" id="error_email"></div>
		<label class="col-4" for="adm_password">
			Kata sandi
		</label>
		<div class="col-8">
			<input class="form-control" type="password" name="adm_password" id="adm_password" value=""/>
		</div>
		<div class="col-12 small text-muted mb-3">Apabila Anda hanya ingin mengubah alamat email, Silakan tuliskan ulang kata sandi lama di kotak ini.</div>
		<div class="text-danger small mb-3 col-12" id="error_password"></div>
		<label class="col-4" for="p_conf">
			Konfirmasi kata sandi
		</label>
		<div class="col-8">
			<input class="form-control" type="password" id="p_conf" value=""/>
		</div>
		<div class="text-danger small mb-3 col-12" id="error_passconf"></div>
	</div>
	<div class="d-flex align-items-center justify-content-end p-2 border">
		<input type="hidden" name="action" value="ubah_akun"/>
		<input type="submit" name="update" id="update" class="btn btn-primary btn-sm" value="Perbarui"/>
	</div>
</form>

<style>#form_akun .col-8, #form_akun label{margin:0;display:flex;align-items:center}</style>
<?php include('footer.php');?>
<script>
$(document).ready(function(){
	$('#adm_password, #p_conf').val('');

$('#form_akun').on('submit', function(event){
	event.preventDefault();
	if($('#adm_email').val()=='') $('#error_email').text('Alamat email/surel tidak boleh dibiarkan kosong!');
	else $('#error_email').text('');
	if($('#adm_nama').val()=='') $('#error_nama').text('Nama tidak boleh dibiarkan kosong!');
	else $('#error_nama').text('');
	if($('#adm_password').val()=='') $('#error_password').text('Kata sandi tidak boleh dibiarkan kosong!');
	else $('#error_password').text('');
	if($('#adm_password').val()!=$('#p_conf').val()) $('#error_passconf').text('Kata sandi tidak sama!');
	else $('#error_passconf').text('');
	if($('#adm_password').val().length<8) $('#error_password').text('Kata sandi harus berisikan minimal 8 karakter.');
	else $('#error_password').text('');
	if($('#error_email,#error_password,#error_passconf').text()=='')
	$.ajax({
		url:"action/admin.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
			$('#update').val('Menyimpan...').prop('disabled', true);
			$('#spinner_loader').show();
		},
		success:function(x)
		{
			if(x.success) topbar(1,x.text);
			if(x.error)
			{
				$('#error_password').text(x.error_password);
				$('#error_email').text(x.error_email);
				$('#error_nama').text(x.error_nama);
			}
		},
		complete:function(){$('#spinner_loader').fadeOut();$('#update').val('Perbarui').prop('disabled', false);}
	})
});
});
</script>