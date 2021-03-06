<?php
namespace lib;

//a simple wrapper around the filesystem to be able to use files in the right directory
use core\Loader;

class Filesystem extends \core\Lib
{

    //generate a full path
    function calculatePath($file)
    {
        //if the string doesnt start with / or ~/ it will be considered a project file
        if (!(substr($file, 0, 1) == "/" || substr($file, 0, 2) == "~/")) {
            $file = $this->path->getApp() . "/" . $file;
        }

        //if it starts with ~/ it will be considered a developer file..
        if (substr($file, 0, 2) == "~/") {
            $file = str_replace("~/", $_SERVER["HOME"] . "/", $file);
        }

        return $file;
    }


    function findRightPath($file)
    {
        if (!(substr($file, 0, 1) == "/" || substr($file, 0, 2) == "~/")) {
            $fullPath = $this->calculatePath("project/modules/" . Loader::$module . "/" . $file);
            if (file_exists($fullPath)) {
                return $fullPath;
            }

            $fullPath = $this->calculatePath("modules/" . Loader::$module . "/" . $file);
            if (file_exists($fullPath)) {
                return $fullPath;
            }

            $fullPath = $this->calculatePath("project/" . $file);
            if (file_exists($fullPath)) {
                return $fullPath;
            }

        }

        $fullPath = $this->calculatePath($file);
        if (file_exists($fullPath)) {
            return $fullPath;
        }

        return false;
    }

    //read a file entirely
    function getFile($file)
    {
        if ($fullPath = $this->findRightPath($file)) {
            return file_get_contents($fullPath);
        }
        return false;
    }


    function getContentType($file)
    {
        if ($fullPath = $this->findRightPath($file)) {
            return mime_content_type($fullPath);
        }
        return false;
    }

    //check if folder or file exists
    function exists($path)
    {
        $path = $this->calculatePath($path);
        return file_exists($path);
    }


    //create a directory
    function mkDir($folder)
    {
        return mkdir($this->calculatePath($folder), 0744, true);
    }

    //create an array of all directories
    function getDirs($dir)
    {
        if(!$this->exists($dir))
        {return array();}

        $dirs = array();
        if ($handle = opendir($this->calculatePath($dir))) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_dir($dir . "/" . $entry)) {
                    $dirs[] = $entry;
                }
            }
            closedir($handle);
        }

        return $dirs;
    }

    //create an array of all files
    function getFiles($dir, $type = false)
    {
        if(!$this->exists($dir))
        {return array();}

        $files = array();
        if ($handle = opendir($this->calculatePath($dir))) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && !is_dir($dir . "/" . $entry) && (!$type || substr($entry, -strlen($type)) == $type)) {
                    $files[] = $entry;
                }
            }
            sort($files,SORT_NATURAL);
            closedir($handle);
        }

        return $files;
    }


    //write to content file if file exists clear it first
    function clearWrite($path, $content)
    {
        $path = $this->calculatePath($path);
        $handle = fopen($path, "w+");
        fwrite($handle, $content);
        fclose($handle);
    }

    function getArray($file)
    {

        if ($path = $this->findRightPath($file)) {
            return include $path;
        }
        return array();

    }

    function writeArray($file,$data){
        $serialized=  "<?php return ".var_export($data, true).";";
        $this->clearWrite($file,$serialized);
    }

    function getModified($file){
        $path = $this->findRightPath($file);
        if($path) {
            return filemtime($path);
        }
        return -1;

    }
}

?>