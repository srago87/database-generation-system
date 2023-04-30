//when new table button is clicked, create a DBTableTemplate
//object, along with New Column <button> and text <input> for table name
function createNewTable(tableArr, tableContainer){

    //create elements and set attributes
    let tdiv = document.createElement("div");
    tdiv.setAttribute("class", "tdiv");

    let htmlTable = document.createElement("table");

    let numTables = tableArr.length;
    let dbTable = new DBTableTemplate(htmlTable, numTables);
    let newColBtn = document.createElement("button");
    newColBtn.textContent = "New Column";
    newColBtn.addEventListener("click", () => {
        dbTable.createNewColumn();
    })

    //remove table button
    let removeTableBtn = document.createElement("button");
    removeTableBtn.setAttribute("class", "remove-table-btn");
    removeTableBtn.innerHTML = '<i class="fa fa-close"></i>';
    removeTableBtn.addEventListener("click", () => {
        tdiv.remove();
        delete tableArr[dbTable.getTableNum()];
    });

    //putting it all together
    tableArr[numTables] = dbTable;
    dbTable.addTableNameField(tdiv);
    tdiv.appendChild(newColBtn);
    tdiv.appendChild(removeTableBtn);
    tdiv.appendChild(htmlTable);
    
    tableContainer.appendChild(tdiv);
}