<?php
session_start();
require_once 'src/model.php';
// データベースの接続情報
Model::setDbConf([
    'host'=>'mysql', 'user'=>'root','pass'=>'root','dbname'=>'pbl2025web2db'
  ]);
  
$no_header_ouput =[
    'user_logout'
    ,'user_check'
    ,'user_save'
    ,'rst_save'
    ,'rev_save'
    ,'user_input_save'
    ,'user_myedit_save'
    ,'rev_detail_rpsave'
    ,'rst_favsave'
];

if(isset($_SESSION['usertype_id'])){
    $do = $_GET['do'] ?? 'rst_list';
} else {
    $do = $_GET['do'] ?? 'user_login';
}

if(in_array($do, $no_header_ouput)){
    include "src/{$do}.php";
} else {
    include "src/pg_header.php";
    include "src/{$do}.php";
    include "src/pg_footer.php";
}