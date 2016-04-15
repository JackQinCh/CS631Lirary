<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/14/16
 * Time: 13:45
 */
session_start();

if(session_destroy()) {
    header("Location: reader_index.php");
}