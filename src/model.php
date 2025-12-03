<?php
class Model
{
    protected $table;
    protected $db;
    protected static $conf = [
        'host'=>'mysql','user'=>'root','pass'=>'root','dbname'=>'test'
    ];

    protected static $codes = [
        'rst_holiday'=>['1'=>'日','2'=>'月','4'=>'火','8'=>'水','16'=>'木','32'=>'金','64'=>'土'],
        'rst_pay'=>['1'=>'現金','2'=>'QRコード','4'=>'電子マネー','8'=>'クレジットカード']
    ];

    function __construct($conf = null){
        self::$conf = $conf?? self::$conf;
        $conn= new mysqli(
            self::$conf['host'], self::$conf['user'],self::$conf['pass'],self::$conf['dbname']
        );
        if($conn->connect_errno){
            die($conn->connect_error);
        }
        $conn->set_charset('utf8');
        $this->db = $conn;
    }
    public static function setDbConf($conf){
        self::$conf = $conf;
    }

    // コードから、値を求めて返す。
    // 例：ユーザ種別2の場合、getValue(2, 'user_type')　結果：'ゲスト'
    public static function getValue($code, $category, $default=null){
        return self::$codes[$category][$code] ?? $default;
    }

    // コードの定義を返す。
    public static function getCodes($category)
    {
        return self::$codes[$category] ?? []; 
    }

    // 検索用SQL文を実行し、問合せ結果を返す。エラーなら処理中止
    // ソート$orderby、表示範囲$limit, $offsetを指定できる
    public function query($sql, $orderby=null, $limit=0, $offset=0){
        $sql .= $orderby ? " ORDER BY {$orderby}" : '';
        $sql .= $limit > 0 ? " LIMIT {$limit} OFFSET {$offset}" : '';
        $rs = $this->db->query($sql);
        if (!$rs) die ('DBエラー: ' . $sql . '<br>' . $this->db->error);
        return $rs->fetch_all(MYSQLI_ASSOC);
    }

    // 更新用SQL文を実行する。エラーなら処理中止
    public function execute($sql){
        $rs = $this->db->query($sql);
        if (!$rs) die ('DBエラー: ' . $sql . '<br>' . $this->db->error);
    }

        
    //getList(): 特定のテーブルに対し一覧表示用データを検索し結果をすべて返す
    public function getList($where=1, $orderby=null, $limit=0, $offset=0){
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        return $this->query($sql,$orderby, $limit, $offset);
    }

    //getDetail(): 特定のテーブルに対して詳細表示用データを検索し１件のみ返す
    public function getDetail($where){
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        $data = $this->query($sql);
        return $data[0]??[];
    }
    
    /*insert(): 特定のテーブルに対しデータを1行追加する。
     * 引数: $data, 配列, 例：['name'=>'foo', 'age'=>18, 'tel'=>'12345'] 
     * 戻り値：追加した行数
     */
    public function insert($data){
        $keys = implode(',', array_keys($data));
        $values = array_map(fn($v)=>is_string($v) ? "'{$v}'" : $v, array_values($data));
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
    public function update($data, $where){
        $keys = array_keys($data);
        $values = array_map(fn($v)=>is_string($v) ? "'{$v}'" : $v, array_values($data));
        $values = array_map(fn($k, $v)=>"{$k}={$v}", array_combine($keys, $values));
        $sql = "UPDATE {$this->table} SET {$values} WHERE {$where}";
        $this->execute($sql);
        return $this->db->affected_rows;
    }
    
    /* delete(): 特定のテーブルに対して条件を満たすデータを削除する。
     * 引数: $where, 条件を表す文字列, 例：'sid=k22rs999'
     * 戻り値：変更した行数
     */
    public function delete($where){
        $sql = "DELETE FROM {$this->table} WHERE {$where}";
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
}

class Restaurant extends Model
{
    protected $table = "t_rstinfo";
    
}

class Review extends Model
{
    protected $table = "t_review";
}