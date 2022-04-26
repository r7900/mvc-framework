<?php

namespace Core;

final class Db
{
    private static $db = null;
    private static $state;


    public static function connect()
    {
        if (!self::$db) {
            try {
                $hostname = DB_HOST;
                $dbname = DB_NAME;
                self::$db = new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", DB_USER, DB_PASS);
                self::$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                echo "Db connected <br>";
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            echo "Db already connected <br>";
        }
    }
    public static function disConnect()
    {
        self::$db = null;
    }


    /*
    public static function connect(){
        if(self::$db === null){
            self::$db = new DB();
        }
        return self::$db;
    }
    public function execQuery($query){
        self::$db->exec($query);
    }
    */

    public static function prepare($query)
    {
        self::$state = self::$db->prepare($query);
    }
    public static function bindParam($param, $value, $pdoparam = null)
    {
        if ($pdoparam == null)
            self::$state->bindParam($param, $value);
        else
            self::$state->bindParam($param, $value, $pdoparam);
    }
    public static function bindValue($param, $value, $pdoparam = null)
    {
        if ($pdoparam == null)
            self::$state->bindValue($param, $value);
        else
            self::$state->bindValue($param, $value, $pdoparam);
    }
    public static function executeStatement()
    {
        return self::$state->execute();
    }
    public static function fetch($mode = \PDO::FETCH_OBJ)
    {
        return self::$state->fetch($mode);
    }
    public static function fetchAll($mode = \PDO::FETCH_OBJ)
    {
        return self::$state->fetchAll($mode);
    }
}
