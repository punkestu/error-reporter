<?php
include __DIR__ . '/../utils/methodMap.php';
include __DIR__ . '/../utils/db.php';

methodMap('GET', function() {
    include __DIR__ . '/../views/register.php';
});
methodMap('POST', function() {
    $credential = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];
    $db = db_connect();
    $res = $db->query('SELECT * FROM users WHERE email = "' . $credential['email'] . '"');
    $user = $res->fetch_assoc();
    if ($user) {
        header('Location: /register.php');
    } else {
        $db->query('INSERT INTO users (email, password) VALUES ("' . $credential['email'] . '", "' . $credential['password'] . '")');
        header('Location: /login.php');
    }
});