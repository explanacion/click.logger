<?php

/* 
 * Скрипт, аккумулирующий данные о кликах и вносящий их в БД
 */

require_once 'conf/db.php';
require_once 'conf/classes.php';

$clicksData = $_POST["info"];

function writeClicksToDB($ip,$pointName,$timestamp)
{
    $statement = "INSERT INTO " . 
            DB\settings::$tableName . "(ip,name,timestamp) VALUES('" . 
            $ip . "','" . 
            $pointName . "', datetime(" . 
            $timestamp . "/1000,'unixepoch','localtime'))";
    DB\db::getInstance()->exec($statement);
}

for ($i = 0; $i < count($clicksData); $i++) {
    $record = new Classes\ClickRecord();
    $record->name = $clicksData[$i]["name"];
    $record->timestamp = $clicksData[$i]["time"];
    $record->ip = $_SERVER["REMOTE_ADDR"];
    
    if ($record->isValid()) {
        writeClicksToDB($record->ip,$record->name,$record->timestamp); 
    }
    
}


