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
$namepage = 'Генерация ника';

require('engine/engine.php');
require('engine/head.php');

if (isset($_POST['0'])) {

    $i = 0;
    $nick = "";
    $int = range(0, 9);
    $eng = range('a', 'z');
    $rus = array('й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю');
    $engg = array('a', 'e', 'i', 'o', 'u', 'y');
    $engsog = array('q', 'w', 'r', 't', 'p', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm');
    $rusg = array('а', 'о', 'у', 'э', 'ы', 'и', 'е');
    $russog = array('ц', 'к', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ф', 'в', 'п', 'р', 'л', 'д', 'ж', 'я', 'ч', 'с', 'м', 'т', 'ь', 'б', 'ю');
    $all = array_merge($int, $eng, $rus);

    while (isset($_POST[$i]) && $i < 10) {
        $temp = $_POST[$i];
        if (strlen($temp) > 7)
            functions::err("Длина одного фрагмента не должна превышать 5 символов");
        switch ($temp) {
            case "0-9":
                $nick.=$int[array_rand($int)];
                break;
            case '$eng':
                $nick.=$eng[array_rand($eng)];
                break;
            case '$rus':
                $nick.=$rus[array_rand($rus)];
                break;
            case "":
                $nick.=$all[array_rand($all)];
                break;
            case '$engg':
                $nick.=$engg[array_rand($engg)];
                break;
            case '$engsog':
                $nick.=$engsog[array_rand($engsog)];
                break;
            case '$rusg':
                $nick.=$rusg[array_rand($rusg)];
                break;
            case '$russog':
                $nick.=$russog[array_rand($russog)];
                break;
            default:
                $nick.=$temp;
                break;
        }
        $i++;
    }
    $nick = ucfirst(htmlspecialchars($nick));
    echo'<div class="maintxt"><h3>Ваш ник: <a href="http://www.google.com/m?q=' . $nick . '&channel=new">' . $nick . '</a></h3><hr>'
    . 'Чтоб сгенерировать новый ник с теми же параметрами просто обновите эту страницу<hr>'
    . '<center><a href="' . $home . '/gen_nick_name.php">Назад</a></center></div>';
} else {

    if (isset($_POST['number'])) {
        $num = $_POST['number'];

        if (!preg_match("|^[\d]*$|", $num) || $num < 2 || $num > 10) {
            functions::err("Число должно быть от 2 до 10");
        }

        echo'<div class="maintxt"><h3>Генератор ников</h3>'
        . 'Для подстановки любой буквы оставте ячейку пустой. Для ввода фрагмента со словаря введите $fragm. Также можете использовать другие спец. слова:'
        . '</br>$eng - любая англ. буква'
        . '</br>$rus - любая русская'
        . '</br>$engg - англ. гласная буква'
        . '</br>$engsog - англ. согласная'
        . '</br>$rusg - рус. гласная'
        . '</br>$russog - рус. согласная';

        echo'<form action="" method="post">';
        for ($i = 0; $i < $num; $i++) {
            echo'</br>' . ($i + 1) . ': <input type="text" name="' . $i . '">';
        }
        echo'</br></br><input type="submit" value="Генерировать"></form></div>';
    } else {

        echo'<div class="maintxt">'
        . '<p ><b>Введите желаемое количество символов (фрагментов) генерируемого ника (от 2 до 10) :</b></p>'
        . '<form action="gen_nick_name.php" method="post">'
        . '<p><input type="number" name="number" min="2" max="10" step="1" value="5" required autofocus></p>'
        . '<p><input type="submit" value="Продолжить"> </p>'
        . '</form>'
        . '</div>';
    }
}
require('engine/end.php');
?>
