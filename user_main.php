<?php
error_reporting(0);
session_start();
require_once("./funcs_v1.php");
loginCheck();
$user_id = $_SESSION["user_id"];
$user_id_index = $_SESSION['user_id_index'];
$nickname = $_SESSION["nickname"];

$mode = $_SESSION["filter_mode"]
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style_bm.css?new">
    <link rel="stylesheet" href="./css/tailwind_out.css">


    <title>Bookmark</title>
</head>
<body>
    <header>
        <div class="header-area">
            <!-- <div class="btn-area" id = "user-btn">
                <img src="./img/add_notes_FILL0_wght400_GRAD0_opsz24.svg" alt="" class="add-note-icon">
                <span>user name</span>
            </div> -->
            <!-- スペース -->
            <span class="spacer1">BOOK MARK APP</span>

            <!-- 記録を追加 -->
            <a href="./bm_edit.php?action=add" class="">
                <span class="btn-area" id = "add-btn">
                    <img src="./img/add_notes_FILL0_wght400_GRAD0_opsz24.svg" alt="" >
                    <span>Add record</span>
                </span>
            </a>
            <!-- パブリックBM -->
            <a target="iframe_bm_add" href="./bm_add.php?action=filter_public" class="">
                <span class="btn-area" id = "search-btn">
                    <img src="./img/travel_explore_FILL0_wght400_GRAD0_opsz24.svg" alt="">
                    <span>PublicBM</span>
                </span>
            </a>

            <!-- お気に入り -->
            <a target="iframe_bm_add" href="./bm_add.php?action=filter_fav" class="">
                <span class="btn-area" id = "favorite-btn">
                    <img src="./img/favorite.svg" alt="">
                    <span>Favorite</span>
                </span>
            </a>

            <!-- ユーザー情報 -->
            <div class="btn-area" id="userinfo-btn">
                <img src="./img/face_5_FILL0_wght400_GRAD0_opsz24.svg" alt="">
            </div>  
            <div class="user-info" id = "user-profile">
                <div class="icon-user-area" id="logout-btn">
                    <img src="./img/face_5_FILL0_wght400_GRAD0_opsz24.svg" alt="" class="logouticon">
                    <img class = "set-img" src="./img/add_a_photo_FILL0_wght400_GRAD0_opsz24.svg" alt="" class="logouticon">
                </div>
                <span class="spacer2"></span>
                <p>~ User Profile ~</p>
                <span>
                    <!-- <button id="btn_profile_edit" >Edit</button>
                    <form id ="profile_edit_form" action="" method="post" onsubmit="update_user_info()">
                        <label for="edit1" class="edit-text">
                            User id
                            <input type="text" name="user_id" id="edit1">
                        </label>
                        <label for="edit2" class="edit-text">
                            nickname
                            <input type="text" name="user_id" id="edit2">
                        </label>
                        <label for="edit3" class="edit-text" >
                            Profile
                            <input type="text" name="user_id" id="edit3">
                        </label>
                        <button type="submit" class="btn-area">Update</button>
                    </form>  -->
                </span> 

                <p>User ID : <?=h($user_id);?></p> 
                <p>Nickname <?=h($nickname);?></p> 
                <p>Profile: (dummy)こんにちは </p> 
                <div class="account-area">
                    <!-- <span class="spacer3"></span> -->
                    <!-- ログアウト -->
                    <div class="account-area-sub" id="logout-btn">
                        <a href="./logout.php" class="">
                            <img src="./img/logout_FILL0_wght400_GRAD0_opsz24.svg" alt="">
                            <span>Logout</span>
                        </a>
                    </div>
                    <!-- ユーザー削除　ダミー -->
                    <div class="account-area-sub" id="logout-btn">
                        <a href="./logout.php" class="">
                            <img src="./img/waving_hand_FILL0_wght400_GRAD0_opsz24.svg" alt="">(dummy)
                            <span>Delete User</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div > 
            <iframe class=" w-[450px] md:w-[600px]" name="iframe_bm_add" src="./bm_add.php" style="background-color:white;height:1000px;"></iframe>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <script>

        $("#btn_profile_edit").on("click",function(e){
            $("#profile_edit_form").toggle(500)
        })
        $("#userinfo-btn").on("click",function(e){   
            $("#user-profile").fadeToggle(250)
        })
            
</script>
</body>
</html>