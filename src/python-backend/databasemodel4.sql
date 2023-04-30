
CREATE TABLE `tableA` (
	`colA1` INTEGER, 
	`colA2` INTEGER, 
	PRIMARY KEY (`colA1`)
);



CREATE TABLE `tableB` (
	`colB3` INTEGER, 
	PRIMARY KEY (`colB3`), 
	FOREIGN KEY(`colB3`) REFERENCES `tableA` (`colA1`)
);


