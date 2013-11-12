<?php

defined('INCMS') or die('Error in end');
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

//класс с основными статическими функциями

class functions {

    public static $counter_online;
    public static $counter_online_guest;
    public static $counter_online_user;

    public static function err($err_message, $back_link = '', $ok = false) {
        global $start_time;
        global $root_way;
        $tf = $ok ? 'Ok!' : 'Ошибка!';
        echo'<div	class="alarm"><font color="yellow">' . $tf . '</font>' . htmlspecialchars($err_message) . (($back_link == '') ? '' : ('<a href="' . htmlspecialchars($back_link) . '"> Вернуться&#8811;</a>')) . '</div>';
        require('end.php');
        exit();
    }

    public static function cut($str, $len) {
        return (strlen($str) > $len) ? substr($str, 0, $len) : $str;
    }

    public static function getIP() {
        return (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    public static function getUA() {
        $ua = (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : $_SERVER['HTTP_USER_AGENT'];
        if (strlen($ua) > 250) {
            $ua = substr($ua, 0, 250);
        } elseif (strlen($ua) < 1) {
            $ua = 'Unknown';
        }
        return $ua;
    }

    public static function pageNav($total_num, $on_page, $diapason, $url) {
        global $start;
        $max_page = ceil($total_num / $on_page);
        $current_page = round(($start / $on_page), 0);
        if (($current_page - $diapason) > -1)
            echo'<a href="' . $url . '">1&nbsp;...&nbsp;</a>';
        for ($i = -($diapason - 1); $i < $diapason; $i++) {
            $go = $current_page + $i;
            if ($go < 0 || ($go + 1) > $max_page)
                continue;
            if ($go == $current_page) {
                echo'<b>[' . ($go + 1) . ']</b>&nbsp;&nbsp;';
                continue;
            }
            echo'<a href="' . $url . ((substr_count($url, '?') > 0) ? '&start=' : '?start=') . ($go * $on_page) . '">' . ($go + 1) . '</a>&nbsp;';
        }
        if (($current_page + $diapason) < $max_page)
            echo'<a href="' . $url . ((substr_count($url, '?') > 0) ? '&start=' : '?start=') . ($total_num - 1) . '">...&nbsp;' . $max_page . '</a>';
    }

    public static function parseDate($int) {
        //d день месяца 01-31
        //H часы от 00 до 23
        //i	минуты с ведущими нулями
        //m	порядковый номер месяца с ведущими нулями
        //s	секунды с ведущими нулями
        //Y	порядковый номер года, 4 цифры
        return date("d.m.Y / H:i", $int);
    }

}

?>
