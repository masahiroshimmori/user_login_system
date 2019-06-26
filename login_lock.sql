-- ユーザロックテーブルの作成
DROP TABLE IF EXISTS user_login_lock;
CREATE TABLE user_login_lock(
`user_id` bigint unsigned NOT NULL COMMENT '識別するためのID',
`error_count` tinyint unsigned NOT NULL COMMENT 'ログインエラーの回数（ログイン成功したらリセット）',
`lock_time` datetime NOT NULL COMMENT 'ロック時間。0000-00-00 00:00:00 なら「ロックされていない」',
PRIMARY KEY(`user_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが1ユーザのロック状態を意味するテーブル';