document.addEventListener("DOMContentLoaded", function () {

    const themeToggle = document.getElementById("theme-toggle");

    if (!themeToggle) {
        console.log("Theme button not found");
        return;
    }

    const icon = themeToggle.querySelector("i");

    // Check saved theme
    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "dark") {
        document.body.classList.add("dark-mode");

        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");

    } else {
        document.body.classList.remove("dark-mode");

        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");
    }


    // Toggle theme
    themeToggle.addEventListener("click", function () {

        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {

            // DARK MODE
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");

            localStorage.setItem("theme", "dark");

        } else {

            // LIGHT MODE
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");

            localStorage.setItem("theme", "light");
        }

    });

});