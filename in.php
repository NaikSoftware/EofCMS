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
$namepage = 'Вход';

require('engine/engine.php');
require('engine/head.php');

if ($login) {
    functions::err('Сначала выйдите с этой учетной записи!');
}

if (isset($_GET['err'])) {
    switch (intval($_GET['err'])) {
        case 1:
            $err = 'Все поля обязательны для заполнения!';
            break;
        case 2:
            $err = 'Неправильный код с картинки';
            break;
        case 3:
            $err = 'Неправильное имя или пароль';
            break;
        default:
            $err = 'Неизвестная ошибка';
            break;
    }
    functions::err($err . ' code:' . $_SESSION['captcha_keystring'], '../in.php');
}

//Если непустой запрос

if (!empty($_POST)) {

//Если непустые поля ввода

    if ($_POST['name'] != '' && $_POST['pass'] != '' && $_POST['keystring'] != '') {
        if ($_POST['keystring'] != $_SESSION['captcha_keystring']) {
            unset($_SESSION['captcha_keystring']);
            header("Location: ?err=2");
            exit();
        }
        if (!preg_match("/^[0-9a-zA-Z]{4,15}$/", $_POST['pass']) || !preg_match("/^[-#*=!)(\[\]@_a-zA-Z0-9а-яА-Я]{3,15}$/", $_POST['name']) || $db->numInTable('eof_users', "WHERE `users_name`='" . $_POST['name'] . "' AND `users_password`='" . $_POST['pass'] . "'") == 0) {
            unset($_SESSION['captcha_keystring']);
            header("Location: ?err=3");
            exit();
        }

//Если все как надо, то...

        unset($_SESSION['captcha_keystring']);

        $result = $db->query("SELECT `users_id` FROM `eof_users` WHERE `users_name`='" . $_POST['name'] . "' AND `users_password`='" . $_POST['pass'] . "'") or die("Error in in.php (select user id)");
        $arr1 = $result->fetch_array(MYSQLI_NUM); //id

        $result = $db->query("SELECT `key` FROM `eof_key` WHERE `users_id`='" . $arr1[0] . "'") or die("Error in in.php");
        $arr2 = $result->fetch_array(MYSQLI_NUM); //key

        if ($arr2[0] != '') {
            $key = $arr2[0];
        } else {
            $key = md5($_POST['name'] . $_POST['pass'] . $id);
            $db->query("INSERT INTO `eof_key` SET `users_id`='" . $arr1[0] . "', `key`='$key'") or die('Error in "in.php" (запись нового ключа)');
        }

//Если с чужого компа, то ставим временные куки
        if ($_POST['no'] == 'no') {
            setcookie('key', $key, 0, '/');
        } else {
            setcookie('key', $key, mktime(0, 0, 0, 1, 1, 2025), '/');
        }

        header("Location: user/new.php");
        exit();
    } else {
        unset($_SESSION['captcha_keystring']);
        header("Location: ?err=1");
        exit();
    }
} else {

//Форма входа

    echo'<div class="maintxt"><form action="in.php" method="post">'
    . '<p>Имя (Никнейм):<br/><input type="text" name="name" maxlength="15" required></p>'
    . '<p>Пароль:<br/><input type="password" name="pass" maxlength="15" required></p>'
    . '<p><input type="checkbox" name="no" value="no">Чужой компьютер</p>'
    . '<p><img src="kcaptcha/?' . session_name() . '=' . session_id() . '"></p>'
    . '<p><input type="text" name="keystring"><br/>Введите код с картинки</p>'
    . '<p><input type="submit" value="Войти"></p>'
    . '</form></div>';
    require('engine/end.php');
}
?>
