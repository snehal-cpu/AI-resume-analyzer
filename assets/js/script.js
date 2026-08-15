console.log("SCRIPT.JS LOADED");

document.addEventListener("DOMContentLoaded", function () {

    const themeToggle = document.getElementById("theme-toggle");

    console.log("Theme button:", themeToggle);

    if (!themeToggle) {
        console.log("Theme button not found");
        return;
    }

    themeToggle.addEventListener("click", function () {

        console.log("BUTTON CLICKED");

        document.body.classList.toggle("dark-mode");

        console.log(
            "Dark mode:",
            document.body.classList.contains("dark-mode")
        );

    });

});