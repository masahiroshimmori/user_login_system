-- パスワード再設定用のトークン管理テーブル
DROP TABLE IF EXISTS reminder_token;
CREATE TABLE reminder_token(
`token` varbinary(255) NOT NULL COMMENT '認識するためのID',
`user_id` bigint unsigned NOT NULL COMMENT '識別するためのID',
`created` datetime NOT NULL COMMENT '作成日時',
PRIMARY KEY(`token`)
)CHARACTER SET 'utf8mb4', ENGINE= InnoDB, COMMENT '1レコードが1ユーザーの1回のパスワード変更用を意味するテーブル'