# 🤖 AI Resume Analyzer

An AI-powered web application that analyzes resumes, evaluates their ATS compatibility, identifies strengths and weaknesses, recommends suitable job roles, and provides actionable suggestions to improve the resume.

---

## 📌 Overview

Many candidates submit resumes without knowing how well they perform against Applicant Tracking Systems (ATS).

**AI Resume Analyzer** helps users understand and improve their resumes before applying for jobs.

The application combines **ATS-based resume evaluation** with **AI-powered analysis** to generate a detailed resume report.

---

## ✨ Features

### 📄 Resume Upload

* Upload your resume for analysis.
* Extract resume text for processing.
* Supports resume-based analysis workflow.

### 📊 ATS Score

* Generates an ATS compatibility score from **0–100**.
* Displays the score visually using a circular progress indicator.
* Assigns a resume grade based on the score.

| Score  | Grade | Status            |
| ------ | ----- | ----------------- |
| 90–100 | A+    | Excellent Resume  |
| 80–89  | A     | Strong Resume     |
| 70–79  | B     | Good Resume       |
| 60–69  | C     | Needs Improvement |
| 0–59   | D     | Weak Resume       |

### 🤖 AI Resume Analysis

The AI analyzes the resume and provides:

* Strengths
* Weaknesses
* Missing skills
* Improvement suggestions
* Recommended job roles
* Interview questions
* Improved resume content

### 💡 AI Suggestions

Provides actionable recommendations to improve resume quality, skills, projects, achievements, and overall presentation.

### 💼 Recommended Job Roles

Suggests suitable job roles based on the candidate's resume and skills.

### 🎯 Missing Skills

Identifies potentially missing skills that can improve the candidate's suitability for relevant roles.

### 🗣️ Interview Preparation

Generates interview questions based on the resume to help candidates prepare for interviews.

### 📝 AI Improved Resume

Generates an improved version of the resume content using AI-based suggestions.

### 📚 Analysis Reports

Users can view previously generated resume analysis reports.

### 🔐 Authentication

* User registration
* User login
* Session-based authentication
* Protected user pages

---

## 🛠️ Tech Stack

### Frontend

* HTML5
* CSS3
* JavaScript
* Font Awesome

### Backend

* PHP
* MySQL

### AI

* Google Gemini API

### Development Environment

* XAMPP
* Apache
* MySQL
* phpMyAdmin

---

## 🏗️ Project Structure

```text
AI-resume-analyzer/
│
├── ai/
│   └── gemini.php
│
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── dashboard.css
│   │   └── result.css
│   │
│   └── js/
│       └── result.js
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── config/
│   └── db.php
│
├── includes/
│   └── sidebar.php
│
├── analyze_resume.php
├── ats_engine.php
├── process_analysis.php
├── reports.php
├── result.php
├── upload.php
├── profile.php
├── index.php
│
└── README.md
```

> The exact project structure may vary depending on the current version of the project.

---

## ⚙️ How It Works

```text
        ┌─────────────────┐
        │   User Login    │
        └────────┬────────┘
                 │
                 ▼
        ┌─────────────────┐
        │ Upload Resume   │
        └────────┬────────┘
                 │
                 ▼
        ┌─────────────────┐
        │ Extract Resume  │
        │      Text       │
        └────────┬────────┘
                 │
          ┌──────┴──────┐
          ▼             ▼
 ┌────────────────┐ ┌────────────────┐
 │   ATS Engine   │ │   AI Analysis  │
 │                │ │                │
 │ Score 0–100    │ │ Gemini API     │
 └───────┬────────┘ └───────┬────────┘
         │                  │
         └────────┬─────────┘
                  ▼
        ┌───────────────────┐
        │ Analysis Report   │
        ├───────────────────┤
        │ ATS Score         │
        │ Strengths         │
        │ Weaknesses        │
        │ Missing Skills    │
        │ Job Roles         │
        │ Suggestions       │
        │ Interview Qs      │
        │ Improved Resume   │
        └─────────┬─────────┘
                  │
                  ▼
        ┌───────────────────┐
        │ Save to MySQL     │
        └───────────────────┘
```

---

## 🚀 Installation

### 1. Install XAMPP

Install XAMPP and start:

* Apache
* MySQL

### 2. Clone the Repository

```bash
git clone https://github.com/snehal-cpu/AI-resume-analyzer.git
```

Move the project into:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\AI-resume-analyzer
```

### 3. Create the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a database:

```text
ai_resume
```

Import the project's SQL database file if available.

### 4. Configure Database

Open:

```text
config/db.php
```

Configure your MySQL credentials:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "ai_resume";
```

### 5. Configure AI API

Open:

```text
ai/gemini.php
```

Add your Gemini API key using a secure configuration method.

**Do not commit API keys to GitHub.**

### 6. Start the Application

Make sure Apache and MySQL are running in XAMPP.

Open:

```text
http://localhost/AI-resume-analyzer/
```

---

## 🔑 Environment & Security

Never upload API keys or passwords to GitHub.

Use environment variables or a local configuration file.

For example:

```text
.env
```

Add sensitive files to `.gitignore`:

```gitignore
.env
config/secrets.php
```

If an API key has accidentally been pushed to a public repository, revoke it and generate a new one.

---

## 🗄️ Database

The application uses MySQL to store:

* User accounts
* Uploaded resume information
* ATS scores
* AI analysis
* Resume improvement suggestions
* Recommended roles
* Interview questions

The main analysis table is:

```text
resume_analysis
```

Important fields include:

```text
resume_id
ats_score
strengths
weaknesses
missing_skills
suggestions
job_roles
interview_questions
improved_resume
```

---

## 📊 ATS Scoring

The ATS engine evaluates the resume using predefined resume-quality criteria.

The final score is normalized to a value between:

```text
0 – 100
```

The score is then converted into a grade:

```text
90+  → A+
80+  → A
70+  → B
60+  → C
Below 60 → D
```

---

## 🧪 Testing

Before deployment, test:

* Registration
* Login/logout
* Resume upload
* Resume text extraction
* ATS score generation
* AI analysis
* Database insertion
* Report generation
* Report history
* View report
* Invalid resume IDs
* Empty resume submissions
* AI/API failure handling

---

## 🔮 Future Improvements

Planned improvements can include:

* 📈 ATS score analytics
* 📊 Resume score history
* 🎨 Improved dashboard UI
* 🌙 Dark/light theme
* 📄 Multiple resume formats
* 🎯 Job-description matching
* 🔍 Keyword optimization
* 📥 Download analysis as PDF
* 🔗 Job recommendation integration
* 👤 Personalized career recommendations
* 📱 Improved mobile responsiveness
* 🔐 Stronger security and validation

---

## 🎯 Project Goals

The main goal of **AI Resume Analyzer** is to help students and job seekers:

* Understand how ATS systems evaluate resumes
* Identify weaknesses in their resumes
* Discover missing skills
* Improve resume content
* Prepare for interviews
* Increase their chances of getting shortlisted

---

## 👩‍💻 Developer

**Snehal Jagtap**

Computer Engineering Student
Pune, Maharashtra, India

GitHub: **snehal-cpu**

---

## ⭐ Contributing

Contributions and suggestions are welcome.

If you would like to improve the project:

```bash
git fork
git clone
git checkout -b feature/your-feature
```

Make your changes, test them, and submit a pull request.

---

## 📜 License

This project is developed for educational and portfolio purposes.

---

## ⭐ Support

If you find this project useful, consider giving the repository a ⭐ on GitHub.

---

**Built with PHP, MySQL, JavaScript, and AI to make resumes smarter. 🤖📄**
