DROP DATABASE IF EXISTS UserDataStore;

CREATE DATABASE UserDataStore;

USE UserDataStore;

CREATE TABLE db_models(
	model_id INT NOT NULL AUTO_INCREMENT,
    sql_language VARCHAR(20),
    db_name VARCHAR(45) NOT NULL,
    PRIMARY KEY(model_id)
);

CREATE TABLE db_tables(
	db_model_id INT NOT NULL,
	table_id INT NOT NULL AUTO_INCREMENT,
    table_name VARCHAR(45) NOT NULL,
    PRIMARY KEY(table_id),
    FOREIGN KEY(db_model_id) REFERENCES db_models(model_id)
);

CREATE TABLE db_columns(
	db_table_id INT NOT NULL,
    column_name VARCHAR(45) NOT NULL,
    data_type VARCHAR(45) NOT NULL,
    PK TINYINT NOT NULL,
    NN TINYINT NOT NULL,
    UQ TINYINT NOT NULL,
    AI TINYINT NOT NULL,
    default_value VARCHAR(45),
    PRIMARY KEY(db_table_id, column_name),
    FOREIGN KEY(db_table_id) REFERENCES db_tables(table_id)
);

CREATE TABLE foreign_keys(
	key_id INT NOT NULL AUTO_INCREMENT,
    key_table_id INT NOT NULL,
    key_column_name VARCHAR(45) NOT NULL,
    reference_table_id INT NOT NULL,
    reference_column_name VARCHAR(45) NOT NULL,
    PRIMARY KEY(key_id),
    FOREIGN KEY(key_table_id, key_column_name) REFERENCES db_columns(db_table_id, column_name),
    FOREIGN KEY(reference_table_id, reference_column_name) REFERENCES db_columns(db_table_id, column_name)
);