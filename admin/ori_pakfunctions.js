function pen_ter(x){
  r1 = '(Tidak diketahui)';
  r2 = '(Tidak diketahui)';
  if(x=='SD')r1='Sekolah Dasar';
  if(x=='SMP')r1='Sekolah Menengah Pertama';
  if(x=='SMA')r1='Sekolah Menengah Atas';
  if(x=='D1')r1='Ahli Pratama';
  if(x=='D2')r1='Ahli Muda';
  if(x=='D3')r1='Ahli Madya';
  if(x=='D4')r1='Ahli';
  if(x=='S1')r1='Sarjana';
  if(x=='S2')r1='Magister';
  if(x=='S3')r1='Doktor';
  return r1;
}
function gol_jab(x){
  r1 = '(Tidak diketahui)';
  r2 = '(Tidak diketahui)';
  if(x.substring(0,3)=='II ')r1='Pengatur';
  if(x.substring(0,3)=='III')r1='Penata';
  if(x.substring(0,3)=='IV ')r1='Pembina';
  if(x.substring(x.length-5)=='I / a')r2=' Muda';
  if(x.substring(x.length-5)=='I / b')r2=' Muda Tingkat I';
  if(x.substring(x.length-5)=='I / c')r2='';
  if(x.substring(x.length-5)=='I / d')r2=' Tingkat I';
  if(x=='IV / a')r2='';
  if(x=='IV / b')r2=' Tingkat I';
  if(x=='IV / c')r2=' Utama Muda';
  if(x=='IV / d')r2=' Utama Madya';
  return r1 + r2;
}
function jab_fung(x){
  r1 = '(Tidak diketahui)';
  if(x=='III / a' || x=='III / b')r1='Pertama';
  if(x=='III / c' || x=='III / d')r1='Muda';
  if(x=='IV / a' || x=='IV / b' || x=='IV / c')r1='Madya';
  if(x=='IV / d')r1='Utama';
  return 'Guru '+r1;
}
function naik_jab(x){
  r1 = '(Tidak diketahui)';
	if(x=='II / a')r1='II / b';
  if(x=='II / b')r1='II / c';
  if(x=='II / c')r1='II / d';
  if(x=='II / d')r1='III / a';
  if(x=='III / a')r1='III / b';
  if(x=='III / b')r1='III / c';
  if(x=='III / c')r1='III / d';
  if(x=='III / d')r1='IV / a';
  if(x=='IV / a')r1='IV / b';
  if(x=='IV / b')r1='IV / c';
  if(x=='IV / c')r1='IV / d';
  return r1;
}
function namabln(x){
  r1 = '(Tidak diketahui)';
  if(x==1)r1='Januari';
  if(x==2)r1='Februari';
  if(x==3)r1='Maret';
  if(x==4)r1='April';
  if(x==5)r1='Mei';
  if(x==6)r1='Juni';
  if(x==7)r1='Juli';
  if(x==8)r1='Agustus';
  if(x==9)r1='September';
  if(x==10)r1='Oktober';
  if(x==11)r1='November';
  if(x==12)r1='Desember';
  return r1;
}
function akm(x){
  r1 = 0;
  if(x=='II / d')r1=100;
  if(x=='III / a')r1=150;
  if(x=='III / b')r1=200;
  if(x=='III / c')r1=300;
  if(x=='III / d')r1=400;
  if(x=='IV / a')r1=550;
  if(x=='IV / b')r1=700;
  if(x=='IV / c')r1=850
  if(x=='IV / d')r1=1050;
  return r1;
}
function validateGolRg() {
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