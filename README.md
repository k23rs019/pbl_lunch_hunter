# Lunch_Hunter

#### **a.** コードを開発環境に配置
```shell
C:/php/lampp-docker8/htdocs/
 ├ pbl_lunch_hunter/
   ├─ css/   CSSファイルが入っている
   ├─ docs/  データベースの構成等の説明が入っている
   ├─ src/   主要機能や画面の実装のPHPソースファイルが入っている
      ├─ model.php　データベース操作に関するクラス
      ・・・
   ├─ index.php　各機能への入り口。
   └─ README.md　このドキュメント
```

#### **b.** コーディング規約

**b1.** 変数名、列名、name属性名を統一
- _,小文字,数字のみ、分かりやすい英語名
    - 例）$sname(変数名),sname(列名),< imput type="text" name="sname" >(属性名)

**b2.** フォルダ名、ファイル名
- フォルダ名：小文字4字以内
    - 例）src
- ファイル名：_,小文字のみの分かりやすい英語名
    - 例）user_login.php
