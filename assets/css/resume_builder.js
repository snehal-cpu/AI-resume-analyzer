document.addEventListener("DOMContentLoaded", function () {

    /* ==========================================
       ADD EXPERIENCE
    ========================================== */

    const experienceContainer =
        document.getElementById("experienceContainer");

    const addExperience =
        document.getElementById("addExperience");

    if (addExperience) {

        addExperience.addEventListener("click", function () {

            const html = `

            <div class="experience-box border rounded p-3 mb-4">

                <div class="text-end mb-3">

                    <button
                    type="button"
                    class="btn btn-danger btn-sm removeExperience">

                    <i class="fa-solid fa-trash"></i>

                    Remove

                    </button>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Company</label>

                        <input
                        type="text"
                        name="company[]"
                        class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Job Title</label>

                        <input
                        type="text"
                        name="position[]"
                        class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Start Date</label>

                        <input
                        type="month"
                        name="start[]"
                        class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>End Date</label>

                        <input
                        type="month"
                        name="end[]"
                        class="form-control">

                    </div>

                    <div class="col-12">

                        <label>Description</label>

                        <textarea
                        rows="4"
                        class="form-control"
                        name="description[]"></textarea>

                    </div>

                </div>

            </div>

            `;

            experienceContainer.insertAdjacentHTML(
                "beforeend",
                html
            );

        });

    }

    /* ==========================================
       REMOVE EXPERIENCE
    ========================================== */

    document.addEventListener("click", function (e) {

        if (e.target.closest(".removeExperience")) {

            e.target
                .closest(".experience-box")
                .remove();

        }

    });

    /* ==========================================
       ADD EDUCATION
    ========================================== */

    const educationContainer =
        document.getElementById("educationContainer");

    const addEducation =
        document.getElementById("addEducation");

    if (addEducation) {

        addEducation.addEventListener("click", function () {

            const html = `

            <div class="education-box border rounded p-3 mt-4">

                <div class="text-end mb-3">

                    <button
                    type="button"
                    class="btn btn-danger btn-sm removeEducation">

                    <i class="fa-solid fa-trash"></i>

                    Remove

                    </button>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>College</label>

                        <input
                        type="text"
                        class="form-control"
                        name="college[]">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Degree</label>

                        <input
                        type="text"
                        class="form-control"
                        name="degree[]">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Passing Year</label>

                        <input
                        type="text"
                        class="form-control"
                        name="year[]">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>CGPA / Percentage</label>

                        <input
                        type="text"
                        class="form-control"
                        name="cgpa[]">

                    </div>

                </div>

            </div>

            `;

            educationContainer.insertAdjacentHTML(
                "beforeend",
                html
            );

        });

    }

    /* ==========================================
       REMOVE EDUCATION
    ========================================== */

    document.addEventListener("click", function (e) {

        if (e.target.closest(".removeEducation")) {

            e.target
                .closest(".education-box")
                .remove();

        }

    });

    /* ==========================================
       FORM VALIDATION
    ========================================== */

    const form = document.querySelector("form");

    if (form) {

        form.addEventListener("submit", function (e) {

            const fullname =
                document.querySelector(
                    "input[name='fullname']"
                ).value.trim();

            const summary =
                document.querySelector(
                    "textarea[name='summary']"
                ).value.trim();

            if (fullname === "") {

                alert("Please enter your full name.");

                e.preventDefault();

                return;

            }

            if (summary.length < 30) {

                alert(
                    "Professional summary should be at least 30 characters."
                );

                e.preventDefault();

                return;

            }

            const button =
                document.querySelector(
                    ".generate-btn"
                );

            if (button) {

                button.disabled = true;

                button.innerHTML = `

                <i class="fa-solid fa-spinner fa-spin"></i>

                Generating Resume...

                `;

            }

        });

    }

});
document.addEventListener("DOMContentLoaded",()=>{


const email = document.getElementById("email");

const phone = document.getElementById("phone");



if(email)
{

email.addEventListener(
"input",
()=>{

email.value = email.value.toLowerCase();

});

}




if(phone)
{

phone.addEventListener(
"input",
()=>{


phone.value =
phone.value.replace(/[^0-9]/g,'');



if(phone.value.length > 12)
{
phone.value =
phone.value.slice(0,12);
}


});

}


});