<?php

function userSQL($type = "login"){
    session_start();
    require("./dbconnect.php");

    //1. POSTデータ取得
    $user_id = $_POST['user_id'];
    $pass = $_POST['pass'];
    $nickname = $_POST['nickname'];

    echo "post: ".$user_id."<br>";
    echo "post: ".$pass."<br>";
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    echo "post: ".$pass_hash."<br>" ;
    //2. DB接続します
    // ***
    //３ SQL作成 ユーザー情報取得
    $stmt = $pdo->prepare("
        SELECT * FROM user 
        WHERE user_id = :user_id;
        ");
    //  2. バインド変数を用意
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    // $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
    //  3. 実行
    $status = $stmt->execute();
    $member = $stmt->fetch();
    //指定したハッシュがパスワードにマッチしているかチェック
    if ($type == "login" && $member["user_id"]!=NULL){
        if (password_verify($pass, $member['pass'])) {
            //DBのユーザー情報をセッションに保存
            echo "match";
            $_SESSION['user_id'] = $member['user_id'];
            $_SESSION['nickname'] = $member['nickname'];
            echo $_SESSION['nickname'];
            $msg = 'ログインしました。';
            // $link = '<a href="index.php">ホーム</a>';
        } else {
            echo "db:".$member["pass"]."<br>";
            $msg = 'メールアドレスもしくはパスワードが間違っています。';
            // $link = '<a href="login.php">戻る</a>';
        }

    }
    if ($type == "registration"){
        // user登録確認
        //DBのユーザー情報をセッションに保存
        $stmt = $pdo->prepare("
        SELECT * FROM user WHERE user_id = :user_id");
        //  2. バインド変数を用意
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        //  3. 実行
        $status = $stmt->execute();
        $member = $stmt->fetch();

        //登録処理
        if($member['user_id'] == $user_id){
            $member = NULL;
            $msg = "登録できませんでした。IDとか変えてトライしてね。" ;
        }else{
            $stmt = $pdo->prepare("
            INSERT INTO user(user_id,pass,nickname) 
            VALUES (:user_id,:pass,:nickname);
            ");
            //  2. バインド変数を用意
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindValue(':pass', password_hash($pass, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
            //  3. 実行
            $status = $stmt->execute();
            var_dump($status);  
            $member = $stmt->fetch(); 
            $msg = "正常に登録されました" ;
        }
    }
    $_SESSION['msg'] = $msg;
}
?>