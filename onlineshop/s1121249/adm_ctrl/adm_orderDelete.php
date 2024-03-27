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
// 変数定義
//**************************************************
//エラー検知用
$bRet = false;

//**************************************************
// 変数取得
//**************************************************
//ID
$nOrderId = isset($_POST['order_id']) ? $_POST['order_id'] : "";

//処理ステップ
$nStepFlg = isset($_POST['step']) ? $_POST['step'] : "";

//**************************************************
// STEP0（検索処理）
//**************************************************
if ($nStepFlg == "") {
    //メンバー情報の取得
    $arrResult = selectOrderadm($nOrderId);

    //オーダーID
    $nOrderId = $arrResult[0]['order_id'];

    //苗字
    $sLastName = $arrResult[0]['last_name'];

    //名前
    $sFirstName = $arrResult[0]['first_name'];

    //アイテムID
    $nItemId = $arrResult[0]['item_id'];

    //アイテムNUM
    $nItemNum = $arrResult[0]['item_num'];

    //販売額
    $nSalesPrice = $arrResult[0]['sales_price'];

    //注文日時
    $nOrderDate = $arrResult[0]['order_date'];
}

//**************************************************
// STEP1（削除処理）
//**************************************************
if ($nStepFlg == "1") {
    //確認画面でOKなら削除
    $bRet = deleteOrderadm($nOrderId);

    //DB登録エラーがある場合は最初のステップに戻す
    if ($bRet == false) {
        $nStepFlg = "";
    }
}

//**************************************************
// HTMLを出力
//**************************************************
if ($nStepFlg == "") {
    require_once('../adm_view/adm_orderDelete.html');
} else if ($nStepFlg == 1) {
    require_once('../adm_view/adm_orderDeleteOK.html');
}
