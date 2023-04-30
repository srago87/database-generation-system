<?php

//userdatastore DB connection
include "db_conn.php";


//insert into db_models table
if(isset($_POST['dbModel'])){
    $dbModel = $_POST['dbModel'];
    $name = $dbModel['db_name'];
    $lang = $dbModel['sql_language'];
    $sql = "INSERT INTO db_models (db_name, sql_language) VALUES ('$name', '$lang')";
    try{
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $model_id = $conn->lastInsertId();
    }
    catch(Exception $e){
        var_dump($e->getMessage());
    }
}

//keeps track of table_ids for db_columns foreign key
$table_ids = array();

//insert into db_tables table
if(isset($_POST['tableNames'])){
    $table_names = $_POST['tableNames'];
    foreach($table_names as $t_name){
        $sql = "INSERT INTO db_tables (db_model_id, table_name) VALUES ($model_id, '$t_name')";
        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            array_push($table_ids, $conn->lastInsertId());
        }
        catch(Exception $e){
            var_dump($e->getMessage());
        }
    }
}

//insert into db_columns table
if(isset($_POST['cols'])){
    $cols = $_POST['cols'];
    
    foreach($cols as $c){
       
        $col_name = $c['column_name'];
        $table_num = $c['table_num'];
        $t_id = $table_ids[$table_num];
        $dt = $c['data_type'];
        $pk = $c['PK'];
        $nn = $c['NN'];
        $uq = $c['UQ'];
        $ai = $c['AI'];
        $def_val = $c['default_value'];

        //format max chars for data types
        if(substr($dt, -2, 2) == "()"){
            switch ($dt){
                case "INT()":
                    $dt = "INT";
                    break;
                case "VARCHAR()":
                    $dt = "VARCHAR(45)";
                    break;
                case "DECIMAL()":
                    $dt = "DECIMAL";
                    break;
            }
        }

        //determine whether default_value field is a number, string, or null
        if($def_val == ""){
            $sql = "INSERT INTO db_columns (db_table_id, column_name, data_type, PK, NN, UQ, AI) " .
            "VALUES ($t_id, '$col_name', '$dt', $pk, $nn, $uq, $ai)";
        }
        elseif(substr($dt, 0, 3) == "INT" || substr($dt, 0, 4) == "DECI"){
            $sql = "INSERT INTO db_columns (db_table_id, column_name, data_type, PK, NN, UQ, AI, default_value) " .
            "VALUES ($t_id, '$col_name', '$dt', $pk, $nn, $uq, $ai, $def_val)"; 
        }
        else{
            $sql = "INSERT INTO db_columns (db_table_id, column_name, data_type, PK, NN, UQ, AI, default_value) " .
            "VALUES ($t_id, '$col_name', '$dt', $pk, $nn, $uq, $ai, '$def_val')";
        }

        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        catch(Exception $e){
            var_dump($e->getMessage());
        }
    }
}


//insert into foreign_keys table
if(isset($_POST["foreignKeys"])){
    
    $fks = $_POST["foreignKeys"];

    foreach($fks as $fk){
        $key = $fk["key_column"];
        $key = explode('.', $key);
        $key_table_name = $key[0];
        $key_column_name = $key[1];

        $ref = $fk["reference_column"];
        $ref = explode('.', $ref);
        $ref_table_name = $ref[0];
        $ref_column_name = $ref[1];

        $sql = "SELECT table_id FROM db_tables WHERE db_model_id = $model_id " .
        "AND table_name = '$key_table_name'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $key_table_id = $res["table_id"];

        $sql = "SELECT table_id FROM db_tables WHERE db_model_id = $model_id " .
        "AND table_name = '$ref_table_name'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $ref_table_id = $res["table_id"];

        $sql = "INSERT INTO foreign_keys (key_table_id, key_column_name, " .
        "reference_table_id, reference_column_name) VALUES ($key_table_id, " .
        "'$key_column_name', $ref_table_id, '$ref_column_name')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
}
?>

<script>
    alert("Your database model has been saved");
</script>