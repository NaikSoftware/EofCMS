<?php

define('INCMS', 1);
$namepage='Ошибка 404';

require('engine/engine.php');
require('engine/head.php');

 functions::err('Запрошеного файла или страницы не существует!');

require('engine/end.php');

?>
