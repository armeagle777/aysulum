ALTER TABLE tb_translate ADD COLUMN IF NOT EXISTS translate_date DATETIME;
ALTER TABLE tb_translate ADD COLUMN IF NOT EXISTS translate_time_from VARCHAR(50);
ALTER TABLE tb_translate ADD COLUMN IF NOT EXISTS translate_time_to VARCHAR(50);

CREATE TABLE IF NOT EXISTS cover_sign_status (
sign_status_id INT AUTO_INCREMENT PRIMARY KEY,
sign_status_name VARCHAR(255) NOT NULL
)  ENGINE=INNODB;

INSERT INTO cover_sign_status (sign_status_name)
VALUES
('Նոր'),
('Ուղարկվել է հաստատման'),
('Ուղարկվել է թարգմանության'),
('Ստացվել է պատասխան');