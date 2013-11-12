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
///////////////////////////
//*Права $user_rights:
//0-забанен
//1-гость
//2-рядовой
//8-админ
//9-создатель
///////////////////////////

$start_time = microtime(true);

///////////////////////////
//*Отображение ошибок и проверка
//*подключения скрипта
///////////////////////////

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
defined('INCMS') or die('Error in engine');

////////////////////////////
//*Подключение класса функций и БД
////////////////////////////

require('functions.php');
require('DB.php');

///////////////////////////
//*Данные для подключения к базе, подключение
///////////////////////////

$db = new DB('localhost', 'root', '', 'cms');

///////////////////////////
//*Прочие переменные для движка
///////////////////////////

$current = time();
if (!isset($sequre))
    $sequre = false;
$root_way = str_pad('./', (substr_count($_SERVER['SCRIPT_NAME'], '/') - 1) * 3 + 2, '../');
$path_to_script = $_SERVER['PHP_SELF'];
$arr = $db->query("SELECT `home`, `main_label` FROM `eof_setting`")->fetch_array(MYSQLI_NUM) or die("Error in select home url" . $db->error);
$home = $arr[0];
$name_site = $arr[1];
$start = (isset($_GET['start'])) ? abs((intval($_GET['start']))) : 0;
$add_query = '';

///////////////////////////
//*Здесь позже будет проверка сессии и кукиес на
//*авторизированность, назначение прав клиенту (если 1, то гость),
//*если права больше 1, то -
//*определение пользовательских переменных, проверка на бан (права=0),
//*иначе - дефолтные установки для гостей
///////////////////////////
//Общее для гостей и пользователей
session_start();
if (isset($_SESSION['upload']) && (time() - $_SESSION['upload']) > 60 * 5)
    unset($_SESSION['upload']);
$ip = $db->real_escape_string(htmlspecialchars(functions::getIP()));
$ua = $db->real_escape_string(htmlspecialchars(functions::getUA()));
$id = $db->real_escape_string(htmlspecialchars(session_id()));

//Если есть ключик, инициализируем пользователя
if (isset($_COOKIE['key']) && $db->numInTable("eof_key", "WHERE `key`='" . $_COOKIE['key'] . "'") != 0) {
    $login = true;

//Выбираем ИД юзера с таблицы ключей
    $result = $db->query("SELECT * FROM `eof_key` WHERE `key`='" . $_COOKIE['key'] . "'")->fetch_array(MYSQLI_NUM) or die('Error in select user_id in engine.php: ' . $db->error);
    $user_id = $result[0];

//Выбираем имя etc
    $result = $db->query("SELECT `users_name`, `users_surname`, `users_sex`, `users_rights`, `users_onpage`, `users_lasttime`, `users_theme`, `users_ip`, `users_ua`  FROM `eof_users` WHERE `users_id`='$user_id'") or die("Error in select user's data (engine.php)" . $db->error);
    $arr_user = $result->fetch_array(MYSQLI_ASSOC);

//Задаем выбранное переменным
    $user_name = $arr_user['users_name']; //имя
    $user_surname = $arr_user['users_surname']; //Имя для вывода в движке и модулях. Можно поменять создателем
    $user_sex = $arr_user['users_sex']; //пол
    $user_rights = $arr_user['users_rights']; //права
    if ($arr_user['users_theme'] != null) {
        $user_theme = $arr_user['users_theme']; //тема
    } else {
        $arr = $db->query("SELECT `default_theme` FROM `eof_setting`")->fetch_array(MYSQLI_NUM) or die("Error in select default register user theme (engine.php)" . $db->error);
        $user_theme = $arr[0]; //тема по умолчанию
    }
    if ($arr_user['users_onpage'] != null) {
        $on_page = $arr_user['users_onpage']; //на страницу
    } else {
        $arr = $db->query("SELECT `on_page` FROM `eof_setting`")->fetch_array(MYSQLI_NUM) or die("Error in select default on_page for registered user" . $db->error);
        $on_page = $arr[0]; //на страницу по умолчанию
    }

//обновляем данные о пользователе
    if ($arr_user['users_ip'] != $ip) {
        $db->query("UPDATE `eof_users` SET `users_ip`='$ip' WHERE `users_id`='$user_id'") or die("Error in refresh registered user IP" . $db->error);
    }
    if ($arr['users_ua'] != $ua) {
        $db->query("UPDATE `eof_users` SET `users_ua`='$ua' WHERE `users_id`='$user_id'") or die("Error in refresh registered user UA" . $db->error);
    }
} else {

    //////////////////////////
    //*Если гость, то...
    /////////////////////////

    $user_name = 'Гость'; //имя гостя
    $user_rights = 1; //права гостя
    $arr = $db->query("SELECT `on_page` FROM `eof_setting`")->fetch_array(MYSQLI_NUM) or die("Error in select on_page for guest" . $db->error);
    $on_page = $arr[0]; //на страницу у гостя
    $arr = $db->query("SELECT `default_theme` FROM `eof_setting`")->fetch_array(MYSQLI_NUM) or die("Error in select theme from DB for guest" . $db->error);
    $user_theme = $arr[0]; //тема оформления гостя
    $login = false;
}

///////////////////////////
//*Занесение данных клиента для статистики с учетом прав в базу,
//*определение переменной $counter_online
///////////////////////////
function online() {
    global $db, $login, $current, $id, $ip, $ua, $namepage, $path_to_script, $sequre, $name_site, $user_id, $home, $add_query;

    if ($sequre) {
        $namepage = $name_site;
        $path_to_script = $home;
    }

    $db->query("DELETE FROM `eof_online`WHERE ($current-`guest_date`)>600") or die("No deleted last guest" . $db->error);

    if (!$login) {
        $result = $db->query("SELECT * FROM `eof_online` WHERE (`guest_id`='$id' OR (`guest_agent`='$ua' AND `guest_ip`='$ip'))") or die('Error in query from "online"');
        $arr = $result->fetch_array(MYSQLI_NUM);
        if ($arr[0] != 0) {
            $db->query("UPDATE `eof_online` SET `guest_date`='$current', `guest_ip`='$ip', `guest_agent`='$ua',  `guest_head`='$namepage', `guest_path`='" . ($path_to_script . $add_query) . "', `guest_click`=(`guest_click`+1) WHERE (`guest_id`='$id' OR (`guest_agent`='$ua' AND `guest_ip`='$ip'))") or die('Error in update online table');
        } else {
            $db->query("INSERT INTO `eof_online` ( `guest_ip`, `guest_agent`, `guest_path`, `guest_head`, `guest_date`, `guest_id`, `guest_click`) VALUES ('$ip', '$ua', '" . ($path_to_script . $add_query) . "', '$namepage', '$current', '$id', 1)") or die("Error in insert guest");
        }
    } else {//если авторизирован
        $db->query("UPDATE `eof_users` SET `users_lasttime`='$current', `users_click`=(`users_click`+1), `users_label`='$namepage', `users_path`='" . ($path_to_script . $add_query) . "' WHERE `users_id`='$user_id'") or die("Error in update registered user data (engine.php)" . $db->error);
    }

    functions::$counter_online_guest = $db->numInTable('eof_online');
    functions::$counter_online_user = $db->numInTable('eof_users', "WHERE ($current-`users_lasttime`)<600");
    functions::$counter_online = functions::$counter_online_guest + functions::$counter_online_user;
}

?>
