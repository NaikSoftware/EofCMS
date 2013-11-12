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

class DB extends mysqli {

    public function __construct($host, $user, $password, $dbname) {
        parent::__construct($host, $user, $password, $dbname);
        if (mysqli_connect_error()) {
            die('MySQLi error: ' . mysqli_connect_errno() . mysqli_connect_error());
        }
    }

    public function numInTable($tbl_name, $where = '') {
        $result = $this->query("SELECT COUNT(*) FROM `" . $tbl_name . "`" . $where) or die("Error in count $tbl_name, $where");
        $arr = $result->fetch_array(MYSQLI_NUM);
        return $arr[0];
    }

}

?>
