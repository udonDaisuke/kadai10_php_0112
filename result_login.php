<?php
    error_reporting(0);
    require_once("./funcs_v1.php");
    session_start();

    $user_id = $_POST["user_id"];
    $user_pass = $_POST["password"];
    $user_pass_hash = password_hash($user_pass,PASSWORD_DEFAULT);

    // DB接続
    $pdo = db_conn("gs_bm_table_2");
    // SQL宣言
    $stmt = $pdo->prepare("
        SELECT * FROM user WHERE user_id = :user_id;
    ");


    // bind
    $stmt->bindValue(':user_id',$user_id,PDO::PARAM_STR);
    // 実行
    $member = sqlTry($stmt);
    //指定したハッシュがパスワードにマッチしているかチェック
    // boolval($member)==true  -> ユーザーが存在
    if (boolval($member)){
        if (password_verify($user_pass, $member['pass'])) {
            //DBのユーザー情報をセッションに保存
            $_SESSION['user_id'] = $member['user_id'];
            $_SESSION['user_id_index'] = $member['id'];

            $_SESSION['nickname'] = $member['nickname'];
            $_SESSION['login_status'] = true;
            $_SESSION['chk_ssid']=session_id();
            redirect("./user_main.php?user=".$member['nickname']);
            // $link = '<a href="index.php">ホーム</a>';
        } else {
            $_SESSION['login_status'] = false;
            redirect("./index.php?login_status=failure");
        }
    }else{
        $_SESSION['login_status'] = false;
        redirect("./index.php?login_status=failure");

    }
?>