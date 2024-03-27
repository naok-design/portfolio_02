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
//苗字
$sLastName = isset($_POST['last_name']) ? $_POST['last_name'] : "";

//名前
$sFirstName = isset($_POST['first_name']) ? $_POST['first_name'] : "";

//メールアドレス
$sLoginId = isset($_POST['login_id']) ? $_POST['login_id'] : "";

//住所
$sAddress = isset($_POST['address']) ? $_POST['address'] : "";


//**************************************************
// 検索処理
//**************************************************
//値を取得
$arrResult = selectMemberadm($sLastName, $sFirstName, $sLoginId, $sAddress);

//**************************************************
// HTMLを出力
//**************************************************
//画面へ表示
if (count($arrResult) > 0) {
    require_once('../adm_view/adm_member.html');
} else {
    require_once('../adm_view/adm_none.html');
}
