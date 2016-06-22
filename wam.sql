CREATE TABLE `word_count_cache` (
    `pageid` INT NOT NULL ,
    `wiki` VARCHAR(64) NOT NULL ,
    `last_updated` DATETIME NOT NULL ,
    `word_count` INT(8) NOT NULL
) ;
ALTER TABLE `word_count_cache` ADD UNIQUE `idx`(`pageid`, `wiki`);

CREATE TABLE `participant_stat_cache` (
  `username` varchar(191) NOT NULL,
  `wiki` varchar(64) NOT NULL,
  `last_updated` DATETIME NOT NULL ,
  `art_count` int(8) NOT NULL,
  `pending_art` int(8) NOT NULL,
  `valid_art` int(8) NOT NULL,
  `invalid_art` int(8) NOT NULL
);
ALTER TABLE `participant_stat_cache` ADD UNIQUE( `username`, `wiki`);
