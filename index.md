### Hoşgeldiniz.
Php ile geliştirilebilir, klasör işlemlerini basic olarak yapabileceğiniz sade sınıfıdır.


# Folderio
Geliştirilebilir klasör işlemleri sınıfı, composer ile kullanım yapılabilmektedir.

### Destek ve Bilgi
(https://www.astald.com) Adresinden ulaşabilirsiniz.

## Kullanım
### Composer ile kullanım

``composer require osmanyilmazco/folderio "~1.2"``

Alternatif olarak ``composer.json`` dosyasınada ekleyebilirsiniz.
``json
"require": {
    "osmanyilmazco/folderio": "~1.2"
}
``

### Normal Kullanım
**Composer olmadan kullanmak için** autoload.php dosyamızı kullanmak istediğimiz yere dahil ediyoruz.
``php
require_once __DIR__ . '/src/autoload.php';
``

**Sınıfımızı tanımlıyoruz** ``$folder = new Astald\Folderio;`` kullanılabilir. İsteğe bağlı.
``php
$items2 = Astald\Folderio::factory()->setFolder('/')->toArray();
$items = Astald\Folderio::factory()->setFolder('src')->toArray(); 
// $folder->setFolder('../')->create('folderio');
// Astald\Folderio::factory()->setFolder('/')->delete('folderio');
``

**setFolder()** fonksiyonumuza, listelemenin baz alınacağı klasörü yazıyoruz. Varsayılan kök dizindir
``php
$folder->setFolder('customer');
``

**toArray()** fonksiyonu ile listelemyi sağlıyoruz.
``php
$folder->toArray();
``

### Kullanım örneği
``php
echo "<table border=1>";
echo "<thead>";
echo "<tr><th>Dosya Adı</th><th>Tam Dosya Adı:</th><th>Dosya Türü:</th><th>Dosya Boyutu:</th><th>Son Düzenleme Tarihi</th><th>Görünürlük</th></tr>";
echo "</thead>";
foreach ($folder->toArray() as $key => $value) {
	echo "<tr>";
	echo "<td>{$value['fileName']}</td>";
	echo "<td>{$value['name']}</td>";
	echo "<td>{$value['type']}</td>";
	echo "<td>{$value['size']}</td>";
	echo "<td>{$value['lastMod']}</td>";
	echo "<td>{$value['visible']}</td>";
	echo "</tr>";
} 
echo "</table>"; 
``
``php
echo "<table border=1>";
echo "<thead>";
echo "<tr><th>Dosya Adı</th><th>Tam Dosya Adı:</th><th>Dosya Türü:</th><th>Dosya Boyutu:</th><th>Son Düzenleme Tarihi</th><th>Görünürlük</th></tr>";
echo "</thead>";
foreach ($folder->setFolder('/')->toArray() as $key => $value) {
	echo "<tr>";
	echo "<td>{$value['fileName']}</td>";
	echo "<td>{$value['name']}</td>";
	echo "<td>{$value['type']}</td>";
	echo "<td>{$value['size']}</td>";
	echo "<td>{$value['lastMod']}</td>";
	echo "<td>{$value['visible']}</td>";
	echo "</tr>";
} 
echo "</table>";
``
