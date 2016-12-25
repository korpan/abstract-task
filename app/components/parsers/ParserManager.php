<?php

namespace App\Components\Parsers;

use Exception;

class ParserManager {
    
    protected $config = [];


    public function __construct($config) {
        $this->config = $config;
    }

    public function run(){
        try{
            $this->runSequence($this->config['sequence']);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die;
        }
    }
    
    protected function runSequence($sequence) {
        foreach ($sequence as $item) {
            if(file_exists($item['file'])){
                $this->parse($item['file'], $item['format'], $item['model'], isset($item['additional_data'])?$item['additional_data']:null);
            }else{
                throw new Exception("File '{$item['file']}' does not exist");
            }
        }
    }
    
    protected function parse($file, $format, $model, $additional_data = null){
        $parser = $this->getParser($format);
        
        $data = $parser->parse($file);
        
        if(class_exists($model) && method_exists($model, 'handleParsedData')){
            (new $model)->handleParsedData($data, $additional_data);
        }
    }
    
    protected function getParser($format){
        switch ($format):
            case 'json':
                $parser = new JSONParser();
                break;
            case 'xml':
                $parser = new XMLParser();
                break;
            default:
                throw new Exception("Cannot parse '{$format}' format");
                break;
        endswitch;
        
        return $parser;
    }
}
