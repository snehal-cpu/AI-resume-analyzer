<?php

require_once "config.php";

function analyzeResume($resumeText, $jobProfile)
{
    $url = "https://api.groq.com/openai/v1/chat/completions";

    $prompt = "
You are an ATS Resume Analyzer.

Analyze the following resume for the job profile: $jobProfile.

Return ONLY valid JSON in this exact format:

{
  \"score\":85,
  \"strengths\":\"Point 1, Point 2, Point 3\",
  \"weaknesses\":\"Point 1, Point 2\",
  \"suggestions\":\"Point 1, Point 2, Point 3\"
}

Resume:

$resumeText
";

    $data = [
        "model" => GROQ_MODEL,
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt
            ]
        ],
        "temperature" => 0.2
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . GROQ_API_KEY
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {

        return [
            "error" => curl_error($ch)
        ];

    }

    curl_close($ch);

    $result = json_decode($response, true);

    if (!isset($result["choices"][0]["message"]["content"])) {

        return [
            "error" => "Invalid response from Groq",
            "raw" => $response
        ];

    }

    $content = trim($result["choices"][0]["message"]["content"]);

    // Remove Markdown code fences if present
    $content = preg_replace('/^```json\s*/', '', $content);
    $content = preg_replace('/^```\s*/', '', $content);
    $content = preg_replace('/```$/', '', $content);

    $analysis = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {

        return [
            "error" => "JSON parsing failed",
            "raw" => $content
        ];

    }

    return $analysis;
}
?>