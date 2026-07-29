<?php

function calculateATS($resumeText)
{
    $text = strtolower($resumeText);

    // ====================================
    // TECHNICAL SKILLS
    // ====================================

    $skills = [

        // Programming Languages
        "c","c++","java","python","php","javascript","typescript",

        // Web
        "html","css","bootstrap","tailwind","react","angular","vue",
        "node.js","express","laravel",

        // Database
        "mysql","mongodb","sqlite","postgresql","oracle",

        // Version Control
        "git","github",

        // Cloud
        "aws","azure","gcp","firebase",

        // DevOps
        "docker","kubernetes",

        // AI
        "machine learning","artificial intelligence","tensorflow",
        "opencv","nlp",

        // Tools
        "figma","postman","linux","rest api","api"
    ];

    $foundSkills = [];

    foreach($skills as $skill)
    {
        if(stripos($text,$skill)!==false)
        {
            $foundSkills[] = $skill;
        }
    }

    $foundSkills = array_unique($foundSkills);

    $skillCount = count($foundSkills);

    $totalSkills = count($skills);

    $skillsScore = round(($skillCount/$totalSkills)*40);

    if($skillsScore>40)
    {
        $skillsScore=40;
    }

    // ====================================
    // PROJECT SCORE (20)
    // ====================================

    $projectScore = 0;

    $projectKeywords = [

        "project",
        "projects",
        "developed",
        "created",
        "built",
        "application",
        "website",
        "system"

    ];

    $projectFound = 0;

    foreach($projectKeywords as $word)
    {
        if(stripos($text,$word)!==false)
        {
            $projectFound++;
        }
    }

    if($projectFound>=6)
        $projectScore=20;

    elseif($projectFound>=4)
        $projectScore=15;

    elseif($projectFound>=2)
        $projectScore=10;

    else
        $projectScore=5;

    // ====================================
    // EXPERIENCE (15)
    // ====================================

    $experienceScore = 0;

    if(
        stripos($text,"internship")!==false ||
        stripos($text,"experience")!==false ||
        stripos($text,"worked")!==false ||
        stripos($text,"employment")!==false
    )
    {
        $experienceScore=15;
    }
    else
    {
        $experienceScore=5;
    }

    // ====================================
    // EDUCATION (15)
    // ====================================

    $educationScore = 0;

    if(
        stripos($text,"engineering")!==false ||
        stripos($text,"b.tech")!==false ||
        stripos($text,"bachelor")!==false ||
        stripos($text,"computer science")!==false
    )
    {
        $educationScore=15;
    }
    elseif(
        stripos($text,"diploma")!==false
    )
    {
        $educationScore=10;
    }
    else
    {
        $educationScore=5;
    }

    // ====================================
    // RESUME SECTIONS (10)
    // ====================================

    $sections = [

        "summary",
        "education",
        "skills",
        "projects",
        "experience",
        "contact"

    ];

    $sectionCount=0;

    foreach($sections as $section)
    {
        if(stripos($text,$section)!==false)
        {
            $sectionCount++;
        }
    }

    $sectionScore=min(10,$sectionCount*2);

    // ====================================
    // FINAL ATS
    // ====================================

    $atsScore =
        $skillsScore+
        $projectScore+
        $experienceScore+
        $educationScore+
        $sectionScore;

    if($atsScore>100)
    {
        $atsScore=100;
    }

    // ====================================
    // GRADE
    // ====================================

    if($atsScore>=90)
        $grade="A+";
    elseif($atsScore>=80)
        $grade="A";
    elseif($atsScore>=70)
        $grade="B";
    elseif($atsScore>=60)
        $grade="C";
    else
        $grade="Needs Improvement";

    $missingSkills=array_values(array_diff($skills,$foundSkills));

    return [

        "ats_score"=>$atsScore,

        "grade"=>$grade,

        "skills_found"=>$skillCount,

        "total_skills"=>$totalSkills,

        "found_skills"=>$foundSkills,

        "missing_skills"=>$missingSkills,

        "skills_score"=>$skillsScore,

        "project_score"=>$projectScore,

        "experience_score"=>$experienceScore,

        "education_score"=>$educationScore,

        "section_score"=>$sectionScore

    ];
}
?>