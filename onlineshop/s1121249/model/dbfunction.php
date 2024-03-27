<?php
####################################################################################
### ユーザ関連
####################################################################################
/****************************************
 * ログインチェック
 * $sLoginId　：ログインID（未指定は空白）
 * $sLoginPass：ログインパスワード（未指定は空白）
 ****************************************/
function loginCheck($sLoginId = "", $sLoginPass = "")
{

    //初期化
    $arrUser = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "   * ";
        $sSql .= "FROM ";
        $sSql .= "   webapp09 ";
        $sSql .= "WHERE ";
        $sSql .= "  login_id = :login_id AND ";
        $sSql .= "  login_pass = :login_pass ";


        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':login_id',   $sLoginId,   PDO::PARAM_STR);
        $stmh->bindValue(':login_pass', $sLoginPass, PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrUser = $stmh->fetch(PDO::FETCH_ASSOC);

        //ログイン情報の有無を判定
        if ($arrUser !== false) {
            return true;
        }
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return false;
}

/****************************************
 * ログインユーザのユーザIDを取得
 * $sLoginId　：ログインID
 * $sLoginPass：ログインパスワード
 ****************************************/
function getUserId($sLoginId = "", $sLoginPass = "")
{

    //初期化
    $arrUser = array();
    $sUserId = "";

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "   id ";
        $sSql .= "FROM ";
        $sSql .= "   webapp09 ";
        $sSql .= "WHERE ";
        $sSql .= "  login_id = :login_id AND ";
        $sSql .= "  login_pass = :login_pass ";


        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':login_id',   $sLoginId,   PDO::PARAM_STR);
        $stmh->bindValue(':login_pass', $sLoginPass, PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrUser = $stmh->fetch(PDO::FETCH_ASSOC);

        //ユーザID取得
        $sUserId = $arrUser["id"];
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $sUserId;
}

/****************************************
 * ログインユーザ名取得
 * $sLoginId　：ログインID
 * $sLoginPass：ログインパスワード
 ****************************************/
function getUserName($sLoginId = "", $sLoginPass = "")
{

    //初期化
    $arrUser = array();
    $sUserName = "";

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "   login_id, ";
        $sSql .= "  last_name,  ";
        $sSql .= "  first_name  ";
        $sSql .= "FROM ";
        $sSql .= "   webapp09 ";
        $sSql .= "WHERE ";
        $sSql .= "  login_id = :login_id AND ";
        $sSql .= "  login_pass = :login_pass ";


        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':login_id',   $sLoginId,   PDO::PARAM_STR);
        $stmh->bindValue(':login_pass', $sLoginPass, PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrUser = $stmh->fetch(PDO::FETCH_ASSOC);

        //ユーザ名取得
        $sUserName = $arrUser["last_name"] . " " . $arrUser["first_name"];
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $sUserName;
}

####################################################################################
### 商品関連
####################################################################################
/****************************************
 * 商品一覧取得
 ****************************************/
function selectItem($keyword, $categoryId)
{

    //初期化
    $arrItem = array();
    $sWhere = "";

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //データ検索のSQLを作成
        $sSql  = "";
        $sSql .= "SELECT ";
        $sSql .= "   A.item_id, ";
        $sSql .= "   A.item_name, ";
        $sSql .= "   A.item_exp, ";
        $sSql .= "   A.item_price, ";
        $sSql .= "   A.item_stock, ";
        $sSql .= "   A.category_id, ";
        $sSql .= "   B.category_name ";
        $sSql .= "FROM ";
        $sSql .= "   item A ";
        $sSql .= "LEFT JOIN ";
        $sSql .= "   category B ";
        $sSql .= "ON ";
        $sSql .= "   A.category_id = B.category_id ";

        //データ検索の条件
        if ($keyword != "") {
            //キーワード
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "A.item_name LIKE :item_name ";
        }
        if ($categoryId != "") {
            //カテゴリID
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "A.category_id = :category_id ";
        }

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //バインドの実行
        if ($keyword != "") {
            //キーワード
            $stmh->bindValue(':item_name',  "%" . $keyword . "%", PDO::PARAM_STR);
        }
        if ($categoryId != "") {
            //カテゴリID
            $stmh->bindValue(':category_id',  $categoryId, PDO::PARAM_INT);
        }

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrItem = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $arrItem;
}

/****************************************
 * 商品一覧取得
 ****************************************/
function selectItemDetail($id)
{

    //初期化
    $arrItem = array();
    $sWhere = "";

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //データ検索のSQLを作成
        $sSql  = "";
        $sSql .= "SELECT ";
        $sSql .= "   A.item_id, ";
        $sSql .= "   A.item_name, ";
        $sSql .= "   A.item_exp, ";
        $sSql .= "   A.item_price, ";
        $sSql .= "   A.item_stock, ";
        $sSql .= "   A.category_id, ";
        $sSql .= "   B.category_name ";
        $sSql .= "FROM ";
        $sSql .= "   item A ";
        $sSql .= "LEFT JOIN ";
        $sSql .= "   category B ";
        $sSql .= "ON ";
        $sSql .= "   A.category_id = B.category_id ";
        $sSql .= "WHERE ";
        $sSql .= "   A.item_id = :item_id ";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //商品ID
        $stmh->bindValue(':item_id',  $id, PDO::PARAM_INT);

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrItem = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $arrItem;
}

/****************************************
 * カテゴリ取得
 ****************************************/
function getCategory()
{

    //初期化
    $arrCategory = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "   * ";
        $sSql .= "FROM ";
        $sSql .= "   category ";


        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql);

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrCategory = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $arrCategory;
}

####################################################################################
### カート関連
####################################################################################
/****************************************
 * カート一覧取得
 * $nUserId：ユーザID
 ****************************************/
function selectCart($nUserId = "")
{

    //ユーザID未指定の場合は×
    if ($nUserId == "") {
        return false;
    }

    //初期化
    $arrCart = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //データ検索のSQLを作成
        $sSql  = "";
        $sSql .= "SELECT ";
        $sSql .= "   A.item_id, ";
        $sSql .= "   A.item_num, ";
        $sSql .= "   B.item_name, ";
        $sSql .= "   B.item_exp, ";
        $sSql .= "   B.item_price, ";
        $sSql .= "   B.item_stock ";
        $sSql .= "FROM ";
        $sSql .= "   cart A ";
        $sSql .= "LEFT JOIN ";
        $sSql .= "   item B ";
        $sSql .= "ON ";
        $sSql .= "   A.item_id = B.item_id ";
        $sSql .= "WHERE ";
        $sSql .= "  A.user_id = :user_id ";
        $sSql .= "ORDER BY ";
        $sSql .= "  A.item_id ";

        //SQLを実行～取得
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $nUserId, PDO::PARAM_INT);
        $stmh->execute();
        $arrCart = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $arrCart;
}

/****************************************
 * カート内件数を取得
 * $nUserId：ユーザID
 ****************************************/
function countCart($nUserId = "")
{

    //ユーザID未指定の場合は×
    if ($nUserId == "") {
        return 0;
    }

    //初期化
    $nCartCnt = 0;

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //データ検索のSQLを作成
        $sSql  = "";
        $sSql .= "SELECT ";
        $sSql .= "   COUNT(*) AS CNT ";
        $sSql .= "FROM ";
        $sSql .= "   cart ";
        $sSql .= "WHERE ";
        $sSql .= "  user_id = :user_id ";

        //SQLを実行～取得
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id', $nUserId, PDO::PARAM_INT);
        $stmh->execute();
        $arrCart = $stmh->fetch(PDO::FETCH_ASSOC);

        //件数を取得
        $nCartCnt = isset($arrCart['CNT']) ? $arrCart['CNT'] : 0;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $nCartCnt;
}

/****************************************
 * カートへ入れる
 * $nItemId ：商品ID
 * $nItemNum：商品数量
 * $nUserId ：ユーザID
 ****************************************/
function addCart($nItemId, $nItemNum, $nUserId)
{

    //初期化
    $result = false;

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //既にデータがあるかどうかを確認するSQL
        $sSql  = "";
        $sSql .= "SELECT ";
        $sSql .= "   * ";
        $sSql .= "FROM ";
        $sSql .= "   cart ";
        $sSql .= "WHERE ";
        $sSql .= "  item_id = :item_id AND ";
        $sSql .= "  user_id = :user_id ";

        //SQL実行～取得
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':item_id', $nItemId, PDO::PARAM_INT);
        $stmh->bindValue(':user_id', $nUserId, PDO::PARAM_INT);
        $stmh->execute();
        $arrItem = $stmh->fetch(PDO::FETCH_ASSOC);

        //登録されていない場合はINSERT
        if ($arrItem === false) {
            //INSERT文作成
            $sSql  = "";
            $sSql .= "INSERT INTO cart ";
            $sSql .= "  (user_id, item_id, item_num) ";
            $sSql .= "VALUES ";
            $sSql .= "  (:user_id, :item_id, :item_num)";
        }
        //登録されている場合はUPDATE
        else {
            //UPDATE文作成
            $sSql  = "";
            $sSql .= "UPDATE cart SET ";
            $sSql .= "  item_num = item_num + :item_num ";
            $sSql .= "WHERE";
            $sSql .= "  item_id = :item_id AND ";
            $sSql .= "  user_id = :user_id ";
        }

        //SQL実行～取得
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id',     $nUserId,          PDO::PARAM_INT);
        $stmh->bindValue(':item_id',     $nItemId,          PDO::PARAM_INT);
        $stmh->bindValue(':item_num',   $nItemNum,         PDO::PARAM_INT);
        $result = $stmh->execute(); //成功したらtrueが入る


    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }

    return $result;
}

/****************************************
 * 数量変更
 * $nItemId ：商品ID
 * $nItemNum：商品数量
 * $nUserId ：ユーザID
 ****************************************/
function changeCart($nItemId, $nItemNum, $nUserId)
{

    //初期化
    $result = false;

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //数量変更の場合
        if ($nItemNum > 0) {
            //UPDATE文作成
            $sSql  = "";
            $sSql .= "UPDATE cart SET ";
            $sSql .= "  item_num = :item_num ";
            $sSql .= "WHERE";
            $sSql .= "  item_id = :item_id AND ";
            $sSql .= "  user_id = :user_id ";

            //SQL実行～取得
            $stmh = $pdo->prepare($sSql);
            $stmh->bindValue(':user_id',  $nUserId,  PDO::PARAM_INT);
            $stmh->bindValue(':item_id',  $nItemId,  PDO::PARAM_INT);
            $stmh->bindValue(':item_num', $nItemNum, PDO::PARAM_INT);
            $result = $stmh->execute();
        }
        //数量が0の場合は削除
        else {
            //DELETE文作成
            $sSql  = "";
            $sSql .= "DELETE FROM cart ";
            $sSql .= "WHERE";
            $sSql .= "  item_id = :item_id AND ";
            $sSql .= "  user_id = :user_id ";

            //SQL実行～取得
            $stmh = $pdo->prepare($sSql);
            $stmh->bindValue(':user_id',  $nUserId,  PDO::PARAM_INT);
            $stmh->bindValue(':item_id',  $nItemId,  PDO::PARAM_INT);
            $result = $stmh->execute();
        }
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }
    return $result;
}

/****************************************
 * カート内クリア
 * $nUserId ：ユーザID
 ****************************************/
function clearCart($nUserId)
{

    //初期化
    $result = false;

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //DELETE文作成
        $sSql  = "";
        $sSql .= "DELETE FROM cart ";
        $sSql .= "WHERE";
        $sSql .= "  user_id = :user_id ";

        //SQL実行～取得
        $stmh = $pdo->prepare($sSql);
        $stmh->bindValue(':user_id',  $nUserId,  PDO::PARAM_INT);
        $result = $stmh->execute();
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }
    return $result;
}

####################################################################################
### 注文関連
####################################################################################
/****************************************
 * 注文確定
 * $nUserId ：ユーザID
 ****************************************/
function compOrder($nUserId)
{

    //初期化
    $result = false;
    $orderDate = date("Y-m-d");

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //カート内の商品情報を取得する
        $arrCart = selectCart($nUserId);

        //注文テーブルへデータを入れるSQL
        $sSql  = "";
        $sSql .= "INSERT INTO orders ";
        $sSql .= "  (user_id, item_id, item_num, sales_price, order_date) ";
        $sSql .= "VALUES ";
        $sSql .= "  (:user_id, :item_id, :item_num, :sales_price, :order_date) ";

        //SQL実行～取得
        foreach ($arrCart as $arr) {
            $stmh = $pdo->prepare($sSql);
            $stmh->bindValue(':user_id',     $nUserId,           PDO::PARAM_INT);
            $stmh->bindValue(':item_id',     $arr["item_id"],    PDO::PARAM_INT);
            $stmh->bindValue(':item_num',    $arr["item_num"],   PDO::PARAM_INT);
            $stmh->bindValue(':sales_price', $arr["item_price"], PDO::PARAM_INT);
            $stmh->bindValue(':order_date',  $orderDate,         PDO::PARAM_STR);
            $stmh->execute();
        }

        //カート内クリア
        clearCart($nUserId);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー（' . __FUNCTION__ . "）：" . $Exception->getMessage() . "<br />");
    }
}

/****************************************
 * メンバー取得
 * $sMemberId：メンバーID（未指定は空白）
 * $sLastName：苗字（未指定は空白）
 ****************************************/
function selectMember($sMemberId = "", $sLastName = "")
{

    //初期化
    $arrResult = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";
        $sWhere = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "	 * ";
        $sSql .= "FROM ";
        $sSql .= "	 webapp09 ";

        //データ検索の条件
        if ($sMemberId != "") {
            //ID
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "id = :id ";
        }
        if ($sLastName != "") {
            //苗字
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "last_name LIKE :last_name ";
        }

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //バインドの実行
        if ($sMemberId != "") {
            //ID
            $stmh->bindValue(':id',  $sMemberId, PDO::PARAM_INT);
        }
        if ($sLastName != "") {
            //苗字
            $stmh->bindValue(':last_name',  "%" . $sLastName . "%", PDO::PARAM_STR);
        }

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrResult = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");
    }

    return $arrResult;
}

/****************************************
 * メンバー登録
 * $sFirstName：名前
 * $sLastName：苗字
 ****************************************/
function insertMember($sFirstName, $sLastName, $sPostal, $sAddress, $sTel, $sLoginId, $sLoginPass)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //データ検索の条件
        $sql = "INSERT INTO webapp09 (last_name, first_name, postal, address, tel, login_id, login_pass)
				VALUES (:last_name, :first_name, :postal, :address, :tel, :login_id, :login_pass)";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':last_name',  $sLastName,  PDO::PARAM_STR);
        $stmh->bindValue(':first_name', $sFirstName, PDO::PARAM_STR);
        $stmh->bindValue(':postal',  $sPostal,  PDO::PARAM_STR);
        $stmh->bindValue(':address', $sAddress, PDO::PARAM_STR);
        $stmh->bindValue(':tel',  $sTel,  PDO::PARAM_STR);
        $stmh->bindValue(':login_id', $sLoginId, PDO::PARAM_STR);
        $stmh->bindValue(':login_pass',  $sLoginPass,  PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

/****************************************
 * メンバー更新
 * $sMemberId：メンバーID
 * $sFirstName：名前
 * $sLastName：苗字
 ****************************************/
function updateMember($sMemberId, $sFirstName, $sLastName)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //データ検索の条件
        $sql = "UPDATE webapp09 
				SET
					last_name = :last_name, 
				    first_name = :first_name
				WHERE
					id = :id
		";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':id',         $sMemberId,  PDO::PARAM_INT);
        $stmh->bindValue(':last_name',  $sLastName,  PDO::PARAM_STR);
        $stmh->bindValue(':first_name', $sFirstName, PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

/****************************************
 * メンバー削除
 * $sMemberId：メンバーID
 ****************************************/
function deleteMember($sMemberId)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //データ検索の条件
        $sql = "DELETE FROM webapp09 
				WHERE  id = :id
		";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':id', $sMemberId,  PDO::PARAM_INT);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

//ここからadmの設定
/****************************************
 * メンバー取得
 * $sMemberId：メンバーID（未指定は空白）
 * $sLastName：苗字（未指定は空白）
 ****************************************/
function selectMemberadm($sLastName = "", $sFirstName = "", $sLoginId = "", $sAddress = "")
{

    //初期化
    $arrResult = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";
        $sWhere = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "	 * ";
        $sSql .= "FROM ";
        $sSql .= "	 webapp09 ";

        //データ検索の条件
        if ($sLastName != "") {
            //苗字
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "last_name LIKE :last_name ";
        }

        if ($sFirstName != "") {
            //名前
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "first_name LIKE :first_name ";
        }

        if ($sLoginId != "") {
            //メールアドレス
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "login_id LIKE :login_id ";
        }

        if ($sAddress != "") {
            //住所
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "address LIKE :address ";
        }

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //バインドの実行
        if ($sLastName != "") {
            //苗字
            $stmh->bindValue(':last_name',  "%" . $sLastName . "%", PDO::PARAM_STR);
        }

        if ($sFirstName != "") {
            //名前
            $stmh->bindValue(':first_name', "%" . $sFirstName . "%", PDO::PARAM_STR);
        }

        if ($sLoginId != "") {
            //メールアドレス
            $stmh->bindValue(':login_id', "%" . $sLoginId . "%", PDO::PARAM_STR);
        }

        if ($sAddress != "") {
            //住所
            $stmh->bindValue(':address', "%" . $sAddress . "%", PDO::PARAM_STR);
        }

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrResult = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");
    }

    return $arrResult;
}

/****************************************
 * メンバー取得_No.2
 * $sMemberId：メンバーID（未指定は空白）
 * $sLastName：苗字（未指定は空白）
 ****************************************/
function selectMemberadmm($sMemberId = "", $sLastName = "", $sFirstName = "", $sPostal = "", $sAddress = "", $sTel = "", $sLoginId = "", $sLoginPass = "")
{

    //初期化
    $arrResult = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";
        $sWhere = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "	 * ";
        $sSql .= "FROM ";
        $sSql .= "	 webapp09 ";

        //データ検索の条件
        if ($sMemberId != "") {
            //id
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "id = :id ";
        }

        if ($sLastName != "") {
            //苗字
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "last_name LIKE :last_name ";
        }

        if ($sFirstName != "") {
            //名前
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "first_name LIKE :first_name ";
        }

        if ($sPostal != "") {
            //郵便番号
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "postal LIKE :postal ";
        }

        if ($sAddress != "") {
            //住所
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "address LIKE :address ";
        }

        if ($sTel != "") {
            //電話番号
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "tel LIKE :tel ";
        }

        if ($sLoginId != "") {
            //メールアドレス
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "login_id LIKE :login_id ";
        }

        if ($sLoginPass != "") {
            //パスワード
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "login_pass LIKE :login_pass ";
        }

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //バインドの実行
        if ($sMemberId != "") {
            //ID
            $stmh->bindValue(':id',  $sMemberId, PDO::PARAM_INT);
        }

        if ($sLastName != "") {
            //苗字
            $stmh->bindValue(':last_name',  "%" . $sLastName . "%", PDO::PARAM_STR);
        }

        if ($sFirstName != "") {
            //名前
            $stmh->bindValue(':first_name', "%" . $sFirstName . "%", PDO::PARAM_STR);
        }

        if ($sPostal != "") {
            //郵便番号
            $stmh->bindValue(':postal', "%" . $sPostal . "%", PDO::PARAM_STR);
        }

        if ($sAddress != "") {
            //住所
            $stmh->bindValue(':address', "%" . $sAddress . "%", PDO::PARAM_STR);
        }

        if ($sTel != "") {
            //電話番号
            $stmh->bindValue(':tel', "%" . $sTel . "%", PDO::PARAM_STR);
        }

        if ($sLoginId != "") {
            //メールアドレス
            $stmh->bindValue(':login_id', "%" . $sLoginId . "%", PDO::PARAM_STR);
        }

        if ($sLoginPass != "") {
            //パスワード
            $stmh->bindValue(':login_pass', "%" . $sLoginPass . "%", PDO::PARAM_STR);
        }

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrResult = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");
    }

    return $arrResult;
}

/****************************************
 * メンバー登録
 * $sFirstName：名前
 * $sLastName：苗字
 ****************************************/
function insertMemberadm($sFirstName, $sLastName, $sPostal, $sAddress, $sTel, $sLoginId, $sLoginPass)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //データ検索の条件
        $sql = "INSERT INTO webapp09 (last_name, first_name, postal, address, tel, login_id, login_pass)
				VALUES (:last_name, :first_name, :postal, :address, :tel, :login_id, :login_pass)";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':last_name',  $sLastName,  PDO::PARAM_STR);
        $stmh->bindValue(':first_name', $sFirstName, PDO::PARAM_STR);
        $stmh->bindValue(':postal', $sPostal, PDO::PARAM_STR);
        $stmh->bindValue(':address', $sAddress, PDO::PARAM_STR);
        $stmh->bindValue(':tel', $sTel, PDO::PARAM_STR);
        $stmh->bindValue(':login_id', $sLoginId, PDO::PARAM_STR);
        $stmh->bindValue(':login_pass', $sLoginPass, PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

/****************************************
 * メンバー更新
 * $sMemberId：メンバーID
 * $sFirstName：名前
 * $sLastName：苗字
 ****************************************/
function updateMemberadm($sMemberId, $sFirstName, $sLastName, $sPostal, $sAddress, $sTel, $sLoginId, $sLoginPass)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //データ検索の条件
        $sql = "UPDATE webapp09 
				SET
					last_name = :last_name, 
				    first_name = :first_name,
                    postal = :postal,
                    address = :address,
                    tel = :tel,
                    login_id = :login_id,
                    login_pass = :login_pass
				WHERE
					id = :id
		";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':id',         $sMemberId,  PDO::PARAM_INT);
        $stmh->bindValue(':last_name',  $sLastName,  PDO::PARAM_STR);
        $stmh->bindValue(':first_name', $sFirstName, PDO::PARAM_STR);
        $stmh->bindValue(':postal', $sPostal, PDO::PARAM_STR);
        $stmh->bindValue(':address', $sAddress, PDO::PARAM_STR);
        $stmh->bindValue(':tel', $sTel, PDO::PARAM_STR);
        $stmh->bindValue(':login_id', $sLoginId, PDO::PARAM_STR);
        $stmh->bindValue(':login_pass', $sLoginPass, PDO::PARAM_STR);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

/****************************************
 * メンバー削除
 * $sMemberId：メンバーID
 ****************************************/
function deleteMemberadm($sMemberId)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //データ検索の条件
        $sql = "DELETE FROM webapp09 
				WHERE  id = :id
		";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':id', $sMemberId,  PDO::PARAM_INT);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

//ここから
/****************************************
 * 注文取得
 * $sMemberId：メンバーID（未指定は空白）
 * $sLastName：苗字（未指定は空白）
 ****************************************/
function selectOrderadm($sLastName = "", $sFirstName = "")
{

    //初期化
    $arrResult = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        //変数の準備
        $sSql  = "";
        $sWhere = "";

        //データ検索のSQLを作成
        $sSql .= "SELECT ";
        $sSql .= "	orders.order_id,  ";
        $sSql .= "	webapp09.last_name,  ";
        $sSql .= "	webapp09.first_name,  ";
        $sSql .= "	orders.item_id,  ";
        $sSql .= "	orders.item_num,  ";
        $sSql .= "	orders.sales_price,  ";
        $sSql .= "	orders.order_date  ";
        $sSql .= "FROM ";
        $sSql .= "	webapp09 ";
        $sSql .= "LEFT JOIN ";
        $sSql .= "  orders ";
        $sSql .= "ON ";
        $sSql .= "  webapp09.id = orders.user_id ";

        //データ検索の条件
        if ($sLastName != "") {
            //苗字
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "webapp09.last_name LIKE :last_name ";
        }

        if ($sFirstName != "") {
            //名前
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "webapp09.first_name LIKE :first_name ";
        }

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //バインドの実行
        if ($sLastName != "") {
            //苗字
            $stmh->bindValue(':last_name',  "%" . $sLastName . "%", PDO::PARAM_STR);
        }

        if ($sFirstName != "") {
            //名前
            $stmh->bindValue(':first_name', "%" . $sFirstName . "%", PDO::PARAM_STR);
        }

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrResult = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");
    }

    return $arrResult;
}

/****************************************
 * 注文削除
 * $sMemberId：メンバーID
 ****************************************/
function deleteOrderadm($nOrderId)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //データ検索の条件
        $sql = "DELETE FROM orders 
				WHERE  order_id = :order_id
		";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':order_id', $nOrderId,  PDO::PARAM_INT);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}

/****************************************
 * 管理者側商品一覧取得
 ****************************************/
function selectItemadm($nItemId, $nItemName)
{

    //初期化
    $arrResult = array();

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {
        $sSql = "";
        $sWhere = "";

        //データ検索のSQLを作成
        $sSql  = "";
        $sSql .= "SELECT ";
        $sSql .= "   item.item_id, ";
        $sSql .= "   item.item_name, ";
        $sSql .= "   item.item_exp, ";
        $sSql .= "   item.item_price, ";
        $sSql .= "   item.item_stock, ";
        $sSql .= "   item.category_id, ";
        $sSql .= "   category.category_name ";
        $sSql .= "FROM ";
        $sSql .= "   item ";
        $sSql .= "LEFT JOIN ";
        $sSql .= "   category ";
        $sSql .= "ON ";
        $sSql .= "   item.category_id = category.category_id ";

        //データ検索の条件
        if ($nItemId != "") {
            //商品ID
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "item.item_id = :item_id ";
        }
        if ($nItemName != "") {
            //キーワード
            $sWhere .= ($sWhere == "") ? "WHERE " : "AND ";
            $sWhere .= "item.item_name LIKE :item_name ";
        }

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sSql . $sWhere);

        //バインドの実行
        if ($nItemId != "") {
            //商品ID
            $stmh->bindValue(':item_id', $nItemId, PDO::PARAM_INT);
        }
        if ($nItemName != "") {
            //キーワード
            $stmh->bindValue(':item_name',  "%" . $nItemName . "%", PDO::PARAM_STR);
        }

        //SQL文の実行
        $stmh->execute();

        //実行結果を取得
        $arrResult = $stmh->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー：' . $Exception->getMessage() . "<br />");
    }

    return $arrResult;
}

/****************************************
 * 商品削除
 ****************************************/
function deleteItemadm($nItemId)
{

    //データベース接続関数の呼び出し
    $pdo = db_connect();

    try {

        //データ検索の条件
        $sql = "DELETE FROM item 
				WHERE  item_id = :item_id
		";

        //ステートメントハンドラを作成
        $stmh = $pdo->prepare($sql);

        //バインドの実行
        $stmh->bindValue(':id', $nItemId,  PDO::PARAM_INT);

        //SQL文の実行
        $stmh->execute();

        //登録成功を返却
        return true;
    } catch (PDOException $Exception) {

        //例外が発生したらエラーを出力
        die('実行エラー :' . $Exception->getMessage() . "<br />");

        //登録失敗を返却
        return false;
    }
}
