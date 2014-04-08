CREATE TABLE IF NOT EXISTS `galleries` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `title` VARCHAR(140) NOT NULL,
   `userid` INT(11) NOT NULL,
   `published` INT(1) NOT NULL DEFAULT 1,
   `leader` INT(11) DEFAULT NULL,
   PRIMARY KEY (`id`),
   CONSTRAINT `galleries_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE,
   CONSTRAINT `galleries_leader_fk` FOREIGN KEY (`leader`) REFERENCES `media` (`medid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gallery_entries` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `gallery_id` INT(11) NOT NULL,
   `medid` INT(11) NOT NULL,
   `caption` TEXT DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY (`gallery_id`),
   CONSTRAINT `entries_gallery_fk` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) 
    ON UPDATE CASCADE ON DELETE CASCADE,
   CONSTRAINT `entries_medid_fk` FOREIGN KEY (`medid`) REFERENCES `media` (`medid`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
