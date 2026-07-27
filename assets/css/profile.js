document.addEventListener("DOMContentLoaded", function () {


    const button = document.querySelector(".update-btn");

    const form = document.querySelector(".profile-form form");



    if(button)
    {

        form.addEventListener("submit", function(){

            button.innerHTML =
            `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Saving...
            `;


            button.disabled = true;


        });



        button.addEventListener("mouseenter", function(){

            button.style.transform="translateY(-3px)";

        });



        button.addEventListener("mouseleave", function(){

            button.style.transform="translateY(0)";

        });


    }






    // Input focus animation

    const inputs = document.querySelectorAll(".input-box input");


    inputs.forEach(input=>{


        input.addEventListener("focus",()=>{


            input.parentElement.style.boxShadow =
            "0 0 12px rgba(0,198,255,.5)";


        });



        input.addEventListener("blur",()=>{


            input.parentElement.style.boxShadow="none";


        });



    });






});