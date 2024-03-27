<?php
//**************************************************
// 初期処理
//**************************************************
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
$sMemberId = isset($_POST['id']) ? $_POST['id'] : "";

//処理ステップ
$nStepFlg = isset($_POST['step']) ? $_POST['step'] : "";

//**************************************************
// STEP0（検索処理）
//**************************************************
if ($nStepFlg == "") {
    //メンバー情報の取得
    $arrResult = selectMemberadmm($sMemberId);

    //苗字
    $sLastName = $arrResult[0]['last_name'];

    //名前
    $sFirstName = $arrResult[0]['first_name'];

    //郵便番号
    $sPostal = $arrResult[0]['postal'];

    //住所
    $sAddress = $arrResult[0]['address'];

    //電話番号
    $sTel = $arrResult[0]['tel'];

    //メールアドレス
    $sLoginId = $arrResult[0]['login_id'];

    //パスワード
    $sLoginPass = $arrResult[0]['login_pass'];
}

//**************************************************
// STEP1（削除処理）
//**************************************************
if ($nStepFlg == "1") {
    //確認画面でOKなら削除
    $bRet = deleteMemberadm($sMemberId);

    //DB登録エラーがある場合は最初のステップに戻す
    if ($bRet == false) {
        $nStepFlg = "";
    }
}

//**************************************************
// HTMLを出力
//**************************************************
if ($nStepFlg == "") {
    require_once('../adm_view/adm_delete.html');
} else if ($nStepFlg == 1) {
    require_once('../adm_view/adm_deleteOK.html');
}
