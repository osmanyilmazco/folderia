<?php 

/**
 * Folderio, Klasör sınıfı
 *  
 * Author:  Osman YILMAZ 
 * Email:   osmnylmz@outlook.com
 * Web:     http://www.astald.com - www.osmnylmz.com
 * 
 * Created Date:	23.03.2016
 * Update Date:		25.03.2016
**/

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
	const VERSION = '1.0.5';

	/**
	 * Hata çıktı bildiri
	 * @var boolean
	*/
	public $debug = false;

	/**
	 * Kök dizin değişkeni
	 * @var string
	*/
	public $rootFolder = null;	

	/**
	 * Kök dizin isim değişkeni
	 * @var string
	*/
	public $rootFolderName = null;

	/**
	 * Listleme filtre değişkeni
	 * @var string
	*/
	public $orderBy = null;

	/**
	 * Gizli dosya ve klasör değişkeni
	 * @var array
	*/
	public $hiddenFiles = array('.','..');   

	/**
	 * Klasör alt kayıt değişkeni
	 * @var boolean
	*/
	public $children = false;

	/**
	 * Klasör oluşturma değişkeni
	 * @var string
	*/
	public $createFolder = null;

	/**
	 * Klasör silme değişkeni
	 * @var string
	*/
	public $deleteFolder = null;

	/**
	 * Klasör paket değişkeni
	 * @var string
	*/
	public $packageFolder = 'localhost';


	/**
        * __construct methodu verileri atar.
        *
        * @param array $config
        **/
	public function __construct(array $config = array()) 
	{
		foreach ($config as $key => $value) {
			$accept = array('debug', 'orderBy' , 'children');

			if(isset($this->$key) and in_array($key, $accept)) {
				$this->$key = $value;
			}
		}
		$this->rootFolder = realpath($_SERVER["DOCUMENT_ROOT"]);

		array_push($this->hiddenFiles, $this->packageFolder); 
	}


	/**
        * Gizlenecek olan dosya, klasörleri belirler
        *
        * @param string $file
       * @return array
       */
	public function setHiddenFiles($files)
	{
		if (is_array($files)) {
			$this->hiddenFiles = array_merge($this->hiddenFiles, $files); 
		} else {
			$this->hiddenFiles = array_push($this->hiddenFiles, $files);
		}
	}

	/**
        * Kökdizinin belirler, varsayılan kök dizindir.
        *
        * @param string $rootFolderName
        */
	public function setFolderName($rootFolderName)
	{
		if(!empty($rootFolderName)) 
		{
			$this->rootFolderName = $rootFolderName;
			$this->rootFolder = $this->rootFolder."/".$this->rootFolderName;
		}
		else 
			$this->rootFolder;
	}

	/**
     * Klasör ve dosyaları listeler.
     *
     * @return array
     */
	public function getFolder()
	{
		try {
			$this->rootFolderName = new DirectoryIterator($this->rootFolder);
			// $this->rootFolderName = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->rootFolder), RecursiveIteratorIterator::SELF_FIRST); 
		} catch (RuntimeException $e) {
			throw new Exception("Error Processing Request Error: {$e->getMessage()}");
		}

		if(!empty($this->orderBy)) 
		{
			$this->folderList = new FieldSortHeap($this->orderBy);
		}

		foreach($this->rootFolderName as $fileinfo) 
		{   
			if (!in_array($fileinfo->getFileName(), $this->hiddenFiles)) {
				$fileArrays = array(
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
				if (!empty($this->orderBy)) {
					$this->folderList->insert($fileArrays); 
				} else {
					$this->folderList[] = $fileArrays; 
				}
			}
			 
		}

		return $this->folderList;
	}


	/**
     * Dosya ve Klasör varlığını kontrol eder.
     *
     * @param string $name
     * @return boolean
     */
	public function folderOrFileExists($name) 
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
		try {
			$this->createFolder = $this->rootFolder ."/". $name;

			if ($this->folderOrFileExists($this->createFolder)) {
				throw new Exception("File exists! Please change folder or file name");			
			}
			return mkdir($this->createFolder, $chmod); 
		} catch (RuntimeException $e) {
			throw new Exception("Couldn't create folder or file: {$e->getMessage()}");
		}
	}

	/**
     * Klasör siler
     *
     * @param string $name
     * @param integer $chmod
     * @return boolean
     */
	public function deleteFolder($name, $permission = true) 
	{
		try {
			$this->deleteFolder = $this->rootFolder ."/". $name;

			if (!$this->folderOrFileExists($this->deleteFolder)) {
				throw new Exception("File doesn't exists!");			
			}
			return rmdir($this->deleteFolder); 
		} catch (RuntimeException $e) {
			throw new Exception("Couldn't deleted folder or file: {$e->getMessage()}");
		}
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
