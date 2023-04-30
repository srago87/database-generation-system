//returns JSON literal containing database name and 
//sql language with which the database will be deployed
function formatDBModelObj(){

    const langBtns = document.querySelectorAll(".lang-btn");
    const langLabels = document.querySelectorAll(".lang-label");

    let dbModel = {};
    const dbName = document.querySelector("#db-name");
    if(dbName.value != ""){
        dbModel["db_name"] = dbName.value;
    }
    else{
        window.alert("You must give your database a name");
        return -1;
    }

    let sqlLang;

    let i;
    for(i=0; i<5; i++){
        let btn = langBtns[i];
        if(btn.checked){
            sqlLang = langLabels[i].textContent;
            break;
        }
    }
    if(i == 5){
        window.alert("You must select a SQL language before saving your DB model");
        return -1;
    }

    dbModel["sql_language"] = sqlLang;
    return dbModel;
}

//returns array of strings representing table names
function formatTablesObj(tableArr){
    let tableNames = [];

    for(let i=0; i<tableArr.length; i++){
        let t = tableArr[i];
        let tName = t.getTableNameField();
        if(tName.value != ""){
            tableNames[i] = tName.value;
        }
        else{
            window.alert("Every table in your database must have a name");
            return -1;
        }
    }

    return tableNames;
}

//returns array of JSON literals, each containing column_name,
//db_table_id, data_type, and 0 or 1 for PK, NN, UQ, AI
function formatColumnsObj(tableArr){

    //create array to store column JSON literals
    let db_columns = [];
    let numCols = 0;

    let columnError = false;

    //for each table
    let numTables = tableArr.length;
    for(let i=0; i<numTables; i++){
       
        let t = tableArr[i];
        let fieldsArr = t.getFieldsArr();
        
        //for each column in this table
        fieldsArr.forEach((c) => {
            
            db_columns[numCols] = {};
            db_columns[numCols]["table_num"] = i;

            //format column name
            let colName = c[0].getTextField();
            if(colName.value != ""){
                db_columns[numCols]["column_name"] = colName.value;
            }
            else{
                window.alert("Each column must have a name");
                columnError = true;
            }

            //format data type
            let dataType = c[1].getDataType();
            if(dataType == "CHAR()"){
                alert("Error: if you select CHAR data type, you must provide a string length");
                columnError = true;
            }

            if(dataType != ""){
                db_columns[numCols]["data_type"] = dataType;
            }
            else{
                alert("Each column must have a data type");
                columnError = true;
            }

            //format optional params
            let paramVals = c[2].getParamValues();
            db_columns[numCols]["PK"] = paramVals[0];
            db_columns[numCols]["NN"] = paramVals[1];
            db_columns[numCols]["UQ"] = paramVals[2];
            db_columns[numCols]["AI"] = paramVals[3];

            //format default value
            let defaultValue = c[3].getTextField().value;
            db_columns[numCols]["default_value"] = defaultValue;
            numCols++;
        });
    }
    if(!columnError){
        return db_columns;
    }
    else{
        return -1;
    }
}

//returns array of JSON literals, each with a key_column and reference_column
function formatForeignKeysObj(tableArr){

    let fkError = false;

    let mapArr = generateKeyMap(tableArr);
    let keyColMap = mapArr[0];
    let refColMap = mapArr[1];

    let foreignKeys = [];
    let soVals = document.querySelectorAll(".fk-input");
    for(let i=0; i<soVals.length; i+=2){

        let keyCol = soVals[i].value;
        let refCol = soVals[i+1].value;
        
        //check for foriegn key DropDowns with no option selected
        if(keyCol == "" || refCol == ""){
            alert("Error: your database cannot have blank foreign keys");
            fkError = true;
        }

        //for key column and ref column, fetch their respective array containig ColumnNameField, DataTypeField,
        //OptionalParametersField, and DefaultValue field (which reuses the ColumnNameField object)
        let keyFieldArr = keyColMap.get(keyCol);
        let refFieldArr = refColMap.get(refCol);

        //make sure keyCol and refCol have the same data type
        if(keyFieldArr[1].getDataType() != refFieldArr[1].getDataType()){
            alert("Warning: In each foreign key, your key column and reference column must be of the same data type. " +
            "Additionally, if max number of characters is specified, it must be the same for both the key column and " + 
            "the reference column.");
            fkError = true;
        }

        //make sure all refCols are primary keys
        if(refFieldArr[2].getParamValues()[0] != 1){
            alert("Error: the reference column of each foreign key must itself be a primary key");
            fkError = true;
        }
        

        let fk = {};
        fk["key_column"] = soVals[i].value;
        fk["reference_column"] = soVals[i+1].value;
        foreignKeys.push(fk);
    }
    if(!fkError){
        return foreignKeys;
    }
    else{
        return -1;
    }
}