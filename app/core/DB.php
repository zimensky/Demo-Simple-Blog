<?php
class DB
{
    private static $_db;

    public static function init()
    {
        if(!self::$_db)
        {
            try
            {
//                self::$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET.';', DB_USER, DB_PASS);
                self::$_db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
                self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            catch(PDOException $e)
            {
                die('Connection error: '.$e->getMessage());
            }
        }
        return self::$_db;
    }

}
