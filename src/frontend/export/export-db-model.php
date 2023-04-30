<?php 
    include "../../user-data-store/php/db_conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Export Page</title>

    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js">
    </script>

</head>
<body>

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

                <a href="../configure/configure-user-data-store.php">
                    <li>
                        Configure
                    </li>
                </a>
        </ul>
    </div>

    <h2>Select the DB model you wish to export</h2>
    <h3>My Database Models</h3>
    
    <?php 
        $sql = "SELECT model_id, db_name, sql_language FROM db_models";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
    ?>

    <table id="db-models-table">
        <tr>
            <th>Database Name</th>
            <th>Model Id</th>
            <th>SQL Language</th>
        </tr>
    </table>

    <script>
        let table = document.querySelector("#db-models-table");
        var res = <?php echo json_encode($res); ?>;

        res.forEach((row) => {
            let tr = document.createElement("tr");
            let tdName = document.createElement("td");
            let name = document.createTextNode(row["db_name"])
            tdName.appendChild(name);

            let tdModelId = document.createElement("td");
            let modelId = document.createTextNode(row["model_id"])
            tdModelId.appendChild(modelId)

            let tdLang = document.createElement("td");
            let lang = document.createTextNode(row["sql_language"]);
            tdLang.appendChild(lang);

            tr.appendChild(tdName);
            tr.appendChild(tdModelId)
            tr.appendChild(tdLang);
            table.appendChild(tr);
        })
    </script>
    <br><br>

    <form method="post">
        <label for="model_id">Enter Model ID for Export:</label><br>
        <input type="text" id="modelid" name="modelid"><br><br>
        <input type="submit" value="Generate SQL Script" name="DDL">
        <input type="submit" value ="Enable CRUD operations" name="CRUD">
    </form>

    <?php
    if(isset($_POST['DDL']))
    {
        $output1 = shell_exec("python3 ../../python-backend/ddl_gen.py " . strval($_POST['modelid']));
        $output2 = shell_exec("python ../../python-backend/ddl_gen.py " . strval($_POST['modelid']));
        $filename = "databasemodel" . strval($_POST['modelid']) . ".sql";
        echo $output1;
        echo $output2;
        echo nl2br(file_get_contents($filename));
    }
    if (isset($_POST['CRUD'])){
        $output3 = shell_exec("python3 ../../python-backend/php_gen.py " . strval($_POST['modelid']));
        $output4 = shell_exec("python ../../python-backend/php_gen.py " . strval($_POST['modelid']));
        echo $output3;
        echo $output4;
    }
    ?>
    

</body>
</html>