ALTER TABLE tb_translate ADD COLUMN  translate_date DATETIME;
ALTER TABLE tb_translate ADD COLUMN  translate_time_from VARCHAR(50);
ALTER TABLE tb_translate ADD COLUMN  translate_time_to VARCHAR(50);



CREATE TABLE IF NOT EXISTS cover_sign_status (
sign_status_id INT AUTO_INCREMENT PRIMARY KEY,
sign_status_name VARCHAR(255) NOT NULL
)
CHARACTER SET 'utf8mb4' 
COLLATE 'utf8mb4_general_ci'
ENGINE=INNODB;





INSERT INTO cover_sign_status (sign_status_name)
VALUES
('Նոր'),
('Ուղարկվել է հաստատման'),
('Ուղարկվել է թարգմանության'),
('Ստացվել է պատասխան');

INSERT INTO tb_file_type (file_type, file_filter)
VALUES
('Մկրտության վկայական', '2'),
('Վարորդական վկայական', '2'),
('Անձնագրի թարգմանություն', '2'),
('Ներքին անձնագիր', '1'),
('Քրեական գործ', '1'),
('Կրթության վկայական', '2');







ALTER TABLE tb_translate ADD COLUMN  sign_status INT(1);
ALTER TABLE tb_translate ADD COLUMN  mailed_to_translators DATETIME;

CREATE TABLE IF NOT EXISTS tb_translate_sign_statuses (
status_id INT AUTO_INCREMENT PRIMARY KEY,
sign_status INT(1) NOT NULL,
sign_status_name VARCHAR(255) NOT NULL
)
CHARACTER SET 'utf8mb4' 
COLLATE 'utf8mb4_general_ci'
ENGINE=INNODB;

INSERT INTO tb_translate_sign_statuses (sign_status,sign_status_name)
VALUES
('0','Ապաակտիվացված'),
('2','Ուղարկվել է բաժնի պետին'),
('3','Ուղարկվել է թարգմանչական ընկ․'),
('4','Հաստատված'),
('5','Կատարված');
