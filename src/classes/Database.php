<?php

namespace App;

use PDO;

class Database
{
    public static function connect(): PDO
    {
        $host = getenv('DB_HOST') ?: 'db';
        $name = getenv('DB_NAME') ?: 'app';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASSWORD') ?: 'root';

        $pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}
