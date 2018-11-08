<?php
session_start();
$userId=$_SESSION['user_id'];
$taskId=$_POST['task_id'];
$pdo = new PDO("mysql:host=localhost; dbname=global","root","");
$taskDelete = $pdo->prepare("DELETE FROM task WHERE user_id='$userId' AND id='$taskId' LIMIT 1"); 
$taskDelete->execute();
header("Location:toDoList.php");
?>