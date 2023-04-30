//function is used for highlighting selected SQL language in white on the control panel
function selectLabel(){
    let langLabels = document.querySelectorAll(".lang-label");
    let radioBtns = document.querySelectorAll(".lang-btn");

    for(let i=0; i<5; i++){
        if(radioBtns[i].checked){
            langLabels[i].classList.add("selected");
        }
        else{
            langLabels[i].classList.remove("selected");
        }
    }
}