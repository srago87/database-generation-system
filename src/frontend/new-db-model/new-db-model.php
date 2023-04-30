<?php 
    include "../../user-data-store/php/db_conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        New Database Model
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dropdown.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js">
    </script>

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

                <a href="new-db-model.php">
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
    
    <label for="db-name" id="db-name-label">Database Name:</label>
    <input type="text" id="db-name"><br>

    <div id="page-content">
        <div id="control-panel">
            <button id="new-table-btn">New Table</button><br>
            <button id="fk-btn">New Foreign Key</button><br>
            <button id="save-btn">Save Database Model</button>

            <h3>Select SQL Language</h3>
            <input type="radio" id="mysql-rb" name="sql-lang" class="lang-btn" onclick="selectLabel()">
            <label for="mysql-rb" class="lang-label">MySQL</label><br>

            <input type="radio" id="postgre-sql-rb" name="sql-lang" class="lang-btn" onclick="selectLabel()">
            <label for="postgre-sql-rb" class="lang-label">PostgreSQL</label><br>

            <input type="radio" id="ms-sql-server-rb" name="sql-lang" class="lang-btn" onclick="selectLabel()">
            <label for="ms-sql-server-rb" class="lang-label">Microsoft SQL Server</label><br>

            <input type="radio" id="oracle-rb" name="sql-lang" class="lang-btn" onclick="selectLabel()">
            <label for="oracle-rb" class="lang-label">Oracle</label><br>

            <input type="radio" id="sqlite-rb" name="sql-lang" class="lang-btn" onclick="selectLabel()">
            <label for="sqlite-rb" class="lang-label">SQLite</label>
        </div>

        <div id="tables-container"></div>

        <div id="fk-container">
            <button id="refresh-fks-btn" title="Refresh Foreign Keys">
                <i class="fa fa-refresh"></i>
            </button>
        </div>
        
    </div>

    <div id="data-load"></div>


    <script src="js/format.js"></script>
    <script src=js/new-table.js></script>
    <script src="js/foreign-keys.js"></script>
    <script src="js/language-highlight.js"></script>
    <script src="js/objects.js"></script>
    <script src="js/create-db-model.js"></script>
</body>
</html>