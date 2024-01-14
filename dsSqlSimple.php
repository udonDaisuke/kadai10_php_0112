<?php
    error_reporting(0);
    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

class sqlDB_cls
{
    // default setting
    public $db_host; //DBホスト
    public $db_id; //アカウント名
    public $db_pw; //パスワード：MAMPは'root'
    public $db_name; //DBNAME
    // public $db_id   = 'root'; //アカウント名
    // public $db_pw   = ''; //パスワード：MAMPは'root'
    // public $db_host = 'localhost'; //DBホスト
    // public $db_name = ""; //DBNAME
    // echo $db_host;


    public $pdo = "";
    public $table = ""; //TABLE名
    public $DATA = "";
    public $ORDER_BY ="";
    public $GROUP_BY ="";
    public $RETURN_ALL =FALSE;

    // コンストラクタ：DBに接続
    public function __construct($str_db_name="",$error_report=false){

        $this->db_host = $_ENV['DB_HOST']; //DBホスト
        $this->db_id   = $_ENV['DB_USER']; //アカウント名
        $this->db_pw   = $_ENV['DB_PASS']; //パスワード：MAMPは'root'
        $this->db_name = $_ENV['DB_NAME']; //DBNAME

        $db_name = $str_db_name; //データベース名
        $db_name = $this->db_name;
        try{
            $this->pdo = new PDO('mysql:dbname=' .$db_name. ';charset=utf8;host=' .$this->db_host, $this->db_id, $this->db_pw);
            return $this->pdo;
        } catch (PDOException $e){

            if($error_report==true){
                $this->pdo=$e;
                return ;
            }else{
                $this->pdo=false;
                return ;
            }
        }        
    }

    // SQL文の一部設定の初期化
    private function _initialize_prop(){
        $this->ORDER_BY="";
        $this->GROUP_BY="";
        $this->DATA="";
        $this->RETURN_ALL=FALSE;
    }

    // ■クラス属性の設定変更
    // <arguments>
    // $key_str：属性名($のぞいた部分)  
    // $value:変更後の値
    // 
    // <return>
    // なし
    public function set_prop($key_str="",$value=""){
        $this->$key_str=$value; //$key_str = $key_strの値
    }
    

    // ■データ取得 デフォルトは全取得
    // <arguments>
    // $target_label：取得するデータのラベル　default="*"
    // $condition_obj：WHERE分に相当する連想配列　[ラベル=>値(=のみ),...]　default=[] 
    // $val_type_obj=: bind時のパラメータ指定　[ラベル=>値,...]　default[] 
    // $where_operator：WHEREの条件が複数ある場合"AND"　"OR"の切り替えが可能(細かいカスタマイズは出来ない)

    // <return>
    // 取得した値
    //     public $ORDER_BY ="";
    //     public $GROUP_BY ="";
    //     public $RETURN_ALL =boolean; によって出力が変わる。
    //  $RETURN_ALL=FALSEのとき 単一(先頭)レコードが返る。
    //  $RETURN_ALL=TRUEのとき レコード単位のarrayが返る。

    // condition_obj＝["user_id="=>111]のように渡す。値は内部でbindされる。
    public function get($target_label="*",$condition_obj=[],$val_type_obj=[],$where_operator="AND"){
        $sql_str="SELECT $target_label FROM $this->table WHERE ";
        
        // bind時のタイプ指定のデフォルト値を作成
        if($val_type_obj ==[]){
            foreach($condition_obj as $key => $value){
                // $value=PDO::PARAM_STR;
                $val_type_obj[$key]=PDO::PARAM_STR;
            }
        }
        // WHERE条件が与えられているとき
        if($condition_obj==[]){
            $sql_str.= "1";
        }else{
            end($condition_obj); //配列のラストに移動
            $last_key = key($condition_obj); //ラストのキーを取得

            foreach($condition_obj as $key=>$value){
                $sql_str.=$key."=:".$key;
                if($key==$last_key){
                    $sql_str.=" ";
                }else{
                    $sql_str.=" $where_operator ";
                }
            }
        }
        if($this->ORDER_BY!=""){
            $sql_str .=' ORDER BY '.$this->ORDER_BY;
        }
        if($this->GROUP_BY!=""){
            $sql_str .=' GROUP BY '.$this->GROUP_BY;
        }
        $sql_str.=';';
        $pdo = $this->pdo;
        $stmt = $pdo->prepare($sql_str); 
        // bindの設定
        foreach ($val_type_obj as $key => $value){
            // bindする値の型を取得。ない場合はPARAM＿STRとする
            if(array_key_exists($key,$val_type_obj)){
                $val_type=$val_type_obj[$key];
            }else{
                $val_type=PDO::PARAM_STR;
            }
            // タイムスタンプを除き、bindする
            if($key !== "timestamp"){
                $stmt ->bindValue(":$key",$condition_obj[$key],$val_type);
            }else{continue;}
        }
        // SQL実行
        $status = $stmt -> execute();


        if($status){
            if($this->RETURN_ALL==FALSE){
                $result = $stmt -> fetch(PDO::FETCH_ASSOC);

                return  $result;
            ;
            }else{
                $list = [];
                while($get = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($list,$get);
                }
                return $list;
            }
        }else{
        }
        $this->_initialize_prop(); //GROUP_BY ORDER_BY DATAを初期化

    }


    // ■データ登録　※パスワードなどはハッシュ化して渡すこと
    // <arguments>
    // $obj：変更内容に関する連想配列　[ラベル=>変更後の値,...]
    // $val_type_obj:bind時のパラメータ指定　[ラベル=>値,...]　default[]　デフォルトでもだいたい大丈夫
    // 
    // <return>
    // execute実行時の返り値boolean
    public function set($obj,$val_type_obj=[]){
        // $obj = [
        //     "user_id"=>"aaaa",
        //     "pass"=>"bbbb",
        //     "nickname"=>"cccc"
        // ];

        // bind時のタイプ指定のデフォルト値を作成
        if($val_type_obj==[]){
            foreach($obj as $key => $value){
                $val_type_obj[$key]=PDO::PARAM_STR;
            }
        }

        $sql_str1 ='INSERT INTO '.$this->table .'(';
        $sql_str2 ='VALUES(';

        end($obj); //内部ポインタを最終要素に移動
        $last_key = key($obj); //キーを取得＠最終要素
        // SQL命令文生成
        foreach ($obj as $key => $value){
            // $$key = $value;
            $sql_str1.=$key;

            if($key == "timestamp" ||$key == "timestamp_update"){
                $sql_str2.='sysdate()';
            }else{
                $sql_str2.=':'.$key;
            }

            if($key==$last_key){
                $sql_str1.=')';
                $sql_str2.=')';    
            }else{
                $sql_str1.=',';
                $sql_str2.=',';    
            }
        }
        $sql =$sql_str1.$sql_str2.';)';
        $pdo = $this->pdo;
        $stmt = $pdo->prepare($sql); 
        // bindの設定
        foreach ($obj as $key => $value){
            // bindする値の型を取得。ない場合はPARAM＿STRとする
            if(array_key_exists($key,$val_type_obj)){
                $val_type=$val_type_obj[$key];
            }else{
                $val_type=PDO::PARAM_STR;
            }
            // タイムスタンプを除き、bindする
            if($key !== "timestamp" && $key !== "timestamp_update" ){
                $stmt ->bindValue(":$key",$value,$val_type);
            }else{continue;}
        }
        // SQL実行
        $status = $stmt -> execute();
        return $status;

    }



    // ■key(ラベル)の一致が$match_valueに一致するレコードを削除
    // <arguments>
    // $key_str：検索対象のラベル(1つのみ)
    // $match_value：削除対象となるレコードの持つ値＠上記ラベル
    // $bind_type：バインド時のパラメータ　default=PDO::PARAM_STR
    // 
    // <return>
    // execute実行時の返り値boolean
    public function del($key_str="",$match_value="",$bind_type=PDO::PARAM_STR){
        $pdo = $this->pdo;
        $stmt = $pdo->prepare("DELETE FROM $this->table WHERE $key_str = :$key_str;"); 
        $stmt ->bindValue(":$key_str",$match_value,$bind_type);
        // SQL実行

        $status = $stmt -> execute();
        return $status;
    }



    // ■レコードの値を更新
    // <arguments>
    // $key_str：更新対象を決めるためのラベル(id,user_idなど)
    // $match_value：更新対象の条件となる値(idなど)
    // $change_contents_obj：変更箇所に関する連想配列　[ラベル=>変更後の値,...]
    // $val_type_obj：bind時のパラメータ指定　[ラベル=>値,...]　default[]　デフォルトでもだいたい大丈夫

    // 
    // <return>
    // execute実行時の返り値boolean
    public function upd($key_str="",$match_value="",$change_contents_obj=[],$val_type_obj=[]){
        $obj = $change_contents_obj;
        if($obj==[]){
            foreach($obj as $key => $value){
                $val_type_obj[$key]=PDO::PARAM_STR;
            }
        }
        $sql_str ='UPDATE '.$this->table .' SET ';

        end($obj); //内部ポインタを最終要素に移動
        $last_key = key($obj); //キーを取得＠最終要素
        // SQL命令文生成
        foreach ($obj as $key => $value){
            // $$key = $value;
            if($key == "timestamp_update"){
                $sql_str.= $key.'='.'sysdate()';
            }else{
                $sql_str.= $key.'=:'.$key;
            }

            if($key==$last_key){
                $sql_str.=" WHERE $key_str=:$key_str;";
            }else{
                $sql_str.=',';
            }
        }
        $pdo = $this->pdo;
        $stmt = $pdo->prepare($sql_str); 
        // bindの設定
        foreach ($obj as $key => $value){
            // bindする値の型を取得。ない場合はPARAM＿STRとする
            if(array_key_exists($key,$val_type_obj)){
                $val_type=$val_type_obj[$key];
            }else{
                $val_type=PDO::PARAM_STR;
            }
            // タイムスタンプを除き、bindする
            if($key !== "timestamp_update"){
                $stmt ->bindValue(":$key",$value,$val_type);
            }else{continue;}
            // 検索値のbind ※PARAM_STRとして
            $stmt ->bindValue(":$key_str",$match_value,PDO::PARAM_STR);

        }
        // SQL実行
        $status = $stmt -> execute();
        return $status;


    }

}


// ＠使用例
// (1)インスタンス生成(引数はDB名)
// $sql = new sqlDB_cls("gs_bm_table_2");

// (2)テーブル指定等ORDER_BY等設定
// $sql->set_prop('table','user');

// $obj = [
//     "user_id"=>"aaaa",
//     "pass"=>"bbbb",
//     "nickname"=>"cccc"
// ];
// *$objの情報を持ったデータを追加する
// $sql->set($obj);


// $chgobj = [
//     "user_id"=>"aaaa2",
//     "pass"=>"bbbb2",
//     "nickname"=>"cccc2"
// ];
// *id=1 に対し、$chgobjに基づいた変更を加える
// $sql->upd("id",1,$chgobj); 

// *id=2のレコードを削除
// $sql->delete("id",2);

// *id=$idのユーザー情報を取得
// $sql = new sqlDB_cls("gs_bm_table_2");
// $sql->set_prop('table','user'); 
// $results = $sql->get("*",["id"=>$id]);

// ※注意と補足
// シンプルな操作のみ対応しているので、クラスの勉強したい人の参考用として使うといいと思います。
// 日付情報はset時はtimestamp,upd時はtimestamp_updateにsysdate()が挿入される。
// 使いたい場合はテーブルのラベルを合わせておく必要がある。
// bindは変数名の$を:に変換したものを勝手につけて処理している。なので、bindの記載はいらない。time_stamp系は対象外
// だいたいよさそう。というくらいの完成度なので、大目に見てください。