<?php

require_once 'classes/FileManager.php';

$fileHandler = new FileManager('dados/wine.data', true,',');

echo '<pre>';
var_dump($fileHandler->getDataAsArray());