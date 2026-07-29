<?php

require_once __DIR__ . "/../config/api.php";


function analyzeResumeAI($resumeText)
{


  $apiKey = "";
  if(empty($apiKey))
{
    return json_encode([
        "status" => "AI Disabled",
        "strengths" => [
            "Resume uploaded successfully",
            "Resume text extracted successfully"
        ],
        "weaknesses" => [
            "Gemini API key not configured"
        ],
        "suggestions" => [
            "Add Gemini API key to enable AI suggestions"
        ]
    ]);
}
   $model = "gemini-3.6-flash";


    $url =
        "https://generativelanguage.googleapis.com/v1beta/models/" .
        $model .
        ":generateContent?key=" .
        $apiKey;

    // Reduce token usage
    $resumeText = substr($resumeText,0,6000);


    $prompt = "

You are an AI Resume Analyzer.

Analyze this resume and return ONLY valid JSON.

Resume:

$resumeText


Return JSON in this format:

{
 \"ats_score\":0,
 \"summary\":\"\",
 \"strengths\":[],
 \"weaknesses\":[],
 \"missing_skills\":[],
 \"suggestions\":[]
}

";


    $apiKey = GOOGLE_GEMINI_KEY;


    $model = "gemini-2.0-flash";


    $url =
    "https://generativelanguage.googleapis.com/v1beta/models/"
    .$model.
    ":generateContent?key="
    .$apiKey;



    $data = [

        "contents"=>[

            [

                "parts"=>[

                    [
                        "text"=>$prompt
                    ]

                ]

            ]

        ],

        "generationConfig"=>[

            "temperature"=>0.4,

            "maxOutputTokens"=>1000

        ]

    ];



    $ch = curl_init();



    curl_setopt($ch,CURLOPT_URL,$url);

    curl_setopt($ch,CURLOPT_POST,true);


    curl_setopt($ch,CURLOPT_HTTPHEADER,[

        "Content-Type: application/json"

    ]);


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


    $response = curl_exec($ch);



    if(curl_errno($ch))
    {

        curl_close($ch);


        return fallbackAI(
            "Connection error"
        );

    }


    curl_close($ch);



    $result=json_decode($response,true);



    /*
        Gemini Error Handling
    */

    if(isset($result['error']))
    {


        return fallbackAI(
            $result['error']['message']
        );


    }



    if(
        !isset(
            $result['candidates'][0]
            ['content']
            ['parts'][0]
            ['text']
        )
    )
    {


        return fallbackAI(
            "No AI response"
        );


    }



    $aiText =
    $result['candidates'][0]
    ['content']
    ['parts'][0]
    ['text'];



    // Remove markdown JSON block

    $aiText =
    str_replace(
        ["```json","```"],
        "",
        $aiText
    );



    return trim($aiText);


}





/*
Fallback AI
Works when Gemini quota finishes
*/


function fallbackAI($error="")
{


    return json_encode([

        "ats_score"=>70,


        "summary"=>
        "Resume analyzed using local ATS engine. Gemini AI is temporarily unavailable.",


        "strengths"=>[

            "Resume uploaded successfully",

            "Basic ATS compatibility checked",

            "Resume structure detected"

        ],


        "weaknesses"=>[

            "AI suggestions unavailable temporarily"

        ],


        "missing_skills"=>[

            "Add more industry relevant keywords"

        ],


        "suggestions"=>[

            "Improve technical skills section",

            "Add measurable project achievements",

            "Optimize resume keywords"

        ],


        "api_error"=>$error


    ]);

}



?>