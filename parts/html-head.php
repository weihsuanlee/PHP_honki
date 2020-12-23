<?php
define('WEB_ROOT', "/honki_books/");
?>
<!doctype html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($title) ? $title . ' - ' : '' ?>HONKI 本気選書</title>
    <link rel="icon" href="<?= WEB_ROOT ?>parts/images/logo.png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Abel&family=Noto+Sans+TC:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= WEB_ROOT ?>bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?= WEB_ROOT ?>fontawesome/css/all.css">
    <link rel="stylesheet" href="<?= WEB_ROOT ?>allCSS/style.css">
</head>

<body>