<?php
error_reporting(0);
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



//XSS対応（ echoする場所で使用！）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//DB接続関数：db_conn() 
//※関数を作成し、内容をreturnさせる。
//※ DBname等、今回の授業に合わせる。
function db_conn($str_db_name){
    try {
        $db_name = $_ENV['DB_NAME']; //データベース名

        $db_id   = $_ENV['DB_USER']; //アカウント名
        $db_pw   = $_ENV['DB_PASS']; //パスワード：MAMPは'root'
        $db_host = $_ENV['DB_HOST']; //DBホスト
        $pdo = new PDO('mysql:dbname=' .$db_name. ';charset=utf8;host=' .$db_host, $db_id, $db_pw);
        return $pdo;
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}


//SQLエラー関数：sql_error($stmt)
function sql_error($stmt){
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
}


//リダイレクト関数: redirect($file_name)
function redirect($file_name){
    header("Location: $file_name");
    exit();
}

// SQL
function sqlTry($stmt){
    $status = $stmt->execute();
    $view = '';
    if ($status === false) {
        $error = $stmt->errorInfo();
        return false;
    } else {
        $result = $stmt->fetch();
        if($result == ""){return false;}
        return $result;
    }
}

// ログイン済ユーザー認証
function loginCheck($ret="exit",$file_name="./index.php",$ng_msg="access_denied"){
    // 1. ログインチェック処理！
    if(!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()){
        if ($ret=="return_bool"){return false;}
        elseif($ret=="exit"){exit('LOGIN ERROR');}
        elseif($ret=="redirect"){redirect($file_name."?login_status=$ng_msg");}
        else{exit('LOGIN ERROR');}
    }else{
        session_regenerate_id();
        $_SESSION['chk_ssid']=session_id();
        return true;
    }
}

