<?php
/** AYARLAR - BAS */
$YoncuUser	= 'API ID';	// Üye İşlemler/Menü Devamı/Güvenlik Ayarları/API Erişimi menüsünden alabilirsiniz.
$YoncuPass	= 'API KEY';// Üye İşlemler/Menü Devamı/Güvenlik Ayarları/API Erişimi menüsünden alabilirsiniz.
$DomainKyt	= 'yoncu.com';	// Kayıt Edilecek Alan adı (domain)
$KayitYili	= 1;	// Alan adı kayit yılı, 1-10
$PromosKod	= '';	// Varsa alan adı için indirim kodunuz
$TestKytMi	= 1;	// 1: Test, 0: Gerçek Kayıt
//Bilgi: Sunucunuzda Json ve Curl yüklü olmalıdır.
/** AYARLAR - SON */

$Post	= 'ka='.urlencode($YoncuUser);
$Post	.= '&sf='.urlencode($YoncuPass);
$Post	.= '&aa='.urlencode($DomainKyt);
$Post	.= '&yl='.urlencode($KayitYili);
$Post	.= '&pk='.urlencode($PromosKod);
$Post	.= '&test='.urlencode($TestKytMi);
$Curl = curl_init();
curl_setopt($Curl, CURLOPT_URL, "http://www.yoncu.com/apiler/domain/get/kayit.php");
curl_setopt($Curl, CURLOPT_HEADER, false);
curl_setopt($Curl, CURLOPT_ENCODING, false);
curl_setopt($Curl, CURLOPT_COOKIESESSION, false);
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($Curl, CURLOPT_HTTPHEADER, array(
	'Connection: keep-alive',
	'User-Agent: '.$_SERVER['SERVER_NAME'],
	'Referer: http://www.yoncu.com/',
	'Cookie: YoncuKoruma='.$_SERVER['SERVER_ADDR'].';YoncuKorumaRisk=0',
));
curl_setopt($Curl, CURLOPT_POSTFIELDS, $Post);
if(curl_errno($Curl) == 0){
	$Json	= trim(curl_exec($Curl));
	if($Json != ""){
		list($Durum,$Bilgi)	= (array)json_decode($Json,true);
		if(json_last_error() == 0){
			if($Durum == true){
				echo 'Kayıt Edildi. Detaylı Bilgi: '.var_export($Bilgi,true);
			}else{
				echo 'Hata: '.$Json;
			}
		}else{
			$JsEr=array(
				0=>'Hata Bulunamadı',
				1=>'Max İçeriğe Ulaşıldı',
				2=>'Data Uyumsuz',
				3=>'Yanlış Kodlanmış',
				4=>'Sözdizimi Hatalı',
				5=>'Karakter Kodlama Hatalı',
			);
			echo 'Data Hata: Veri Json Değil ('.$JsEr[json_last_error()].')';
			echo "<br/>Gelen Veri:<br/>".$Json;
		}
	}else{
		echo 'Data Hata: Veri Boş Çekildi';
	}
}else{
	echo "Curl Hata: ".curl_errno($Curl)." - ".curl_error($Curl);
}
curl_close($Curl);
