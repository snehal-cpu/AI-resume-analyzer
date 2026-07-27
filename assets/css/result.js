document.addEventListener("DOMContentLoaded",function(){


const scoreElement=document.getElementById("atsScore");


if(scoreElement)
{

let finalScore=parseInt(
scoreElement.innerText
);


let current=0;


let animation=setInterval(function(){


current++;


scoreElement.innerText=current+"%";


if(current>=finalScore)
{

clearInterval(animation);

}


},20);


}



// Card animation

const cards=document.querySelectorAll(".card");


cards.forEach(function(card,index){


card.style.opacity="0";

card.style.transform="translateY(30px)";


setTimeout(function(){


card.style.transition=".5s";

card.style.opacity="1";

card.style.transform="translateY(0)";


},index*100);


});


});