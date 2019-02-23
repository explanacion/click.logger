<?php

/* 
 * Скрипт, берущий из БД статистику
*/

require_once 'conf/db.php';

/* AJAX check  */
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{    
    exit;    
}
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
{
    exit;
}


$mode = filter_var($_GET["mode"],FILTER_SANITIZE_SPECIAL_CHARS);
$pointname = filter_var($_GET["pointname"],FILTER_SANITIZE_SPECIAL_CHARS);
$ipaddr = filter_var($_GET["ipaddr"],FILTER_SANITIZE_SPECIAL_CHARS);

if ($mode === "all")
{
    // подзапрос округляет временную метку до минут, внешний запрос считает клики по минутам
    $statement = "SELECT temptime,ip,name,count(*) as NUM FROM ("
            . "SELECT strftime(\"%d.%m.%Y %H:%M\",timestamp) AS temptime,ip,name "
            . "from " . DB\settings::$tableName . ") GROUP BY temptime,name,ip";
}
else if ($mode == "pointname_ip")
{
    $statement = "SELECT temptime,ip,name,count(*) as NUM FROM ("
            . "SELECT strftime(\"%d.%m.%Y %H:%M\",timestamp) AS temptime,ip,name "
            . "from " . DB\settings::$tableName . " WHERE name='" 
            . $pointname . "' AND ip='" . $ipaddr . "') "
            . "GROUP BY temptime";
}
else if ($mode === "pointname")
{
    $statement = "SELECT temptime,ip,name,count(*) as NUM FROM ("
            . "SELECT strftime(\"%d.%m.%Y %H:%M\",timestamp) AS temptime,ip,name "
            . "from " . DB\settings::$tableName . " WHERE name='" . $pointname . "') "
            . "GROUP BY temptime";
}
else if ($mode === "ip")
{
    $statement = "SELECT temptime,ip,name,count(*) as NUM FROM ("
            . "SELECT strftime(\"%d.%m.%Y %H:%M\",timestamp) AS temptime,ip,name "
            . "from " . DB\settings::$tableName . " WHERE ip='" . $ipaddr . "') "
            . "GROUP BY temptime";    
}
else 
{
    exit("mode is incorrent");
}

$data = DB\db::getInstance()->query($statement);



foreach ($data as $row)
{
    echo "<tr>";
    echo "<td>" . $row["temptime"] . "</td><td>" . $row["name"] . "</td><td>" . 
            $row["ip"] . "</td><td>" . $row["NUM"] . "</td>";
    echo "</tr>";
}
