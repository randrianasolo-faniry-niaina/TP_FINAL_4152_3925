<?php
ini_set("display_errors", "1");
function dbconnect()
{
    static $connect = null;

    if ($connect === null) {
        // $connect = mysqli_connect('localhost', 'ETU004152', 'jpKZdNVR', 'db_s2_ETU004152');
        $connect = mysqli_connect('localhost', 'root', '', 'employees');

        if (!$connect) {

            die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
        }


        mysqli_set_charset($connect, 'utf8mb4');
    }

    return $connect;
}
