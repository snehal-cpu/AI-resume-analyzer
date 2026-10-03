<!-- MOBILE MENU BUTTON -->

<button type="button" class="mobile-menu-btn" id="mobileMenuBtn">
    ☰
</button>


<!-- SIDEBAR -->

<div class="sidebar" id="sidebar">

    <div class="logo">
        <i class="fa-solid fa-robot"></i>
        <span>ResumeAI</span>
    </div>

    <ul>

      <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
    <a href="dashboard.php">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
    </a>
</li>


       <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'upload.php' ? 'active' : ''; ?>">
    <a href="upload.php">
        <i class="fa-solid fa-upload"></i>
        <span>Upload Resume</span>
    </a>
</li>


      <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
    <a href="reports.php">
        <i class="fa-solid fa-chart-column"></i>
        <span>Reports</span>
    </a>
</li>

      <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'resume_builder.php' ? 'active' : ''; ?>">
    <a href="resume_builder.php">
        <i class="fa-solid fa-file-pen"></i>
        <span>Resume Builder</span>
    </a>
</li>

<li class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
    <a href="profile.php">
        <i class="fa-solid fa-user"></i>
        <span>Profile</span>
    </a>
</li>


       <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
    <a href="settings.php">
        <i class="fa-solid fa-gear"></i>
        <span>Settings</span>
    </a>
</li>

        <li>
            
            <a href="auth/logout.php" class="logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</div>


<!-- OVERLAY -->

<div class="sidebar-overlay" id="sidebarOverlay"></div>


<!-- MOBILE MENU SCRIPT -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("mobileMenuBtn");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    if (!menuBtn || !sidebar || !overlay) {
        return;
    }

    menuBtn.addEventListener("click", function () {

        sidebar.classList.toggle("mobile-open");
        overlay.classList.toggle("active");

    });

    overlay.addEventListener("click", function () {

        sidebar.classList.remove("mobile-open");
        overlay.classList.remove("active");

    });

});

</script>