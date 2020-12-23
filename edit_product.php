<?php
// require __DIR__ . '/is_admin.php'; 確認是admin
require __DIR__ . '/db_connect.php';

if (!isset($_GET['sid'])) {
    header('Location: product_list.php');
    exit;
}

$sid = intval($_GET['sid']);
$row = $pdo->query("SELECT * FROM `products` p JOIN `categories` c ON p.`category_sid`= c.`sid` WHERE p.sid= $sid ")
    ->fetch();
if (empty($row)) {
    header('Location: product_list.php');
    exit;
}
$c_sql = "SELECT * FROM categories ";
$categories = $pdo->query($c_sql)->fetchAll();

$pageName = 'edit_product';
$title = '修改商品';

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<div class="container-fluid">
    <form name="form1" onsubmit="checkForm(); return false;">
        <div class="row justify-content-center">
            <div class="col-lg-10 add-main-bg">
                <div class="alert alert-danger message" id="message" style="display: none">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <p id="info"></p>
                </div>
                <h5 class="add-nav">
                    修改商品
                </h5>
                <div class="row justify-content-center">
                    <input type="hidden" name="sid" value="<?= $sid ?>">
                    <div class="col-3">
                        <div class="upload-img preview-no-padding mb-3" onclick="book_pics.click()">
                            <img alt="" id="preview" class="w-100" style="background-color: white" src="./uploads/<?= $row['book_pics'] ?>">
                            <div id="upload_instruction" class="upload-instruction <?= empty($row['book_pics']) ? '' : 'd-none' ?>">
                                <i class="fas fa-images"></i>
                                <p>上傳圖片</p>
                                <small>僅支援圖片格式 (jpg/png/gif) </small>
                            </div>
                            <input type="file" id="book_pics" name="book_pics" accept="image/*" onchange="fileChange()" style="display:none">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">書名</span>
                            </div>
                            <input type="text" class="form-control" id="title" name="title" value="<?= $row['title'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">外文書名</span>
                            </div>
                            <input type="text" class="form-control" name="title_eng" value="<?= $row['title_eng'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="category_sid">類別</label>
                            </div>
                            <select name="category_sid" class="custom-select" id="category_sid">
                                <option value="<?= $row['category_sid'] ?>"><?= $row['name'] ?>(已選擇)</option>
                                <?php foreach ($categories as $c) : ?>
                                    <option value="<?= $c['sid'] ?>"><?= $c['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">作者</span>
                            </div>
                            <input type="text" class="form-control" name="author" value="<?= $row['author'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">出版社</span>
                            </div>
                            <input type="text" class="form-control" name="publication" value="<?= $row['publication'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">出版年份</span>
                            </div>
                            <input type="text" class="form-control" name="pub_year" value="<?= $row['pub_year'] ?>">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">定價 $</span>
                            </div>
                            <input type="number" class="form-control" id="price" name="price" value="<?= $row['price'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">折扣</span>
                            </div>
                            <input type="text" class="form-control" name="discount" value="<?= $row['discount'] ?>">
                            <div class="input-group-append">
                                <span class="input-group-text">折</span>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">實際售價 $</span>
                            </div>
                            <input type="number" class="form-control" name="final_price" id="final_price" value="<?= $row['final_price'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">庫存</span>
                            </div>
                            <input type="number" id="stock_num" class="form-control" name="stock_num" value="<?= $row['stock_num'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">ISBN</span>
                            </div>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $row['ISBN'] ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">語言</span>
                            </div>
                            <input type="text" class="form-control" name="language" value="<?= $row['language'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">內容簡介</span>
                            </div>
                            <textarea class="form-control" id="book_overview" rows="8" name="book_overview"><?= $row['book_overview'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-11">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">作者介紹</span>
                            </div>
                            <textarea class="form-control" id="author_intro" rows="8" name="author_intro"><?= $row['author_intro'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-11">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">書籍目錄</span>
                            </div>
                            <textarea class="form-control" id="list" rows="8" name="list"><?= $row['list'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-11">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">其他備註</span>
                            </div>
                            <textarea class="form-control" id="remark" rows="3" name="remark"><?= $row['remark'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <button id="submit" type="submit" class="btn submit-button">
                        儲存修改
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


<?php include __DIR__ . '/parts/footer.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    const upload_instruction = document.querySelector('#upload_instruction');
    const book_pics = document.querySelector('#book_pics');
    const preview = document.querySelector('#preview');
    const reader = new FileReader();

    reader.addEventListener('load', function(event) {
        preview.src = reader.result;
        preview.style.objectFit = 'contain';
    })

    function fileChange() {
        reader.readAsDataURL(book_pics.files[0]);
        console.log(book_pics.files);
        upload_instruction.style.display = book_pics.files ? 'none' : 'block';
    }
    const submit = document.querySelector('#submit');
    const message = document.querySelector('#message');
    const info = document.querySelector('#info');

    const title = document.querySelector('#title');
    const isbn = document.querySelector('#isbn');
    const price = document.querySelector('#price');
    const category_sid = document.querySelector('#category_sid');
    const stock_num = document.querySelector('#stock_num');

    const final_price = document.querySelector('#final_price');

    function checkForm() {
        let isPass = true;
        title.style.backgroundColor = 'white';
        isbn.style.backgroundColor = 'white';
        price.style.backgroundColor = 'white';
        stock_num.style.backgroundColor = 'white';
        info.innerHTML = '';
        message.style.display = 'none';

        if (title.value.length == 0) {
            isPass = false;
            title.style.backgroundColor = 'var(--yellow)';
            info.innerHTML += "請輸入書名 <br>";
            message.style.display = 'block';
        }
        if (price.value.length == 0) {
            isPass = false;
            price.style.backgroundColor = 'var(--yellow)';
            info.innerHTML += "請輸入定價 <br>";
            message.style.display = 'block';
        }
        if (stock_num.value.length == 0) {
            isPass = false;
            stock_num.style.backgroundColor = 'var(--yellow)';
            info.innerHTML += "請輸入庫存數量 <br>";
            message.style.display = 'block';
        }
        if (isbn.value.length == 0) {
            isPass = false;
            isbn.style.backgroundColor = 'var(--yellow)';
            info.innerHTML += "請輸入ISBN (或ISSN) <br>";
            message.style.display = 'block';
        }
        location.href = "#";
        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('edit_product_api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        message.classList.remove('alert-danger');
                        message.classList.add('alert-success');
                        message.innerHTML = '<i class="fas fa-check-circle mr-2"></i>修改成功';
                        submit.style.display = 'none';
                        window.setTimeout("document.location.href='product_list.php'", 2000)
                    } else {
                        message.classList.remove('alert-success');
                        message.classList.add('alert-danger');
                        message.innerHTML = obj.error || '<i class="fas fa-exclamation-triangle mr-2"></i>修改未成功';
                    }
                    message.style.display = "block";
                })
        }
    }
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>