<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<style>
		table, tr, th, td {
			border: 1px solid #bebebe;
			border-collapse: collapse;
			padding: 5px
		}
		form {
			display: inline-block;
		}
		.f1, .f2 {
			margin: 20px 0;
		}
		.f1 {
			margin-right: 163px;
		}
	</style>
	<title>Добавить дело</title>
</head>
<body>
	<!-- Добавление нового дела -->
	<form class="f1" action="actionAddBusiness.php" method="post" enctype="multipart/form-data">
		<input class="input" type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
		<input class="input" type="hidden" name="assigned_user_id" value="<?= $_SESSION['user_id'] ?>">
		<input class="input" type="hidden" name="is_done" value="0">
		<input class="input" type="date" name="date">
		<input class="input" type="text" name="description" placeholder="Описание дела">
		<input class="submit" type="submit" name="" value="Добавить">
	</form>
	<!-- группировка по дате добавления -->
	<form class="f1" action="" method="post" enctype="multipart/form-data">
		<select name="select">
			<option <?php if ($_POST['select'] == 'сначала старые') 
				{echo "selected='selected'";}?> value="сначала старые">сначала старые</option>
			<option <?php if ($_POST['select'] == 'сначала новые') 
				{echo "selected='selected'";}?> value="сначала новые">сначала новые</option>
		</select>
		<input class="submit" type="submit" name="s" value="Сортировать">
	</form>

	<!-- Вывод общего списка дел -->
	<table>
    <tr>
        <th>Описание</th>
        <th>Дата</th>
        <th>Статус</th>
        <th>Исполнитель</th>
        <th>Действия</th>
    </tr>
<?php
	$pdo = new PDO("mysql:host=localhost; dbname=global","root","");
	$user_id=$_SESSION['user_id'];
	if (empty($_POST) || in_array('сначала старые', $_POST)) {
		$sth = $pdo->prepare("SELECT task.id, task.user_id, task.description, user.login AS assigned_user_id, task.is_done, task.date_added FROM task JOIN user ON user.id=task.assigned_user_id WHERE user_id='$user_id' ORDER BY date_added");
	} elseif (in_array('сначала новые', $_POST)) {
		$sth = $pdo->prepare("SELECT task.id, task.user_id, task.description, user.login AS assigned_user_id, task.is_done, task.date_added FROM task JOIN user ON user.id=task.assigned_user_id WHERE user_id='$user_id' ORDER BY date_added DESC");
	}
    $sth->execute();
    $sth = $sth->fetchAll(PDO::FETCH_ASSOC);
	$assignedUserList = $pdo->prepare("SELECT * FROM user"); 
	$assignedUserList->execute();
    $assignedUserList = $assignedUserList->fetchAll(PDO::FETCH_ASSOC);   
	foreach ($sth as $value) {
		?>	
		<tr>
		  <td><?= $value['description'] ?></td>
		  <td><?php echo date("d-m-Y", strtotime($value['date_added'])) ?></td>
		  <td><?= $value['assigned_user_id'] ?></td>
		  <td>
		  	<?php if ($value['is_done']=='0') {
		  		?>
		  		<span style="color:red">в процессе </span>
		  		<?php
		  	} elseif ($value['is_done']=='1'){
		  		?>
		  		<span style="color:green">завершено </span>
		  		<?php
		  	} ?>
		  	<form action="changeStatus.php" method="post" enctype="">
			  	<input type="hidden" name="task_id" value="<?=$value['id']?>">
			  	<input type="submit" name="" value="изменить статус">
			</form>	
		  </td>
		  <td>
		  	<form  action="transfer.php" method="post" enctype="">
		  		<input type="hidden" name="task_id" value="<?=$value['id']?>">
		  		<select name="assigned_user_id">
		  			<?php 
					foreach ($assignedUserList as $assignedUser) { ?>
					  <option <?php if ($task['assigned_user_id'] == $assignedUser['id']) {?>
					    selected <?php } ?> value="<?= $assignedUser['id'] ?>">
					    <?= $assignedUser['login'] ?>
					  </option>
					<?php 
					} 
					?>
		  		</select>
		  		<input type="submit" name="" value="передать">			  		
		  	</form>
		  	<form action="delete.php" method="post" enctype="">
		  		<input type="hidden" name="task_id" value="<?=$value['id']?>">
			  	<input type="submit" name="" value="удалить">
			</form>
		   </td>
		</tr>
		<?php		
	}					
	$sth = $pdo->prepare("SELECT COUNT(*) FROM task WHERE user_id='$user_id' GROUP BY is_done");
    $sth->execute();
    $sth = $sth->fetchAll(PDO::FETCH_ASSOC);
		
    $num1=$sth[1]['COUNT(*)'];
	$num0=$sth[0]['COUNT(*)'];
?>
	</table>
		<h4>Выполнено - <?php echo "$num1"?>; В процессе - <?php echo "$num0"?></h4>
</body>
</html>