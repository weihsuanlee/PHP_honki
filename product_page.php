<?php
// require __DIR__ . '/is_admin.php'; 確認是admin
require __DIR__ . '/db_connect.php';

if (!isset($_GET['sid'])) {
    header('Location: product_list.php');
    exit;
}
$sid = intval($_GET['sid']);
$row = $pdo->query(" SELECT * FROM `products` WHERE sid = $sid ")
    ->fetch();
if (empty($row)) {
    header('Location: product_list.php');
    exit;
}
$pageName = 'product_page';
$title = '商品內頁';
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-10 col-md-12 col-lg-6 book-main-bg">
            <i class="bookmark fas fa-bookmark"></i>
            <div class="book-title">
                <h3 class="book-name"><?= $row['title'] ?></h3>
                <h5 class="mb-4"><?= $row['title_eng'] ?></h5>
                <h6>作者／ <?= $row['author'] ?></h6>
                <h6>出版社 ／ <?= $row['publication'] ?></h6>
                <h6 class="mt-3">出版年份：<?= $row['pub_year'] ?></h6>
                <h6>語言：<?= $row['language'] ?></h6>
                <h6>ISBN：<?= $row['ISBN'] ?></h6>
            </div>
        </div>
        <div class="col-10 col-md-12 col-lg-4 product">
            <a href="edit_product.php?sid=<?= $sid ?>">
                <button class="btn book-btn page-edit-button">
                    <i class="fas fa-pen mr-2"></i>
                    修改商品 <i class="fas fa-angle-right ml-1"></i>
                </button>
            </a>
            <div class="tags">
                <div class="tag wishlist-tag">
                    <a href="#">
                        <i class="heart far fa-heart"></i>
                        <p>加入收藏</p>
                    </a>
                </div>
                <div class="tag price-tag">
                    <span class="badge badge-pill discount"><?= $row['discount'] ?>折</span>
                    <h4 class="real-price">NT $<?= $row['final_price'] ?></h4>
                </div>
            </div>
            <div class="product-picture">
                <img class="w-100" src="<?= WEB_ROOT ?>uploads/<?= $row['book_pics'] ?>" alt="">
            </div>
            <div class="buttons">
                <button class="btn book-btn add-to-cart">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    放入購物車
                </button>
                <button class="btn book-btn purchase">
                    <i class="fas fa-mouse-pointer mr-2"></i>
                    直接購買
                </button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-10 col-md-10 col-lg-10">
                <div class="row book-nav">
                    <a class="col-4 book-nav-button active" href="#overview">
                        <h5>內容簡介</h5>
                    </a>
                    <a class="col-4 book-nav-button" href="#introduction">
                        <h5>作者介紹</h5>
                    </a>
                    <a class="col-4 book-nav-button" href="#list">
                        <h5>書籍目錄</h5>
                    </a>
                </div>
                <div class="row justify-content-center book-details-bg">
                    <div class="col-11 mb-5 mt-2" id="overview">
                        <h5>內容簡介</h5>
                        <hr>
                        <p><?= nl2br($row['book_overview']) ?></p>
                    </div>
                    <div class="col-11 mb-5 mt-3" id="introduction">
                        <h5>作者介紹</h5> 
                        <hr>
                        <p><?= nl2br($row['author_intro']) ?></p>
                    </div>
                    <div class="col-11 mb-5 mt-3" id="list">
                        <h5>書籍目錄</h5>
                        <hr>
                        <p><?= nl2br($row['list']) ?></p>
                    </div>
                </div>

        </div>
    </div>
</div>
<?php include __DIR__ . '/parts/footer.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>