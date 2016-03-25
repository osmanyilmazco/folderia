# Folderio
Folderio klasör listeleme sınıfı, composer ile kullanım yapılmaktadır.

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

Listeleme yapılırken görünmesini istemediğimiz dosyalarımızı paramet
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
foreach ($folder->getFolder() as $key => $value) {
	echo "Dosya Adı : {$value['filename']}, Tam Dosya Adı: {$value['name']}, Dosya Türü: {$value['type']}, Dosya Boyutu: {$value['size']}, Son Düzenleme Tarihi: {$value['lastmod']}";
	echo "<br>";
}
```
