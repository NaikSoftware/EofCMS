<?php
/*EofCMS
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

/*
 * Writer online records for game Chars (Android)
 */
define('INCMS', 1);
$namepage='EofCms';

require('engine/engine.php');
//require('engine/head.php');

// Create table
//$db->query("DROP TABLE IF EXISTS `chars`") or die($db->error);
//$db->query("CREATE TABLE IF NOT EXISTS `chars` ( `id` int(10) NOT	NULL AUTO_INCREMENT PRIMARY KEY, `login` varchar(10) NOT NULL, `pass` varchar(10) NOT NULL, `model` varchar(100) NOT NULL, `score` int(10) NOT NULL, `time_game_length` varchar(15) NOT NULL, `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `ip` varchar(15) NOT NULL, `devid` varchar(250) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8") or die($db->error);
//echo'Таблица "chars" создана';

if (isset($_GET['l']) && isset($_GET['p']) && isset($_GET['valid'])) {
    // validate new login and pass
    $login = $db->real_escape_string(strval($_GET['l']));
    $valid = $_GET['valid'];
    $pass = $db->real_escape_string(strval($_GET['p']));
    if ($valid != md5($login . $pass . strval(strlen($login))) || strlen($login) > 10 || strlen($login) < 3 || strlen($pass) > 10 || strlen($pass) < 3) {
        exit();
    }
    if ($db->numInTable('chars', "WHERE `login`='" . $login . "' AND `pass`='" . $pass . "'") > 0) {
        header("Content-Length: 3");
        echo(379);// all right
        exit();
    }
    if (checkName($login)) {
        header("Content-Length: 1");
        echo'0';
        exit();
    } else {
       $db->query("INSERT INTO `chars` SET `score`='0', `time_game_length`='0', `model`='Register', `devid`='Register', `login`='" . $login . "', `ip`='" . $ip . "', `pass`='" . $pass . "'") or die($db->error);
       header("Content-Length: 3");
       echo(379);// all right
       exit();
    }
}

if (isset($_POST['valid']) && isset($_POST['l']) && isset($_POST['p']) && isset($_POST['score']) && isset($_POST['time']) && isset($_POST['model']) && isset($_POST['devid']) && strpos($ua, "java")) {
    // have all fields
    $score = abs(intval($db->real_escape_string($_POST['score'])));
    $time_game_length = abs(intval($db->real_escape_string($_POST['time']))) / 10;
    $model = trim(strval($db->real_escape_string($_POST['model'])));   // 100 chars
    $devid = trim(strval($db->real_escape_string($_POST['devid'])));   // 250 chars
    $login = trim(strval($db->real_escape_string($_POST['l'])));     // 10
    $pass = trim(strval($db->real_escape_string($_POST['p'])));     // 10
    $valid = $_POST['valid'];
    
    //file_put_contents("dat.txt", $score . " " . $time_game_length . " " . $model . " " . $devid . " " . $name . " " . $hash);
    
    if ($score < 1 || $time_game_length < 1) {
        echo'1';
        exit();
    }
    if (strlen($model) > 100 || strlen($devid) > 250 || strlen($login) > 10 || strlen($login) < 3 || strlen($pass) > 10 || strlen($pass) < 3) {
        echo'2';
        exit();
    }
    if ($valid != md5($login . $pass . strval(strlen($login)))) {
       echo'4';
       exit();
    }


    // write data
    $db->query("UPDATE `chars` SET `score`='$score', `time_game_length`='$time_game_length', `model`='$model', `ip`='$ip', `devid`='$devid' WHERE `login`='$login' AND `pass`='$pass'") or die($db->error);

    
} else {
    //file_put_contents("dat.txt", "Invalid request");
    functions::err("Invalid request. Blocking you ip: $ip", false);
}

function checkName($login) {
    global $db;
    if ($db->numInTable('chars', "WHERE `login`='" . $login . "'") > 0) {
         return true;
    } else {
        return false;
    }
}

//require('engine/end.php'
