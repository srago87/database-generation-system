/*
DropDown object contains...
selectBox: <div> main box that holds selectOptions and value,
selectOption: <div> option that is currently selected,
optionsList: array of <li>s, each representing a dropdown option
soValue: text <input> for displaying currently selected option
options: <ul> of dropdown options
content: <div> for holding options <ul>
ddNumber: as a unique identifier
*/
class DropDown{
    selectBox;
    selectOption;
    optionsList;
    soValue;
    options;
    content;
    ddNumber;

    //constructor takes array of option strings, placeholder string, and boolean for
    //whether soValue <input> is readonly
    constructor(optionNames, placeHolder, readOnlyOption, ddNumber){
        this.ddNumber = ddNumber;

        //setup selectBox <div>, selectOption <div>, and soValue <input>
        this.selectBox = document.createElement("div");
        this.selectBox.setAttribute("class", "select-box");

        this.selectOption = document.createElement("div");
        this.selectOption.setAttribute("class", "select-option");

        this.soValue = document.createElement("input");
        this.soValue.setAttribute("type", "text");
        this.soValue.setAttribute("placeholder", placeHolder);
        this.soValue.setAttribute("id", "soValue");
        this.soValue.setAttribute("name", "");
        this.soValue.readOnly = readOnlyOption;

        this.selectOption.appendChild(this.soValue);

        //create content <div>
        this.content = document.createElement("div");
        this.content.setAttribute("class", "content");

        //setup options <ul>
        this.options = document.createElement("ul");
        this.options.setAttribute("class", "options");
        this.optionsList = [];
        optionNames.forEach((opt) => {
            let li = document.createElement("li");
            li.textContent = opt;
            this.options.appendChild(li);
            this.optionsList.push(li);
        })

        //put elements together
        this.content.appendChild(this.options);
        this.selectBox.appendChild(this.selectOption);
        this.selectBox.appendChild(this.content);

        //add event listeners for opening menu and selecting options
        this.selectOption.addEventListener("click", () => {
            this.selectBox.classList.toggle("active");
        });
        this.optionsList.forEach((li) => {
            li.addEventListener("click", () => {
                let text = li.textContent;
                this.soValue.value = text;
                this.selectBox.classList.remove("active");
            });
        })
    }

    //returns priamry <div> node for JS object
    getNode(){
        return this.selectBox;
    }

    //returns text of selected option
    getText(){
        return this.soValue.value;
    }

    getDDNumber(){
        return this.ddNumber;
    }

    //adds new option to dropdown menu
    addOption(opt){
        this.content.removeChild(this.options);

        let li = document.createElement("li");
        li.textContent = opt;
        li.addEventListener("click", () => {
            let text = li.textContent;
            this.soValue.value = text;
            this.selectBox.classList.remove("active");
        });
        this.optionsList.push(opt);
        this.options.appendChild(li);
        this.content.appendChild(this.options);
    }

    //removes all options from dropdown menu
    clearOptions(){
        this.soValue.value = "";
        this.optionsList = [];
        this.options.remove();
        this.options = document.createElement("ul");
        this.options.setAttribute("class", "options");
        this.content.appendChild(this.options);
    }
}

//ColumnNameField object contains HTML <td> and HTML <input>
//of type text
class ColumnNameField{
    tableData;
    textField;

    constructor(){
        this.tableData = document.createElement("td");
        this.textField = document.createElement("input");
        this.textField.setAttribute("type", "text");
        this.tableData.appendChild(this.textField);
    }

    getTextField(){
        return this.textField;
    }
}

//DataTypeField object contains <td> and DropDown object
class DataTypeField{
    tableData;
    dropdown;

    constructor(){
        this.tableData = document.createElement("td");
        let dataTypes = ["INT()", "CHAR()", "VARCHAR()", "DECIMAL()", "DATE"];
        this.dropdown = new DropDown(dataTypes, "Data Type", false, 0);
        this.tableData.appendChild(this.dropdown.getNode());   
    }

    //returns string representing data type and max character count
    getDataType(){
        return this.dropdown.getText();
    }
}

//Parameter object contains HTML <input> of type checkbox 
//and HTML <label>
class Parameter{
    checkbox;
    label;

    //constructor takes string "PK", "NN", "UQ", or "AI"
    //as argument
    constructor(paramType){
        this.checkbox = document.createElement("input");
        this.checkbox.setAttribute("type", "checkbox");
        this.checkbox.setAttribute("id", paramType);

        this.label = document.createElement("label");
        this.label.textContent = paramType;
    }

    getCheckBox(){
        return this.checkbox;
    }
}

//optionalParametersField object contains an HTML <td> and
//array of Parameter objects
class OptionalParametersField{
    tableData;
    params;

    constructor(){
        this.tableData = document.createElement("td");

        this.params = [];
        this.params[0] = new Parameter("PK");
        this.params[1] = new Parameter("NN");
        this.params[2] = new Parameter("UQ");
        this.params[3] = new Parameter("AI");

        this.params.forEach((p) => {
            this.tableData.appendChild(p.checkbox);
            this.tableData.appendChild(p.label);
        })


    }

    getParams(){
        return this.params;
    }

    //encodes which optionalParameters are checked into bit array
    getParamValues(){
        let paramVals = [0, 0, 0, 0];
        for(let i=0; i<4; i++){
            if(this.params[i].getCheckBox().checked){
                paramVals[i] = 1;
            }
        }
        return paramVals;
    }
}

/*
DBTable Template puts all the above classes together
in an HTML table where user enters data about their
DB tables
*/
class DBTableTemplate{
    tableNum;
    htmlTable;
    htmlNumRows;
    tableNameLabel;
    tableNameField;
    fieldsArr;

    constructor(HTMLtable, tableNum){
        this.tableNum = tableNum;
        let tableNameField = document.createElement("input");
        tableNameField.setAttribute("type", "text");
        let tableNameLabel = document.createElement("label");
        tableNameLabel.textContent = "Table Name:";
        tableNameLabel.setAttribute("class", "table-name-label");
        
        this.tableNameField = tableNameField;
        this.tableNameLabel = tableNameLabel;

        //create HTML table headers
        let headers = [];
        for(let i=0; i<4; i++){
            headers[i] = document.createElement("td");
        }
        headers[0].textContent = "Column Name";
        headers[1].textContent = "Data Type";
        headers[2].textContent = "Optional Parameters";
        headers[3].textContent = "Default Value";
        let headerRow = document.createElement("tr");
        headers.forEach((h) => {
            headerRow.appendChild(h);
        })
        HTMLtable.appendChild(headerRow);

        //create fields for HTML row 0
        let f1 = new ColumnNameField();
        let f2 = new DataTypeField();
        let f3 = new OptionalParametersField();
        let f4 = new ColumnNameField();

        //setup fields array
        let fieldsArr = [];
        fieldsArr[0] = [];
        fieldsArr[0][0] = f1;
        fieldsArr[0][1] = f2;
        fieldsArr[0][2] = f3;
        fieldsArr[0][3] = f4;
        this.fieldsArr = fieldsArr;

        //create HTML row 0
        let htmlRow0 = document.createElement("tr");
        htmlRow0.appendChild(f1.tableData);
        htmlRow0.appendChild(f2.tableData);
        htmlRow0.appendChild(f3.tableData);
        htmlRow0.appendChild(f4.tableData);

        HTMLtable.appendChild(htmlRow0);
        this.htmlTable = HTMLtable;
        this.htmlNumRows = 0;
    }

    getTableNameField(){
        return this.tableNameField;
    }

    getFieldsArr(){
        return this.fieldsArr;
    }

    getTableNum(){
        return this.tableNum;
    }

    getHTMLNumRows(){
        return this.htmlNumRows;
    }

    addTableNameField(div){
        div.appendChild(this.tableNameLabel);
        div.appendChild(this.tableNameField);
    }

    //method is mapped to New Column button in DBTableTemplate
    createNewColumn(){
        this.htmlNumRows++;

        //create new fields and add them to fields array
        let f1 = new ColumnNameField();
        let f2 = new DataTypeField();
        let f3 = new OptionalParametersField();
        let f4 = new ColumnNameField();

        this.fieldsArr[this.htmlNumRows] = [];
        this.fieldsArr[this.htmlNumRows][0] = f1;
        this.fieldsArr[this.htmlNumRows][1] = f2;
        this.fieldsArr[this.htmlNumRows][2] = f3;
        this.fieldsArr[this.htmlNumRows][3] = f4;

        let htmlRow = document.createElement("tr");
        htmlRow.appendChild(f1.tableData);
        htmlRow.appendChild(f2.tableData);
        htmlRow.appendChild(f3.tableData);
        htmlRow.appendChild(f4.tableData);

        this.htmlTable.appendChild(htmlRow);
    }
}