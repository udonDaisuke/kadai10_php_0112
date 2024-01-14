<?php
// error_reporting(0);
session_start();
require_once("./funcs_v1.php");
require_once("./dsSqlSimple.php");

$id = $_GET['item_id'];
$result = $_POST;


$action = $_GET['action'];
$img_name = $_FILES['img_url']['name'];
$img_name_org = $_POST['img_url_org'];
// 画像を保存 
// 未入力時はひきつぎ
if(empty($img_name)){
    $img_path = $img_name_org;
//入力時はアップロード古いデータを削除 
}else{
    // 差し替え前データ削除
    unlink($img_name_org);
    $extension = pathinfo($_FILES['img_url']['name'], PATHINFO_EXTENSION);
    $new_name = uniqid() . '.' . $extension;
    $img_path = './img/bm_img/' . $new_name;
}
move_uploaded_file($_FILES['img_url']['tmp_name'], $img_path);
// $result情報更新＠img_url
$result['img_url']=$img_path;
// 不要なデータを削除
unset($result['img_url_org']);

// public判定 true or false
$public = isset($result["public"]);
$result["public"]=$public; 



$sql = new sqlDB_cls("gs_bm_table_2");
$sql->set_prop('table','bookmark');   

if($action=='add'){
    $nickname = $_SESSION["nickname"];
    $result['user']=$_SESSION["user_id_index"];
    $result['timestamp']='';
    $result['timestamp_update']='';

    $results = $sql->set($result);
    // iframe外  
    redirect("./user_main.php?user=".$nickname);    
}else{
    $nickname = $_SESSION["nickname"];
    // データ追加
    $result['timestamp_update']='';
    // 更新実行
    $results = $sql->upd("id",$id,$result);
    // iframe内  
    redirect("./bm_add.php?user=".$nickname);    

}

?>