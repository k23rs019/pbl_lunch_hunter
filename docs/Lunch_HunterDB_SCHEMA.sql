-- Project Name : Lunch_Hunter
-- Date/Time    : 2025/12/01
-- RDBMS Type   : MySQL

-- 口コミ
create table t_review(
    review_id SERIAL not null comment '口コミID'
    , eval_pint INT not null comment '評価点'
    , review_comment VARCHAR(250) comment 'コメント'
    , rst_id BIGINT not null comment '書き込まれた店舗'
    , user_id CHAR(16) not null comment '登録ユーザID'
    , photo1 BLOB comment '写真１'
    , photo2 BLOB comment '写真２'
    , photo3 BLOB comment '写真３'
    , rev_state BOOLEAN not null comment'表示状態'
    , constraint t_review_pkc primary key (review_id)
) comment '口コミ';
-- 通報口コミ
create table t_report(
    report_id SERIAL not null comment '通報ID'
    , review_id BIGINT not null comment '口コミID'
    , report_time TIMESTAMP not null comment '通報時間'
    , user_id CHAR(16) not null comment 'ユーザID'
    , report_reason INT not null comment '通報理由'
    , report_state INT not null comment '処理状態'
    , constraint t_report_pkc primary key (report_id)
) comment '通報口コミ';
-- 店舗情報
create table t_rstinfo(
    rst_id SERIAL not null comment '店舗ID'
    , rst_name VARCHAR(64) not null comment'店舗名'
    , rst_address VARCHAR(256) not null comment '住所'
    , start_time TIME not null comment '営業開始時間'
    , end_time TIME not null comment '営業終了時間'
    , tel_num VARCHAR(32) not null comment '電話番号'
    , rst_holiday INT not null comment '店休日'
    , rst_pay INT comment '支払い方法'
    , rst_infl VARCHAR(2048) comment '店舗URL'
    , photo1 BLOB comment '写真'
    , user_id CHAR(16) not null comment '登録ユーザID'
    , discount BOOLEAN not null comment '割引登録'
    , constraint t_rstinfo_pkc primary key (rst_id)
) comment '店舗情報';
-- ジャンル
create table t_rst_genre(
    rst_id BIGINT not null comment '登録店舗ID'
    , genre_id INT not null comment 'ジャンル種別ID'
) comment 'ジャンル';
-- ジャンル種別
create table t_genre(
    genre_id INT not null comment 'ジャンル種別ID'
    , genre CHAR(16) not null comment 'ジャンル名'
    , constraint t_genre_pkc primary key (genre_id)
) comment 'ジャンル種別';
-- ユーザ
create table t_user(
    user_id CHAR(16) not null comment 'ユーザID'
    , user_l_name VARCHAR(16) not null comment '本名＿姓'
    , user_f_name VARCHAR(16) not null comment '本名＿名'
    , user_l_kana VARCHAR(16) not null comment '本名＿姓（カナ）'
    , user_f_kana VARCHAR(16) not null comment '本名＿名（カナ）'
    , user_account VARCHAR(32) not null comment 'アカウント名'
    , password VARCHAR(32) not null comment 'パスワード'
    , usertype_id INT not null comment 'ユーザ種別ID'
    , constraint t_user_pkc primary key (user_id)
) comment 'ユーザ';
-- ユーザ種別
create table t_usertype(
    usertype_id INT not null comment 'ユーザ種別ID'
    , usertype VARCHAR(16) not null comment 'ユーザ種別'
    , constraint t_usertype_pkc primary key (usertype_id)
) comment 'ユーザ種別';
-- お気に入り店舗
create table t_favorite(
    user_id CHAR(16) not null comment '登録ユーザID'
    , rst_id BIGINT not null comment '登録店舗ID'
) comment 'お気に入り店舗';