<?php
error_reporting(0);


    require_once("./funcs_v1.php");
    
    require_once("./dsSqlSimple.php");
    
    $item_id = $_GET["item_id"]; 


    if(isset($_POST['yes'])) {
        $sql = new sqlDB_cls("gs_bm_table_2");
        $sql->set_prop('table','bookmark');   
        var_dump($sql);
        // 画像データ削除
        $img_path = $sql->get("*",["id"=>$item_id])["img_url"]; 
        unlink($img_path);
        // レコード削除
        $results = $sql->del("id",$item_id); 
        var_dump($results);
        redirect("./bm_add.php?info=item_deleted");
    } else if(isset($_POST['no'])) {
        redirect("./bm_add.php?user=".$member['nickname']);
    }

    
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
    <h1 class="w-full text-center text-lg font-bold pt-6">Are you sure to delete this item?</h1>
    <form class ="flex justify-center gap-10 p-5" action="./bm_delete.php?item_id=<?=h($item_id)?>" method="post" class="flex">
        <input name="yes" type="submit" class="w-20 h-8 bg-blue-500 text-white font-bold text-lg rounded-md hover:shadow-lg cursor-pointer" value ="YES">
        <input name="no" type="submit" class="w-20 h-8 bg-blue-500 text-white font-bold text-lg rounded-md hover:shadow-lg cursor-pointer" value="NO">
    </form>
</body>
</html>