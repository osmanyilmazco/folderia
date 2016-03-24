<?php 

/*
 * Folderio, Klasör sınıfı
 *  
 * Author:  Osman YILMAZ 
 * Email:   osmnylmz@outlook.com
 * Web:     http://www.astald.com - www.osmnylmz.com
 * 
 * Created Date: 23.03.2016
 *
**/

namespace Astald;

use Astald\FieldSortHeap;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Folderio
{	
	/*
	 * Class is version 
	*/
	const VERSION = '1.0';

	/*
	 * Class is variable
	*/
	public $debug = false;
	public $rootFolder = NULL;
	public $rootFolderName = NULL;
	public $orderBy = NULL;
	public $packageFolder = 'localhost';
	public $fileHidden = array('.','..');  
	public $fileHiddenAdd = array();  
	public $threeList = false;

	/*
	 *  Sınıf çağrılacağı zaman ilk çalışacak olan methodumuz.
	**/
	public function __construct() 
	{ 
		$this->rootFolder = $_SERVER["DOCUMENT_ROOT"];
		
		array_push($this->fileHidden, $this->packageFolder); 
	}

	/*
	 *  gizlemek istediğimiz dosya ve klasörler için kullanmış olduğumuz method
	**/
	public function setFileHidden($fileHiddenAdd)
	{
		$this->fileHidden = array_merge($this->fileHidden, $fileHiddenAdd); 
	}

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

	public function getFolder()
	{
		try 
		{
			$this->rootFolderName = new RecursiveDirectoryIterator($this->rootFolder);
			// $this->threeList // $this->rootFolderName = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->rootFolder), RecursiveIteratorIterator::SELF_FIRST); 
		} catch (RuntimeException $e) {
			throw new Exception("Error Processing Request Error: {$e->getMessage()}", 1);
		}

		if(!empty($this->orderBy)) 
		{
			$this->folderList = new FieldSortHeap($this->orderBy);
		}

		foreach($this->rootFolderName as $fileinfo) 
		{  
			if(!in_array($fileinfo->getFileName(), $this->fileHidden)) 
			{
				if(!empty($this->orderBy))
				{
					$this->folderList->insert(array(
						'name' => $fileinfo->getPathname(),
						'fullname' => $fileinfo,
						'filename' => $fileinfo->getFileName(),
						'type' => ($fileinfo->getType() == "dir") ? "dir" : mime_content_type($fileinfo->getRealPath()),
						'size' => $this->fileSizeConvert($fileinfo->getSize()),
						'lastmod' => $fileinfo->getMTime()
					));
				}
				else
				{
					$this->folderList[] = array(
						'fullname' => $fileinfo,
						'name' => $fileinfo->getPathname(),
						'filename' => $fileinfo->getFileName(),
						'type' => ($fileinfo->getType() == "dir") ? "dir" : mime_content_type($fileinfo->getRealPath()),
						'size' => $this->fileSizeConvert($fileinfo->getSize()),
						'lastmod' => $fileinfo->getMTime()
					);
				}
			}
			 
		}

		return $this->folderList;
	}



	/*
	 * File Size
	*/ 
	public $bytes = NULL;
	public function fileSizeConvert($bytes)
    {
        if ($bytes >= 1073741824)
            $this->bytes = number_format($bytes / 1073741824, 2) . ' GB';
        elseif ($bytes >= 1048576)
            $this->bytes = number_format($bytes / 1048576, 2) . ' MB';
        elseif ($bytes >= 1024)
            $this->bytes = number_format($bytes / 1024, 2) . ' KB';
        elseif ($bytes > 1)
            $this->bytes = $bytes . ' bytes';
        elseif ($bytes == 1)
            $this->bytes = $bytes . ' byte';
        else
            $this->bytes = '0 bytes';

        return $this->bytes;
	}

}
