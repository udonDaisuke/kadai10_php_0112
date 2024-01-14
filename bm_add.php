<?php
    error_reporting(0);

    require_once("./funcs_v1.php");

    session_start();
    $action = $_GET["action"];
    $user_id_index = $_SESSION['user_id_index'];
    $_SESSION["filter_mode"] = "My BM";

    require_once("./dsSqlSimple.php");
    $sql = new sqlDB_cls("gs_bm_table_2");
    $sql->set_prop('table','bookmark');

    $sql->set_prop('RETURN_ALL',TRUE);
    $sql->set_prop('ORDER_BY','timestamp_update DESC');
    if($action=="filter_fav"){
        // 自分のお気に入り投稿だけ表示
        $results = $sql->get("*",['user'=>$user_id_index,'favorite'=>TRUE]);
        $_SESSION["filter_mode"] = "* Your Favorite";

    }elseif($action=="filter_public"){
        // パブリック投稿だけ表示
        $results = $sql->get("*",['public'=>TRUE]); 
        $_SESSION["action"] = $action;
        $_SESSION["filter_mode"] = "* Public BM";

    }else{
        // 自分の投稿だけ表示
        $results = $sql->get("*",['user'=>$user_id_index]); 
        $_SESSION["action"] = $action;
    }
    $sql->set_prop('table','user');

    $filter_mode = $_SESSION["filter_mode"];

    // ユーザー情報取得by id
    function user($id){
        $sql = new sqlDB_cls("gs_bm_table_2");
        $sql->set_prop('table','user');
        $results = $sql->get("*",["id"=>$id]);    
        return $results;
    }


    // filter_mode表示
    if($action=="filter_fav"){
        // 自分のお気に入り投稿だけ表示
        $_SESSION["filter_mode"] = "* Your Favorite";
        $link_cls = 'class="no-underline font-bold text-lg text-blue-700"';
        $filter_cls = 'class="inline-block text-lg font-bold text-pink-700 w-full text-center"';
        $filter_mode = "My Favorite";

    }elseif($action=="filter_public"){
        // パブリック投稿だけ表示
        $_SESSION["filter_mode"] = "* Public BM";
        $link_cls = 'class="no-underline font-bold text-lg text-blue-700"';
        $filter_cls = 'class="inline-block text-lg font-bold text-green-600 w-full text-center"';
        $filter_mode = "Public Bookmark";

    }else{
        // 自分の投稿だけ表示
        $_SESSION["action"] = $action;
        $link_cls = 'class="hidden"';
        $filter_cls = 'class="inline-block text-lg font-bold text-black w-full text-center"';
        $filter_mode = "MY Bookmark";
    }
    $view = "<a href='./bm_add.php' $link_cls>◀ Filter Reset </a><span class='flex h-1 w-3'> </span>
            <p $filter_cls>―――― $filter_mode ――――</p>";
    $view .= '<div class="sm:w-10/12 mx-auto">';
    foreach ($results as $result) {
        $view .= '<div class="relative flex justify-between px-6 border border-t-2">';

        $user_nickname = user($result["user"])['nickname'];
        // var_dump($result);
        $view .= '<div class="flex w-1/3 min-h-40 py-3 items-center">';
        $view .= '<img  class="flex max-h-full w-full " src="'.$result["img_url"].'" alt="no image" >';
        $view .= '</div>';
        $view .= '<div class="flex flex-col w-2/3 pl-2 pt-4" >';
        $view .= '<a class="" href="">User: '.$user_nickname.'</a>';

        $view .= '<p class="">Name: '.$result["name"].'</p>';
        // $view .= '<a class="" href="'.$result["ref_url"].'">URL: '.$result["ref_url"].'</a>';
        $view .= '<p class="">Comment: '.$result["comment"].'</p>';
        $view .= '<p class="">Date: '.$result["timestamp_update"].'</p>';
        $view .= '<div class="flex justify-between">';
        if($result["ref_url"]==""){
            $view .= '<a class="block w-1/5 h-8 bg-slate-200 px-2 rounded-lg cursor-pointer hover:shadow-md border border-slate-500 text-base text-center text-slate-400 font-bold pt-1 pointer-events-none" href="'.$result["ref_url"].'"> URL</a>';
        }else{
            $view .= '<a class="block w-1/5 h-8 bg-orange-200 px-2 rounded-lg cursor-pointer hover:shadow-md border border-orange-500 text-base text-center text-black font-bold pt-1 " href="'.$result["ref_url"].'" target="_blank" rel="noopener noreferrer"> URL</a>';

        }

        // 自分のBMのみ表示
        if($user_id_index == $result["user"]){
            $view .='<form class="w-1/5" action="./bm_edit.php?action=edit&item_id='.$result['id'].'" method="post">';
            $view .='<input class="block w-full h-8 bg-yellow-100 text-2xl text-center px-2 rounded-lg cursor-pointer hover:shadow-md border border-yellow-600 " type="submit" value="&#128395">';
            $view .='</form>';
            $view .='<form class="w-1/6" action="./bm_delete.php?item_id='.$result['id'].'" method="post" >';
            $view .='<input class="flex w-full h-8 bg-gray-300 text-2xl text-center px-2 rounded-lg cursor-pointer hover:shadow-md bg-[url('."'./img/delete_forever.svg'".')] bg-no-repeat bg-center border border-gray-500 delete-icon" type="submit" value="" >';
            $view .='</form>';
        // publicのBMでの表示
        }else{
            $view .='<form class="w-1/5  opacity-20" action="" method="post">';
            $view .='<input class="block w-full h-8  bg-slate-200 px-2 rounded-lg border border-slate-500 text-base text-center text-slate-400 " type="submit" value="&#128395">';
            $view .='</form>';
            $view .='<form class="w-1/6 opacity-20" action="" method="post" >';
            $view .='<input class="flex w-full h-8  bg-slate-200 px-2 rounded-lg border border-slate-500 text-base text-center text-slate-100 bg-[url('."'./img/delete_forever.svg'".')] bg-no-repeat bg-center delete-icon" type="button" value="" >';
            $view .='</form>';

        }

        $view .= '</div>';
        $view .= '</div>';
        // 自分のBMのみ表示
        if($user_id_index == $result["user"]){
            if($result['favorite']){
                $view .= '<form method = "post" action="./bm_edit.php?action=fav_remove&item_id='.$result['id'].'"  class="block absolute top-4 right-5">
                <label class="cursor-pointer" for ="item_'.$result['id'].'">
                    <img class="top-2 right-2" src="./img/Vector.svg" alt="like-true">
                    <input id="item_'.$result['id'].'" type="submit" class ="hidden absolute" value =" " >
                </label>
                </form>';
        
            }else{
                $view .= '<form method = "post" action="./bm_edit.php?action=fav_add&item_id='.$result['id'].'"  class="block absolute top-4 right-5 ">
                <label class="cursor-pointer" for ="item_'.$result['id'].'">
                    <img class=" top-2 right-2" src="./img/favorite_border.svg" alt="like-false">
                    <input id="item_'.$result['id'].'" type="submit" class ="hidden absolute" value =" " >
                </label>
                </form>';        
            }
        // publicのBMでの表示
        }else{
            $view .= '<form method = "post" action=""  class="block absolute top-4 right-5 ">
            <label class="" for ="item_'.$result['id'].'">
                <img class=" top-2 right-2 opacity-20" src="./img/favorite_border.svg" alt="like-false">
            </label>
            </form>';        
        }
        $view .= '</div>';
    }
    $view .= '</div>';

// echo $view;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="./css/tailwind_out.css">
    <title>Document</title>
</head>
<body>
    <style>
        .delete-icon{
            background-image: url("./img/delete_forever.svg");
        }
    </style>
    <main class="hidden w-full">
        <?=($view)?>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script>
        $('main').fadeIn(300)
    </script>
</body>
</html>