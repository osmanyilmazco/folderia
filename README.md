# Folderio
Geliştirilebilir klasör işlemleri sınıfı, composer ile kullanım yapılmaktadır.

## Kullanım
### Composer ile kullanım

```composer require osmanyilmazco/folderio "~1.1"```

Alternatif olarak ```composer.json``` dosyasınada ekleyebilirsiniz.
```json
"require": {
    "osmanyilmazco/folderio": "~1.1"
}
```

### Normal Kullanım
**Composer olmadan kullanmak için** autoload.php dosyamızı kullanmak istediğimiz yere dahil ediyoruz.
```php
require_once __DIR__ . '/src/autoload.php';
```

**Sınıfımızı tanımlıyoruz** ```$folder = new Astald\Folderio;``` kullanımı yerine use ile sınıfımızda belirtiyoruz.  Siz istediğiniz gibi kullanabilirsiniz.
```php
$folder = new Astald\Folderio;

$items = $folder->setFileHidden(array("index.php","localhost","folderio"))->setFolder('src')->toArray();
// $folder->setFolder('/folderio-master')->createFolder('test');
// $folder->setFolder('/folderio-master')->deleteFolder('test');
$itemsTwo = $folder->setFolder('/')->toArray();
```
7

**setFolderName()** fonksiyonumuza, listelemenin baz alınacağı klasörü yazıyoruz. Varsayılan kök dizindir
```php
$folder->setFolder('customer');
```

**toArray()** fonksiyonu ile listelemyi sağlıyoruz.
```php
$folder->toArray();
```

### Kullanım örneği
```php
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
```
```php
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
```
