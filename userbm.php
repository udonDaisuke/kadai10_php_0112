<?php
error_reporting(0);
    require_once("./funcs_v1.php");

    session_start();
    $user_id =  $_SESSION['nickname'];

    $user_name =  $_SESSION['nickname'];
    loginCheck("redirect","./index.php","access_denied");
    // require_once("./bm.php");
    // $bm_all = getBm();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style_prev.css">
    <title>Document</title>
</head>
<body>
    <div >
        <form action="./logout.php" method="post">
            <button class="btn" type="submit">ログアウト</button>
        </form>
        <div class="bm-submit">
        <form action="./bm.php" method="post" target="iframe-bm">
        <!-- <form action="./" method="post"> -->

        <div class="bm-area">
                <h1 class="h1-txt">ブックマーク登録<?= ": ".$user_name." でログイン中";?></h1>
                <label for="name">
                    Subject
                    <input type="text" name="name" id="name" placeholder="**subject**" required>
                </label>
                <label for="URL">
                    URL
                    <input type="text" name="URL" id="URL" placeholder="**reference url**" required>
                </label>
                <label for="summary">
                    Comment
                    <input type="text" name="summary" id="summary" placeholder="**comment**" required>
                </label>

                <button class="btn" type="submit" >登録</button>
            </div>
            <div><a href="./" class="registration">リロード</a></div>
        </div>
        </form>

        <div class="bm-all">
            <iframe id="iframe-bm" name="iframe-bm" width="700" height="500"></iframe>
        </div>
        



</body>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

<script>
    $('body').fadeIn(1000)
</script>


</html>