<?php
// require __DIR__ . '/is_admin.php';
require __DIR__ . '/db_connect.php';



$output = [
    'success' => false,
    'code' => 0,
    'error' => '參數不足',
];

if (!isset($_POST['title']) or !isset($_POST['price']) or !isset($_POST['isbn']) or !isset($_POST['stock_num'])) {
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$upload_folder = __DIR__ . '/uploads';
$ext_map = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif',
];

$sid = intval($_POST['sid']);
$row = $pdo->query("SELECT * FROM `products` p JOIN `categories` c ON p.`category_sid`= c.`sid` WHERE p.sid= $sid ")
    ->fetch();


if (!empty($_FILES) and !empty($_FILES['book_pics']['type']) and $ext_map[$_FILES['book_pics']['type']]) {
    $output['file'] = $_FILES;
    $filename = uniqid() . $ext_map[$_FILES['book_pics']['type']];
    $output['filename'] = $filename;
    move_uploaded_file($_FILES['book_pics']['tmp_name'], $upload_folder . '/' . $filename);
}

$discount_db = strlen($_POST['discount']) == 1 ? $_POST['discount'] / 10 : $_POST['discount'] / 100;

$sql = "UPDATE `products` SET
`book_pics`=?, 
`title`=?,
`title_eng`=?,
`author`=?,
`publication`=?,
`pub_year`=?,
`ISBN`=?,
`price`=?,
`discount`=?,
`final_price`=?,
`category_sid`=?,
`language`=?,
`author_intro`=?,
`book_overview`=?,
`list`=?,
`stock_num`=?,
`remark`=?
WHERE `sid`=?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    empty($filename) ? $row['book_pics'] : $filename,
    $_POST['title'],
    $_POST['title_eng'],
    $_POST['author'],
    $_POST['publication'],
    $_POST['pub_year'],
    $_POST['isbn'],
    $_POST['price'],
    $discount_db,
    $_POST['final_price'],
    $_POST['category_sid'],
    $_POST['language'],
    $_POST['author_intro'],
    $_POST['book_overview'],
    $_POST['list'],
    $_POST['stock_num'],
    $_POST['remark'],
    $_POST['sid'],
]);

$output['rowCount'] = $stmt->rowCount();
if ($stmt->rowCount()) {
    $output['success'] = true;
    unset($output['error']);
} else {
    $output['error'] = '無資料變更';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
