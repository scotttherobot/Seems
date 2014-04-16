CREATE TABLE IF NOT EXISTS `media_sizes` (
   `medid` INT(11) NOT NULL,
   `small_fname` VARCHAR(200) DEFAULT NULL,
   `small_src` VARCHAR(200) DEFAULT NULL,
   `medium_fname` VARCHAR(200) DEFAULT NULL,
   `medium_src` VARCHAR(200) DEFAULT NULL,
   PRIMARY KEY(`medid`),
   CONSTRAINT `media_sizes_medid_fk` FOREIGN KEY (`medid`) REFERENCES `media` (`medid`) 
   ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
