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

require('../engine/engine.php');

switch ($_GET['act']) {
    case '':
        show_lib();
        break;
    case 'load':
        incHead();
        (isset($_GET['id'])) ? show_load($_GET['id']) : functions::err('Не правильный адрес');
        break;
    case 'show_create':
        incHead();
        (isset($_GET['id'])) ? show_create($_GET['id']) : functions::err('Не правильный адрес');
        break;
    case 'create':
        incHead();
        (isset($_GET['id'])) ? create($_GET['id']) : functions::err('Не правильный адрec');
}
exit;

/////////////////////////
//*Отображаем каталог или статью
/////////////////////////

function show_lib() {
    global $db, $user_rights, $start, $on_page, $root_way, $start_time, $namepage, $add_query;
//Если нет переменных в запросе (на главную страницу), то выводим все с полем parent = main
    if (empty($_GET['id'])) {
        $parent = 'main';
    } else {
        $parent = $db->real_escape_string($_GET['id']); //иначе - по parent = ID
    }

    if ($db->numInTable('eof_library', "WHERE `library_id`='$parent'") < 1 && $parent != 'main') {
        incHead();
        functions::err('Такой папки или статьи нету');
    }
    $result = $db->query("SELECT * FROM `eof_library` WHERE `library_id`='$parent'") or die('Error in select library by id');
    $arr = $result->fetch_assoc();
    if ($arr['library_file'] == null || $parent == 'main') {
        $folder = true;
    } else {
        $folder = false;
        if (file_exists('files/' . $arr['library_id']) == false) {
            incHead();
            functions::err('Путь к файлу неправильный или файла с текстом не существует');
        }
    }

    $add_query = '/id_' . $parent;
    incHead('Библиотека: «' . (($parent == 'main') ? 'Главная' : $arr['library_name']) . '»');
    echo'<div class="maintxt">';


    if ($folder) {
        $result = $db->query("SELECT * FROM `eof_library` WHERE `library_parent`='$parent' ORDER BY `library_date` DESC LIMIT $start, $on_page") or die('Error in select library main page: ' . $db->error);
        if ($result->num_rows < 1) {
            echo'В папке пусто';
        } else {
            /* если не пустая папка, то вывод */
            echo'<div class="phdr">Библиотека' . ($parent == 'main' ? '' : (': ' . $arr['library_name'])) . '</div>';
            for ($i = 0; $i < $result->num_rows; $i++) {
                $arr1 = $result->fetch_assoc();
                if ($arr1['library_file'] == 1) {
                    //file
                } else {
                    //folder
                }
            }
        }
    } else {
        /* если не папка, то выводим статью */
    }

//Меню внизу страницы, если создатель или админ - права 8-9
//!CREATE setting on page text
    if ($user_rights > 7) {
        echo'<div class="fmenu">';
        echo'<ul type="circle">';
        if ($parent != 'main') {
            echo'<li><a href="?act=rename&id=' . $parent . '">Переименовать</a></li>';
            echo'<li><a href="?act=edit&id=' . $parent . '">Редактировать</a></li>';
            echo'<li><a href="?act=delete&id=' . $parent . '">Удалить</a></li>';
        }
        if ($folder) {
            echo'<li><a href="' . $root_way . 'library/index.php?act=load&id=' . $parent . '">Загрyзить</a></li>';
            echo'<li><a href="' . $root_way . 'library/index.php?act=show_create&id=' . $parent . '">Создать папку</a></li>';
        }
        echo'</ul></div>';
    }


    echo'</div></div>';
    require('../engine/end.php');
}

/////////////////////////
//*Форма загрузки статьи.
//*Отправляет в upload.php
/////////////////////////

function show_load($in_folder) {
    global $user_rights, $root_way, $start_time, $db;
    if ($user_rights < 8) {
        functions::err("Ошибка доступа");
    }
    if ($db->numInTable('eof_library', "WHERE `library_id`='" . $db->real_escape_string($in_folder) . "' AND `library_file`='null'") != 1 && $in_folder != 'main') {
        functions::err('Неверный адрес (такой папки нету)');
    }

    echo'<div class="maintxt">'
    . '<h3>Загрузка файла</h3>'
    . '<form action=upload.php?id=' . $in_folder . ' method=post enctype=multipart/form-data>'
    . 'Не более 1 Мб, в кодировке UTF-8<br/>'
    . '<input type=file name=uploadfile><br/>'
    . 'Сюда введите слова для ссылки ЧПУ<br/><input type=text name=file_id maxlength=50 required><br/>'
    . 'Введите название статьи (книги)<br/><input type=text name=file_label maxlength=255 required></br>'
    . 'Источник или автор(ы)<br/><input type=text name=file_avtor maxlength=255 required><br/>'
    . '<input type=submit value=Загрузить></form></br>'
    . '</div>';

    $_SESSION['upload'] = time();


    require('../engine/end.php');
}

function show_create($in_parent) {
    global $user_rights, $root_way, $start_time, $db;

    if ($user_rights < 8) {
        functions::err("Ошибка доступа");
    }

    $in_parent = $db->real_escape_string($in_parent);
    if ($db->numInTable('eof_library', "WHERE `library_id`='$in_parent' AND `library_file`='null'") != 1 && $in_parent != 'main') {
        functions::err('Неверный адрес (такой папки нету)');
    }

    echo'<div class="maintxt">'
    . '<h3>Создание папки</h3>'
    . '<form action="?act=create&id=' . $in_parent . '" method="get">'
    . 'Сюда введите слова для ссылки ЧПУ<br/><input type=text name=new_id maxlength=50 required><br/>'
    . 'Введите название папки<br/><input type=text name=folder_label maxlength=255 required></br>'
    . '<input type=submit value=Создать></form></br>'
    . '</div>';

    require"../engine/end.php";
}

function incHead($title = 'Библиотека') {
    global $db, $login, $current, $id, $ip, $ua, $namepage, $path_to_script, $sequre, $name_site, $user_id, $home, $add_query, $user_surname, $user_theme;
    $namepage = $title;
    require('../engine/head.php');
}

function create($in_parent) {
    global $user_rights, $root_way, $start_time, $db;

    if ($user_rights < 8) {
        functions::err("Ошибка доступа");
    }

    $in_parent = $db->real_escape_string($in_parent);
    if ($db->numInTable('eof_library', "WHERE `library_id`='$in_parent' AND `library_file`='null'") != 1 && $in_parent != 'main') {
        functions::err('Неверный адрес (такой папки нету)');
    }


    require"../engine/end.php";
}

?>
