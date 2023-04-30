//create new database table
let tableTemplateArr = [];
const tableTemplateContainer = document.querySelector("#tables-container");

const newTableBtn = document.querySelector("#new-table-btn");
newTableBtn.addEventListener("click", () => {
    createNewTable(tableTemplateArr, tableTemplateContainer);
});


//create new foreign key
let keyDropDowns = [];
let refDropDowns = [];
let fkNumber = 0;

const fkBtn = document.querySelector("#fk-btn");
const fkContainer = document.querySelector("#fk-container");

fkBtn.addEventListener("click", () => {
    newForeignKey(tableTemplateArr, keyDropDowns, refDropDowns, fkNumber, fkContainer);
});

//refresh foreign key options with most recent user-defined columns
let refreshFKBtn = document.querySelector("#refresh-fks-btn");
refreshFKBtn.addEventListener("click", () => {
    refreshFKs(tableTemplateArr, keyDropDowns, refDropDowns);
});

//checks for errors, then sends all data to load-user-data.php
function saveDBModel(tableArr){
    let dbModel = formatDBModelObj();
    if(dbModel == -1){
        return -1;
    }
    let tableNames = formatTablesObj(tableArr);
    if(tableNames == -1){
        return -1;
    }
    let cols = formatColumnsObj(tableArr);
    if(cols == -1){
        return -1;
    }
    let fks = formatForeignKeysObj(tableArr);
    if(fks == -1){
        return -1;
    }

    
    $(document).ready(function(){
        $.post("../../user-data-store/php/load-user-data.php", {
            dbModel: dbModel,
            tableNames: tableNames,
            cols: cols,
            foreignKeys: fks
        }, function(data, status){
            $("#data-load").html(data);
        });
    });
    
    console.log("data sent");
}

const saveBtn = document.querySelector("#save-btn");
saveBtn.addEventListener("click", () => {
    saveDBModel(tableTemplateArr);
});