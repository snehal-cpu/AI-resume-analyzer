document.addEventListener("DOMContentLoaded", function () {

    const themeToggle = document.getElementById("theme-toggle");

    if (!themeToggle) {
        return;
    }

    const icon = themeToggle.querySelector("i");

    function applyTheme(theme) {

        if (theme === "dark") {

            document.body.classList.add("dark-mode");

            if (icon) {
                icon.classList.remove("fa-moon");
                icon.classList.add("fa-sun");
            }

        } else {

            document.body.classList.remove("dark-mode");

            if (icon) {
                icon.classList.remove("fa-sun");
                icon.classList.add("fa-moon");
            }
        }
    }

    // Load saved theme
    const savedTheme = localStorage.getItem("theme") || "light";

    applyTheme(savedTheme);


    // Toggle theme
    themeToggle.addEventListener("click", function () {

        const currentTheme =
            document.body.classList.contains("dark-mode")
                ? "dark"
                : "light";

        const newTheme =
            currentTheme === "dark"
                ? "light"
                : "dark";

        localStorage.setItem("theme", newTheme);

        applyTheme(newTheme);
    });

});