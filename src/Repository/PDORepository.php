<?php

abstract class PDORepository {

  const USERNAME = "root";
  const PASSWORD = "";
	const HOST ="localhost";
	const DB = "stampy-test";

    protected function getConnection(){
        $u=self::USERNAME;
        $p=self::PASSWORD;
        $db=self::DB;
        $host=self::HOST;
        $connection = new PDO("mysql:dbname=$db;host=$host", $u, $p);
        return $connection;
    }

}
