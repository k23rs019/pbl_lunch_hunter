<?php
//SQL処理
class Model
{
    protected $table;
    protected $db;

    protected $blobColumns = ['photo1', 'photo2', 'photo3'];

    protected static $conf = [
        'host' => 'mysql', 'user' => 'root', 'pass' => 'root', 'dbname' => 'test'
    ];

    protected static $codes = [
        'rst_holiday' => ['1' => '日', '2' => '月', '4' => '火', '8' => '水', '16' => '木', '32' => '金', '64' => '土', '128' => '年中無休', '256' => '未定'],
        'rst_pay' => ['1' => '現金', '2' => 'QRコード', '4' => '電子マネー', '8' => 'クレジットカード'],
        'report_reason' => ['1' => '写真', '2' => 'コメント', '3' => '両方'],
        'report_state' => ['1' => '未処理', '2' => '削除', '3' => '取り消し'],
        'rst_discount'  => ['0' => '割引なし', '1' => '割引あり']
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
        $needBlob = false;

        // 写真データが含まれているかチェック
        foreach ($this->blobColumns as $photo) {
            if (!empty($data[$photo])) {
                $needBlob = true;
                break;
            }
        }

        return $needBlob
            ? $this->insertBlob($data)  // 画像入り
            : $this->insertAnother($data);     // 通常 INSERT
    }
    public function insertAnother($data)
    {
        if (empty($data)) die('INSERT用データが空です');
        $keys = implode(',', array_map(fn ($k) => "`$k`", array_keys($data)));
        $values = array_map(function ($v) {
            if (is_null($v)) {
                return "NULL";
            } elseif (is_bool($v)) {
                return $v ? 1 : 0;
            } elseif (is_numeric($v)) {
                return $v; // 数値はクォート不要
            } else {
                return "'" . $this->db->real_escape_string($v) . "'";
            }
        }, array_values($data));
        $values = implode(",", $values);
        $sql = "INSERT INTO {$this->table} ($keys) VALUES ($values)";
        $this->execute($sql);
        return $this->db->affected_rows;
    }

    public function insertBlob($data)
    {
        $keys = implode(',', array_map(fn ($k) => "`$k`", array_keys($data)));

        // ? をカラム数分作る
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ($keys) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        // バインド
        $types = '';
        $values = [];
        foreach ($data as $value) {
            if (is_null($value)) {
                $types .= 's'; // NULL も string として bind_param する
                $values[] = null;
            } elseif (is_int($value)) {
                $types .= 'i';
                $values[] = $value;
            } elseif (is_float($value)) {
                $types .= 'd';
                $values[] = $value;
            } else {
                $types .= 's'; // string / binary
                $values[] = $value;
            }
        }

        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        return $this->db->affected_rows;
    }


    /*update(): 特定のテーブルに対してデータを更新する。
     * 引数: $data, 配列, 例：['name'=>'foo', 'age'=>18, 'tel'=>'12345']
     * 　　　$where, 条件を表す文字列, 例：'sid=k22rs999'
     * 戻り値：変更した行数
     */
    public function update($data, $where)
    {
        // 写真を含むかどうか判定
        foreach ($this->blobColumns as $col) {
            if (isset($data[$col])) {
                return $this->updateBlob($data, $where);
            }
        }

        // 通常UPDATE
        return $this->updateAnother($data, $where);
    }
    public function updateAnother($data, $where)
    {
        $setParts = [];
        foreach ($data as $k => $v) {
            $v = is_string($v) ? "'" . $this->db->real_escape_string($v) . "'" : $v;

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

    public function updateBlob($data, $where)
    {
        $setSql = implode(',', array_map(fn ($k) => "`$k` = ?", array_keys($data)));

        if (is_array($where)) {
            $whereParts = [];
            foreach ($where as $key => $value) {
                $whereParts[] = "$key = ?";
            }
            $whereSql = implode(' AND ', $whereParts);
        } else {
            $whereSql = $where;
        }

        $sql = "UPDATE {$this->table} SET {$setSql} WHERE {$whereSql}";
        $stmt = $this->db->prepare($sql);

        $types = '';
        $values = [];

        // SET の値
        foreach ($data as $value) {
            $types .= is_int($value) ? 'i' : 's';
            $values[] = $value;
        }

        // WHERE の値
        if (is_array($where)) {
            foreach ($where as $value) {
                $types .= is_int($value) ? 'i' : 's';
                $values[] = $value;
            }
        }

        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        return $stmt->affected_rows;
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
                if (is_numeric($value)) {
                    $whereParts[] = "$key = $value";
                } else {
                    $whereParts[] = "$key = '" . $this->db->real_escape_string($value) . "'";
                }
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
        return $this->getDetail("user_id='{$uid}' AND password='{$upass}'");
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
        foreach ($where as $key => $values) {
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ', $data);
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
    public function get_userlist_filtered($search_key = '', $stop_user = false, $orderby = null)
    {
        $where = [];

        if ($search_key !== '') {
            $escaped_key = $this->db->real_escape_string($search_key);
            $where[] = "("
                . "user_id LIKE '%{$escaped_key}%' OR "
                . "user_account LIKE '%{$escaped_key}%' OR "
                . "user_l_name LIKE '%{$escaped_key}%' OR "
                . "user_f_name LIKE '%{$escaped_key}%' OR "
                . "user_l_kana LIKE '%{$escaped_key}%' OR "
                . "user_f_kana LIKE '%{$escaped_key}%'"
                . ")";
        }
        
        // 停止中か通常か
        if ($stop_user) {
            $where[] = "usertype_id = 2";
        }

        // ORDER BY
        $order = '';
        if ($orderby === 'id') {
            $order = 'user_id ASC';
        } elseif ($orderby === 'address') {
            $order = "CONCAT(user_l_kana, user_f_kana) ASC";
        }

        // get_userlist に渡す
        return $this->get_userlist($where, $order);
    }


    //ユーザリスト
    function get_userlist($where = 1, $orderby = null, $limit = 0, $offset = 0)
{
    $whereStr = '';
    if (!empty($where)) {
        if (is_array($where)) {
            $parts = [];
            foreach ($where as $k => $v) {
                if (is_int($k)) {
                    $parts[] = $v;
                } else {
                    $v_escaped = $this->db->real_escape_string($v);
                    $parts[] = "$k = '$v_escaped'";
                }
            }
            $whereStr = implode(' AND ', $parts);
        } else {
            $whereStr = $where;
        }
    }

    $sql = "SELECT * FROM t_user NATURAL JOIN t_usertype";

    if ($whereStr !== '') {
        $sql .= " WHERE {$whereStr}";
    }

    return $this->query($sql, $orderby, $limit, $offset);
}


    //お気に入り店舗
    function get_favorite($user_id)
    {
        $sql = "SELECT rst_id FROM t_favorite WHERE user_id = '{$user_id}'";
        $favorites = $this->query($sql);
        $favorite = [];
        foreach ($favorites as $fav) {
            $rst_id = $fav['rst_id'];
            $rst = new Restaurant();
            $result = $rst->get_RstDetail(['rst_id'=>$rst_id]);
            if (!empty($result)) {
                $favorite[] = $result;
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
        foreach ($where as $key => $values) {
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ', $data);
        $rst = $this->getDetail($wherestr);

        $flag = (int)$rst['rst_holiday']; 
        $holidays = [];
        foreach (self::$codes['rst_holiday'] as $bit => $label) {
            if ($flag & (int)$bit) {
                $holidays[] = $label;
            }
        }
        $rst['holidays'] = $holidays;

        $flag = (int)$rst['rst_pay'];
        $pays = [];
        foreach (self::$codes['rst_pay'] as $bit => $label) {
            if ($flag & (int)$bit) {
                $pays[] = $label;
            }
        }
        $rst['pays'] = $pays;

        $rst['discount_label'] = self::getValue($rst['discount'], 'rst_discount', '不明');

        $rst_id = $rst['rst_id'];
        $sql = "SELECT * FROM t_rst_genre NATURAL JOIN t_genre WHERE rst_id = {$rst_id}";
        $rst['rst_genre'] = $this->query($sql);
        return $rst;
    }
    function rst_insert($data)
    {
        $rst = $this->insert($data);
        return $this->db->insert_id;
    }
}
class Genre extends Model
{
    protected $table = "t_rst_genre";
    function save_genre($rst_id, $genres)
    {
        // 既存のジャンルを削除
        $this->delete(['rst_id' => $rst_id]);

        $rows = 0;
        // 新しいジャンルを挿入
        foreach ($genres as $genre_id) {
            $rows += $this->insert([
                'rst_id'   => $rst_id,
                'genre_id' => intval($genre_id)
            ]);
        }
        return $rows;
    }
}
class Review extends Model
{
    protected $table = "t_review";
    function get_RevDettail($where)
    {
        $data = [];
        foreach ($where as $key => $values) {
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ', $data);
        $rev = $this->getDetail($wherestr);
        return $rev;
    }
}

class Report extends Model
{
    protected $table = "t_report";
    function get_RepoDettail($where)
    {
        $data = [];
        foreach ($where as $key => $values) {
            $data[] = "$key = '$values'";
        }
        $wherestr = implode(' AND ', $data);
        $repo = $this->getDetail($wherestr);
        $repo['report_reason'] = $this->getValue($repo['report_reason'], 'report_reason');
        return $repo;
    }
}

class Favorite extends Model
{
    protected $table = "t_favorite";
}
