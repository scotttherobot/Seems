CREATE TABLE IF NOT EXISTS `settings` (
   `id` int(7) NOT NULL AUTO_INCREMENT,
   `name` VARCHAR(100) NOT NULL,
   `value` VARCHAR(255) NOT NULL,
   PRIMARY KEY(`id`),
   KEY(`name`)
)
