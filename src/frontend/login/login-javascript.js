const loginForm = document.getElementById("submit").addEventListener("click", loginButton);
const userName = document.getElementById("uname")
const passWord = document.getElementById("pword")


function loginButton(){
    if ((((userName.value === '' || userName.value == null) && (passWord.value === '' || passWord.value == null)) 
    || (userName.value === '' || userName.value == null) && (passWord.value !== '' || passWord.value != null))
    || ((userName.value !== '' || userName.value != null) && (passWord.value === '' || passWord.value == null)) )
    
    {alert("username and password fields are both required  to proceed")}
    else if ((userName.value === 'uname') && (passWord.value === 'pword')) {
        alert("login successful!")} 
        else {alert("incorrect username or password")}
}
