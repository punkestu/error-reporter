<?php
include __DIR__ . '/../utils/methodMap.php';
include __DIR__ . '/../utils/db.php';

methodMap('GET', function() {
    include __DIR__ . '/../views/login.php';
});
methodMap('POST', function() {
    $credential = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];
    $db = db_connect();
    $res = $db->query('SELECT * FROM users WHERE email = "' . $credential['email'] . '" AND password = "' . $credential['password'] . '"');
    $user = $res->fetch_assoc();
    if ($user) {
        session_start();
        $_SESSION['user'] = $user;
        header('Location: /');
    } else {
        header('Location: /login.php');
    }
});