<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="uploads/styleForm.css">
	<title>Вход</title>
</head>
<body>
<div class="form">
	<h2>Список дел</h2>
	<form class="indexForm" action="loginFormCheckIn.php" method="post" enctype="multipart/form-data">
		<input class="submit" type="submit" name="checkIn" value="Регистрация">
	</form>
	<form class="indexForm" action="loginFormAuthorization.php" method="post" enctype="multipart/form-data">
		<input class="submit" type="submit" name="authorization" value="Авторизация">
	</form>
</div>
</body>
</html>