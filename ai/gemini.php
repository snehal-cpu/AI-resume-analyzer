<?php

require_once __DIR__ . "/../config/api.php";


function analyzeResumeAI($resumeText)
{
    // Get Gemini API key from environment variable
  $apiKey = require __DIR__ . "/../config/gemini_key.php";

    if (empty($apiKey)) {
        return fallbackAI("Gemini API key not configured");
    }

    // Clean resume text
    $resumeText = trim($resumeText);

    if (empty($resumeText)) {
        return fallbackAI("Resume text is empty");
    }

    // Limit text sent to Gemini
    $resumeText = substr($resumeText, 0, 6000);

    // Gemini model
   $model = "gemini-3.6-flash";

    $url =
        "https://generativelanguage.googleapis.com/v1beta/models/" .
        $model .
        ":generateContent?key=" .
        $apiKey;


    /*
    ==========================================================
    AI PROMPT
    ==========================================================
    */

    $prompt = <<<PROMPT

You are a professional ATS Resume Analyzer.

Your job is to analyze ONLY the resume text provided below.

IMPORTANT RULES:

1. Do NOT invent information.
2. Do NOT assume the candidate has skills, experience, projects,
   certifications or achievements that are not present in the resume.
3. Every strength must be supported by actual information from the resume.
4. Weaknesses must identify real missing, weak, unclear or poorly
   presented information.
5. Do not give generic praise such as "excellent resume" or
   "good technical skills" unless the resume provides evidence.
6. If an important section is missing, mention it as a weakness.
7. Analyze the actual technical skills, projects, education,
   experience and achievements found in the resume.
8. Check whether achievements contain measurable results.
9. Check whether projects describe technologies and contributions.
10. Identify potentially missing skills based on the technologies
    and roles mentioned in the resume.
11. ATS score must reflect the actual quality of THIS resume.
12. Do not give a perfect score unless the resume genuinely deserves it.
13. Return ONLY valid JSON. No markdown and no explanation outside JSON.

RESUME:

$resumeText


Return exactly this JSON structure:

{
    "ats_score": 0,
    "summary": "",
    "strengths": [],
    "weaknesses": [],
    "missing_skills": [],
    "suggestions": [],
    "job_roles": [],
    "interview_questions": [],
    "improved_resume": ""
}

For strengths:
Give 3-6 specific strengths based on evidence from the resume.

For weaknesses:
Give 3-6 specific weaknesses or missing information.

For missing_skills:
Mention skills that would reasonably improve the candidate's profile
based on the roles/projects already present in the resume.
Do not randomly add unrelated skills.

For suggestions:
Give practical improvements specific to this resume.

For job_roles:
Suggest suitable entry-level roles based ONLY on the candidate's
actual skills and projects.

For interview_questions:
Create questions based on technologies, projects and experience
actually present in the resume.

For improved_resume:
Give concise suggestions for improving the resume content and
structure. Do not invent achievements.

PROMPT;


    /*
    ==========================================================
    GEMINI REQUEST
    ==========================================================
    */

    $data = [

        "contents" => [

            [

                "parts" => [

                    [
                        "text" => $prompt
                    ]

                ]

            ]

        ],

        "generationConfig" => [

    "temperature" => 0.2,

    "maxOutputTokens" => 4000,

    "responseMimeType" => "application/json",

    "responseSchema" => [

        "type" => "OBJECT",

        "properties" => [

            "ats_score" => [
                "type" => "INTEGER"
            ],

            "summary" => [
                "type" => "STRING"
            ],

            "strengths" => [
                "type" => "ARRAY",
                "items" => [
                    "type" => "STRING"
                ]
            ],

            "weaknesses" => [
                "type" => "ARRAY",
                "items" => [
                    "type" => "STRING"
                ]
            ],

            "missing_skills" => [
                "type" => "ARRAY",
                "items" => [
                    "type" => "STRING"
                ]
            ],

            "suggestions" => [
                "type" => "ARRAY",
                "items" => [
                    "type" => "STRING"
                ]
            ],

            "job_roles" => [
                "type" => "ARRAY",
                "items" => [
                    "type" => "STRING"
                ]
            ],

            "interview_questions" => [
                "type" => "ARRAY",
                "items" => [
                    "type" => "STRING"
                ]
            ],

            "improved_resume" => [
                "type" => "STRING"
            ]

        ],

        "required" => [
            "ats_score",
            "summary",
            "strengths",
            "weaknesses",
            "missing_skills",
            "suggestions",
            "job_roles",
            "interview_questions",
            "improved_resume"
        ]

    ]

]

    ];


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            "Content-Type: application/json"
        ]
    );

    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        json_encode($data)
    );

    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );

  curl_setopt(
    $ch,
    CURLOPT_TIMEOUT,
    180
);

curl_setopt(
    $ch,
    CURLOPT_CONNECTTIMEOUT,
    30
);


    $response = curl_exec($ch);


    /*
    ==========================================================
    CURL ERROR
    ==========================================================
    */

    if (curl_errno($ch)) {

        $error = curl_error($ch);

        curl_close($ch);

        return fallbackAI(
            "Connection error: " . $error
        );
    }


    curl_close($ch);


    /*
    ==========================================================
    DECODE GEMINI RESPONSE
    ==========================================================
    */

    $result = json_decode($response, true);


    if (!$result) {

        return fallbackAI(
            "Invalid response from Gemini"
        );
    }


    /*
    ==========================================================
    GEMINI API ERROR
    ==========================================================
    */

    if (isset($result['error'])) {

        return fallbackAI(
            $result['error']['message'] ?? "Gemini API error"
        );
    }


    /*
    ==========================================================
    GET AI TEXT
    ==========================================================
    */

    if (
        !isset(
            $result['candidates'][0]
            ['content']
            ['parts'][0]
            ['text']
        )
    ) {

        return fallbackAI(
            "Gemini returned no analysis"
        );
    }


    $aiText =
        $result['candidates'][0]
        ['content']
        ['parts'][0]
        ['text'];


    /*
    ==========================================================
    CLEAN JSON
    ==========================================================
    */

    $aiText = trim($aiText);

    $aiText = str_replace(
        [
            "```json",
            "```"
        ],
        "",
        $aiText
    );

    $aiText = trim($aiText);


    /*
    ==========================================================
    VALIDATE JSON
    ==========================================================
    */

    $decoded = json_decode($aiText, true);


    if (
        json_last_error() !== JSON_ERROR_NONE ||
        !is_array($decoded)
    ) {

        return fallbackAI(
            "Gemini returned invalid JSON"
        );
    }


    /*
    ==========================================================
    MAKE SURE REQUIRED FIELDS EXIST
    ==========================================================
    */

    $decoded['ats_score'] =
        intval($decoded['ats_score'] ?? 0);

    $decoded['summary'] =
        $decoded['summary'] ?? "";

    $decoded['strengths'] =
        is_array($decoded['strengths'] ?? null)
        ? $decoded['strengths']
        : [];

    $decoded['weaknesses'] =
        is_array($decoded['weaknesses'] ?? null)
        ? $decoded['weaknesses']
        : [];

    $decoded['missing_skills'] =
        is_array($decoded['missing_skills'] ?? null)
        ? $decoded['missing_skills']
        : [];

    $decoded['suggestions'] =
        is_array($decoded['suggestions'] ?? null)
        ? $decoded['suggestions']
        : [];

    $decoded['job_roles'] =
        is_array($decoded['job_roles'] ?? null)
        ? $decoded['job_roles']
        : [];

    $decoded['interview_questions'] =
        is_array($decoded['interview_questions'] ?? null)
        ? $decoded['interview_questions']
        : [];

    $decoded['improved_resume'] =
        $decoded['improved_resume'] ?? "";


    /*
    ==========================================================
    RETURN ARRAY
    ==========================================================
    */

    return $decoded;
}


/*
==============================================================
FALLBACK
==============================================================
*/

function fallbackAI($error = "")
{
    return [

        "ats_score" => 0,

        "summary" =>
            "AI analysis could not be completed.",

        "strengths" => [],

        "weaknesses" => [
            "AI analysis is currently unavailable."
        ],

        "missing_skills" => [],

        "suggestions" => [
            "Please try analyzing the resume again."
        ],

        "job_roles" => [],

        "interview_questions" => [],

        "improved_resume" => "",

        "api_error" => $error

    ];
}

?>