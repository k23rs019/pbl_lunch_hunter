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


#### **c.** データベース検索用コード一覧

**必須コード**
-require_once('model.php');

-$model = new User(); 利用したいデータに応じた宣言
    -ユーザの場合->$model = new User()
    -店舗の場合->$model = new Restaurant()
    -口コミの場合->$model = new Review()

**データ検索用コード**
-getList(): 特定のテーブルに対し一覧表示用データを検索し結果をすべて返す

-getDetail(): 特定のテーブルに対して詳細表示用データを検索し１件のみ返す
    
-insert($data): 特定のテーブルに対しデータを1行追加する。
    -引数: $data, 配列, 例：['name'=>'foo', 'age'=>18, 'tel'=>'12345'] 戻り値：追加した行数

-update($data, $where): 特定のテーブルに対してデータを更新する。
    引数: $data, 配列, 例：['name'=>'foo', 'age'=>18, 'tel'=>'12345']
    　　　$where, 条件を表す文字列, 例：'sid=k22rs999'
    

-delete($where): 特定のテーブルに対して条件を満たすデータを削除する。
     引数: $where, 条件を表す文字列, 例：'sid=k22rs999'