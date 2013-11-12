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
defined('INCMS') or die('Error in end');

echo'<div class="footer">На сайте - <b>' . functions::$counter_online . '</b> (<a href="../online.php?mode=guest">Гостей: ' . functions::$counter_online_guest . '</a> | <a href="../online.php?mode=user">Своих: ' . functions::$counter_online_user . '</a>)</div>'
 . '<div class="tmn">Последнее: <b>' . htmlspecialchars(file_get_contents($root_way . 'etc/new.dat')) . '</b></div>'
 . '<table rules="none" width="100%" border="0px"><tr><td><font color="white"><b>NaikSoftware©</b></font></td>'
 . '<td align="right">Time: ' . (round((microtime(true) - $start_time), 3)) . '</td></tr></table><br/>'
 . '<center>';

/* if($path_to_script=='/index.php'){
  echo'<script type="text/javascript" src="http://mobtop.ru/c/25046.js"></script><noscript><a href="http://mobtop.ru/in/25046"><img src="http://mobtop.ru/25046.gif" alt="MobTop.Ru - Рейтинг и статистика мобильных сайтов"/></a></noscript>';
  } else {
  echo'<script type="text/javascript" src="http://mobtop.ru/c/25047.js"></script><noscript><a href="http://mobtop.ru/in/25047"><img src="http://mobtop.ru/25047.gif" alt="MobTop.Ru - Рейтинг и статистика мобильных сайтов"/></a></noscript>';
  } */
echo'</center></body></html>';
?>
