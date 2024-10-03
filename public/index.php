<?php
include __DIR__ . '/../utils/methodMap.php';
include __DIR__ . '/../utils/db.php';

session_start();

methodMap("GET", function () {
    $props["cursor"] = ($_GET["cursor"] ?? 1) - 1;
    $props["limit"] = 3;
    $db = db_connect();
    $res = $db->query('SELECT e.*, u.email FROM errors e JOIN users u ON e.reported_by = u.id ORDER BY e.reported_at DESC LIMIT ' . $props["limit"] . ' OFFSET ' . $props["cursor"] * $props["limit"]);
    $props["error-list"] = $res->fetch_all(MYSQLI_ASSOC);
    if (count($props["error-list"]) === 0) {
        header('Location: /');
        return;
    }
    $res = $db->query('SELECT COUNT(*) FROM errors');
    $props["total"] = $res->fetch_row()[0];
    include __DIR__ . '/../views/index.php';
});
methodMap("POST", function () {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        return;
    }

    $head = $_POST['head'];
    $description = $_POST['description'];
    $userId = $_SESSION['user']['id'];

    $db = db_connect();
    $db->query('INSERT INTO errors (reported_by, head, description) VALUES ("'
        . $userId . '", "'
        . $head . '", "'
        . $description . '")');

    header('Location: /');
});
