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
 * Online records for game Chars (android)
 */
define('INCMS', 1);

$namepage = 'Chars Record';

require('engine/engine.php');

// HEAD BEGIN

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n" .
 "\n" . '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">' .
 "\n" . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">' .
 "\n" . '<head>' .
 "\n" . '<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>' .
 "\n" . '<meta http-equiv="Content-Style-Type" content="text/css" />' .
 "\n" . '<meta name="Generator" content="EofCms, http://eof-cms.h2m.ru" />' .
 "\n" . '<link rel="stylesheet" href="' . $home . '/theme/' . $user_theme . '/style.css" type="text/css" />' .
 "\n" . '<link rel="shortcut icon" href="' . $home . '/favicon.ico" />' .
 "\n" . '<title>' . $namepage . '</title>' .
 "\n" . '</head><body>';

// HEAD END

$on_page = 20;
$total_num = $db->numInTable('chars');
// Выборка пользователей
$result = $db->query("SELECT * FROM `chars` ORDER BY `score` DESC LIMIT $start, $on_page") or die('Error in online.php, select online users' . $db->error);
// Проверка
if ($total_num == 0) {
    functions::err("Пусто");
}
$num_rows = $result->num_rows;
if ($num_rows == 0) {
    functions::err("На этой странице пусто", '../online.php?start=0');
}
$on_page_list = $num_rows < $on_page ? $num_rows : $on_page;

//Вывод

echo'<div class="maintxt">';
echo'<div class="phdr">Chars Scores</div>';
$bool = true;
for ($i = 0; $i < $on_page_list; $i++) {
    $sql = $result->fetch_array(MYSQLI_ASSOC);
    if ($bool) {
        $div = 'list1';
    } else {
        $div = 'list2';
    }
    echo'<div class="' . $div . '">';
    $bool = !$bool;
    // CONTENT BEGIN

    echo '<center><font size=+1>' . htmlspecialchars($sql['login']) . '</font></center>'
    . '<img src="theme/' . $user_theme . '/images/op.gif"/>&nbsp;'
    . '<b>Score: ' . htmlspecialchars($sql['score']) . '; Game length: ' . htmlspecialchars($sql['time_game_length']) . ' s</b><br/>'
    . 'Device: ' . htmlspecialchars($sql['model']) . '<br/>'
    . '<i>' . $sql['time'] . '</i>';

    // CONTENT END
    echo'</div>';
}

//постраничка
functions::pageNav($total_num, $on_page, 5, '');
echo'</div>';

//require('engine/end.php');

echo'</center></body></html>';
?>
