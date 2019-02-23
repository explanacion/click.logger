<?php

/* 
 * Скрипт, добавляющий и удаляющий точки
*/

require_once 'conf/db.php';

/* AJAX check  */
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{    
    exit;    
}
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != XMLHttpRequest)
{
    exit;
}

function addPointToDB($name)
{
    $value = filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS);
    $statement = "INSERT INTO " . 
            DB\settings::$tablePointsName . "(name) VALUES('" . 
            $value . "')";
    DB\db::getInstance()->exec($statement);
}

function deletePointFromDB($name)
{
    $value = filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS);
    $statement = "DELETE FROM " . 
            DB\settings::$tablePointsName . " WHERE name='" . $value . "'";
    file_put_contents("test.txt",$statement . "\n",FILE_APPEND);
    DB\db::getInstance()->exec($statement);
}

if ($_POST["action"] === "add")
    addPointToDB($_POST["point"]);
elseif ($_POST["action"] === "del")
    deletePointFromDB($_POST["point"]);