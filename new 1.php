<html>
<head>
</head>
<body>
<?php 
  // Начинаем сессию 
  session_start(); 
  // Получаем уникальный id сессии 
  $id_session = session_id(); 
  // Устанавливаем соединение с базой данных 
  include "config.php"; 
  // Проверяем, присутствует ли такой id в базе данных 
  $query = "SELECT * FROM session 
            WHERE id_session = '$id_session'"; 
  $ses = mysql_query($query); 
  if(!$ses) exit("<p>Ошибка в запросе к таблице сессий</p>"); 
  // Если сессия с таким номером уже существует, 
  // значит пользователь online - обновляем время его 
  // последнего посещения 
  if(mysql_num_rows($ses)>0) 
  { 
    $query = "UPDATE session SET putdate = NOW(), 
                                 user = '$_SESSION[user]' 
              WHERE id_session = '$id_session'"; 
    mysql_query($query); 
  } 
  // Иначе, если такого номера нет - посетитель только что 
  // вошёл - помещаем в таблицу нового посетителя 
  else 
  { 
    $query = "INSERT INTO session 
              VALUES('$id_session', NOW(), '$_SESSION[user]')"; 
    if(!mysql_query($query)) 
    { 
      echo $query."<br>"; 
      echo "<p>Ошибка при добавлении пользователя</p>"; 
      exit(); 
    } 
  } 
  // Будем считать, что пользователи, которые отсутствовали 
  // в течении 20 минут - покинули ресурс - удаляем их 
  // id_session из базы данных 
  $query = "DELETE FROM session 
            WHERE putdate < NOW() -  INTERVAL '20' MINUTE"; 
  mysql_query($query); 
?>


</body>
</html>