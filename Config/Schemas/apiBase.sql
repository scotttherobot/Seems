CREATE TABLE `users` (
   `userid` INT(11) NOT NULL AUTO_INCREMENT,
   `username` VARCHAR(25) NOT NULL,
   `firstname` VARCHAR(20) NOT NULL,
   `lastname` VARCHAR(20) NOT NULL,
   `email` VARCHAR(50) NOT NULL,
   `sign_up_date` INT(11) NOT NULL,
   `pw_hash` CHAR(128),
   PRIMARY KEY (`userid`),
   KEY `username` (`username`),
   KEY `sign_up_date` (`sign_up_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sessions` (
   `userid` INT(11) NOT NULL,
   `api_key` VARCHAR(32) NOT NULL,
   `expire` INT(11) NOT NULL,
   PRIMARY KEY (`userid`),
   KEY `api_key` (`api_key`),
   CONSTRAINT `keys_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
CREATE TABLE `user_settings` (
   `userid` INT(11) NOT NULL,
   `key` VARCHAR(100),
   `value` VARCHAR(100),
   PRIMARY KEY (`userid`),
   CONSTRAINT `settings_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `media` (
   `medid` INT(11) NOT NULL AUTO_INCREMENT,
   `userid` INT(11) NOT NULL,
   `date` INT(11) NOT NULL,
   `type` ENUM('IMAGE', 'VIDEO') NOT NULL,
   `fname` VARCHAR(200) NOT NULL,
   `src` VARCHAR(200),
   PRIMARY KEY (`medid`),
   KEY `userid` (`userid`),
   CONSTRAINT `media_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `profiles` (
   `userid` INT(11) NOT NULL,
   `date` INT(11) NOT NULL,
   `avatar` INT(11) NOT NULL,
   `about` TEXT,
   PRIMARY KEY (`userid`),
   CONSTRAINT `profiles_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `profiles_avatar_fk` FOREIGN KEY (`avatar`) REFERENCES `media` (`medid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
