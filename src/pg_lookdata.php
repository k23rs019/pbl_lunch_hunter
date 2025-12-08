<?php
require_once('model.php');
$user = new User();
$rst = new Restaurant();
$rev = new Review();

echo '<pre>';
var_dump($user->get_Userdetail(['user_id'=>'test']));
echo '<br>';
var_dump($rst->get_RstDetail(['rst_id'=>'1']));
echo '<br>';
var_dump($rev->get_RevDettail(['review_id'=>'1']));
echo '</pre>';
