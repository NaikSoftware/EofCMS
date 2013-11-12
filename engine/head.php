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
defined('INCMS') or die('Error in head');

online();

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n" .
 "\n" . '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">' .
 "\n" . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">' .
 "\n" . '<head>' .
 "\n" . '<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>' .
 "\n" . '<meta http-equiv="Content-Style-Type" content="text/css" />' .
 "\n" . '<meta name="Generator" content="Naikcms, http://naik.wup.ru" />' .
 "\n" . '<link rel="stylesheet" href="' . $home . '/theme/' . $user_theme . '/style.css" type="text/css" />' .
 "\n" . '<link rel="shortcut icon" href="' . $home . '/favicon.ico" />' .
 "\n" . '<title>' . $namepage . '</title>' .
 "\n" . '</head><body>';
echo'<img src="' . $home . '/theme/' . $user_theme . '/images/logo.png" alt="' . $home . '" width="100%" height="40" vspace="0"/>'
 . '<div class="header">Приветствуем, <b>' . ($login ? $user_surname : 'Гость') . '</b><br/>';
if (!$login) {
    echo'<a href="' . $home . '/in.php">Вход</a> | <a href="' . $home . '/reg.php">Регистрация</a> | <a href="' . $home . '">Главная</a></div>';
} else {
    echo'<a href="' . $home . '/user/anketa.php">Кабинет</a> | <a href="' . $home . '/user/new.php">Новое</a> | <a href="' . $home . '">Главная</a> | <a href="' . $home . '/user/anketa.php?exit">Выход</a></div>';
}
?>
