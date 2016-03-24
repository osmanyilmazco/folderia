# Folderia
Folderia List Classes

## Kullanım
**Sınıfımızı tanımlıyoruz**
```
require "class/Folder.php";
```
**Sınıfımızı tanımlıyoruz**
```
$folder = new Folder;
```
#### Listeleme tipi varsayılan NULL dur, siz isterseniz, "size", "type" vb. yapabilirsiniz.

```
$folder->orderBy = 'type';
```

**Gizlemek istediğimiz dosyaları giriyoruz**
```
$folder->setFileHidden(array("index.php","zsystem"));
```

**gireceğimiz dosyanın adını baz alarak listelemeyi sağlıyoruz, Varsayılan kök dizindir **
```
$folder->setFolderName('customer');
```

### Listelemeyi yapıyoruz.
```
foreach ($folder->getFolder() as $key => $value) {
	echo "Dosya Adı : {$value['filename']}, Tam Dosya Adı: {$value['name']}, Dosya Türü: {$value['type']}, Dosya Boyutu: {$value['size']}, Son Düzenleme Tarihi: {$value['lastmod']}";
	echo "<br>";
}
```
