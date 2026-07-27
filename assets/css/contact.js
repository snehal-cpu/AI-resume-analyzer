document.addEventListener("DOMContentLoaded", () => {

    // ===============================
    // Reveal Animation
    // ===============================

    const cards = document.querySelectorAll(
        ".contact-card,.faq-section,.contact-form-card"
    );

    const observer = new IntersectionObserver((entries)=>{

        entries.forEach(entry=>{

            if(entry.isIntersecting){

                entry.target.classList.add("show");

            }

        });

    },{
        threshold:0.15
    });


    cards.forEach(card=>{

        card.classList.add("hidden");

        observer.observe(card);

    });



    // ===============================
    // Contact Form Validation
    // ===============================

    const form = document.querySelector("form");

    if(form){

        form.addEventListener("submit",(e)=>{

            const subject =
            document.querySelector("input[name='subject']");

            const message =
            document.querySelector("textarea[name='message']");

            if(subject.value.trim()===""){

                alert("Please enter a subject.");

                subject.focus();

                e.preventDefault();

                return;

            }


            if(message.value.trim().length<10){

                alert("Message should contain at least 10 characters.");

                message.focus();

                e.preventDefault();

                return;

            }


            const btn=document.querySelector(".send-btn");

            btn.innerHTML=`
            <i class="fa-solid fa-spinner fa-spin"></i>
            Sending...
            `;

            btn.disabled=true;

        });

    }




    // ===============================
    // Contact Card Hover Effect
    // ===============================

    const contactCards=document.querySelectorAll(".contact-card");

    contactCards.forEach(card=>{

        card.addEventListener("mouseenter",()=>{

            card.style.transform="translateY(-10px) scale(1.03)";

        });


        card.addEventListener("mouseleave",()=>{

            card.style.transform="translateY(0px) scale(1)";

        });

    });



    // ===============================
    // Accordion Icon Rotation
    // ===============================

    const buttons=document.querySelectorAll(".accordion-button");

    buttons.forEach(btn=>{

        btn.addEventListener("click",()=>{

            buttons.forEach(b=>{

                if(b!==btn){

                    b.classList.remove("active-faq");

                }

            });

            btn.classList.toggle("active-faq");

        });

    });



    // ===============================
    // Smooth Scroll
    // ===============================

    document.querySelectorAll('a[href^="#"]').forEach(anchor=>{

        anchor.addEventListener("click",function(e){

            e.preventDefault();

            const target=document.querySelector(this.getAttribute("href"));

            if(target){

                target.scrollIntoView({

                    behavior:"smooth"

                });

            }

        });

    });

});