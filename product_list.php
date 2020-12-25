<?php
require __DIR__ . '/db_connect.php';
$pageName = 'product_list';
$title = '書城';

$perPage = 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$category_sid = isset($_GET['category_sid']) ? intval($_GET['category_sid']) : 0;
$params = [];
$where = ' WHERE 1 ';
// 類別條件篩選
if (!empty($category_sid)) {
    $where .= " AND category_sid = $category_sid ";
    $params['category_sid'] = $category_sid;
}
// 搜尋資料
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $keyword = $pdo->quote('%' . $search . '%');
    $where .= sprintf(" AND (`title` LIKE %s OR `title_eng` LIKE %s OR `publication` LIKE %s OR `author` LIKE %s) ", $keyword, $keyword, $keyword, $keyword);
    $params['search'] = $search;
}


$t_sql = "SELECT COUNT(*) FROM products $where ";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$totalPages = ceil($totalRows / $perPage);

if ($page > $totalPages) $page = $totalPages;
if ($page < 1) $page = 1;

$rows = [];
//如果有資料
if (!$totalRows == 0) {
    $sql = sprintf("SELECT * FROM products %s ORDER BY `sid` DESC LIMIT %s, %s ", $where, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}


//分類資料(for sidebar)
$c_sql = "SELECT * FROM categories ";
$categories = $pdo->query($c_sql)->fetchAll();

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-3 col-sm-3 col-md-2 categories-side-bar ml-auto">
            <!-- 分類選單 -->
            <div class="btn-group-vertical w-100 mr-auto mb-5">
                <a type="button" href="?" class="btn btn-dark">所有商品</a>
                <?php if (!empty($params['search'])) { ?>
                    <?php foreach ($categories as $c) : ?>
                        <a type="button" href="?<?php $params['category_sid'] = $c['sid'];
                                                echo http_build_query($params); ?>" class="btn sidebar-btn <?= $category_sid == $c['sid'] ? 'sidebar-btn-active' : '' ?>">
                            <?= $c['name'] ?>
                        </a>
                    <?php endforeach;
                    unset($params['category_sid']); ?>
                    <?php } else {
                    foreach ($categories as $c) : ?>
                        <a type="button" href="?category_sid=<?= $c['sid'] ?>" class="btn sidebar-btn <?= $category_sid == $c['sid'] ? 'sidebar-btn-active' : '' ?>">
                            <?= $c['name'] ?>
                        </a>
                    <?php endforeach; ?>
                <?php }; ?>
            </div>
        </div>
        <div class="col-9 mr-auto">
            <div class="row justify-content-end">

                <form class="form-inline mb-3 mr-4 search-bar">
                    <input class="form-control" type="search" name="search" value="<?= htmlentities($search) ?>">
                    <button class="btn" type="submit"><i class="fas fa-search mx-2"></i></button>
                </form>
            </div>
            <a href="<?= WEB_ROOT ?>add_product.php">
                <div class="add-items">
                    <i class="fas fa-plus-circle"></i>
                    <p>上架新書籍</p>
                </div>
                <?php if ($totalRows == 0) : ?>
                    <div class="text-center mt-5">
                        <h5><i class="fas fa-book mr-2"></i>無商品</h5>
                    </div>
                <?php endif; ?>
            </a>
            <div class="row">
                <?php foreach ($rows as $r) : ?>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3" data-sid="<?= $r['sid'] ?>">
                        <div class="card" id="card<?= $r['sid'] ?>">
                            <div class="book-pic">
                                <a href="product_page.php?sid=<?= $r['sid'] ?>">
                                    <img src="<?= WEB_ROOT ?>uploads/<?= $r['book_pics'] ?>" alt="">
                                </a>
                                <div class="icons">
                                    <a href="edit_product.php?sid=<?= $r['sid'] ?>" class="edit-icon">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a class="delete-icon" data-toggle="modal" data-target="#delete-confirm<?= $r['sid']; ?>" href="javascript: delete_item(<?= $r['sid'] ?>)">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <div class="modal fade" id="delete-confirm<?= $r['sid']; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-exclamation-circle"></i> 確認刪除</h5>
                                                    <p id="text"></p>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>是否刪除選取的商品？</h5>
                                                    <img class="my-4" src="<?= WEB_ROOT ?>uploads/<?= $r['book_pics'] ?>" alt="">
                                                    <p>"<?= $r['title'] ?>"</p>
                                                    <p class="small">*商品被刪除後無法回復</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                                                    <a href="javascript: delete_item(<?= $r['sid'] ?>);" class="btn btn-primary">確認刪除</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="product_page.php?sid=<?= $r['sid'] ?>">
                                <div class="card-body">
                                    <h6 class="card-title"><?= $r['title'] ?></h6>
                                    <div class="d-flex justify-content-between">
                                        <p class="card-text author"><?= $r['author'] ?></p>
                                        <p class="card-text final_price">$ <?= $r['final_price'] ?></p>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- 分頁 -->
            <?php if (!$totalRows == 0) : ?>
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item <?= $page == 1 ? 'd-none' : '' ?>">
                                    <a class="page-link" href="?<?php $params['page'] = $page - 1;
                                                                echo http_build_query($params) ?>">
                                        <i class="fas fa-long-arrow-alt-left"></i>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++) :
                                    $params['page'] = $i;
                                ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a href="?<?php $params['page'] = $i;
                                                    echo  http_build_query($params) ?>" class="page-link"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page == $totalPages ? 'd-none' : '' ?>">
                                    <a class="page-link" href="?<?php $params['page'] = $page + 1;
                                                                echo http_build_query($params); ?>">
                                        <i class="fas fa-long-arrow-alt-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
</div>

<?php include __DIR__ . '/parts/footer.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_item(sid) {
        location.href = "delete_product.php?sid=" + sid;
    }
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>