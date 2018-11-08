<?php
session_start();
if (empty($_POST['login']) || empty($_POST['password'])) {
    header("Location:loginFormCheckIn.php");
    exit;
} 
    $pdo = new PDO("mysql:host=localhost; dbname=global","root","");
    $login = $_POST['login'];
    $password = $_POST['password'];
    $sth = $pdo->prepare("SELECT id from user WHERE login='$login'");
    $sth->execute();
    $sth = $sth->fetchAll(PDO::FETCH_ASSOC);
 
    if (!empty($sth)) {
        echo "Такой логин уже существует";
        require("loginFormCheckIn.php");
        exit;
    }
    $sth = $pdo->prepare("INSERT INTO user (login, password) VALUES ('$login', '$password')");
    $sth->execute();
  
    $sth = $pdo->prepare("SELECT * from user WHERE login='$login' AND password='$password'");
    $sth2->execute();
    $sth2 = $sth2->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($sth2)) {
        foreach ($sth2 as $value) {
            $_SESSION['user_id']=$value['id'];
            $_SESSION['login']=$value['login'];
            $_SESSION['password']=$value['password'];
        
        }
    }
        header("Location:toDoList.php");
?>