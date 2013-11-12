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

//Сделать редактирование engine.php для БД, проверку введенного

if (!empty($_POST)) {
    if (isset($_POST['nameuserdb']) && isset($_POST['passdb']) && isset($_POST['namedb']) && isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['url']) & isset($_POST['name_site'])) {
        if (!mysql_connect('127.0.0.1', $_POST['nameuserdb'], $_POST['passdb'])) {
            echo'Неправильные данные подключения MySQL!';
            exit();
        } else {
            mysql_select_db($_POST['namedb']) or die('Неправильное имя базы данных MySQL');
        }

        mysql_query("DROP TABLE IF EXISTS `eof_online`, `eof_users`, `eof_key`, `eof_setting`, `eof_library`") or die(mysql_error());
        echo'Таблица "eof_online", "eof_key", "eof_setting", "eof_library" и "eof_users" удаленbl<br/>';

        $sql = mysql_query("CREATE TABLE IF NOT EXISTS `eof_online` ( `guest_ip` varchar(15) NOT NULL, `guest_agent` varchar(300) NOT NULL, `guest_path` varchar(255) NOT NULL, `guest_head` varchar(255) NOT NULL, `guest_date` int(10) UNSIGNED NOT NULL, `guest_id` varchar(50) NOT NULL, `guest_click` smallint(5) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(mysql_error());
        echo'Таблица "eof_online" создана<br/>';

        $sql = mysql_query("CREATE TABLE IF NOT EXISTS `eof_users` ( `users_id` int(6) NOT	NULL AUTO_INCREMENT PRIMARY KEY, `users_name` varchar(15) NOT NULL, `users_sex` tinyint(1) NOT NULL, `users_password` varchar(15) NOT NULL, `users_rights` tinyint(2) NOT NULL, `users_status` varchar(255) DEFAULT NULL, `users_regtime` int(10) UNSIGNED NOT NULL, `users_lasttime` int(10) UNSIGNED NOT NULL, `users_click` mediumint(7) UNSIGNED NOT NULL, `users_label` varchar(255) NOT NULL, `users_path` varchar(255) NOT NULL, `users_votes` mediumint(7) NOT NULL, `users_money` int(10) NOT NULL, `users_ip` varchar(15) NOT NULL, `users_ua` varchar(250) NOT NULL, `users_theme` varchar(255) DEFAULT NULL, `users_onpage` smallint(3) DEFAULT NULL, `users_surname` varchar(15) NOT NULL, `users_avatar` varchar(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(mysql_error());
        echo'Таблица "eof_users" создана<br/>';

        $sql = mysql_query("INSERT INTO `eof_users` SET `users_name`='" . $_POST['name'] . "', `users_surname`='" . $_POST['name'] . "', `users_sex`='" . $_POST['sex'] . "', `users_password`='" . $_POST['pass'] . "', `users_rights`='9', `users_regtime`='" . time() . "', `users_lasttime`='" . time() . "', `users_click`='1', `users_label`='Installing', `users_path`='/', `users_votes`='0', `users_money`='0', `users_ip`='127.0.0.1', `users_ua`='Unknown UA'") or die("Ошибка установки Создателя");
        echo'Создатель создан<br/>';

        $sql = mysql_query("CREATE TABLE IF NOT EXISTS `eof_key` (`users_id` int(6) NOT NULL, `key` varchar(50) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(mysql_error());
        echo'Таблица "eof_key" создана<br/>';

        $sql = mysql_query("CREATE TABLE IF NOT EXISTS `eof_library` (`library_id` varchar(50) NOT NULL, `library_file` smallint(1) DEFAULT NULL, `library_parent` varchar(50) NOT NULL, `library_read` int(10) DEFAULT NULL, `library_date` int(10) UNSIGNED DEFAULT NULL, `library_name` varchar(255) NOT NULL, `library_avtor` varchar(255) DEFAULT NULL, `users_id` int(6) DEFAULT NULL, `library_moder` tinyint(1) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die('Error in install library db ' + mysql_error());
        echo'Таблица "eof_library" создана<br/>';


        mysql_query("CREATE TABLE IF NOT EXISTS `eof_setting` (`home` varchar(250) NOT NULL, `on_page` int(3) NOT NULL, `default_theme` varchar(250) NOT NULL, `main_label` varchar(250) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die(mysql_error());
        mysql_query("INSERT INTO `eof_setting` SET `home`='" . $_POST['url'] . "', `on_page`='2', `default_theme`='default', `main_label`='" . $_POST['name_site'] . "'") or die("Ошибка занесения настроек сайта в БД");
        echo'Настройки сайта занесены в БД и создана таблица "eof_setting" создана<br/>';

//последнее на сайте
        fopen('etc/new.dat', 'w');
        file_put_contents('etc/new.dat', 'Установлен сайт');
        echo'Файл в котором хранится "последнее" обнулен';
    } else {
        echo'Заполните все поля ввода!';
        exit();
    }
} else {
    echo'<form action="install.php" method="post">'
    . '<p><center><b>Установка</b></center></p>'
    . '<p>Имя пользователя MySQL<br/><input type="text" name="nameuserdb" required/>'
    . '<p>Пароль MySQL<br/><input type="text" name="passdb"/></p>'
    . '<p>Имя базы данных<br/><input type="text" name="namedb" required/></p>'
    . '<p>Логин создателя<br/><input type="text" name="name" required/></p>'
    . '<p>Пароль создателя<br/><input type="text" name="pass" required/></p>'
    . '<p>Адрес сайта<br/><input type="text" name="url" required/></p>'
    . '<p>Имя сайта<br/><input type="text" name="name_site" required/></p>'
    . '<p><input type="submit" value="Установить"/></p></form>';
}
?>
