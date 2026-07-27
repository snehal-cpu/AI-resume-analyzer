<?php
session_start();
require_once "config/db.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT fullname,email FROM users WHERE id=?"
);

mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$success = "";

if(isset($_POST['send']))
{
    $success = "Your message has been sent successfully. We'll get back to you soon.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Help & Contact</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet" href="assets/css/contact.css">

</head>

<body>

<!-- Sidebar -->

<div class="sidebar">

<div class="logo">
<i class="fa-solid fa-robot"></i>
<span>ResumeAI</span>
</div>

<ul>

<li>
<a href="dashboard.php">
<i class="fa-solid fa-house"></i>
Dashboard
</a>
</li>

<li>
<a href="upload.php">
<i class="fa-solid fa-upload"></i>
Upload Resume
</a>
</li>

<li>
<a href="reports.php">
<i class="fa-solid fa-chart-column"></i>
Reports
</a>
</li>

<li>
<a href="resume_builder.php">
<i class="fa-solid fa-file-pen"></i>
Resume Builder
</a>
</li>

<li>
<a href="profile.php">
<i class="fa-solid fa-user"></i>
Profile
</a>
</li>

<li>
<a href="settings.php">
<i class="fa-solid fa-gear"></i>
Settings
</a>
</li>

<li class="active">
<a href="contact.php">
<i class="fa-solid fa-headset"></i>
Help & Contact
</a>
</li>

<li>
<a href="auth/logout.php">
<i class="fa-solid fa-right-from-bracket"></i>
Logout
</a>
</li>

</ul>

</div>

<!-- Main -->

<div class="main">

<!-- Topbar -->

<div class="topbar">

<h3>Help & Contact</h3>

<div class="profile">

<img
src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['fullname']); ?>&background=2563eb&color=fff">

</div>

</div>

<!-- Hero -->

<div class="contact-hero">

<h1>
<i class="fa-solid fa-headset"></i>
Need Help?
</h1>

<p>
Our support team is always ready to help you with resume uploads,
AI analysis, ATS reports and account issues.
</p>

</div>

<?php if($success!=""){ ?>

<div class="alert alert-success">

<?php echo $success; ?>

</div>

<?php } ?>

<!-- Contact Cards -->

<div class="row g-4">

<div class="col-md-4">

<div class="contact-card">

<div class="icon-box">

<i class="fa-solid fa-envelope"></i>

</div>

<h4>Email Support</h4>

<p>support@airesume.com</p>

<span>Reply within 24 hours</span>

</div>

</div>

<div class="col-md-4">

<div class="contact-card">

<div class="icon-box">

<i class="fa-solid fa-phone"></i>

</div>

<h4>Call Us</h4>

<p>+91 98765 43210</p>

<span>Mon - Sat | 9 AM - 6 PM</span>

</div>

</div>

<div class="col-md-4">

<div class="contact-card">

<div class="icon-box">

<i class="fa-solid fa-location-dot"></i>

</div>

<h4>Office</h4>

<p>Pune, Maharashtra</p>

<span>India</span>

</div>

</div>

</div>

<!-- FAQ -->

<div class="faq-section">

<h2>

<i class="fa-solid fa-circle-question"></i>

Frequently Asked Questions

</h2>

<div class="accordion mt-4" id="faqAccordion">

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#faq1">

How do I upload my resume?

</button>

</h2>

<div id="faq1"
class="accordion-collapse collapse"
data-bs-parent="#faqAccordion">

<div class="accordion-body">

Go to Upload Resume, choose your PDF and click Upload.

</div>

</div>

</div>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#faq2">

How is ATS Score calculated?

</button>

</h2>

<div id="faq2"
class="accordion-collapse collapse"
data-bs-parent="#faqAccordion">

<div class="accordion-body">

The AI evaluates your resume based on important skills, formatting, keywords and readability.

</div>

</div>

</div>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#faq3">

How can I improve my resume?

</button>

</h2>

<div id="faq3"
class="accordion-collapse collapse"
data-bs-parent="#faqAccordion">

<div class="accordion-body">

Use Resume Builder and AI Suggestions to improve your resume.

</div>

</div>

</div>

</div>

</div>

<!-- Contact Form -->

<div class="contact-form-card">

<h2>

<i class="fa-solid fa-paper-plane"></i>

Send us a Message

</h2>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Name</label>

<input
type="text"
class="form-control"
value="<?php echo htmlspecialchars($user['fullname']); ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
class="form-control"
value="<?php echo htmlspecialchars($user['email']); ?>"
readonly>

</div>

</div>

<div class="mb-3">

<label>Subject</label>

<input
type="text"
name="subject"
class="form-control"
placeholder="Enter subject">

</div>

<div class="mb-3">

<label>Message</label>

<textarea
class="form-control"
name="message"
rows="6"
placeholder="Describe your issue..."></textarea>

</div>

<button
type="submit"
name="send"
class="send-btn">

<i class="fa-solid fa-paper-plane"></i>

Send Message

</button>

</form>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/contact.js"></script>

</body>
</html>