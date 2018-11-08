<?php
session_start();
$userId=$_SESSION['user_id'];
$taskId=$_POST['task_id'];
$pdo = new PDO("mysql:host=localhost; dbname=global","root","");
$taskStatus = $pdo->prepare("SELECT is_done FROM task WHERE user_id='$userId' AND id='$taskId'");
$taskStatus->execute();
$taskStatus = $taskStatus->fetchAll(PDO::FETCH_ASSOC);
if ($taskStatus[0]['is_done']=='1') {
	$changeStatus = $pdo->prepare("UPDATE task SET is_done=0 WHERE user_id='$userId' AND id='$taskId' LIMIT 1");
	$changeStatus->execute();
} elseif ($taskStatus[0]['is_done']=='0') {
	$changeStatus = $pdo->prepare("UPDATE task SET is_done=1 WHERE user_id='$userId' AND id='$taskId' LIMIT 1");
	$changeStatus->execute();
}
header("Location:toDoList.php");
?>