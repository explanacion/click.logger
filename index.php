<?php

// Заготовка для проекта. 
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

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Логгер кликов</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
    </script>
</head>
<body>
</body>
</html>