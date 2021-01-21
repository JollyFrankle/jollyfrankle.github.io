<?php include('header.php');?>
<style>.Vhead{display:flex;justify-content:space-between;border-bottom:1px solid var(--gray);margin-bottom:.5rem}.Vhead h5{margin-bottom:0}.Vhead span{color:var(--gray)}ul.Vlist{list-style-type:none;padding-left:1.5rem}.Vlist li[new]::before{content:"+";color:var(--success)}.Vlist li[fix]::before{content:"#";color:var(--primary)}.Vlist li[del]::before{content:"-";color:var(--danger)}.Vlist li[new]::before,.Vlist li[fix]::before,.Vlist li[del]::before{font-weight:700;width:1.5rem;margin-left:-1.5rem;display:inline-block}.Vdesc{margin-bottom:.5rem}ul.Vlist ol{padding-left:1.7rem}
</style>
<h2 class="pb-2 border-bottom">Kredit dan Catatan Perubahan</h2>
<h4 class="pb-2 border-bottom">Kredit dan Ucapan Terima Kasih</h4>
<div class="row">
  <div class="col-sm">
    <h5 class="border-bottom mb-1">Sumber Terbuka</h5>
    <ul class="pl-4">
      <li>
        <b>Bootstrap</b><br>
        <a href="https://getbootstrap.com/">https://getbootstrap.com/</a>
      </li>
      <li>
        <b>DataTables</b><br>
        <a href="https://datatables.net/">https://datatables.net/</a>
      </li>
      <li>
        <b>jQuery</b><br>
        <a href="https://jquery.com/">https://jquery.com/</a>
      </li>
    </ul>
  </div>
  <div class="col-sm border-left border-right">
    <h5 class="border-bottom mb-1">Developer</h5>
    <b>Jolly Hans Frankle</b>
  </div>
  <div class="col-sm">
    
  </div>
</div>
<div class="form-row">
  <div class="col-md">
    <h4 class="bg-dark text-white rounded px-1">Developmental</h4>
    <p class="text-muted">17 Juli 2020 - 22 Agustus 2020</p>
    <p class="small">Tahapan awal perkembangan situs. Terdapat banyak penambahan fitur, namun situs masih sangat tidak stabil dan belum layak diujicoba skala kecil.</p>
  </div>
  <div class="col-md">
    <h4 class="bg-warning rounded px-1">Alpha</h4>
    <p class="text-muted">Early Alpha: 23 Agustus 2020 - 31 Agustus 2020</p>
    <p class="text-muted">Mid-Alpha: 1 September 2020 - 21 Oktober 2020</p>
    <p class="text-muted">Late Alpha: 22 Oktober 2020 - saat ini</p>
  </div>
  <div class="col-md">
    <h4 class="bg-success text-white rounded px-1">Beta</h4>
    <p class="text-muted">Belum tersedia untuk uji coba skala menengah.</p>
  </div>
  <div class="col-md">
    <h4 class="bg-primary text-white rounded px-1">Rilis</h4>
    <p class="text-muted">Belum tersedia untuk digunakan publik.</p>
  </div>
</div>

<h4 class="pb-2 border-bottom">Catatan Perubahan</h4>
<div class="Vhead">
  <h5>Alpha 3.9.2 (2012H1)</h5>
  <span>Dirilis: TBA</span>
</div>
<div class="Vdesc">
  Versi 2012H1 merupakan pembaruan minor yang bertujuan untuk menyesuaikan format yang ada pada situs dengan format yang ada di Dinas Pendidikan dan Kebudayaan.
</div>

<ul class="Vlist">
  <li new>Penambahan halaman muka utama situs</li>
  <li new>Penambahan status kepegawaian "Kontrak Kabupaten/Kota" <small class="text-muted">(Usulan Rapat 6 November 2020)</small></li>
  <li new>Penambahan kolom entri gaji (untuk status kepegawaian GTTY dan HON-KOM) <small class="text-muted">(Usulan Rapat 6 November 2020)</small></li>
  <li new>Pengaktifan alamat surel sister, <a href="mailto:sister@gtkprovntt.com">sister@gtkprovntt.com</a>.</li>
  <li new>Penambahan 4 kategori filter data GTK pada Grafik Statistik GTK (dashboard-beta admin):</li>
  <ol>
    <li>Jumlah GTK PNS berdasarkan Usia</li>
    <li>Jumlah GTK Non-PNS berdasarkan Usia</li>
    <li>Jumlah GTK Perempuan berdasarkan Usia</li>
    <li>Jumlah GTK Laki-laki berdasarkan Usia</li>
  </ol>
  <li new>Pengeksporan Daftar Hadir ke bentuk PDF lengkap ditambahkan. Ukuran kertas yang tersedia: F4 dan A4.</li>
  <li fix>Pemindahan direktori akses akun sekolah dari <code>/</code> ke <code>/sekolah/</code>.</li>
  <li fix>Perubahan dan perbaikan pada halaman Kredit dan Catatan Perubahan.</li>
  <li fix>Perubahan dan penyesuaian halaman Ubah Akun Admin dan Ubah Akun Sekolah, perubahan akun divalidasi pada server dan client.</li>
  <li fix>Perubahan backend: pemadatan rincian jenis GTK.</li>
  <li fix><b>Bug fix:</b> tanggal hari ini tidak ditampilkan dengan tepat pada halaman Rekap Daftar Hadir (versi sekolah dan admin).</li>
  <li fix><b>Bug fix:</b> tidak dapat melakukan pendaftaran akun sekolah baru (server error).</li>
  <li fix><b>Bug fix:</b> permintaan pengubahan/penghapusan data GTK tidak divalidasi/disaring pada permintaan admin.</li>
  <li del>Tombol salin, ekspor ke CSV, ekspor ke PDF, dan ekspor ke XLSX telah dihilangkan dari halaman Rekap Daftar Hadir, Kini digantikan dengan tombol ekspor lengkap ke bentuk PDF. (Ekspor lengkap: ekspor sesuai format daftar hadir formal).</li>
</ul>

<h5 class="border-bottom">15/08/2020 - Alpha 3.2.4</h4>
<ul class="pl-4">
    <li><b>BUG:</b> Data sekolah tidak dapat diperbarui, karena tombol "Perbarui Data" berubah menjadi "Memuat...".</li>
    <li><b>IMPROVEMENT:</b> Aplikasi menggunakan CDN dari Cloudflare. Kecepatan muat (TTFB) sekitar 1 detik, akan tetapi tingkat keamanan sudah ditingkatkan.</li>
    <li>15:20 - <b>FATAL:</b> Accidental removal of <i>data-sekolah.php</i>.</li>
    <li>15:23 - <b>OTHER:</b> Restoring <i>data-sekolah.php</i> from server backup at 15/08/2020 03:08 WITA - ETA. 17:00 back online.</li>
    <li>16:59 - <b>FIX:</b> <i>data-sekolah.php</i> is restored.</li>
    <li>17:00 - <b>ADDITION</b> Adding <i>akun.php</i> by copying the source code from <i>data-sekolah.php</i>.</li>
    <li>18:00 - <b>NEW:</b> <i>akun.php</i> added and is now functional (v.1.0)</li>
    <li>19:03 - <b>NEW:</b> <i>rekap-lb.php</i> added and is now functional (v.1.0)</li>
</ul>

<h6>16/08/2020 - Alpha 3.2.4.1</h5>
<ul class="pl-4">
    <li>09:02 - <b>FIX:</b> A bug that causes the number inputs to be locked/readonly in <i>data-sekolah.php</i>.</li>
    <li>09:07 - <b>FIX:</b> <i>permintaan-info.php</i> is now back to normal.</li>
    <li>17:27 - <b>FIX:</b> Updated the GTK's attendance recap box in <i>daftar-gtk.php</i> to suit the updated <i>dfhd_status</i> in the database.</li>
</ul>

<h6>17/08/2020 - Alpha 3.2.4.2</h5>
<ul class="pl-4">
    <li>06:54 - <b>FIX:</b> A typo in code from <i>daftar-gtk.php</i> that causes error 404 when saving GTK's Data.</li>
    <li>07:09 - <b>NEW:</b> Added a javascript alert when data failed to load (error code 404 or 500 or other error codes) to <i>daftar-gtk.php</i>. Applies on HTML error codes when loading GTK's data, updating GTK's data, deleting GTK, and showing GTK's attendance recap.</li>
</ul>

<h6>20/08/2020 - Alpha 3.2.4.2</h5>
<ul class="pl-4">
    <li>07:04 - <b>FIX:</b> Missing string in code that causes the number of rombels uneditable when changing school's status in <i>data-sekolah.php</i>.</li>
    <li>07:04 - <b>NEW:</b> Processing error screen di <code>data-sekolah.php</code>.</li>
    <li>07:11 - <b>NEW:</b> Validasi NPSN sudah ada di <code>data-sekolah.php</code>.</li>
    <li>07:25 - <b>NEW:</b> Added shaking animation to alerts in <kbd>#console</kbd>.</li>
    <li class="text-success">10:41 - <b>NEW:</b> <kbd>tbl_unggahan</kbd> ditambahkan di database, memuat <code>ung_id</code>, <code>ung_sek</code>, <code>ung_desc</code>, <code>ung_tipe</code>, <code>ung_tgl</code>, dan <code>ung_file</code>. Nama berkas akan disimpan di database dengan format <samp>&lt;npsn&gt;_&lt;tipe_file&gt;_&lt;periode&gt;+&lt;kode_unik&gt;.pdf</samp>.</li>
    <li class="text-primary">13:05 - <b>NEW:</b> Halaman baru ditambahkan: Unggahan berkas (<code>profile.php</code>) (sementara)</li>
    <li>14:41 - <b>NEW:</b> Tindakan "Hapus Berkas" pada <code>profile.php</code> sudah diimplementasikan. Hapus data dari database dan hapus file dari penyimpanan.</li>
    <li>17:40 - <b>IMP:</b> Perapian halaman Pengunggahan Berkas telah selesai.</li>
    <li>18:34 - <b>FIX:</b> Spinner-Loader di halaman yang ada DataTable dan action tambahan (seperti ubah data individu, hapus data individu, dsb) tidak tampil setelah menekan tombol <i>submit</i>. Halaman yang terdampak: <code>/daftar-gtk</code>, <code>/daftar-hadir</code>, <code>/permintaan-info</code>, dan <code>/profil</code>.</li>
    <li>18:54 - <b>IMP:</b> Perubahan URL halaman pengunggahan berkas dari <code>/profil</code> ke <code>/unggahan</code>.</li>
    <li>18:59 - <b>IMP:</b> Penambahan halaman Unggahan Berkas ke navigasi utama dan perombakan susunan dan kategori item dalam navigasi (kategori: Utama, Unduh dan Unggah, dan Info Aplikasi).</li>
</ul>

<h6>20/08/2020 - Alpha 3.2.4.3</h5>
<ul class="pl-4">
    <li>07:32 - <b>IMP:</b> Perubahan tambahan pada halaman <code>/unggahan</code></li>
    <li>07:54 - <b>IMP:</b> Perubahan tambahan pada halaman <code>/data-sekolah</code></li>
    <li>09:08 - <b>IMP:</b> Perubahan kecil terhadap tombol submit pada <code>/permintaan-info</code></li>
    <li>11:31 - <b>NEW:</b> Error code untuk browser yang tidak mendukung JavaScript (redirect ke <code>/no-js</code>). <a class="btn btn-outline-dark btn-sm" href="no-js">Lihat</a></li>
    <li>11:34 - <b>IMP:</b> Penyamaan tampilan halaman HTTP 404 dan HTTP 403 dengan error code yang lain. <a class="btn btn-outline-dark btn-sm" href="404.html">Lihat 404</a> <a class="btn btn-outline-dark btn-sm" href="403.html">Lihat 403</a></li>
    <li>12:34 - <b>IMP:</b> Optimisasi mini: pengurangan </li>
</ul>

<h6>24/08/2020 - Alpha 3.2.4.3</h6>
<ul class="pl-4">
    <li>14:35 - <b>NEW:</b> Ditambahnya lapisan pengaman tingkat 2 saat hendak memuat data GTK, menghapus GTK, dan menampilkan laporan kehadiran GTK. Apabila ID GTK diubah secara ilegal melalui Inspect Element, maka akan secara otomatis di<i>refuse</i> oleh server.</li>
</ul>

<h6>30/08/2020 - Alpha 3.5.2</h6>
<ul class="pl-4">
    <li><b>NEW:</b> Perubahan besar tampilan Dashboard</li>
</ul>
<?php include('footer.php');?>
<script>
$('.Vlist li[new]').attr("title","<b>[DITAMBAH]</b>: Fitur baru yang ditambahkan.");
$('.Vlist li[fix]').attr("title","<b>[DIUBAH]</b>: Fitur yang diubah fungsi/tampilannya.");
$('.Vlist li[del]').attr("title","<b>[DIHAPUS]</b>: Fitur yang dihapus/dihilangkan.");
$('.Vlist li').attr("data-toggle","tooltip").attr("data-placement","left").attr("data-html","true");
$(function(){$('[data-toggle="tooltip"]').tooltip()});
</script>