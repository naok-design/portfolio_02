<?php
//**************************************************
// 初期処理
//**************************************************
//SESSIONスタート
session_start();

//データベース接続関数の定義ファイルを読み込み
require_once('../model/dbconnect.php');

//データベース操作関数の定義ファイルを読み込み
require_once('../model/dbfunction.php');

//**************************************************
// 変数取得
//**************************************************
//ログインID
$sLoginId = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : "";

//ログインパスワード
$sLoginPass = isset($_SESSION['login_pass']) ? $_SESSION['login_pass'] : "";

//商品ID
$nItemId = isset($_POST['item_id']) ? $_POST['item_id'] : "";

//（検索用）商品名
$nItemName = isset($_POST['item_name']) ? $_POST['item_name'] : "";

//商品価格
$nItemPrice = isset($_POST['item_price']) ? $_POST['item_price'] : "";

//（検索用）カテゴリID
$nCateId = isset($_POST['category_id']) ? $_POST['category_id'] : "";

//販売停止
$nSalesStopFlag = isset($_POST['category_id']) ? $_POST['category_id'] : "";

//**************************************************
// 検索処理
//**************************************************
//商品一覧を取得
$arrResult = selectItemadm($nItemId, $nItemName, $nCateId);

//カテゴリを取得
$arrCategory = getCategory();

//**************************************************
// HTMLを出力
//**************************************************
//画面へ表示
if (count($arrResult) > 0) {
    require_once('../adm_view/adm_item.html');
} else {
    require_once('../adm_view/adm_noneitem.html');
}
