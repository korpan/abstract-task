<?php

namespace App\Components\Parsers;

class JSONParser extends AbstractParser{
    
    public function parse($file) {
        if(file_exists($file)){
            $content = file_get_contents($file);
        }
        
        return json_decode($content, true);
    }
    
}
