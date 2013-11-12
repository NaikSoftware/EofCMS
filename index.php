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

require('engine/engine.php');
$namepage = $name_site;
require('engine/head.php');
echo'<div class="maintxt">';
echo '<div class="menu"><a href="test.php">Галерея</a></div>'
 . '<div class="menu"><a href="gen_nick_name.php">Генератор ников</a></div>'
 . '<div class="menu"><a href="library/">Библиотека</a></div>'
 . '<div class="news">Сайт на даный момент находится в разработке. Написана регистрация - тестируем, работаю над личным кабинетом, почтой.</div>';

echo'<div class="menu"><a href="users.php">Пользователей </a> (' . $db->numInTable('eof_users') . ') <font color="red">' . (($new_user = $db->numInTable('eof_users', "WHERE `users_regtime`>" . (time() - 86400))) ? "+$new_user" : '') . '</font></div>';
echo'</div>';

require('engine/end.php');
?>
