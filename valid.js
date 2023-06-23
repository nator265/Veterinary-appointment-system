// validation code for inputs

var fullname = document.forms['form']['fullname'];
var phone = document.forms['form']['phone'];
var password = document.forms['form']['password'];

function validated(){
    if(fullname.value.length < 1){
        fullname.style.border = "1px solid red";
        if(phone.value.length < 1){
            phone.style.border = "1px solid red";
        }
        if(password.value.length < 1){
            password.style.border = "1px solid red";
            return false;
        }
    }
    if(phone.value.length < 1){
        phone.style.border = "1px solid red";
    }
    if(password.value.length < 1){
        password.style.border = "1px solid red";
        return false;
    }
}