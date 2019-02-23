<?php
 
// В индексном файле представлен интерфейс для логгера кликов, в котором все
// точки собраны в едином месте
// 
// В тестовом задании сказано про файлы test1.php, test2.php, test3.php, но
// подход, при котором для создания новой точки нужно создавать новый файл 
// вручную, мне кажется не очень правильным. Вместо этого я предлагаю хранить
// информацию о точках в БД, а возможность добавлять их или удалять сделать в 
// административном интерфейсе. В случае же файлов можно бы было создавать их 
// в отдельной папке, скажем inc, затем подключать в нужное место с помощью
// include_once.
require_once 'conf/db.php';
$db = DB\db::getInstance();
//DB\db::createTestDB();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Логгер кликов</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        clicksArray = new Array();
        // обработчик на клик по кнопке с именем точки
        $(document).on("click","#pointButton", function (e) {
            var pointName = $(e.target).val();
            clicksArray.push({ name: pointName, time: Date.now() })
        });
        
        // отправка данных раз в 10 секунд
        setInterval(function() {
           if (clicksArray.length > 0) {
                console.log(clicksArray);
                $.ajax({
                    type: "POST",
                    url: "aggregator.php",
                    data: {info: clicksArray},
                });
                clicksArray = [];
           } 
        }, 10000);
        
    </script>
</head>
<body>
    <h3>Точки:</h3>
    <?php
    $data = $db->query("SELECT DISTINCT name from points;");
    if ($data)
    {
        while ($row = $data->fetch()) {
            echo "<input type=\"button\" id=\"pointButton\" value=\"" . $row["name"] . "\">&nbsp;&nbsp;";
        }
    }
    ?>
</body>
</html>