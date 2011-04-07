<?php
require_once("config/connect.php");
if(isset($_SESSION['is_admin'])) {
    if($_SESSION['is_admin']) {
        include "business.php";
    } else {
        include "client.php";
    }
} else {
        include "client.php";
}

?>
