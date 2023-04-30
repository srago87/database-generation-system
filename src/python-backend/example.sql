
CREATE TABLE authors (
	`authorPK` INTEGER NOT NULL AUTO_INCREMENT, 
	`firstName` VARCHAR(45) NOT NULL, 
	`lastName` VARCHAR(45) NOT NULL, 
	`emailAddress` VARCHAR(45) NOT NULL, 
	`birthDateTime` DATETIME NOT NULL, 
	PRIMARY KEY (`authorPK`)
)



CREATE TABLE books (
	`bookPK` INTEGER NOT NULL AUTO_INCREMENT, 
	name VARCHAR(45) NOT NULL, 
	version VARCHAR(45) NOT NULL, 
	`publishedDate` DATE, 
	PRIMARY KEY (`bookPK`)
)



CREATE TABLE book2author (
	`book2authorPK` INTEGER NOT NULL AUTO_INCREMENT, 
	`authorFK` INTEGER NOT NULL, 
	`bookFK` INTEGER NOT NULL, 
	PRIMARY KEY (`book2authorPK`), 
	FOREIGN KEY(`authorFK`) REFERENCES authors (`authorPK`), 
	FOREIGN KEY(`bookFK`) REFERENCES books (`bookPK`)
)


