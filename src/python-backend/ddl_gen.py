import sqlalchemy as sqla
from sqlalchemy import Table, Column, Integer, String, ForeignKey, DateTime, Date, SmallInteger, Float, Time
import sys
import mysql.connector as ms
import cgi

##Auxilary function for generating SQL script without running automatically
def dump(sql, *multiparams, **params):
    print(sql.compile(dialect=engine.dialect))


##Auxilary function for checking datatype
def check_data_type(dtype):
    if "tiny" in dtype.lower():
        return SmallInteger
    elif "varchar" in dtype.lower():
        ind1 = dtype.index('(')
        ind2 = dtype.index(')')
        length = dtype[ind1+1:ind2]
        return String(int(length))
    elif "int" in dtype.lower():
        return Integer
    elif "float" in dtype.lower():
        return Float
    elif "date" in dtype.lower():
        return Date
    elif "datetime" in dtype.lower():
        return DateTime
    elif "timestamp" in dtype.lower():
        return Time

if __name__ == "__main__":
    ##Connecting to our project database
    try:
        cnx = ms.connect(user='', password='',
                                    host='',
                                    port='',
                                    database='UserDataStore')
    except ms.Error as err:
        if err.errno == ms.errorcode.ER_ACCESS_DENIED_ERROR:
            print("Something is wrong with your user name or password")
        elif err.errno == ms.errorcode.ER_BAD_DB_ERROR:
            print("Database does not exist")
        else:
            print(err)

    cursor = cnx.cursor(buffered=True)
    cursor_fk = cnx.cursor()

    #form = cgi.FieldStorage()
    model_id = sys.argv[1]

    ##Grab SQL language
    cursor.execute("SELECT sql_language FROM db_models WHERE model_id = " + str(model_id))



    ##Establish DDL engine in proper language
    sql_lang = cursor.fetchone()[0]
    sqldriver = ""
    if sql_lang.lower() == "mysql":
        sqldriver = 'mysql+pymysql://'
    elif sql_lang.lower() == "oracle":
        sqldriver = 'oracle+cx_oracle://'
    elif "microsoft" in sql_lang.lower():
        sqldriver = "mssql+pyodbc://"
    elif sql_lang.lower() == "sqlite":
        sqldriver = "sqlite+pysqlite://"
    elif sql_lang.lower() == "postgresql":
        sqldriver = "postgresql+pg8000://"

    ##Select all tables that match the model id we need to generate for
    cursor.execute("SELECT table_id, table_name FROM db_tables WHERE db_model_id = " + str(model_id))

    engine = sqla.create_mock_engine(sqldriver, dump)
    metadata_obj = sqla.MetaData()



    table_ids = {}
    for round in cursor:
        table_ids[round[0]] = round[1]


    ##Iterate through each table_id and create tables for mock engine
    columns = []
    for table_id in table_ids:
        cursor.execute("SELECT * FROM db_columns WHERE db_table_id = " + str(table_id))
        for round in cursor:
            data_type = check_data_type(round[2])
            pk = True if round[3] == 1 else False
            nullable = True if round[4] == 0 else False 
            unique = True if round[5] == 1 else False
            ai = True if round[6] == 1 else False

            cursor_fk.execute("SELECT * FROM foreign_keys WHERE key_table_id = {} AND key_column_name = '{}'".format(round[0], round[1]))
            fk = cursor_fk.fetchone()
            if fk:
                ref_table = table_ids[fk[3]]
                ref_column = str(fk[4])
                fk_string = ref_table + "." + ref_column
                col = Column(round[1], data_type, ForeignKey(fk_string), primary_key=pk, nullable = nullable, unique = unique, autoincrement=ai)
            else:
                col = Column(round[1], data_type, primary_key=pk, nullable = nullable, unique = unique, autoincrement=ai)
            fk = None

            columns.append(col)
            table = Table(table_ids[table_id], metadata_obj, *columns, extend_existing=True)
            columns = []


    ##Write DDL to file
    original_stdout = sys.stdout
            
    cursor.execute("SELECT db_name FROM db_models WHERE model_id = " + str(model_id))
    db_name = str(cursor.fetchone()[0])
    

    create_statement = ''
    use_statement = ''
    if sql_lang.lower() == "sqlite":
        create_statement = "ATTACH DATABASE " + db_name + ".db AS " + db_name + ";"
    elif sql_lang.lower() == "postgresql":
        create_statement = "CREATE DATABASE " + db_name + ";"
    elif sql_lang.lower() == "mysql" or sql_lang.lower() == "microsoft sql server":
        create_statement = "CREATE DATABSE " + db_name + ";"
        use_statement = "USE " + db_name + ";"
    else:
        create_statement = "CREATE DATABASE " + db_name + ";"
        use_statement = "USE DATABASE " + db_name + ";"
   

    ddl_file = "databasemodel" + str(model_id) + ".sql"
    with open(ddl_file, "a+") as ddl:
        ddl.truncate(0)
        if create_statement: ddl.write(create_statement + "\n")
        if use_statement: ddl.write(use_statement + "\n")
        sys.stdout = ddl
        metadata_obj.create_all(engine, checkfirst=False)
        sys.stdout = original_stdout
    ddl.close()


    ##Fix semicolons for proper DDL execution
    with open(ddl_file, "r") as ddl:
        ddl_read = ddl.readlines()
    ddl.close()

    linecount = 0
    for line in ddl_read:
        if line == ")\n":
            ddl_read[linecount] = ");\n"
        linecount += 1
    
    with open(ddl_file, "w") as ddl:
        ddl.writelines(ddl_read)
    ddl.close()
    cnx.close()


    ##Commented code to run database on user's server
    #engine = sqla.create_engine('mysql://{USR}:{PWD}@localhost:3306/db', echo=True)

    #with engine.connect() as con:
    #    with open("sean_csv_export_example.sql") as file:
    #        query = sqla.text(file.read())
    #        con.execute(query)


