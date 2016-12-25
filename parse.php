<?php

use App\Components\Parsers\ParserManager;
use Engine\Config\Config;


require __DIR__.'/bootstrap/autoload.php';


Config::instantiate([
    Config::APP=>require __DIR__.'/config/app.php',
    Config::DATABASE=>require __DIR__.'/config/database.php',
]);

$config = require __DIR__.'/config/parser.php';

$parserManager = new ParserManager($config);


//run parsing sequence configured in parser config
$parserManager->run();
