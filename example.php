<?php 

namespace Astald;
 
use Astald\FieldSortHeap;
use Exception;
use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Folderio, Klasör sınıfı
 *  
 * Author : Osman YILMAZ 
 * Email  : osmnylmz@outlook.com
 * Web    : http://www.astald.com - www.osmnylmz.com
 * 
 * Created Date     : 23.03.2016 
 * Last Update Date : 27.03.2016
**/
    
class Folderio
{   
    /*
     * Sınıf sürümü
    */
    const VERSION = '1.1.0';

    /**
     * Hata çıktı bildiri
     * @var boolean
    */
    public $debug = false;

    /**
     * Kök dizin
     * @var string
    */
    private $folder;

    /**
     * Sıralama sınıf nesnesi
     * @var SplHeap Object
    */
    private $shortHeap;  

    /**
     * Gizli dosyalar ve klasörler
     * @var array
    */
    private $hiddenFiles  = array('.', '..');

    /**
     * Kök dizin sonuç değerleri
     * @var array
    */
    private $items  = array();

    /**
     * Dizin tanımlaması yapılır.
     * 
     * @param string $folder 
     */
    public function __construct($folder = '')
    {
        $this->folder = realpath(''); 

        if (!empty($folder)) {
            $this->setFolder($folder);
        } 
    }

    /**
     * Kökdizinin belirler, varsayılan kök dizindir.
     *
     * @param string $name
     * @return Folderio
    */
    public function setFolder($folder) 
    {
        $realpath = realpath('') . '/' . $folder;

        if ($this->exists($realpath)) {
            $this->folder = $realpath;
            $this->shortHeap = null;
            $this->hiddenFiles = array('.', '..');
            $this->items = array();
        } else {
            throw new Exception("Folder doesn't exists!");
        }
        return $this;
    }

    /**
     * Gizlenecek olan dosya, klasörleri belirler
     *
     * @param string $file 
     * @return Folderio
    */
    public function setHiddenFiles($files)
    {
        if (is_array($files)) {
            $this->hiddenFiles = array_merge($this->hiddenFiles, $files); 
        } else {
            $this->hiddenFiles = array_push($this->hiddenFiles, $files);
        }
        return $this;
    }

    /**
     * Klasör ve dosyaları listeler.
     *
     * @return array
    */
    private function getData()
    { 
        try {
            $iterator = new DirectoryIterator($this->folder);
            // $folderName = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), RecursiveIteratorIterator::SELF_FIRST); 
        } catch (RuntimeException $e) {
            throw new Exception("Error Processing Request Error: {$e->getMessage()}");
        }

        foreach ($iterator as $fileinfo) {   
            if (!in_array($fileinfo->getFileName(), $this->hiddenFiles)) {
                $item = array(
                    'name' => $fileinfo->getPathname(),
                    'fullName' => $fileinfo,
                    'fileName' => $fileinfo->getFileName(),
                    'type' => ($fileinfo->getType() == "dir") ? "dir" : "file",
                    'realType' => ($fileinfo->getType() == "dir") ? "dir" : mime_content_type($fileinfo->getRealPath()),
                    'size' => $this->fileSizeConvert($fileinfo->getSize()),
                    'visible' => $fileinfo->isDot() ? 1 : 0,
                    'getOwner' => $fileinfo->getOwner(),
                    'lastMod' => $fileinfo->getMTime()
                );
                $this->items[] = $item;
                if (!empty($this->shortHeap)) { 
                    $this->shortHeap->insert($item); 
                }
            } 
        }  
    }

    /**
     * Filtreleme methodu.
     *
     * @return Folderio
    */
    public function orderBy($name) 
    { 
        $this->shortHeap = new FieldSortHeap($name); 
        return $this;
    }

    /**
     * Kök dizin sonuçlarını array olarak döndürür.
     *
     * @return array
    */
    public function toArray() 
    { 
        if (count($this->items) < 1) {
            $this->getData();
        } 

        if (isset($this->shortHeap)) {
            return iterator_to_array($this->shortHeap);
        }
        return $this->items;
    }

    /**
     * Kök dizin sonuçlarını json olarak döndürür.
     *
     * @return array
    */
    public function toJson() 
    { 
        return json_encode($this->toArray());
    }

    /**
     * Dosya ve Klasör varlığını kontrol eder.
     *
     * @param string $name
     * @return boolean
     */
    private function exists($name) 
    {
        if (file_exists($name)) {
            return true; 
        } else {
            return false;
        }
    }

    /**
     * Klasör oluşturur
     *
     * @param string $name
     * @param integer $chmod
     * @return boolean
     */
    public function createFolder($name, $chmod = 0700) 
    { 
        $path = $this->folder . '/' . $name;
        
        if ($this->exists($path)) {
            throw new Exception("File exists! Please change folder or file name");          
        }

        return mkdir($path, $chmod); 
    }

    /**
     * Klasör siler
     *
     * @param string $name 
     * @return boolean
     */
    public function deleteFolder($name, $permission = true) 
    { 
        $path = $this->folder . '/' . $name;

        if (!$this->exists($path)) {
            throw new Exception("File doesn't exists!");            
        }

        return rmdir($path); 
    }

    /**
     * Dosya boyutunu hesaplar
     * 
     * @param integer $bytes 
     */
    public function fileSizeConvert($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
}
