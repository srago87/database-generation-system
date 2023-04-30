<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Configure Database Model Storage</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- navbar at top of page -->
    <div class="navbar">
        <ul>
                <a href="../index.php">
                    <li>
                        Home
                    </li>
                </a>

                <a href="../new-db-model/new-db-model.php">
                    <li>
                        Create a new database model
                    </li>
                </a>
       
                <a href="../export/export-db-model.php">
                    <li>
                        Export
                    </li>
                </a>

                <a href="configure-user-data-store.php">
                    <li>
                        Configure
                    </li>
                </a>
        </ul>
    </div>

    <div>
        DROP DATABASE IF EXISTS UserDataStore; <br><br>

        CREATE DATABASE UserDataStore; <br><br>

        USE UserDataStore; <br><br>

        CREATE TABLE db_models(
        <div class="indent">
            model_id INT NOT NULL AUTO_INCREMENT, <br>
            sql_language VARCHAR(20), <br>
            db_name VARCHAR(45) NOT NULL, <br>
            PRIMARY KEY(model_id)
        </div>
        ); <br><br>

        CREATE TABLE db_tables(
        <div class="indent">
            db_model_id INT NOT NULL, <br>
            table_id INT NOT NULL AUTO_INCREMENT, <br>
            table_name VARCHAR(45) NOT NULL, <br>
            PRIMARY KEY(table_id), <br>
            FOREIGN KEY(db_model_id) REFERENCES db_models(model_id)
        </div>
        ); <br><br>

        CREATE TABLE db_columns( <br>
        <div class="indent">
            db_table_id INT NOT NULL, <br>
            column_name VARCHAR(45) NOT NULL, <br>
            data_type VARCHAR(45) NOT NULL, <br>
            PK TINYINT NOT NULL, <br>
            NN TINYINT NOT NULL, <br>
            UQ TINYINT NOT NULL, <br>
            AI TINYINT NOT NULL, <br>
            default_value VARCHAR(45), <br>
            PRIMARY KEY(db_table_id, column_name), <br>
            FOREIGN KEY(db_table_id) REFERENCES db_tables(table_id) <br>
        </div>
        ); <br><br>

        CREATE TABLE foreign_keys( <br>
        <div class="indent">
            key_id INT NOT NULL AUTO_INCREMENT, <br>
            key_table_id INT NOT NULL, <br>
            key_column_name VARCHAR(45) NOT NULL, <br>
            reference_table_id INT NOT NULL, <br>
            reference_column_name VARCHAR(45) NOT NULL, <br>
            PRIMARY KEY(key_id), <br>
            FOREIGN KEY(key_table_id, key_column_name) REFERENCES db_columns(db_table_id, column_name), <br>
            FOREIGN KEY(reference_table_id, reference_column_name) REFERENCES db_columns(db_table_id, column_name) <br>
        </div>
        );
    </div>

</body>
</html>