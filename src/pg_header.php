<!-- ヘッダー -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta http-equiv="Content-TYPE" content="text/html; charset=UTF-8">
  <title>Lunch Hunter</title>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js'></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<?php
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<body>
  <div class="navbar navbar-default" style="background-color:beige; color:black;">
    <div class="container">
      <!-- 左側：システム名 -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">ナビゲーションの切替</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <span style="
        display: block;
        padding: 15px 15px;
        font-size: 20px;
        font-weight: normal;
        color:black;
        background-color: transparent;
        text-decoration: none;
        cursor: default;
      ">
          Lunch Hunter
        </span>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
              <?php
              echo '<li class="navbar-text">アカウント：';
              echo $_SESSION['user_account'] ?? '', ' &nbsp;&nbsp;&nbsp;';
              echo '</li>';
              if (isset($_SESSION['usertype_id'])) {
                $menu = array(); //メニュー項目：プログラム名（拡張子.php省略）
                if ($_SESSION['usertype_id'] === '1') {  //社員
                  $menu = array(   //社員メニュー
                    '店舗一覧'  => 'rst_list',
                    'MY_PAGE'  => 'user_page',
                    '店舗登録'  => 'rst_input',
                  );
                }
                if ($_SESSION['usertype_id'] === '9') {  //管理者
                  $menu = array(   //管理者メニュー
                    '店舗一覧'  => 'rst_list',
                    'ユーザ登録' => 'user_input',
                    'ユーザ一覧'  => 'user_list',
                    '通報済み口コミ一覧' => 'rev_report',
                  );
                }

                foreach ($menu as $label => $action) {
                  echo  '<li><a href="?do=' . $action . '">' . $label . '</a></li>&nbsp;&nbsp;';
                }
                echo  '<li><a href="?do=user_logout" >ログアウト</a></li>&nbsp;&nbsp;';
              } else {
                echo  '<li><a href="?do=user_login" >ログイン</a></li>';
              }
              ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="container">