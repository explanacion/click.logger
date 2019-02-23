<?php

/* 
 Административный интерфейс логгера. Здесь можно просматривать все точки,
 * ip-адреса и хиты
*/
require_once 'conf/db.php';
require_once 'conf/classes.php';
$db = DB\db::getInstance();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Логгер кликов</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        function addPoint()
        {
            var value = $("#newpoint").val();
            var option = $("<option>");
            option.val(value).text(value);
            $("#points").append(option);
            $("#setpoint").append(option);
            $.post('pointseditor.php', {action: "add", point: value}, function () {
                console.log("point added");
            });
        }
        
        function showAddPointControls()
        {
            if ($("#plus").val() === "+") 
            {
                $("#newpoint").show();
                $("#addbutton").show();
                $("#plus").val("-");
            }
            else
            {
                $("#newpoint").hide();
                $("#addbutton").hide();
                $("#plus").val("+");
            }
        }
        
        function removePoint()
        {
            var selectedValue = $("#points").val();
            if (window.confirm("Вы действительно хотите удалить " + selectedValue))
            {
                $.post('pointseditor.php', {action: "del", point: selectedValue}, function () {
                    console.log("point deleted");
                });
                $("#points option:selected").remove();
            }
        }
        
        function getClickStats()
        {
            var isPointName = $('#pointname').is(':checked');
            var isIPaddr = $('#ipaddr').is(':checked');
            var pointname = $('#setpoint option:selected').val();
            var ipaddr = $('#setip option:selected').val();
            
            var mode = "all";
            if (isPointName && isIPaddr)
                mode = "pointname_ip";
            else if (isPointName)
                mode = "pointname";
            else if (isIPaddr)
                mode = "ip";
            
            var response = '';
            $.ajax({
                type: "GET",
                url: "stats.php",
                data: {mode: mode, pointname: pointname, ipaddr: ipaddr},
                async: false,
                success: function(text)
                {
                    response = text;
                }
            });
            $('#reportTable > tbody').html(response);
        }
    </script>
</head>
<body>
    <div class="point-selector">
        Добавить/удалить точку:
        <label for="points">Выберите имя точки</label>
        <select class="form-control" id="points" name="name">
            <?php
            $data = $db->query("SELECT DISTINCT name from points;");
            while ($row = $data->fetch()) {
                echo "<option>" . $row["name"] . "</option>";
            }
            ?>
        </select>
        <input type="button" id="plus" onclick="showAddPointControls();" value="+">
        <input type="text" id="newpoint" style="display: none" value="Новая точка">
        <input type="button" id="addbutton" style="display: none" onclick="addPoint();" value="Добавить">
        <input type="button" id="removebutton" onclick="removePoint();" value="X">
        <p></p>
        <form action="#">
            <p>Выборка по критериям:</p>
            <p>
                <input id="pointname" type="checkbox">Имя точки&nbsp;
                <select id="setpoint">
                <?php
                $data = $db->query("SELECT DISTINCT name from points;");
                while ($row = $data->fetch()) {
                    echo "<option>" . $row["name"] . "</option>";
                }
                ?>
                </select>
                <br>
                <input id="ipaddr" type="checkbox">IP-адрес&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select id="setip">
                <?php
                $data = $db->query("SELECT DISTINCT ip from clicks;");
                while ($row = $data->fetch()) {
                    echo "<option>" . $row["ip"] . "</option>";
                }
                ?>  
                </select>
                <br>
            </p>
            <button onclick="getClickStats()">Получить количество хитов</button>
        </form>
        <p></p>
        <table id="reportTable" border="1">
            <thead>
            <tr>
                <th scope="col">Минута</th>
                <th scope="col">Точка</th>
                <th scope="col">IP-адрес</th>
                <th scope="col">Хитов в минуту</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>    
</body>
</html>