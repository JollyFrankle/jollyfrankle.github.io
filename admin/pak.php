<?include'header.php';?>
<style>#InfoAtas label{margin-bottom:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block}clr{display:inline-block;width:1.25em}.table td,.table th{padding:.1rem .35rem;vertical-align:middle;border-color:#343a40!important}::-webkit-calendar-picker-indicator{margin-left:0}.form-control-plaintext{outline:none;background:#eee;padding:0;margin:-.5em 0}@media print{.navbar.fixed-top{display:none!important}.print-card{border:0!important;padding:0;margin:-3em; -1em}.print-hidden{display:none!important}.pak-main{display:block!important}.form-control-plaintext{background:none}::-webkit-calendar-picker-indicator {display:none;-webkit-appearance:none}}@page {size: 210mm 297mm;margin:2em;}</style>
<h2 class="border-bottom pb-2 print-hidden">Perhitungan Angka Kredit</h2>
<form id="pak_main" autocomplete=off method="post"<? if(!empty($_GET["id_gtk"]) || !empty($_GET["id_pak"]))echo' class="onload_ajax"';?>>
<div class="alert alert-info print-hidden" id="InfoAtas">
	<h4>Pengaturan Dasar</h4>
	<p>Silahkan lengkapi beberapa informasi di bawah ini sebelum Anda mulai mengisi PAK:</p>
	<div class="form-row">
		<div class="col-md-3 mb-3">
			<label for="no_surat">Nomor Surat</label>
			<input type="text" id="no_surat" class="form-control input2box" maxlength=50 />
		</div>
		<div class="col-md-3 mb-3">
			<label for="tgl_awal">Tgl. Awal Penilaian</label>
			<input type="text" id="tgl_awal" class="form-control input2box" maxlength=20 />
		</div>
		<div class="col-md-3 mb-3">
			<label for="tgl_akhir">Tgl. Akhir Penilaian</label>
			<input type="text" id="tgl_akhir" class="form-control input2box" maxlength=20 />
		</div>
		<div class="col-md-3 mb-3">
			<label for="akmin">Angka Kredit Minimal <small class="text-muted">(otomatis)</small></label>
			<input type="number" id="akmin" class="form-control" max=9999 />
		</div>
		<div class="col-md-3 mb-3">
			<label for="font">Font (jenis huruf)</label>
			<select id="font" class="form-control custom-select">
				<option>Roboto</option>
				<option>Bookman Old Style</option>
				<option>Times New Roman</option>
				<option>Arial</option>
				<option>Arial Narrow</option>
				<option>Calibri</option>
			</select>
		</div>
		<div class="col-md-3 mb-3">
			<label for="gol_rg">Gol. Ruang saat ini</label>
			<select id="gol_rg" class="custom-select form-control">
				<option disabled>— II/a - II/d:</option>
				<option>II / a</option>
				<option>II / b</option>
				<option>II / c</option>
				<option>II / d</option>
				<option disabled>— III/a - III/d:</option>
				<option>III / a</option>
				<option>III / b</option>
				<option>III / c</option>
				<option>III / d</option>
				<option disabled>— IV/a - IV/d:</option>
				<option>IV / a</option>
				<option>IV / b</option>
				<option>IV / c</option>
				<option>IV / d</option>
			</select>
		</div>
		<div class="col-md-3 mb-3">
			<label for="monthsel">Target kenaikan</label>
			<select id="monthsel" class="custom-select form-control">
				<option value=4></option>
				<option value=10></option>
			</select>
		</div>
		<div class="col-md-3 mb-3">
			<label>Cetak uk. A4 (297x210mm)</label>
			<button onclick="window.print();" type="button" class="btn btn-block btn-light">Cetak halaman ini</button>
		</div>
	</div>
</div>
<div class="card card-body print-card">
	<div style="overflow-x:auto;overflow-y:hidden;">
		<div class="pak-main m-auto" style="min-width:1000px;max-width:1000px;font-size:12pt">
<div class="row">
		<div class="col-2 m-auto">
				<img src="/storage/media/LogoNTT-BW-110px.png">
		</div>
		<div class="col-10 text-center">
				<div style="font-size:16pt" class="h4 m-0">PEMERINTAH PROVINSI NUSA TENGGARA TIMUR</div>
				<div style="font-size:20pt" class="h3 m-0 font-weight-bold">DINAS PENDIDIKAN DAN KEBUDAYAAN</div>
				<div style="font-size:12pt">Jalan Jenderal Soeharto No. 57 Telp. (0380) 833064 Kupang</div>
				<div style="font-size:12pt">Faximile 821954 Kode Pos 85118</div>
		</div>
	</div>
	<hr class="border border-dark mb-2 mt-1">
	<div class="text-center mb-3">
		<div class="h4 mb-1 text-underline">PENETAPAN ANGKA KREDIT</div>
		<div class="h5 mb-0">JABATAN FUNGSIONAL GURU</div>
		<div class="mb-0">NOMOR: <span id="set_no_surat"></span></div>
	</div>
	<div class="d-flex justify-content-between font-weight-bold">
			<div>INSTANSI: PEMERINTAH PROVINSI NTT</div>
			<div>MASA PENILAIAN: <span id="set_tgl_awal">NOT SET</span> s/d <span id="set_tgl_akhir">NOT SET</span></div>
	</div>
<table class="table table-bordered" id="perhitungan">
	<tbody id="ket-perorangan">
		<tr>
			<th>I.</th>
			<th colspan=9>KETERANGAN PERORANGAN</th>
		</tr>
		<tr>
			<td rowspan="13"></td>
			<td width=30>1.</td>
			<td colspan=2>N a m a</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_nama" class="input2box form-control-plaintext font-weight-bold" maxlength=50></td>
		</tr>
		<tr>
			<td>2.</td>
			<td colspan=2>N I P</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_nip" class="form-control-plaintext" maxlength=21></td>
		</tr>
		<tr>
			<td>3.</td>
			<td colspan=2>NUPTK</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_nuptk" class="form-control-plaintext" maxlength=20></td>
		</tr>
		<tr>
			<td>4.</td>
			<td colspan=2>Nomor Seri Karpeg</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_karpeg" class="form-control-plaintext" maxlength=10></td>
		</tr>
		<tr>
			<td>5.</td>
			<td colspan=2>Pangkat/Golongan Ruang/TMT</td>
			<td class="text-center">:</td>
			<td colspan=4>
				<div class="d-flex justify-content-between overflow-hidden">
					<span id="gtk_gol"></span>
					<input style="width:150px" type="date" id="gtk_tmtpt" class="form-control-plaintext">
				</div>
			</td>
		</tr>
		<tr>
			<td>6.</td>
			<td colspan=2>Tempat dan Tanggal Lahir</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_ttl" class="form-control-plaintext" maxlength=30></td>
		</tr>
		<tr>
			<td>7.</td>
			<td colspan=2>Jenis Kelamin</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_jk" class="form-control-plaintext" maxlength=9></td>
		</tr>
		<tr>
			<td>8.</td>
			<td colspan=2>Pendidikan Tertinggi</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_pt" class="form-control-plaintext" maxlength=30></td>
		</tr>
		<tr>
			<td>9.</td>
			<td colspan=2>Jabatan Fungsional / TMT</td>
			<td class="text-center">:</td>
			<td colspan=4 id="gtk_jf"></td>
		</tr>
		<tr>
			<td rowspan=2>10.</td>
			<td rowspan=2>Masa Kerja Golongan</td>
			<td>Lama</td>
			<td class="text-center">:</td>
			<td colspan=4>
				<input type="text" id="mkg_lama_t" class="form-control-plaintext d-inline" style="width:2.5em" maxlength=2> tahun
				<input type="text" id="mkg_lama_b" class="form-control-plaintext d-inline ml-2" style="width:2.5em" maxlength=2> bulan
			</td>
		</tr>
		<tr>
			<td>Baru</td>
			<td class="text-center">:</td>
			<td colspan=4>
				<div id="mkg_baru_t" class="d-inline-block" style="width:2.5em"></div>
				tahun
				<div id="mkg_baru_b" class="ml-2 d-inline-block" style="width:2.5em"></div>
				bulan
			</td>
		</tr>
		<tr>
			<td>11.</td>
			<td colspan=2>Jenis Guru</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_jenis" class="form-control-plaintext" maxlength=30></td>
		</tr>
		<tr>
			<td>12.</td>
			<td colspan=2>Unit Kerja</td>
			<td class="text-center">:</td>
			<td colspan=4><input type="text" id="gtk_sekolah" class="input2box form-control-plaintext" maxlength=30></td>
		</tr>
	</tbody>
	<tbody id="perak" class="border-top-0">
		<tr>
			<th>II.</th>
			<th colspan=4 style="border-right:0!important">PENETAPAN ANGKA KREDIT</th>
			<th style="border-left:0!important" width=105></th>
			<th width=135>LAMA</th>
			<th width=135>BARU</th>
			<th width=135>JUMLAH</th>
		</tr>
		<tr>
			<td rowspan=13></td>
			<th>1.</th>
			<th colspan=7>UNSUR UTAMA</th>
		</tr>
		<tr>
			<td rowspan=9></td>
			<td colspan=4 class="text-nowrap">
				<clr>a.</clr>
				Pendidikan
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr></clr>
				<clr>1)</clr>
				Mengikuti Pendidikan dan Memperoleh Gelar/Ijazah/Akta
			</td>
			<td><input type="text" id="l1a1" class="form-control-plaintext pakl1"/></td>
			<td><input type="text" id="b1a1" class="form-control-plaintext pakb1"/></td>
			<td id="j1a1" class="pakj1"></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr></clr>
				<clr>2)</clr>
				Mengikuti Pelatihan Pra Jabatan
			</td>
			<td><input type="text" id="l1a2" class="form-control-plaintext pakl1"/></td>
			<td><input type="text" id="b1a2" class="form-control-plaintext pakb1"/></td>
			<td id="j1a2" class="pakj1"></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr>b.</clr>
				Pembelajaran / Bimbingan dan Tugas Tertentu
			</td>
			<td><input type="text" id="l1b" class="form-control-plaintext pakl1"/></td>
			<td><input type="text" id="b1b" class="form-control-plaintext pakb1"/></td>
			<td id="j1b" class="pakj1"></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr>c.</clr>
				Pengembangan Keprofesian Berkelanjutan
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr></clr>
				<clr>1)</clr>
				Melaksanakan Pengembangan Diri
			</td>
			<td><input type="text" id="l1c1" class="form-control-plaintext pakl1"/></td>
			<td><input type="text" id="b1c1" class="form-control-plaintext pakb1"/></td>
			<td id="j1c1" class="pakj1"></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr></clr>
				<clr>2)</clr>
				Melaksanakan Publikasi Ilmiah
			</td>
			<td><input type="text" id="l1c2" class="form-control-plaintext pakl1"/></td>
			<td><input type="text" id="b1c2" class="form-control-plaintext pakb1"/></td>
			<td id="j1c2" class="pakj1"></td>
		</tr>
		<tr>
			<td colspan=4 class="text-nowrap">
				<clr></clr>
				<clr>3)</clr>
				Melaksanakan Karya Inovatif
			</td>
			<td><input type="text" id="l1c3" class="form-control-plaintext pakl1"/></td>
			<td><input type="text" id="b1c3" class="form-control-plaintext pakb1"/></td>
			<td id="j1c3" class="pakj1"></td>
		</tr>
		<tr>
			<th colspan=4 class="text-nowrap">Jumlah Unsur Utama</th>
			<th id="l1">0</td>
			<th id="b1">0</td>
			<th id="j1">0</td>
		</tr>
		<tr>
			<th>2.</th>
			<th colspan=7>UNSUR PENUNJANG</th>
		</tr>
		<tr>
			<td rowspan=2></td>
			<td colspan=4>Penunjang Tugas Guru</td>
			<td><input type="text" id="l2a" class="form-control-plaintext"/></td>
			<td><input type="text" id="b2a" class="form-control-plaintext"/></td>
			<td id="j2a" class="pakj2"></td>
		</tr>
		<tr>
			<th colspan=4>Jumlah Unsur Penunjang</th>
			<th id="l2">0</td>
			<th id="b2">0</td>
			<th id="j2">0</td>
		</tr>
		<tr>
			<th class="border-top-0"></th>
			<th colspan=5>JUMLAH UNSUR UTAMA DAN UNSUR PENUNJANG</th>
			<th id="tl">0</td>
			<th id="tb">0</td>
			<th id="tj">0</td>
		</tr>
		<tr>
			<th class="align-top">III.</th>
			<td colspan="8">Dapat dipertimbangkan untuk dinaikan dalam Jabatan Fungsional <b id="jf_naik">Belum Ditetapkan</b>, Pangkat / Golongan Ruang <b id="gol_naik">Belum Ditetapkan</b>, TMT. <b id="tmt_baru">Belum Ditetapkan</b>.<br>
				Nilai Tabungan untuk kenaikan Pangkat / Golongan Ruang / Jabatan periode berikutnya: <b id="tabungan">Belum Ditetapkan</b>
			</td>
		</tr>
	</tbody>
</table>
	<div class="row">
		<div class="col-7 d-flex align-items-center">
			<table>
				<tr class="small">
					<td class="align-top pr-1"><b><u><i>Asli:</i></u></b></td>
					<td>
						disampaikan dengan hormat kepada:<br/>
						Kepala Kantor Regional X<br/>
						Badan Kepegawaian Negara di Denpasar
					</td>
				</tr>
			</table>
		</div>
		<div class="col-5">
			<table class="mx-auto mb-1">
				<tr>
					<td class="pr-3 text-nowrap">DITETAPKAN DI</td>
					<td class="text-center">:</td>
					<td><input type="text" class="form-control-plaintext" style="width:9em" id="f_tpt" value="KUPANG" maxlength=20 /></td>
				</tr>
				<tr>
					<td class="pr-3 text-nowrap">PADA TANGGAL</td>
					<td class="text-center">:</td>
					<td><input type="text" class="form-control-plaintext" id="f_tgl" style="width:9em" maxlength=20 /></td>
				</tr>
			</table>
			<?$kadis=json_decode(sql_value($connect, "SELECT txt FROM options WHERE set_var='kadis'"),true);?>
			<div class="text-center">
				KEPALA DINAS PENDIDIKAN DAN KEBUDAYAAN<br>
				PROVINSI NUSA TENGGARA TIMUR<div class="py-5"></div>
				<div><input type="text" class="form-control-plaintext text-center font-weight-bold" style="text-decoration:underline" value="<?=$kadis["nama"];?>"/></div>
				<div><input type="text" class="form-control-plaintext text-center" value="<?=$kadis["gol"];?>"/></div>
				<div><input type="text" class="form-control-plaintext text-center" value="NIP. <?=$kadis["nip"];?>"/></div>
			</div>
		</div>
		<div class="col-12" style="margin-top:-1rem;">
			<b><u>Tembusan:</u></b>
			<ol class="pl-3 small my-0">
				<li>Gubernur Nusa Tenggara Timur di Kupang,</li>
				<li>Kepala Badan Kepegawaian Negara di Jakarta,</li>
				<li>Kepala Badan Kepegawaian Daerah Provinsi Nusa Tenggara Timur di Kupang,</li>
				<li>Saudara/i <span id="set_gtk_nama"></span>, guru di <span id="set_gtk_sekolah"></span>.</li>
			</ol>
		</div>
	</div>
</div>
</div>
</div>
<div class="print-hidden p-2 border rounded mt-3 bg-white d-md-flex d-block align-items-center justify-content-between" style="position:sticky;bottom:0">
	<div class="text-muted small mb-md-0 mb-2">
		<input type="hidden" id="ActionPAK" name="action" value="tambah"/>
		<input type="hidden" id="IdPAK" name="pak_id" value=""/>
		Simpan data ini ke server apabila Anda ingin melanjutkan penyuntingan di lain hari. <br/><b>Perlu diperhatikan bahwa Anda hanya bisa menyimpan data dari 10 guru di basis data</b>.
	</div>
	<input type="submit" id="SubmitPAK" class="btn btn-primary btn-sm d-md-inline-block d-block" value="Memuat..."/>
	</div>
</form>
<?include'footer.php';?>
<style id="font-pak"></style>
<script>
$('#gol_rg,#monthsel').val('');
// Versi pretty dari function berikut ada di /admin/ori_pakfunction.js
function gol_jab(r){return r1="(Tidak diketahui)",r2="(Tidak diketahui)","II "==r.substring(0,3)&&(r1="Pengatur"),"III"==r.substring(0,3)&&(r1="Penata"),"IV "==r.substring(0,3)&&(r1="Pembina"),"I / a"==r.substring(r.length-5)&&(r2=" Muda"),"I / b"==r.substring(r.length-5)&&(r2=" Muda Tingkat I"),"I / c"==r.substring(r.length-5)&&(r2=""),"I / d"==r.substring(r.length-5)&&(r2=" Tingkat I"),"IV / a"==r&&(r2=""),"IV / b"==r&&(r2=" Tingkat I"),"IV / c"==r&&(r2=" Utama Muda"),"IV / d"==r&&(r2=" Utama Madya"),r1+r2}function jab_fung(r){return r1="(Tidak diketahui)","III / a"!=r&&"III / b"!=r||(r1="Pertama"),"III / c"!=r&&"III / d"!=r||(r1="Muda"),"IV / a"!=r&&"IV / b"!=r&&"IV / c"!=r||(r1="Madya"),"IV / d"==r&&(r1="Utama"),"Guru "+r1}function naik_jab(r){return r1="(Tidak diketahui)","II / a"==r&&(r1="II / b"),"II / b"==r&&(r1="II / c"),"II / c"==r&&(r1="II / d"),"II / d"==r&&(r1="III / a"),"III / a"==r&&(r1="III / b"),"III / b"==r&&(r1="III / c"),"III / c"==r&&(r1="III / d"),"III / d"==r&&(r1="IV / a"),"IV / a"==r&&(r1="IV / b"),"IV / b"==r&&(r1="IV / c"),"IV / c"==r&&(r1="IV / d"),r1}function namabln(r){return r1="(Tidak diketahui)",1==r&&(r1="Januari"),2==r&&(r1="Februari"),3==r&&(r1="Maret"),4==r&&(r1="April"),5==r&&(r1="Mei"),6==r&&(r1="Juni"),7==r&&(r1="Juli"),8==r&&(r1="Agustus"),9==r&&(r1="September"),10==r&&(r1="Oktober"),11==r&&(r1="November"),12==r&&(r1="Desember"),r1}function akm(r){return r1=0,"II / d"==r&&(r1=100),"III / a"==r&&(r1=150),"III / b"==r&&(r1=200),"III / c"==r&&(r1=300),"III / d"==r&&(r1=400),"IV / a"==r&&(r1=550),"IV / b"==r&&(r1=700),"IV / c"==r&&(r1=850),"IV / d"==r&&(r1=1050),r1}function validateGolRg() {
	gtk_gol =$('#gol_rg').val();
	$('#gtk_gol').text(gol_jab(gtk_gol)+' ('+gtk_gol+')');
	$('#gtk_jf').text(jab_fung(gtk_gol));
	$('#jf_naik').text(jab_fung(naik_jab(gtk_gol)));
	$('#gol_naik').text(gol_jab(naik_jab(gtk_gol))+' ('+naik_jab(gtk_gol)+')');
	$('#akmin').val(akm(gtk_gol));
}
function valAngkaKredit(){
	$('.pakj1').each(function(){
		$(this).text((Number($('#l'+$(this).attr("id").substring(1,5)).val())+Number($('#b'+$(this).attr("id").substring(1,5)).val())).toFixed(3));
	});
	var l1=0,b1=0,j1=0;
	$(".pakl1").each(function(){l1 += +$(this).val();}); $('#l1').text(l1.toFixed(3));
	$(".pakb1").each(function(){b1 += +$(this).val();}); $('#b1').text(b1.toFixed(3));
	$('#j1').text((l1+b1).toFixed(3));
	sum_l1 = l1; sum_b1 = b1; sum_j1 = l1+b1;
	sum_l2 = Number($('#l2a').val());
	sum_b2 = Number($('#b2a').val());
	sum_j2 = sum_l2+sum_b2;
	$('#l2').text(sum_l2.toFixed(3)); $('#b2').text(sum_b2.toFixed(3)); $('#j2,#j2a').text(sum_j2.toFixed(3));
	$('#tl').text((sum_l1+sum_l2).toFixed(3));
	$('#tb').text((sum_b1+sum_b2).toFixed(3));
	$('#tj').text((sum_j1+sum_j2).toFixed(3));
	akmin = $('#akmin').val();
	$('#tabungan').text(((sum_j1+sum_j2)-Number(akmin)).toFixed(3));
}
function valMKG(){
	var date1=new Date($('#gtk_tmtpt').val()),
	date2=new Date(periode($('#monthsel').val())+'-'+$('#monthsel').val()),
	diff_y = date2.getYear()-date1.getYear();
	diff_m = date2.getMonth()-date1.getMonth();
	TMTPT_skrg = diff_y*12+diff_m,
	MKG_Lama = Number($('#mkg_lama_t').val())*12+Number($('#mkg_lama_b').val()),
	JlhBln = TMTPT_skrg + MKG_Lama,
	m = JlhBln % 12,
	y = Math.floor(JlhBln / 12);
	$('#mkg_baru_t').text(y);
	$('#mkg_baru_b').text(m);
	$('#tmt_baru').text('1 '+namabln($('#monthsel').val())+' '+periode($('#monthsel').val()));
}
$('#pak_main input:not([type=hidden],[type=submit]), #pak_main select').each(function(){
	if($(this).attr("id")) $(this).attr("name","pak["+$(this).attr("id")+"]");
});
$('#pak_main').on('submit', function(event){
	event.preventDefault();
	$('#spinner_loader').show();
	$.ajax({
		url:"action/pak.php",
		method:"POST",
		data:$(this).serialize(),
		dataType:"json",
		beforeSend:function(){
			$('#SubmitPAK').val('Menyimpan...').prop('disabled', true);
		},
		success:function(x)
		{
			$('#SubmitPAK').val(x.btn_txt).attr("disabled",false);
			if(x.success)
			{
				topbar(1,x.text);
				if(x.redirect) location.href=x.redirect; else $('#spinner_loader').fadeOut();
			}
			if(x.error)
			{
				topbar(0,x.text);
				$('#spinner_loader').fadeOut();
			}
		},
		error:function(){
			$('#SubmitPAK').prop('disabled', false).val('Ulangi');
		}
	})
});
<? if(!empty($_GET["id_gtk"]) || !empty($_GET["id_pak"])){?>
$.ajax({
	url:"action/pak.php",
	method:"POST",
	data:{action:"fetch", <?if($_GET["id_gtk"])echo'id_gtk:"'.$_GET["id_gtk"].'"'; else echo 'id_pak:"'.$_GET["id_pak"].'"'?>},
	dataType:"json",
	async: false,
	success:function(x)
	{
		$('#spinner_loader').fadeOut();
		$('#SubmitPAK').val(x.btn_txt);
		if(x.success)
		{
			dtPAK = x.data;
			$('#ActionPAK').val(x.action);
			$.each(dtPAK,function(par,val){$('#'+par).val(val);});
			if(x.pak_id) $('#IdPAK').val(x.pak_id);
			if(dtPAK.gol_rg) validateGolRg();
			if(dtPAK.font) $('#font-pak').html('.pak-main{font-family:'+dtPAK.font+'}');
			valAngkaKredit(); valMKG();
			$(".input2box").each(function(){
				$('#set_'+$(this).attr('id')).text($(this).val());
			});
		}
		if(x.error) topbar(0,x.text);
	}
});
<?} else {?>
$("#SubmitPAK").val("Tambah ke Daftar Tunggu");
<?}?>
$('#gol_rg').on("change",function(){validateGolRg();});
$(".input2box").on("input",function(){
	$('#set_'+$(this).attr('id')).text($(this).val());
});
$(".input2box").each(function(){
	$('#set_'+$(this).attr('id')).text($(this).val());
});
$('#font').on("change", function(){
	$('#font-pak').html('.pak-main{font-family:'+$('#font').val()+'}')
});
$("#perak input").attr("step",".001").attr("max",999).attr("type","number");
$("#perak input").on('keydown', (e) => {
	if([','].indexOf(e.key) !== -1){
		e.preventDefault();
		topbar("info","Gunakan tanda titik (.) sebagai pemisah desimal.");
	}
});

$('#perak input,#akmin').on("input", function() {
	valAngkaKredit();
});
function periode(b){
	if(Number(new Date().getMonth())+2>b){x=Number(new Date().getFullYear())+1;}else{x=new Date().getFullYear();}
		return x;
}
$('#monthsel [value=4]').text('April '+periode(4));
$('#monthsel [value=10]').text('Oktober '+periode(10));
$('#mkg_lama_b,#mkg_lama_t,#gtk_tmtpt,#monthsel').on("input",function(){
valMKG();
});

</script>