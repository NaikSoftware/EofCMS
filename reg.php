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
$namepage = 'Регистрация на "' . $home . '"';
require('engine/head.php');

//Если авторизированы, то выходим

if ($login) {
    functions::err("Вы уже зарегистрированы, повторная регистрация запрещена!");
}

//Проверка ошибок

if (isset($_GET['err'])) {
    switch (intval($_GET['err'])) {
        case 1:
            $err = 'Все поля обязательны для заполнения!';
            break;
        case 2:
            $err = 'Неправильно введен проверочный код с картинки, будьте вниматльней.';
            break;
        case 3:
            $err = 'Вы неправильно повторили пароль.';
            break;
        case 4:
            $err = 'В никнейме допустимы буквы и цифры, символы ), (, [, ], !, –, _, *, @, #, = Длинна должна быть от 3 до 10 символов';
            break;
        case 5:
            $err = 'В пароле допустимы цифры и английские буквы. Длинна пароля 4-15 символов.';
            break;
        case 6:
            $err = 'Поле "О себе" должно быть 4-500 символов.';
            break;
        case 7:
            $err = 'Ник уже занят, выберите другой.';
            break;
        default:
            $err = 'Неизвестная ошибка';
            break;
    }
    functions::err($err, '../reg.php');
}

//Если непустые поля ввода

if (!empty($_POST)) {
    if ($_POST['name'] != '' && $_POST['sex'] != '' && $_POST['pass1'] != '' && $_POST['pass2'] != '' && $_POST['iam'] != '' && $_POST['keystring'] != '') {
        if ($_POST['keystring'] != $_SESSION['captcha_keystring']) {
            $error = 2;
        }
        if ($_POST['pass1'] != $_POST['pass2']) {
            $error = 3;
        }
        if (!preg_match("/^[-#*=!)(\[\]@_a-zA-Z0-9а-яА-Я]{3,15}$/", $_POST['name'])) {
            $error = 4;
        }
        if (!preg_match("/^[0-9a-zA-Z]{4,15}$/", $_POST['pass1'])) {
            $error = 5;
        }
        if (!preg_match("/^(.){4,500}$/", $_POST['iam'])) {
            $error = 6;
        }
        if (!preg_match("/^(1|2)$/", $_POST['sex'])) {
            unset($_SESSION['captcha_keystring']);
            exit();
        }
        if (($db->numInTable('eof_users', "WHERE `users_name`='" . $_POST['name'] . "'")) != 0) {
            $error = 7;
        }

//Проверка прошла успешно, сохраняум в базу

        if (!$error) {
            $db->query("INSERT INTO `eof_users` SET `users_name`='" . $_POST['name'] . "', `users_sex`='" . $_POST['sex'] . "', `users_password`='" . $_POST['pass1'] . "', `users_rights`='2', `users_regtime`='" . $current . "', `users_lasttime`='" . $current . "', `users_click`='1', `users_label`='$namepage', `users_path`='$path_to_script', `users_votes`='0', `users_money`='0', `users_ip`='$ip', `users_ua`='$ua'") or die("Ошибка скрипта регистрации");
            echo'<div class="maintxt">Поздравляем!<br/>Вы успешно зарегистрированы.<br/>Ваш ник: ' . $_POST['name'] . '<br/>Ваш пароль: ' . $_POST['pass1'] . '<br/><a href="in.php">ВОЙТИ<a/></div>';
            file_put_contents('./etc/new.dat', 'Новичок ' . $_POST['name']);
            require('engine/end.php');
            unset($_SESSION['captcha_keystring']);
            exit();
        } else {
            unset($_SESSION['captcha_keystring']);
            header("Location: ?err=$error");
            exit();
        }
    } else {
        unset($_SESSION['captcha_keystring']);
        header("Location: ?err=1");
        exit();
    }
} else {

//Форма регистрации

    echo'<div class="phdr">Регистрация</div>'
    . '<div class="maintxt"><form action="reg.php" method="post">'
    . '<p>Сначала прочитайте <b><a href="/info.php">Правила&#8811;</a></b></p>'
    . '<p>Никнейм<br/><input type="text" name="name" size=15 maxlength=15 required/>&nbsp;'
    . 'Пол:<select size="1" name="sex" required><option value="1">Мужской</option><option value="2">Женский</option></select></p>'
    . '<p>Пароль<br/><input type="password" name="pass1" maxlength=15 required/></p>'
    . '<p>Повторите пароль<br/><input type="password" name="pass2" maxlength=15 required/></p>'
    . '<p>О себе<br/><textarea name="iam" maxlength="500" plaseholder="Кратко о себе" required></textarea></p>'
    . '<p><img src="kcaptcha/?session_name()=session_id()"></p>'
    . '<p><input type="text" name="keystring"><br/>Введите код с картинки</p>'
    . '<p><input type="submit" value="Зарегистрироваться"/></p></form></div>';
    require('engine/end.php');
}
?>
