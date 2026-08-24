const toggle = document.getElementById("theme-toggle");

const body = document.body;

// Load saved theme
if(localStorage.getItem("theme") === "dark"){
    body.classList.add("dark-mode");
    toggle.innerHTML='<i class="fa-solid fa-sun"></i>';
}

toggle.addEventListener("click",()=>{

    body.classList.toggle("dark-mode");

    if(body.classList.contains("dark-mode")){

        localStorage.setItem("theme","dark");

        toggle.innerHTML='<i class="fa-solid fa-sun"></i>';

    }else{

        localStorage.setItem("theme","light");

        toggle.innerHTML='<i class="fa-solid fa-moon"></i>';

    }

});