<?php
if (!isset($pageName)) $pageName = '';
?>

<nav class="navbar navbar-light bg-green">
    <div class="nav-block nav-cart" type="button" data-toggle="collapse" data-target="#menu">
        <i class="fas fa-bars"></i>
    </div>
    <a class="navbar-brand" href="<?= WEB_ROOT ?>index_.php">
        <img src="<?= WEB_ROOT ?>parts/images/logo.png" alt="">
    </a>
    <div class="d-flex nav-icons">
        <div class="nav-item nav-user">
            <a class="nav-block nav-link" href="#">
                <i class="far fa-user"></i>
            </a>
        </div>
        <div class="nav-item nav-cart">
            <a class="nav-block nav-link" href="#">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </div>
    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mr-auto">
            <li>
                <a class="nav-page" href="<?= WEB_ROOT ?>product_list.php">商品列表</a>
            </li>
            <li>
                <a class="nav-page" href="<?= WEB_ROOT ?>add_product.php">新增商品</a>
            </li>
            <!-- <li>
                <a class="nav-page" href="<?= WEB_ROOT ?>inventory_management.php">庫存管理</a>
            </li> -->
        </ul>
    </div>


</nav>