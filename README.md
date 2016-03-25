# Folderio
Gelişmiş klasör işlemleri sınıfı, composer ile kullanım yapılmaktadır.

## Kullanım
### Composer ile kullanım

```composer require osmanyilmazco/folderio "~1.0"```

Alternatif olarak ```composer.json``` dosyasınada ekleyebilirsiniz.
```json
"require": {
    "osmanyilmazco/folderio": "~1.0"
}
```

### Normal Kullanım
**Composer olmadan kullanmak için** autoload.php dosyamızı kullanmak istediğimiz yere dahil ediyoruz.
```php
require_once __DIR__ . '/src/autoload.php';
```

**Sınıfımızı tanımlıyoruz** ```$folder = new Astald\Folderio;``` kullanımı yerine use ile sınıfımızda belirtiyoruz.  Siz istediğiniz gibi kullanabilirsiniz.
```php
use Astald\Folderio;
$folder = new Folderio; // $folder = new Folderio($config);
```

#### Listeleme tipi varsayılan NULL dur, ihtiyacınıza göre, **"size", "type"** vb. yapabilirsiniz.

```php
$folder->orderBy = 'type';
```

Listeleme yapılırken görünmesini istemediğimiz dosyalarımızı dizi değişkenimizle belirtiyoruz.
```php
$folder->setFileHidden(array("index.php","zsystem"));
```

**setFolderName()** fonksiyonumuza, listelemenin baz alınacağı klasörü yazıyoruz. Varsayılan kök dizindir
```php
$folder->setFolderName('customer');
```

**getFolder()** fonksiyonu ile listelemyi sağlıyoruz.
```php
$folder->getFolder();
```

### Kullanım örneği
```php
echo "<table border=1>";
echo "<thead>";
echo "<tr><th>Dosya Adı</th><th>Tam Dosya Adı:</th><th>Dosya Türü:</th><th>Dosya Boyutu:</th><th>Son Düzenleme Tarihi</th><th>Görünürlük</th></tr>";
echo "</thead>";
foreach ($folder->getFolder() as $key => $value) {
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
