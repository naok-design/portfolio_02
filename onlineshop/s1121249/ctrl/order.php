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

//Step
$nStep = isset($_POST['step']) ? $_POST['step'] : "";


//**************************************************
// ログインチェック処理
//**************************************************
//ログインチェックを取得
$loginOk = loginCheck($sLoginId, $sLoginPass);

//ログインOKならユーザIDとユーザ名を取得
if ($loginOk === true) {
    $userId   = getUserId($sLoginId, $sLoginPass);
    $userName = getUserName($sLoginId, $sLoginPass);
}
//ログインチェックがNGならログイン画面へ
else {
    header("location: login.php");
    exit();
}

//**************************************************
// STEP0 処理
//**************************************************
if ($nStep == "") {
    //カート内一覧を取得
    $arrCart = selectCart($userId);

    //合計金額を計算
    $nTotalPrice = 0;
    foreach ($arrCart as $item) {
        $nTotalPrice += $item['item_price'] * $item['item_num'];
    }

    $nZeikomiTotalPrice = 0;
    foreach ($arrCart as $item) {
        $nZeikomiTotalPrice += $item['item_price'] * $item['item_num'] * 1.1;
    }
}

//**************************************************
// STEP1 処理
//**************************************************
if ($nStep == "1") {
    //注文確定処理
    compOrder($userId);
}

//**************************************************
// HTMLを出力
//**************************************************
//画面へ表示
if ($nStep == "") {
    require_once('../view/order_conf.html');
} else {
    require_once('../view/order_end.html');
}
