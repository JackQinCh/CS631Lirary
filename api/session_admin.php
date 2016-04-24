<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 4/23/16
 * Time: 20:23
 */

include('config.php');
session_start();

if(!isset($_SESSION['admin'])){
    header("location: ../admin_index.php");
}

