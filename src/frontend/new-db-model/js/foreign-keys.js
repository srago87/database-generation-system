//retrieves most recent columns from tableArr
function refreshForeignKeyOptions(tableArr){
    if(tableArr.length == 0){
        return [];
    }
    let columns = [];

    for(let i=0; i<tableArr.length; i++){
        let t = tableArr[i];
        let fieldsArr = t.getFieldsArr();

        fieldsArr.forEach((c) => {
            let tName = t.getTableNameField().value
            let colName = c[0].getTextField().value;
            if(tName != "" && colName != ""){
                columns.push(`${tName}.${colName}`);
            }
        })
    }

    return columns;
}

//function gets mapped to New Foreign Key <button> in new-db-model.php
function newForeignKey(tableArr, keyDDs, refDDs, fkNumber, fkContainer){
    let colList = refreshForeignKeyOptions(tableTemplateArr);
    let fkDiv = document.createElement("div");
    fkDiv.setAttribute("class", "fk-div");
    let keyColumnDropDown = new DropDown(colList, "Key Column", true, fkNumber);
    keyColumnDropDown.soValue.setAttribute("class", "fk-input");
    let refColumnDropDown = new DropDown(colList, "Reference Column", true, fkNumber);
    refColumnDropDown.soValue.setAttribute("class", "fk-input");

    keyDDs.push(keyColumnDropDown);
    refDDs.push(refColumnDropDown);
    fkNumber++;

    //remove foreign key button
    let removeFKBtn = document.createElement("button");
    removeFKBtn.setAttribute("class", "remove-fk-btn");
    removeFKBtn.innerHTML = '<i class="fa fa-remove"></i>';
    removeFKBtn.addEventListener("click", () => {
        fkDiv.remove();
        delete keyDDs[keyColumnDropDown.getDDNumber()];
        delete refDDs[refColumnDropDown.getDDNumber()];
    })

    fkDiv.appendChild(keyColumnDropDown.getNode());
    fkDiv.appendChild(refColumnDropDown.getNode());
    fkDiv.appendChild(removeFKBtn);
    fkContainer.appendChild(fkDiv)
}

//function gets mapped to refresh button in foreign keys section of new-db-model.php
function refreshFKs(tableArr, keyDDs, refDDs){
    let i;
    for(i=0; i<keyDDs.length; i++){
        keyDDs[i].clearOptions();
        refDDs[i].clearOptions();
    }

    let colList = refreshForeignKeyOptions(tableArr);
    for(i=0; i<keyDDs.length; i++){
        colList.forEach((opt) => {
            keyDDs[i].addOption(opt);
            refDDs[i].addOption(opt);
        });
    }
}

//auxiliary function used for foreign key error checking
//returns array of two Maps, each mapping foreign key columns to their associated fields arr
//array[0] Map is for key columns, array[1] Map is for reference columns
function generateKeyMap(tableArr){
    keyMap = new Map();
    referenceMap = new Map();

    let soVals = document.querySelectorAll(".fk-input");
    for(let i=0; i<soVals.length; i+=2){
        let foreignKey = soVals[i].value;
        let reference = soVals[i+1].value;

        if(foreignKey == "" || reference == ""){
            continue;
        }

        let arr = foreignKey.split(".");
        let keyTable = arr[0];
        let keyCol = arr[1];

        arr = reference.split(".");
        let refTable = arr[0];
        let refCol = arr[1];

        tableArr.forEach((t) => {
            let tableName = t.getTableNameField().value;
            if(tableName == keyTable || tableName == refTable){
                let fieldsArr = t.getFieldsArr();
                fieldsArr.forEach((c) => {
                    let colName = c[0].getTextField().value;
                    if(colName == keyCol){
                        keyMap.set(foreignKey, c);
                    }
                    if(colName == refCol){
                        referenceMap.set(reference, c);
                    }
                });
            }
        });
    }

    return [keyMap, referenceMap];
}