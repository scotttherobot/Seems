CREATE TABLE IF NOT EXISTS `content` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `userid` INT(11) NOT NULL,
   `date` INT(11) NOT NULL,
   `type` ENUM('POST', 'PAGE') NOT NULL DEFAULT 'POST',
   `published` INT(1) NOT NULL DEFAULT 0,
   `title` TEXT NOT NULL,
   `body` TEXT NOT NULL,
   `leader` INT(11) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `userid` (`userid`),
   CONSTRAINT `content_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE,
   CONSTRAINT `content_leader_fk` FOREIGN KEY (`leader`) REFERENCES `media` (`medid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
