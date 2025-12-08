<?php
//SQL処理
class Model
{
    protected $table;
    protected $db;

    protected static $conf = [
        'host' => 'mysql', 'user' => 'root', 'pass' => 'root', 'dbname' => 'test'
    ];

    protected static $codes = [
        'rst_holiday' => ['1' => '日', '2' => '月', '4' => '火', '8' => '水', '16' => '木', '32' => '金', '64' => '土', '128'=>'年中無休', '256'=>'未定'],
        'rst_pay' => ['1' => '現金', '2' => 'QRコード', '4' => '電子マネー', '8' => 'クレジットカード']
    ];

    function __construct($conf = null)
    {
        self::$conf = $conf ?? self::$conf;
        $conn = new mysqli(
            self::$conf['host'],
            self::$conf['user'],
            self::$conf['pass'],
            self::$conf['dbname']
        );
        if ($conn->connect_errno) {
            die($conn->connect_error);
        }
        $conn->set_charset('utf8');
        $this->db = $conn;
    }
    public static function setDbConf($conf)
    {
        self::$conf = $conf;
    }

    // コードから、値を求めて返す。
    // 例：ユーザ種別2の場合、getValue(2, 'user_type')　結果：'ゲスト'
    public static function getValue($code, $category, $default = null)
    {
        return self::$codes[$category][$code] ?? $default;
    }

    // コードの定義を返す。
    public static function getCodes($category)
    {
        return self::$codes[$category] ?? [];
    }

    // 検索用SQL文を実行し、問合せ結果を返す。エラーなら処理中止
    // ソート$orderby、表示範囲$limit, $offsetを指定できる
    public function query($sql, $orderby = null, $limit = 0, $offset = 0)
    {
        $sql .= $orderby ? " ORDER BY {$orderby}" : '';
        $sql .= $limit > 0 ? " LIMIT {$limit} OFFSET {$offset}" : '';
        $rs = $this->db->query($sql);
        if (!$rs) die('DBエラー: ' . $sql . '<br>' . $this->db->error);
        return $rs->fetch_all(MYSQLI_ASSOC);
    }

    // 更新用SQL文を実行する。エラーなら処理中止
    public function execute($sql)
    {
        $rs = $this->db->query($sql);
        if (!$rs) die('DBエラー: ' . $sql . '<br>' . $this->db->error);
    }


    //getList(): 特定のテーブルに対し一覧表示用データを検索し結果をすべて返す
    public function getList($where = 1, $orderby = null, $limit = 0, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        return $this->query($sql, $orderby, $limit, $offset);
    }

    //getDetail(): 特定のテーブルに対して詳細表示用データを検索し１件のみ返す
    public function getDetail($where)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        $data = $this->query($sql);
        return $data[0] ?? [];
    }

    /*insert(): 特定のテーブルに対しデータを1行追加する。
     * 引数: $data, 配列, 例：['name'=>'foo', 'age'=>18, 'tel'=>'12345'] 
     * 戻り値：追加した行数
     */
    public function insert($data)
    {
        if(empty($data)) die('INSERT用データが空です');
        $keys = implode(',', array_map(fn($k) => "`$k`", array_keys($data)));
        $values = array_map(fn($v) => is_string($v) ? "'" . $this->db->real_escape_string($v) . "'" : $v, array_values($data));
        $values = implode(",", $values);
        $sql = "INSERT INTO {$this->table} ($keys) VALUES ($values)";
        $this->execute($sql);
        return $this->db->affected_rows;
    }

    /*update(): 特定のテーブルに対してデータを更新する。
     * 引数: $data, 配列, 例：['name'=>'foo', 'age'=>18, 'tel'=>'12345']
     * 　　　$where, 条件を表す文字列, 例：'sid=k22rs999'
     * 戻り値：変更した行数
     */
    public function update($data, $where)
    {
        $setParts = [];
        foreach ($data as $k => $v) {
            $v = is_string($v) ? "'{$v}'" : $v;
            $setParts[] = "{$k}={$v}";
        }
        $setStr = implode(',', $setParts);
        if (is_array($where)) {
            $whereParts = [];
            foreach ($where as $key => $value) {
                $whereParts[] = "$key = '$value'";
            }
            $whereStr = implode(' AND ', $whereParts);
        } else {
            $whereStr = $where;
        }
        $sql = "UPDATE {$this->table} SET {$setStr} WHERE {$whereStr}";
        $this->execute($sql);
        return $this->db->affected_rows;
    }

    /* delete(): 特定のテーブルに対して条件を満たすデータを削除する。
     * 引数: $where, 条件を表す文字列, 例：'sid=k22rs999'
     * 戻り値：変更した行数
     */
    public function delete($where)
    {
        if (is_array($where)) {
            $whereParts = [];
            foreach ($where as $key => $value) {
                $whereParts[] = "$key = '$value'";
            }
            $whereStr = implode(' AND ', $whereParts);
        } else {
            $whereStr = $where;
        }
        $sql = "DELETE FROM {$this->table} WHERE {$whereStr}";
        $this->execute($sql);
        return $this->db->affected_rows;
    }
}

class User extends Model
{
    protected $table = "t_user";

    function auth($uid, $upass)
    {
        return $this->getDetail("uid='{$uid}' AND upass='{$upass}'");
    }
    //姓名結合
    function username($user)
    {
        $username = "{$user['user_l_name']} {$user['user_f_name']}";
        return $username;
    }
    //フリガナ結合
    function userkana($user)
    {
        $userkana = "{$user['user_l_kana']} {$user['user_f_kana']}";
        return $userkana;
    }
    //ユーザ詳細
    function get_Userdetail($where)
    {
        $data = [];
        foreach($where as $key => $values){
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ',$data);
        $user = $this->getDetail($wherestr);
        if (empty($user)) return [];
        $usertype_id = $user['usertype_id'];

        $sql = "SELECT usertype FROM t_usertype WHERE usertype_id={$usertype_id}";
        $result = $this->query($sql);

        $usertype = $result[0]['usertype'] ?? '不明';
        $user['usertype'] = $usertype;
        $user['username'] = $this->username($user);
        $user['userkana'] = $this->userkana($user);
        return $user;
    }
    //ユーザリスト
    function get_userlist($orderby = null, $limit = 0, $offset = 0)
    {
        $sql = "SELECT * FROM t_user NATURAL JOIN t_usertype WHERE usertype_id = '1'";
        $users =  $this->query($sql, $orderby, $limit, $offset);

        return $users;
    }
    //お気に入り店舗
    function get_favorite($user_id)
    {
        $sql = "SELECT rst_id FROM t_favorite WHERE user_id = {$user_id}";
        $favorites = $this->query($sql);
        $favorite = [];
        foreach ($favorites as $fav) {
            $rst_id = $fav['rst_id'];
            $sql = "SELECT * FROM t_rstinfo WHERE rst_id = {$rst_id}";
            $result = $this->query($sql);
            if (!empty($result)) {
                $favorite[] = $result[0];
            }
        }
        return $favorite;
    }
    //Myリスト
    function get_mylist($table, $user_id)
    {
        $sql = "SELECT * FROM {$table} WHERE user_id = {$user_id}";
        $mylist = $this->query($sql);
        return $mylist;
    }
}

class Restaurant extends Model
{
    protected $table = "t_rstinfo";
    function get_RstDetail($where)
    {
        $data = [];
        foreach($where as $key => $values){
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ',$data);
        $rst = $this->getDetail($wherestr);
        // holidays（ビットフラグ）をラベル配列に変換
        $flag = (int)$rst['rst_holiday'];  // DB の値（10進）
        $holidays = [];

        foreach (self::$codes['rst_holiday'] as $bit => $label) {
            if ($flag & (int)$bit) {
                $holidays[] = $label;
            }
        }

        // 配列で格納（例： ['月','水','金']）
        $rst['holidays'] = $holidays;

        $flag = (int)$rst['rst_pay'];  // DBの値（10進）
        $pays = [];

        foreach (self::$codes['rst_pay'] as $bit => $label) {
            if ($flag & (int)$bit) {
                $pays[] = $label;
            }
        }

        $rst['pays'] = $pays;

        $rst_id = $rst['rst_id'];
        $sql = "SELECT * FROM t_rst_genre NATURAL JOIN t_genre WHERE rst_id = {$rst_id}";
        $rst['rst_genre'] = $this->query($sql);
        return $rst;
    }
}
class Genre extends Model
{
    protected $table = "t_rst_genre";
    function save_genre($rst_id, $genres)
    {
        foreach ($genres as $genre) {
            $data = ['rst_id'=>$rst_id,'genre_id' => $genre];
            $this->insert($data);
        }
        return true;
    }
}
class Review extends Model
{
    protected $table = "t_review";
    function get_RevDettail($where){
        $data = [];
        foreach($where as $key => $values){
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ',$data);
        $rev = $this->getDetail($wherestr);
        return $rev;
    }
}

class Report extends Model
{
    protected $table = "t_report";
    function get_RepoDettail($where){
        $data = [];
        foreach($where as $key => $values){
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ',$data);
        $repo = $this->getDetail($wherestr);
        return $repo;
    }
}
