# sonalysts-dbgs

Setup MySQL Server connection for UserDataStore database in src/user-data-store/php/db_conn.php. You must also enter the same connection information in src/python-backend/ddl_gen.py and src/python-backend/php_gen.py

We never ended up finishing the login page or CRUD interface. In its current state the app can generate DDL scripts (.sql format) in MySQL, MS SQL Server, PostgreSQL, SQlite, and Oracle. It does this mostly using python's sqlalchemy library.