ALTER TABLE tb_translate ADD COLUMN IF NOT EXISTS translate_date DATETIME;
ALTER TABLE tb_translate ADD COLUMN IF NOT EXISTS translate_time_from VARCHAR(50);
ALTER TABLE tb_translate ADD COLUMN IF NOT EXISTS translate_time_to VARCHAR(50);