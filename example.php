<?php 

/*
 * Folderio, Klasör sınıfı
 *  
 * Author:	Osman YILMAZ 
 * Email:	osmnylmz@outlook.com
 * Web:		http://www.astald.com - www.osmnylmz.com
 * 
 * Created Date: 23.03.2016
 *
**/

// require_once __DIR__ . '/vendor/autoload.php';   // Composer ile kullanmak için.
require_once __DIR__ . '/src/autoload.php';  // Composer olmadan kullanmak için.

/*
 * Sınıfımızı tanımlıyoruz $folder = new Astald\Folderio; kullanımı yerine use ile sınıfımızda belirtiyoruz. Siz istediğiniz gibi kullanabilirsiniz.
*/
use Astald\Folderio;
$folder = new Folderio;

// Listeleme tipi varsayılan NULL dur, ihtiyacınıza göre, "size", "type" vb. yapabilirsiniz.
$folder->orderBy = 'type';

/* Listeleme yapılırken görünmesini istemediğimiz dosya veya klasörlerimizin adınız yazıyoruz. */
$folder->setFileHidden(array("index.php","zsystem"));

/* setFolderName() fonksiyonumuza, listelemenin baz alınacağı klasörü yazıyoruz. Varsayılan kök dizindir. */
$folder->setFolderName('customer');

foreach ($folder->getFolder() as $key => $value) {
	echo "Dosya Adı : {$value['filename']}";
	echo "<br>";
	echo "Tam Dosya Adı: {$value['name']}";
	echo "<br>";
	echo "Dosya Türü: {$value['type']}";
	echo "<br>";
	echo "Dosya Boyutu: {$value['size']}";
	echo "<br>";
	echo "Son Düzenleme Tarihi: {$value['lastmod']}";
	echo "<br>";
}
