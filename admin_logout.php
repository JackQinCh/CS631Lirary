<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 20:59
 */

session_start();

if(session_destroy()) {
    header("Location: admin_index.php");
}