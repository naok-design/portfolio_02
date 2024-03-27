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

//エラーメッセージ用
$arrErr = array();

//**************************************************
// 変数取得
//**************************************************
//苗字
$sLastName = isset($_POST['last_name']) ? $_POST['last_name'] : "";

//名前
$sFirstName = isset($_POST['first_name']) ? $_POST['first_name'] : "";

//郵便番号
$sPostal = isset($_POST['postal']) ? $_POST['postal'] : "";

//住所
$sAddress = isset($_POST['address']) ? $_POST['address'] : "";

//電話番号
$sTel = isset($_POST['tel']) ? $_POST['tel'] : "";

//メールアドレス
$sLoginId = isset($_POST['login_id']) ? $_POST['login_id'] : "";

//パスワード
$sLoginPass = isset($_POST['login_pass']) ? $_POST['login_pass'] : "";

//処理ステップ
$nStepFlg = isset($_POST['step']) ? $_POST['step'] : "";


//**************************************************
// STEP1（確認画面）
//**************************************************
if ($nStepFlg == 1 || $nStepFlg == 2) {

    // 苗字チェック
    if ($sLastName == "") {
        $arrErr['last_name'] = "苗字を入力してください";
    } else if (mb_strlen($sLastName, "UTF-8") > 20) {
        $arrErr['last_name'] = "苗字は20文字以内で入力してください";
    }

    // 名前チェック
    if ($sFirstName == "") {
        $arrErr['first_name'] = "名前を入力してください";
    } else if (mb_strlen($sFirstName, "UTF-8") > 20) {
        $arrErr['first_name'] = "名前は20文字以内で入力してください";
    }

    // 郵便番号チェック
    if ($sPostal == "") {
        $arrErr['postal'] = "郵便番号を入力してください";
    } else if (mb_strlen($sPostal, "UTF-8") != 7) {
        $arrErr['postal'] = "郵便番号を7桁で入力してください";
    }

    // 住所チェック
    if ($sAddress == "") {
        $arrErr['address'] = "住所を入力してください";
    }

    // 電話番号チェック
    if ($sTel == "") {
        $arrErr['tel'] = "電話番号を入力してください";
    } else if (mb_strlen($sTel, "UTF-8") != 11) {
        $arrErr['tel'] = "電話番号を11桁で入力してください";
    }

    // メールアドレスチェック
    if ($sLoginId == "") {
        $arrErr['login_id'] = "メールアドレスを入力してください";
    }

    // パスワードチェック
    if ($sLoginPass == "") {
        $arrErr['login_pass'] = "パスワードを入力してください";
    }

    //入力エラーがある場合は最初のステップに戻す
    if (count($arrErr) > 0) {
        $nStepFlg = "";
    }
}

//**************************************************
// STEP2（完了画面）
//**************************************************
if ($nStepFlg == 2 && count($arrErr) == 0) {

    //データ登録
    $bRet = insertMember($sFirstName, $sLastName, $sPostal, $sAddress, $sTel, $sLoginId, $sLoginPass);

    //DB登録エラーがある場合は最初のステップに戻す
    if ($bRet == false) {
        $nStepFlg = "";
    }
}

//**************************************************
// HTML表示
//**************************************************
if ($nStepFlg == "") {
    require_once('../view/entry.html');
} else if ($nStepFlg == 1) {
    require_once('../view/check.html');
} else if ($nStepFlg == 2) {
    require_once('../view/end.html');
}
