<!-- ヘッダー -->
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-TYPE" content="text/html; charset=UTF-8">
  <link rel="stylesheet" TYPE="text/css" href="css/style.css">
</head>

<body>
  <header>
    <div class="wrapper">
      <div id="navbar">

        <?php
        function h($str)
        {
          return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        }
        echo $_SESSION['user_account'] ?? '', ' &nbsp;&nbsp;&nbsp;';
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
            echo  '<a href="?do=' . $action . '">' . $label . '</a>&nbsp;&nbsp;';
          }
          echo  '<a href="?do=user_logout">ログアウト</a>&nbsp;&nbsp;';
          echo '</div>';
        } else {
          echo  '<a href="?do=user_login">ログイン</a>';
        }
        ?>
      </div>
  </header>
  <h2 align="left">Lunch Hunter</h2>