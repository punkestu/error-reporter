<?php
include __DIR__ . '/../../utils/db.php';

session_start();

if (!isset($_SESSION['user']) || !isset($_GET['id']) || !isset($_GET['column']) || !isset($_GET['value'])) {
    header('Location: /');
    return;
}

$db = db_connect();
$res = $db->query('SELECT * FROM errors WHERE id = ' . $_GET['id']);
$error = $res->fetch_assoc();

if ($_SESSION['user']['id'] !== $error['reported_by']) {
    header('Location: /');
    return;
}

$db->query('UPDATE errors SET ' . $_GET["column"] . '="' . $_GET["value"] . '" WHERE id = ' . $_GET['id']);
header('Location: /');
