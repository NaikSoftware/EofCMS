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
$sequre = true;
define('INCMS', 1);

require('../engine/engine.php');
require('../engine/head.php');

if ($user_rights < 8)
    functions::err("Нет доступа");

if (!isset($_SESSION['upload']))
    functions::err("Время ожидания выгрузки истекло", $root_way . 'library/index.php?act=load&id=' . $_GET['id']);
unset($_SESSION['upload']);

if ($_FILES['uploadfile']['error'] != 0) {
    functions::err('Ошибка загрузки файла. Код: ' . $_FILES['uploadfile']['error'], $root_way . 'library/index.php?act=load&id=' . $_GET['id']);
}

if (empty($_GET['id']) || empty($_POST['file_id']) || empty($_POST['file_label']) || empty($_POST['file_avtor'])) {
    functions::err("Не заполнены все поля ввода", $root_way . 'library/index.php?act=load&id=' . $_GET['id']);
}

if ($db->numInTable('eof_library', "WHERE `library_id`='" . $db->real_escape_string($_GET['id']) . "' AND `library_file`='null'") != 1 && $_GET['id'] != 'main') {
    functions::err('Неверный адрес (такой папки нету)');
}

if ($db->numInTable('eof_library', "WHERE `library_id`='" . $db->real_escape_string($_POST['file_id']) . "'") > 0 || $_POST['file_id'] == 'main') {
    functions::err('Файл с таким ID адресом уже есть, введите другой', $root_way . 'library/index.php?act=load&id=' . $_GET['id']);
}

if ($_FILES['uploadfile']['size'] > (1024 * 1024)) {
    functions::err('Файл больше чем 1 Мб. Выберите другой', $root_way . 'library/index.php?act=load&id=' . $_GET['id']);
}

//Если все хорошо,
//Имя для ЧПУ, имя статьи, автор или источник
$name_link = $db->real_escape_string(htmlspecialchars(functions::cut($_POST['file_id'], 50)));
$name_book = $db->real_escape_string(htmlspecialchars(functions::cut($_POST['file_label'], 255)));
$name_avtor = $db->real_escape_string(htmlspecialchars(functions::cut($_POST['file_avtor'], 255)));


//Сохраняем сам файл в папку files 

copy($_FILES['uploadfile']['tmp_name'], 'files/' . $name_link) or die('Error in load text file to library');

//Заносим в базу запись

$db->query("INSERT INTO `eof_library` SET `library_id`='$name_link', `library_file`='1', `library_parent`='" . $db->real_escape_string($_GET['id']) . "', `library_read`='0', `library_date`='" . time() . "', `library_name`='$name_book', `library_avtor`='$name_avtor', `users_id`='$user_id'") or die("Ошибка при загрузке файла в библиотеку, запись в БД не создана" . $db->error);

//Все OK

functions::err('Файл ' . $_FILES['uploadfile']['name'] . ' успешно загружен и сохранен как ' . $name_link, $root_way . 'library/id_' . $_GET['id'], true);



require('../engine/end.php');
?>
