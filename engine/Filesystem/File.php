<?php

namespace Engine\Filesystem;


class File {
    
    /**
     * returns path replacing dots with DIRECTORY_SEPARATOR
     * 
     * @param string $path
     * @return string $path
     */
    public static function getPath($path){
        $path = self::realpath($path);

        if(strpos($path,'.')!==false){
            return str_replace('.',DIRECTORY_SEPARATOR,$path);
        }else{
            return $path;
        }
    }
    
    
    public static function realpath($path) {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }
    
}
