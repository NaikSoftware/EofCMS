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
define('INCMS', 1);

$namepage = 'Кто на сайте?';

require('engine/engine.php');
require('engine/head.php');

//Проверка

if ($_GET['mode'] == 'guest') {
    $mode = 1;
    $total_num = functions::$counter_online_guest;
//Выборка
    $result = $db->query("SELECT `guest_ip`, `guest_agent`, `guest_path`, `guest_head`, `guest_date`, `guest_click` FROM `eof_online` ORDER BY `guest_date` DESC LIMIT $start, $on_page") or die('Error in online.php, select guest' . $db->error);
} elseif ($_GET['mode'] == 'user') {
    $mode = 2;
    $total_num = functions::$counter_online_user;
//Выборка пользователей
    $result = $db->query("SELECT * FROM `eof_users` WHERE ($current-`users_lasttime`<600) ORDER BY `users_lasttime` DESC LIMIT $start, $on_page") or die('Error in online.php, select online users' . $db->error);
} else {
    functions::err("Ссылка неправильная");
}
if ($total_num == 0) {
    functions::err("Пусто");
}
$num_rows = $result->num_rows;
if ($num_rows == 0) {
    functions::err("На этой странице пусто", '../online.php?mode=' . $_GET['mode'] . '&start=0');
}
$on_page_list = $num_rows < $on_page ? $num_rows : $on_page;

//Вывод

echo'<div class="maintxt">';
echo'<div class="phdr">' . ($mode == 1 ? 'Гости' : 'Пользователи') . ' онлайн</div>';
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
    if ($mode == 1) {
        echo'<table cellpadding="5px" border="0px" rules="none"><tr><td><img src="theme/' . $user_theme . '/images/on_guest.png" alt="Guest"/></td><td><b>Гость</b> (Переходов: ' . $sql['guest_click'] . ')<br/><font size="-3">' . functions::parseDate($sql['guest_date']) . '</font></td></tr></table><img src="theme/' . $user_theme . '/images/op.gif" alt="go"/>&nbsp;';
    } else {
        echo'<table cellpadding="5px" border="0px" rules="none"><tr><td><img src="/user/avatars/' . ($sql['users_avatar'] == null ? 'none.png' : $sql['users_avatar']) . '" alt="' . $sql['users_surname'] . '"/></td><td><b>' . $sql['users_surname'] . '</b> ';
        switch ($sql['users_rights']) {
            case 8:
                echo'[SV]';
                break;
            case 9:
                echo'[SV!]';
                break;
        }
        echo' (Переходов: ' . $sql['users_click'] . ')<br/><font size="-3">' . functions::parseDate($sql['users_lasttime']) . '</font></td></tr></table><img src="theme/' . $user_theme . '/images/op.gif" alt="go"/>&nbsp;';
    }
    if ($sql['guest_path'] == '/online.php' || $sql['users_path'] == '/online.php') {
        echo'Здесь, смотрит список';
    } else {
        echo'<a href="' . ($mode == 1 ? $sql['guest_path'] : $sql['users_path']) . '">' . ($mode == 1 ? $sql['guest_head'] : $sql['users_label']) . '»»</a>';
    }
    if ($user_rights > 7) {
        echo'<hr><font color="red"><i>UserAgent:</i> </font>' . ($mode == 1 ? $sql['guest_agent'] : $sql['users_ua']) . '<br/><font color="red"><i>IP:</i> </font>' . ($mode == 1 ? $sql['guest_ip'] : $sql['users_ip']);
    }
    echo'</div>';
}

//постраничка
functions::pageNav($total_num, $on_page, 5, ('?mode=' . $_GET['mode']));
echo'</div>';

require('engine/end.php');
?>
