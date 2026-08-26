<!DOCTYPE html>
<html lang="en">

<head>

<style>
html{
    scroll-behavior:smooth;
}
</style>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Resume Analyzer</title>
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="assets/css/home.css">



</head>

<body>

<header>

<div class="container navbar">

<div class="logo">

<h2>AI Resume Analyzer</h2>

</div>

<nav>

<ul>

<li>
<a href="#home" class="nav-link active">Home</a>
</li>

<li>
<a href="#about" class="nav-link">About</a>
</li>

<li>
<a href="#features" class="nav-link">Features</a>
</li>

</ul>

</nav>

<div class="buttons">

<a href="./auth/login.php" class="login-btn">Login</a>

<a href="./auth/register.php" class="register-btn">Register</a>

 <button id="theme-toggle" class="theme-btn">
        <i class="fa-solid fa-moon"></i>
    </button>

</div>

</div>


</header>

<section class="hero" id="home">

<div class="container hero-container">

<div class="hero-text">

<span class="tag">🚀 AI Powered Resume Analysis</span>

<h1>
Build a Resume
That Gets You Hired
</h1>

<p>

Analyze your resume using Artificial Intelligence.
Receive ATS scores, identify missing skills,
improve formatting, and get personalized career recommendations.

</p>

<div class="hero-buttons">
<div class="shape shape1"></div>
<div class="shape shape2"></div>
<a href="./auth/register.php" class="primary-btn">
Get Started
</a>

<a href="#features" class="secondary-btn">
Learn More
</a>

</div>

</div>

<div class="hero-image">

<img src="assets/images/hero.png" alt="AI Resume Analyzer">

</div>

</div>

</section>

<!-- Features Section -->

<section class="features" id="features">

    <div class="container">

        <div class="section-title">

            <h2>Why Choose AI Resume Analyzer?</h2>

            <p>
                Powerful AI tools designed to help students and professionals
                create ATS-friendly resumes and improve their chances of getting hired.
            </p>

        </div>

        <div class="feature-grid">

            <div class="feature-card">
                <i class="fa-solid fa-file-lines"></i>
                <h3>Resume Analysis</h3>
                <p>Analyze your resume with AI and identify areas for improvement.</p>
            </div>

            <div class="feature-card">
                <i class="fa-solid fa-chart-line"></i>
                <h3>ATS Score</h3>
                <p>Check whether your resume is optimized for Applicant Tracking Systems.</p>
            </div>

            <div class="feature-card">
                <i class="fa-solid fa-brain"></i>
                <h3>Skill Detection</h3>
                <p>Automatically identify technical and soft skills from your resume.</p>
            </div>

            <div class="feature-card">
                <i class="fa-solid fa-lightbulb"></i>
                <h3>Smart Suggestions</h3>
                <p>Receive personalized recommendations to strengthen your resume.</p>
            </div>

            <div class="feature-card">
                <i class="fa-solid fa-briefcase"></i>
                <h3>Job Match</h3>
                <p>Compare your resume against job descriptions to identify missing keywords.</p>
            </div>

            <div class="feature-card">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Secure Storage</h3>
                <p>Your resumes are securely stored and protected.</p>
            </div>

        </div>

    </div>

</section>


<!-- How It Works -->

<section class="how-it-works" id="about">

    <div class="container">

        <div class="section-title">
            <h2>How It Works</h2>
            <p>
                Get professional AI-powered resume feedback in just four simple steps.
            </p>
        </div>

        <div class="steps">

            <div class="step">
                <div class="circle">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3>Create Account</h3>
                <p>Register and create your personal account securely.</p>
            </div>

            <div class="line"></div>

            <div class="step">
                <div class="circle">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h3>Login</h3>
                <p>Access your personalized dashboard.</p>
            </div>

            <div class="line"></div>

            <div class="step">
                <div class="circle">
                    <i class="fa-solid fa-file-arrow-up"></i>
                </div>
                <h3>Upload Resume</h3>
                <p>Upload your PDF or DOCX resume securely.</p>
            </div>

            <div class="line"></div>

            <div class="step">
                <div class="circle">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <h3>AI Analysis</h3>
                <p>Receive ATS score and smart recommendations instantly.</p>
            </div>

        </div>

    </div>

</section>

<!-- Testimonials -->

<section class="testimonials">

    <div class="container">

        <div class="section-title">
            <h2>What Our Users Say</h2>
            <p>Hear from students and job seekers who improved their resumes with AI Resume Analyzer.</p>
        </div>

        <div class="testimonial-grid">

            <div class="testimonial-card">
                <div class="stars">
                    ★★★★★
                </div>
                <p>
                    "The ATS suggestions helped me improve my resume, and I got shortlisted for interviews."
                </p>
                <h4>Rahul Sharma</h4>
                <span>Computer Engineering Student</span>
            </div>

            <div class="testimonial-card">
                <div class="stars">
                    ★★★★★
                </div>
                <p>
                    "The AI feedback was clear and easy to understand. My resume looks much more professional now."
                </p>
                <h4>Sneha Patel</h4>
                <span>Fresher</span>
            </div>

            <div class="testimonial-card">
                <div class="stars">
                    ★★★★★
                </div>
                <p>
                    "The skill detection and ATS score gave me confidence before applying for internships."
                </p>
                <h4>Arjun Mehta</h4>
                <span>Software Developer Intern</span>
            </div>

        </div>

    </div>

</section>

<section class="faq">

<div class="container">

<div class="section-title">

<h2>Frequently Asked Questions</h2>

</div>

<div class="faq-container">

<details>
<summary>Is my resume secure?</summary>
<p>Yes. Your resume is stored securely and only accessible through your account.</p>
</details>

<details>
<summary>Which file formats are supported?</summary>
<p>Currently, PDF and DOCX files are supported.</p>
</details>

<details>
<summary>How is the ATS score calculated?</summary>
<p>The AI evaluates formatting, keywords, readability, skills, and overall ATS compatibility.</p>
</details>

<details>
<summary>Can I analyze multiple resumes?</summary>
<p>Yes. You can upload and analyze multiple resumes from your dashboard.</p>
</details>

</div>

</div>

</section>

<footer>

<div class="container footer-grid">

<div>

<h2>AI Resume Analyzer</h2>

<p>
Empowering students and professionals with AI-powered resume analysis.
</p>

</div>

<div>

<h3>Quick Links</h3>

<ul>
<li><a href="#">Home</a></li>
<li><a href="#features">Features</a></li>
<li><a href="#">About</a></li>
<li><a href="#">Contact</a></li>
</ul>

</div>

<div>

<h3>Contact</h3>

<p>Email: resumeanalyze.team6@gmail.com</p>

<p>Phone: +91 XXXXX XXXXX</p>

</div>

</div>

<div class="copyright">

© 2026 AI Resume Analyzer. All Rights Reserved.

</div>

</footer>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({
    duration:1000,
    once:true
});
</script>



</body>
</html>