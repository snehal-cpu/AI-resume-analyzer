document.addEventListener("DOMContentLoaded",()=>{


let email =
document.getElementById("registerEmail");


let phone =
document.getElementById("registerPhone");



if(email)
{
email.addEventListener("input",()=>{

email.value =
email.value.toLowerCase();

});
}



if(phone)
{
phone.addEventListener("input",()=>{


phone.value =
phone.value.replace(/[^0-9]/g,'');


if(phone.value.length > 12)
{
phone.value =
phone.value.substring(0,12);
}


});
}



});