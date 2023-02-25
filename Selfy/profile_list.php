<?php

// SESSION開始！！
session_start();
// 関数群の読み込み
require_once('funcs.php');
// ログインチェック処理！
loginCheck();


$lid = $_SESSION['lid'];

// 関数ファイルでreturnで外に出した$pdoを使う
$pdo = db_conn();

//２．データ登録SQL作成
$stmt = $pdo->prepare('SELECT * FROM record_exchange 
LEFT JOIN register00_photo ON record_exchange.object = register00_photo.lid 
LEFT JOIN register01_on ON record_exchange.object = register01_on.lid where record_exchange.lid = :lid');
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

//３．データ表示

$view="";   

if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {  
        //GETデータ送信リンク作成
        
        $view .= '<li><img src="./img/' . $result['photo_on'] . '" width="200">' . '<br>';
        $view .= '<h3>' . $result['catch_phrase_on'] . '</h3>';
        $view .= $result['name01j'] . '  ' . $result['name02j'] . '<br>';
        $view .= $result['name01e'] . '  ' . $result['name02e'] . '<br>';
        $view .= '<a href="profile_detail.php?id=' . $result['id'] . '">開く</a>' . '  ';
        $view .= '<a href="view00_photo.php">交換する</a>' . '</li><br><br>';
    
        $r = $result['object'];
        // $check = "SELECT EXISTS(SELECT * FROM record_exchange where record_exchange.lid =$r and record_exchange.object = :lid)";
        $check = "SELECT * FROM record_exchange where record_exchange.object = :lid and record_exchange.lid ='". $r ."'";
        $check2 = "SELECT EXISTS($check)";

        echo $check;

        if ($check2 == 0){
            $view .= '<a href="view00_photo.php">交換する</a>' . '</li><br><br>';
        }else{
            $view .= '</li><br><br>';
        }
        


    }

}








// 関数ファイルでreturnで外に出した$pdoを使う
$pdo2 = db_conn();

//２．データ登録SQL作成
$stmt2 = $pdo2->prepare('SELECT * FROM record_exchange where record_exchange.lid = :lid');
$stmt2->bindValue(':lid', $lid, PDO::PARAM_STR);
$status2 = $stmt2->execute();

//３．データ表示

if ($status2 === false) {
    $error2 = $stmt2->errorInfo();
    exit('SQLError:' . print_r($error2, true));
} else {

        $result = $stmt2->rowCount();
        $count = '<h3>■交換人数 / Exchange ： ' . $result . ' 名</h3><br>';


}


?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール一覧 / Profile List</title>
</head>

<body>

    <div><h2>プロフィール一覧 / Profile List</h2></div>

    <Ul><div><?= $count ?></div></Ul>    
    <Ul><div><?= $view ?></div></Ul>


</body>

</html>