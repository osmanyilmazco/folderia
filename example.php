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

 
$folder = new Astald\Folderio;

$items2 = $folder->setFolder('/')->toArray();
$items = $folder->setFolder('src')->toArray();
// $folder->setFolder('../')->createFolder('folderio');
// $folder->setFolder('../')->deleteFolder('folderio');
 

echo "<table border=1>";
echo "<thead>";

echo "<tr><th>Dosya Adı</th><th>Tam Dosya Adı:</th><th>Dosya Türü:</th><th>Dosya Boyutu:</th><th>Son Düzenleme Tarihi</th><th>Görünürlük</th></tr>";
echo "</thead>";
foreach ($items as $key => $value) {
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

echo "<table border=1>";
echo "<thead>";

echo "<tr><th>Dosya Adı</th><th>Tam Dosya Adı:</th><th>Dosya Türü:</th><th>Dosya Boyutu:</th><th>Son Düzenleme Tarihi</th><th>Görünürlük</th></tr>";
echo "</thead>";
foreach ($items2 as $key => $value) {
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
