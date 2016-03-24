<?php 

/*
 * Folderia, Klasör sınıfı
 *  
 * Author:  Osman YILMAZ 
 * Email:   osmnylmz@outlook.com
 * Web:     http://www.astald.com - www.osmnylmz.com
 * 
 * Created Date: 23.03.2016
 *
**/

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Astald\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/Astald/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);


    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});