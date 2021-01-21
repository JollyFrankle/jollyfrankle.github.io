		</main>
	</div>
</div>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-167603452-2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-167603452-2');
$(".nav-link,.navmenu").each(function() {
	var full = window.location.href,
	segn = full.substr(full.lastIndexOf('/') + 1);
	if(segn.startsWith($(this).attr("href")))
	$(this).addClass('active').css("pointer-events","none");
});
$(document).on("click","a:not(.no_sl,.page-link,[target=_blank])",function(){$('#spinner_loader').show();});
$(document).ready(function(){if($('.onload_ajax').length=='0'){$('#spinner_loader').fadeOut();}});
$(document).ajaxError(function(event, xhr, ajaxOptions, thrownError) {
	var ErrAlert=">>> GALAT! <<<\n——————————————\nTerjadi kesalahan dalam pemrosesan data. Silakan ulangi permintaan Anda dengan cara menekan ulang tombol submit/kirim, atau memuat ulang halaman ini.\n\nKode Galat: "+xhr.status;
	alert(ErrAlert);
	console.log(ErrAlert+"\n——————————————\nWaktu galat:\n"+xhr.getResponseHeader("date"));
	$('#spinner_loader').fadeOut();
});
function topbar(type,data) {
	var alertclass="alert small alert-dismissible fade show";
	if(type=="success"		|| type==1) var pre='<div class="'+alertclass+' alert-success">';
	else if(type=="error" || type==0) var pre='<div class="'+alertclass+' alert-danger">';
	else if(type=="info") var pre='<div class="'+alertclass+' alert-info">';
	else var pre='<div class="alert alert-warning small"><b>GALAT:</b> Input tidak valid pada elemen "type". <code>topbar(<i>type</i>,<i>data</i>);</code> ';
	
	if(data!="") var mid=data;
	else var mid='<b>GALAT:</b> Tidak ada input pada elemen "data". <code>topbar(<i>type</i>,<i>data</i>);</code> ';
	
	var close='<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	var end="</div>";
	return $("#console").html(pre+mid+close+end);
}
</script>