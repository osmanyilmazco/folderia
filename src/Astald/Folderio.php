<?php 

/**
 * Folderio, Klasör sınıfı
 *  
 * Author : Osman YILMAZ 
 * Email  : astald@astald.com <info@astald.com>
 * Web    : http://www.astald.com
 * 
 * Created Date     : 23.03.2016 
 * Last Update Date : 09.04.2016
 */

namespace Astald;
 
use Astald\FieldSortHeap;
use Exception;
use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator; 

class Folderio
{   
    /*
     * Sınıf sürümü
     */
    const VERSION = '1.2.5';

    /**
     * [$folder Kök dizin]
     * @var [string]
     */
    private $folder;

    /**
     * [$shortHeap Sıralama sınıf nesnesi]
     * @var [SplHeap Object]
     */
    private $shortHeap;  

    /**
     * [$hiddenFiles Gizli dosyalar ve klasörler]
     * @var array
     */
    private $hiddenFiles  = array('.', '..');

    /**
     * [$items Kök dizin sonuç değerleri]
     * @var array
     */
    public $items  = array();

    /**
     * [$aliases Klasör oluşturma, silme, yeniden isimlendirme ve taşıma işlemleri için kullanım kolaylığı sağlanır.]
     * @var array
     */
    public $aliases = array(
        'create'    => array('createFolder', 'createDir', 'createDirectory'),
        'delete'    => array('deleteDir', 'deleteDirectory'),
        'rename'    => array('renameFolder', 'renameDir', 'renameDirectory'),
        'move'      => array('moveFolder','moveDir', 'moveDirectory'),
        'hidden'    => array('hiddenFolder', 'hiddenFolders', 'hiddenFile', 'hiddenFiles')
    );

    /**
     * [__construct Dizin tanımlaması yapılır]
     * @param string $folder [description]
     */
    public function __construct($folder = '')
    {
        $this->setFolder($folder);
    }

    /**
     * [__call Sınıf içinde tanımlanan methodların varlığını kontrol eder.]
     * @param  [string] $methodName      [description]
     * @param  [array]  $methodArguments [description]
     * @return [type]                    [description]
     */
    public function __call($methodName, $methodArguments)
    {
        foreach ($this->aliases as $method => $_aliases)
        {
            if (in_array($methodName, $_aliases) && method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $methodArguments);
            }
        }
    }

    /**
     * [factory Sınıfı kendi içinde tanımlayıp çağırır.]
     * @param  [string] $folder [description]
     * @return [type]           [description]
     */
    public static function factory($folder = NULL)
    {
        return new self($folder);
    }

    /**
     * [Gizlenecek olan dosya, klasörleri belirler]
     * @param  [string] $name [description]
     * @return [type]         [description]
     */
    public function hidden($name)
    { 
        if (is_array($name)) {
            $this->hiddenFiles = array_merge($this->hiddenFiles, $name); 
        } else {
            $this->hiddenFiles = array_push($this->hiddenFiles, $name);
        }  
        return $this;
        return $this;
    }


    /**
     * [create Klasör oluşturur]
     * @param  [string]  $name  [description]
     * @param  integer $chmod [description]
     * @return [type]         [description]
     */
    public function create($name, $chmod = 0700) 
    { 
        $path = $this->folder . DIRECTORY_SEPARATOR . $name;
        
        if (!file_exists($path)) {         
            return mkdir($path, $chmod); 
        }
    }

    /**
     * [delete Klasör siler]
     * @param  [string] $name [silinmesi istenilen isim değişkeni]
     * @return [type]   
     */
    public function delete($name)
    { 
        $path = $this->folder . DIRECTORY_SEPARATOR . $name;

        if (file_exists($path)) {
            return rmdir($path); 
        }
    }

    /**
     * [directory methodu döngü yapar.]
     * @param  [object] $iterator [klasör listelemesini sağlar]
     * @return [Folderio]
     */
    private function direcotry()
    { 
        try {
            $iterator = new DirectoryIterator($this->folder . DIRECTORY_SEPARATOR); 
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
     * [orderBy Filtreleme methodu.]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function orderBy($name) 
    { 
        $this->shortHeap = new FieldSortHeap($name);
        return $this;
    }

    /**
     * [toArray Dizin sonuçları methodu]
     * @return [array]      [description]
     */
    public function toArray() {
        if (count($this->items) < 1) {
            $this->direcotry();
        } 
        if (isset($this->shortHeap)) {
            return iterator_to_array($this->shortHeap);
        }
        return $this->items;
    }

    /**
     * [toArray Dizin sonuçları methodu]
     * @return [json]      [description]
    */
    public function toJson() 
    { 
        return json_encode($this->toArray());
    }


    /**
     * [setFolder Kökdizinin belirler, varsayılan kök dizini baz alır.]
     * @param [type] $folder [description]
     */
    public function setFolder($folder)
    {
        $realpath = realpath($folder);

        if ( ! file_exists($realpath) ) {
            throw new Exception("Folder doesn't exists!");
        }


        $this->folder = $realpath;
        $this->shortHeap = null;
        $this->hiddenFiles = array('.', '..');
        $this->items = array();

        return $this;
    }

    /**
     * [fileSizeConvert Dosya boyutunu hesaplar]
     * @param  [integer] $bytes [description]
     * @return [type]        [description]
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
