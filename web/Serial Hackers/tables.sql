CREATE DATABASE hackers_db;
use hackers_db;
CREATE TABLE hackers (
  handle varchar(30) NOT NULL,
  age numeric(2,0) NOT NULL,
  quote text(200) NOT NULL,
  photo text(50) NOT NULL,
  l bigint DEFAULT 0,
  d bigint DEFAULT 0,
  reverse int NOT NULL DEFAULT 0,
  web int NOT NULL DEFAULT 0,
  guessing int NOT NULL DEFAULT 0, 
  beer int NOT NULL DEFAULT 0,
  PRIMARY KEY (handle)
); 
CREATE TABLE secret(
  flag varchar(64) NOT NULL
);
CREATE USER 'r'@'localhost' IDENTIFIED BY '*****';
GRANT SELECT ON hackers_db.hackers TO 'r'@'localhost';
GRANT UPDATE (l,d) ON hackers_db.hackers TO 'r'@'localhost';
GRANT SELECT ON hackers_db.secret TO 'r'@'localhost';
INSERT INTO secret (flag) VALUES('INS{S3r14l_k1ll3R}');
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Axl Torvalds',51,'He exists in a world beyond your world.','images/axl.png',100,100,10,20);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Acid Burn',20,'Never send a boy to do a woman job','images/acid.png',40,60,20,10);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Phantom Phreak',20, 'So, uh, what is your interest in Kate Libby, eh? Academic? Purely sexual?','images/phantom.png', 10,10,50,30);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Lord Nikon',26,'You are in the butter zone now, baby!','images/lord.png', 10,10,50,30);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('The Plague',44,'Kid, do not threaten me. There are worse things than death, and uh, I can do all of them.', 'images/plague.png', 40,80,50,75);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Cereal Killer',25,'I kinda feel like God.', 'images/cereal.png', 20,70,30,90);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Zero Cool',18,'Mess with the best, Die like the rest', 'images/zero.png', 40,90,30,20);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('The Mac Guy', 36, 'Tip of the day: Encrypt your laptop, keep your data safe','images/mac.png',30,20,40,10);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Stan',51,'Nothing is impossible.','images/stan.png',30,70,100,60);
INSERT INTO hackers (handle, age, quote, photo, web, reverse, guessing, beer) VALUES('Warlock', 34, ' Why did you bring a cop to my command center?','images/warlock.png',20,20,30,80);
