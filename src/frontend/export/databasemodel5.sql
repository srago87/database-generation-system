ATTACH DATABASE myDB.db AS myDB;

CREATE TABLE "tableA" (
	"colA1" INTEGER, 
	"colA2" VARCHAR(45), 
	PRIMARY KEY ("colA1")
);



CREATE TABLE "tableB" (
	"colB1" INTEGER, 
	FOREIGN KEY("colB1") REFERENCES "tableA" ("colA1")
);


