<?php
require __DIR__ . '/db_connect.php';
$pageName = 'inventory_management';
$title = '庫存管理';

$perPage = 30;
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


$t_sql = "SELECT COUNT(*) FROM book_product $where ";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$totalPages = ceil($totalRows / $perPage);

if ($page > $totalPages) $page = $totalPages;
if ($page < 1) $page = 1;

$rows = [];
//如果有資料
if (!$totalRows == 0) {
    $sql = sprintf("SELECT p.*, c.name FROM `book_product` p JOIN `book_categories` c ON p.category_sid = c.category_sid %s LIMIT %s, %s ", $where, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}


//分類資料(for sidebar)
$c_sql = "SELECT * FROM book_categories ";
$categories = $pdo->query($c_sql)->fetchAll();

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-3 col-sm-3 col-md-2 categories-side-bar ml-auto mr-4">
            <!-- 分類選單 -->
            <a href="<?= WEB_ROOT ?>add_product.php">
                <div class="add-items">
                    <i class="fas fa-plus-circle"></i>
                    <p>上架新書籍</p>
                </div>
            </a>
            <div class="btn-group-vertical w-100 mr-auto mb-5">
                <a type="button" href="?" class="btn btn-dark">所有商品</a>
                <?php if (!empty($params['search'])) { ?>
                    <?php foreach ($categories as $c) : ?>
                        <a type="button" href="?<?php $params['category_sid'] = $c['category_sid'];
                                                echo http_build_query($params); ?>" class="btn sidebar-btn <?= $category_sid == $c['category_sid'] ? 'sidebar-btn-active' : '' ?>">
                            <?= $c['name'] ?>
                        </a>
                    <?php endforeach;
                    unset($params['category_sid']); ?>
                    <?php } else {
                    foreach ($categories as $c) : ?>
                        <a type="button" href="?category_sid=<?= $c['category_sid'] ?>" class="btn sidebar-btn <?= $category_sid == $c['category_sid'] ? 'sidebar-btn-active' : '' ?>">
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



            <?php if (!$totalRows == 0) : ?>
                <div class="row">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">編號</th>
                                <th scope="col">圖片</th>
                                <th scope="col" style="width: 25%;">書名</th>
                                <th scope="col">類別</th>
                                <th scope="col" style="width: 8%;">作者</th>
                                <th scope="col" style="width: 10%;">出版社</th>
                                <th scope="col">定價</th>
                                <th scope="col">折扣</th>
                                <th scope="col">實際售價</th>
                                <th scope="col">ISBN</th>
                                <th scope="col">庫存數量</th>
                                <th scope="col">變更</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $r) : ?>
                                <tr>
                                    <td><?= $r['sid'] ?></td>
                                    <td><img style="width:4rem;" src="<?= WEB_ROOT ?>uploads/<?= $r['book_pics'] ?>" alt=""></td>
                                    <td><?= $r['title'] ?></td>
                                    <td><?= $r['name'] ?></td>
                                    <td><?= $r['author'] ?></td>
                                    <td><?= $r['publication'] ?></td>
                                    <td><?= $r['price'] ?></td>
                                    <td><?= $r['discount'] ?></td>
                                    <td><?= $r['final_price'] ?></td>
                                    <td><?= $r['ISBN'] ?></td>
                                    <td><?= $r['stock_num'] ?></td>
                                    <td>
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
                                                    <div class="modal-body text-center">
                                                        <h5>是否刪除選取的商品？</h5>
                                                        <img class="my-4" style="width: 7rem;" src="<?= WEB_ROOT ?>uploads/<?= $r['book_pics'] ?>" alt="">
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
            <?php else : ?>
                <div class="text-center mt-5">
                    <h5><i class="fas fa-book mr-2"></i>無商品</h5>
                </div>
            <?php endif; ?>
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