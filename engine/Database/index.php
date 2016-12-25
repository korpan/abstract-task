<?php
define('ROOT', dirname(dirname(__FILE__)));

define('DEBUG', FALSE);

require_once ROOT.DIRECTORY_SEPARATOR.'autoload.php';
require_once ROOT.DIRECTORY_SEPARATOR.'routes.php';


(new Engine\App)->run();