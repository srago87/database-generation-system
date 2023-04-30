import mysql.connector as ms
import cgi
import sys

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

    #form = cgi.FieldStorage()
    model_id = sys.argv[1]
    filepath = "../../php-slim-dbgs-api/"

    ##Select all tables that match the model id we need to generate for
    cursor = cnx.cursor()
    cursor.execute("SELECT table_id, table_name FROM db_tables WHERE db_model_id = " + str(model_id))

    table_ids = {}
    for round in cursor:
        table_ids[round[0]] = round[1]

    columns = []
    for table_id in table_ids:
        table_name = table_ids[table_id]
        cursor.execute("SELECT * FROM db_columns WHERE db_table_id = " + str(table_id))
        for round in cursor:
            col_name = round[1]
            data_type = round[2]
            pk = True if round[3] == 1 else False
            nullable = True if round[4] == 0 else False 
            unique = True if round[5] == 1 else False
            ai = True if round[6] == 1 else False

            col = [col_name, data_type, pk, nullable, unique]

            if pk:
                primary_key = col_name
            columns.append(col)

    ##GENERATE MODEL FILE
    mdl_file = filepath + "/models/" + table_name + ".php"
    with open(mdl_file, "a") as mdl:
        mdl.truncate(0)
        mdl.write("<?php \n \n include_once('models/Model.php');\n \n")
        mdl.write("class " + table_name + " extends Model {\n")

        for col in columns:
            mdl.write("\tpublic $" + col[0] + " = \'\';\n")
        
        mdl.write("\n\n")

        for col in columns:
            mdl.write("\tpublic function get" + col[0] + "()\n\t{\n")
            mdl.write("\t\treturn $this->" + col[0] + ";\n")
            mdl.write("\t}\n\n")

            mdl.write("\tpublic function set" + col[0] + "($" + col[0] + ")\n    {\n")
            mdl.write("\t\t$this->" + col[0] + " = $" + col[0] + ";\n")
            mdl.write("\t\treturn $this;\n")
            mdl.write("\t}\n\n")
        
        mdl.write("}\n?>")
        mdl.close()


    ##GENERATE DATABASE FILE
    db_file = filepath + "/databases/" + table_name + "BaseDatabase.php"
    with open(db_file, "a") as db:
        db.truncate(0)
        db.write("<?php\n\ninclude_once(\'models/" + mdl_file + "\');\ninclude_once(\'databases/Database.php\');\n\n")
        db.write("class " + table_name + "Database extends Database { \n")
        db.write("\tpublic function __construct($pdo, $logger) {\n\t\tparent::_construct($pdo, $logger);\n\n")
        db.write("\t\t$this->fields = [")
        for col in columns:
            db.write("\"" + col[0] +"\"")
            if columns.index(col) != (len(columns)-1): db.write(", ")
        db.write("];\n\t\t$this->tableName = \"" + table_name +"\";\n")
        db.write("\t\t$this->id = \"" + primary_key + "\";\n\n")
        db.write("\t\t$this->newFieldList = $this->generateFieldList(false);\n")
        db.write("\t\t$this->newColonFieldList = $this->generateColonFieldList(false);\n")
        db.write("\t\t$this->allFieldList = $this->generateFieldList(true);\n")
        db.write("\t\t$this->allColonFieldList = $this->generateColonFieldList(true);\n")
        db.write("\t}\n\n\tpublic function generateCreateExecuteArray($record) {\n")
        db.write("\t\t$result = array(\n")

        for col in columns:
            if col[0] != primary_key: 
                db.write("\t\t\t\':" + col[0] + "\' => $record->" + col[0])
                if columns.index(col) != (len(columns)-1): db.write(",\n")
        db.write("\n\t\t);\n\t\treturn $result;\n\t}\n\n")
        db.write("\tpublic function generateEditAssignmentList() {\n\t\t$result = ")
        first = True

        for col in columns:
            if col[0] != primary_key:
                if not first: db.write("\t\t\t\t  ")
                if first == True: first = False
                db.write("\"" + col[0] + " = :" + col[0])
                if columns.index(col) != (len(columns)-2): db.write(", \" .\n")
                else: db.write("\";\n")

        db.write("\t\t return $result;\n\t}\n\n\n")
        db.write("\tpublic function generateEditExecuteArray($record) {\n\t\t$result = array(\n")
        for col in columns:
            if col[0] != primary_key: 
                db.write("\t\t\t\':" + col[0] + "\' => $record->" + col[0])
                if columns.index(col) != (len(columns)-1): db.write(",\n")
            else:
                db.write("\t\t\t\'id\' => $record->" + col[0])
                if columns.index(col) != (len(columns)-1): db.write(",\n")
        db.write("\t\t);\n\t\treturn $result;\n\t}\n\n")
        db.write("\tfunction setPK($record, $id) {\n\t\t$record->" + primary_key + "= $id;\n\t}\n\n")
        db.write("\tpublic function loadSingleRecord($fields) {\n")
        db.write("\t\t$record = new " + table_name + "();\n")
        for col in columns:
            if col[0] == primary_key:
                db.write("\t\t$record->set" + col[0] + "(intval($fields[\'" + col[0] + "\']));\n")
            else:
                db.write("\t\t$record->set" + col[0] + "($fields[\'" + col[0] + "\']);\n")
        db.write("\n\t\treturn $record;\n\t}\n}\n?>")
        db.close()




    ##GENERATE BASE CONTROLLER FILE
    ctrl_file = filepath + "/controllers/" + table_name + "BaseController.php"
    with open(ctrl_file, "a") as ctrl:
        ctrl.truncate(0)
        ctrl.write("<?php \n")
        ctrl.write("\tuse Psr\Container\ContainerInterface;\n\n")
        ctrl.write("\tequire_once('databases/" + table_name + "BaseDatabase.php');\n")
        ctrl.write("\trequire_once('databases/" + table_name + "BaseDatabase.php');\n\n")

        ctrl.write("\tclass " + table_name + "BaseController extends Controller {\n")
        ctrl.write("\t\tpublic function __construct(ContainerInterface $container) {\n")
        ctrl.write("\t\t\tparent::__construct($container, new " + table_name + "Database($container[\'db\'], $container[\'logger\']));\n\t\t}\n\n")
        ctrl.write("\t\tpublic function loadFromData($data) {\n\t\t$record = new "+ table_name + "();\n")
        ctrl.write("\t\t\tif (array_key_exists(\'" + primary_key + "\', $data)) {\n")
        ctrl.write("\t\t\t\t$record->set" + primary_key + "($data[\'" + primary_key + "\']);\n            }\n")
        for col in columns:
            ctrl.write("\t\t\t$record->set" + col[0] + "($data[\'" + col[0] +"\']);\n")
        ctrl.write("\t\t\t\treturn $record;\n\t\t}\n\t}\n?>")
        



    cnx.close()


