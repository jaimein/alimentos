<?php

try {
    
    $db = new PDO('mysql:host='. Config::$mvc_bd_hostname . ';dbname=' . Config::$mvc_bd_nombre . '', Config::$mvc_bd_usuario, Config::$mvc_bd_clave);
    // Realiza el enlace con la BD en utf-8
    $db->exec("set names utf8");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "<p>Error: No puede conectarse con la base de datos.</p>\n";
    echo "<p>Error: " . $e->getMessage();
}




?>