<?php
/* EofCMS
  Copyright (C) 2013  NaikSoftware

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License along
  with this program; if not, write to the Free Software Foundation, Inc.,
  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */
 
/*
 * Парсер картинок с sever.ru
 */
define('INCMS', 1);
$namepage = 'Лучшие картинки Вапа';

require('engine/engine.php');
require('engine/head.php');

$home = 'http://naik.wup.ru/';

(isset($_GET['start'])) ? $start = abs(intval($_GET['start'])) : $start = 0;

if (!isset($_GET['dir']) || $_GET['dir'] == 'pic') {
    $path = 'http://pic.sever.ru/';
    razmer($path);
    require('engine/end.php');
    exit();
} else {
    $dom = 'http://pic.sever.ru/dc3.php?dir=' . $_GET['dir'];
    $path = $dom . '&s=' . $start;
    if (show($path) == "inlist")
        folder($path);
}

function show($path_to_page) {
    global $start;
    $data = file_get_contents($path_to_page);
    if (!preg_match_all("/(?:cache)[-a-zA-Z0-9_.\s\"\/]+.jpg/", $data, $cache)) {
        return "inlist";
    }
    preg_match_all("/(?:pic)[-a-zA-Z0-9_.\s\"\/]{1,}.jpg/", $data, $download);
    preg_match("/(Страниц:\s\<b\>)([0-9]{1,3})/", $data, $s_all);
    echo'<div class="maintxt">';

    for ($i = 0; $i < sizeof($cache[0]); $i++) {
        echo'<img src="http://pic.sever.ru/' . $cache[0][$i] . '" alt="naik.wup.ru"/><a href="http://pic.sever.ru/' . $download[0][$i * 2] . '">Скачать</a><br/>';
    }

    echo'<div class="bmenu">';
    functions::pageNav($s_all[2], 1, 5, '?dir=' . $_GET['dir']);
    echo"<br/><hr>Всего страниц: " . ($s_all[2]);
    echo back() . '</div></div>';
    return "nolist";
}

function razmer($path) {
    echo'<div class="phdr">Выберите размер картинок</div>';
    $data = file_get_contents($path);
    preg_match_all("/dir[^\"]+/", $data, $pagesize);

    for ($i = 0; $i < sizeof($pagesize[0]); $i++) {
        preg_match("/[0-9x]{7}/", $pagesize[0][$i], $page);
        echo'<div class="menu"><a href="' . $home . 'test.php?' . $pagesize[0][$i] . '">' . $page[0] . '</a><br/></div>';
    }
}

function folder($path) {
    $bool = true;
    $data = file_get_contents($path);
    preg_match_all("/dir=[^\"]+/", $data, $page);
    echo'<div class="phdr">Выберите раздел</div>';
    echo'<div class="maintxt">';

    for ($i = 0; $i < sizeof($page[0]); $i++) {
        if (preg_match("/Novye$|Muzhchiny$|!Znamenitosti$/", $page[0][$i]))
            continue;
        ($bool) ? ($div = '<div class="list1">') : ($div = '<div class="list2">');
        $bool = !$bool;
        echo $div . '<a href="' . $home . 'test.php?' . $page[0][$i] . '">' . translate(preg_replace("/\&[-a-zA-Z0-9\&\?\=_\;]+|\/|\!/", "", strrchr($page[0][$i], "/"))) . '</a></div>';
    }

    echo'</div><div class="bmenu">' . back() . '</div>';
}

function translate($text) {
    $text = strtolower($text);
    $tr = array("a" => "а", "b" => "б", "v" => "в", "g" => "г", "d" => "д", "e" => "е", "j" => "й", "zh" => "ж", "z" => "з", "i" => "и", "y" => "й", "k" => "к", "l" => "л", "m" => "м", "n" => "н", "o" => "о", "p" => "п", "r" => "р", "s" => "с", "t" => "т", "u" => "у", "f" => "ф", "h" => "х", "c" => "ц", "ch" => "ч", "sh" => "ш", "щ" => "sch", "y" => "ы", "q" => "ь", "yu" => "ю", "ya" => "я", "_" => " ");
    return strtr($text, $tr);
}

function back() {
    return '<ul><li><a href="?dir=' . str_replace(strrchr($_GET['dir'], '/'), '', $_GET['dir']) . '">Назад</a></li></ul></div>';
}

require('engine/end.php');
?>
