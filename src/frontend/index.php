<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        Homepage
    </title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    

    <div class="navbar">
        <ul>

                <a href="index.php">
                    <li>
                        Home
                    </li>
                </a>

                <a href="new-db-model/new-db-model.php">
                    <li>
                        Create a new database model
                    </li>
                </a>
       
                <a href="export/export-db-model.php">
                    <li>
                        Export
                    </li>
                </a>

                <a href="configure/configure-user-data-store.php">
                    <li>
                        Configure
                    </li>
                </a>
        </ul>
    </div>

    <div class="title">
        <h1>Sonalysts Database Generation System</h1>
    </div> 

    <h4>Important!</h4>
    <p>
        Our tool relies on a MySQL database to store your database models. 
        If this is your first time using the DBGS, you can click the link below
        to download a MySQL script that will help you configure your instance of
        the Database Generation System. Alternatively, you can copy and paste
        the same script from the <i>Configure</i> page.
    </p>

    <a href="../user-data-store/generate-db-model-store.sql" download>MySQL Script Download</a> <br><br>

</body>
</html>