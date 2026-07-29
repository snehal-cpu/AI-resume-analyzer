// ======================================
// AI Resume Analyzer
// Premium Reports JavaScript
// ======================================



document.addEventListener("DOMContentLoaded", function(){



// ================= LIVE SEARCH =================


const searchInput = document.getElementById("searchInput");

const tableBody = document.getElementById("reportTable");



if(searchInput && tableBody)
{


searchInput.addEventListener("keyup", function(){


let filter = this.value.toLowerCase();


let rows = tableBody.querySelectorAll("tr");


let visibleCount = 0;



rows.forEach(function(row){


let text = row.innerText.toLowerCase();



if(text.includes(filter))
{

row.style.display="";

visibleCount++;

}

else

{

row.style.display="none";

}


});




// No result message

let noResult = document.getElementById("noResult");


if(visibleCount===0)
{


if(!noResult)
{

let tr=document.createElement("tr");

tr.id="noResult";


tr.innerHTML=`

<td colspan="5" 
style="
text-align:center;
padding:40px;
color:#94a3b8;
">

<i class="fa-solid fa-search fa-2x"></i>

<br><br>

No matching resume found

</td>

`;


tableBody.appendChild(tr);


}


}

else

{


if(noResult)
{

noResult.remove();

}


}



});


}









// ================= DELETE CONFIRMATION =================



const deleteButtons=document.querySelectorAll(".delete-btn");



deleteButtons.forEach(function(button){



button.addEventListener(
"click",
function(event){


let confirmDelete=confirm(

"⚠️ Are you sure you want to delete this resume report?"

);



if(!confirmDelete)

{

event.preventDefault();

}



});


});









// ================= PAGE FADE =================



document.body.style.opacity="0";


document.body.style.transition="opacity .5s ease";



setTimeout(function(){


document.body.style.opacity="1";


},100);









// ================= ROW ANIMATION =================



const rows=document.querySelectorAll("#reportTable tr");



rows.forEach(function(row){



row.addEventListener(
"mouseenter",
function(){


this.style.transform="translateY(-3px)";

this.style.transition=".25s";


});





row.addEventListener(
"mouseleave",
function(){


this.style.transform="translateY(0)";


});


});








// ================= FUTURE FILTER =================



window.filterReports=function(type){


console.log(
"Filter:",
type
);


};



});