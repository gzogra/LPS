<?php

$api_key = $_POST['api_openai'];
$url = "https://api.openai.com/v1/chat/completions";

$systemPrompt = "You are a machine learning engineer assistant. You are answers must be clear and precise. Help create a dataset on user's preferences. Given the users input create a new prompt in the domain specified by it.";

$userMessage = "'".$_POST['sample_prompt']."'";
$userInstruction = $_POST['guidelines']; 
$temp=$_POST['total_prompts'];
$model = $_POST['llm_generator'];
$threshold = $_POST['sample_prompt'];

$generated_prompts = array();


for($i=1; $i<=$temp; $i++){
    
    // Generate similar wuestion to the users sample
    $promptInjection = "Exercise Sample: " .$userMessage . "Do not provide solution of the exersice, i want you to create just a similar exercise in context domain semantically. Do not include in your answer the literal 'Example Exercise'";
    
    $data = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt ],
            ['role' => 'user', 'content' => $promptInjection ],
        ],
        'temperature'=>0.75
    ];
    
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $responseData = json_decode($response, true);
    $responseContent = $responseData['choices'][0]['message']['content'];
    
    $max_retries = 0;
    $judge_threshold = 0;
    
    while(($threshold<=$judge_threshold) || ($max_retries==3)){
        
    // Using the generated response from the model we generate a solution to the query
        $new_data = "Folowing the guidelines defined by the user create a solution to user query. ";
        $new_data .= "User Input: '" . $responseContent . "' "; 
        $new_data .= "User Guidelines: '" . $userInstruction . "' "; 
        
        $data_solution = [
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $new_data ],
            ],
            'temperature'=>0.75
        ];
        
        $ch_solution = curl_init($url);
        curl_setopt($ch_solution, CURLOPT_POST, 1);
        curl_setopt($ch_solution, CURLOPT_POSTFIELDS, json_encode($data_solution));
        curl_setopt($ch_solution, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_solution, CURLOPT_HTTPHEADER, $headers);
        
        $response_solution = curl_exec($ch_solution);
        $responseData_solution = json_decode($response_solution, true);
        $responseContent_solution = $responseData_solution['choices'][0]['message']['content'];
        
    // At this point we have a generated prompt and a generated answer for this specific prompt.
    // Now we need to evaluate if the answer to thw prompt meets the user's preferences 
            
        $for_judgment = "According to the folowing criteria:" . $userInstruction . " evaluate the provided answer to the following prompt: '". $responseContent . "' Answer: '" .$responseContent_solution. "';
        $for_judgment .= Never forget to assign an average score from 0(none of the criteria fulfilled) to 10 (all criteria fulfilled). If multiple criteria are aplied return explicitly the overall score. The finaly assigned score must be one number and always an integer number. Your response must be only the score without any more text.";
                
        $data_j = [
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $for_judgment ],
            ],
            'temperature'=>0
        ];
        
        $ch_j = curl_init($url);
        curl_setopt($ch_j, CURLOPT_POST, 1);
        curl_setopt($ch_j, CURLOPT_POSTFIELDS, json_encode($data_j));
        curl_setopt($ch_j, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_j, CURLOPT_HTTPHEADER, $headers);
        
        $response_j = curl_exec($ch_j);
        $responseData_j = json_decode($response_j, true);
        $responseContent_j = $responseData_j['choices'][0]['message']['content'];
        
        preg_match_all('/\d+/', $responseContent_j, $found);
        $numbers = $found[0];
        $judge_threshold = $numbers[0];
        
        $max_retries++;
    }

    
    $temp_array = array("query" => $responseContent, "solution" => $responseContent_solution, "judge_evaluation" => $responseContent_j);
    $temp_array = array_map('utf8_encode', $temp_array);
    array_push($generated_prompts, $temp_array);


}

date_default_timezone_set('Europe/Athens');
$datetime = date('Y-m-d_H-i-s');
$filename = 'example_' . $datetime . '.json';
$filepath = 'saved_datasets/' . $filename;

$json = json_encode($generated_prompts);
file_put_contents($filepath, $json);

echo json_encode(array('filename' => $filename, 'filepath' => $filepath));

?>