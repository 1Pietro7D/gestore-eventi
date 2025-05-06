<?php

class Database {
    private static $conn;

    public static function connect() {
        if (!self::$conn) {
            self::$conn = new PDO('mysql:host=localhost;dbname=gestore-eventi;charset=utf8', 'root', '');
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}
