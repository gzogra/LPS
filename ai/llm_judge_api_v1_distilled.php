<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit();
}

header("Content-Type: application/json");

$openai_judge_selector = ["gpt-4o-mini"];
$anthropic_judge_selector = ["claude-3-5-sonnet-20240620"];
$aiml_judge_selector = ["meta-llama/Llama-3.2-90B-Vision-Instruct-Turbo", "gemini-1.5-pro"];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    
    $input = json_decode(file_get_contents('php://input'), true);
    $received_input = file_get_contents('php://input');
    $temperature = 0;
    
    if(isset($input['modelSelection']) && $input['modelSelection']!=1){
        
        if (in_array($input['judge'], $openai_judge_selector))
        {
            if ($input['model_response']!="")
            {
                $url = "https://api.openai.com/v1/chat/completions";
                
                $model_judge = $input['judge'];
                $provided_context = $input['prompt_context'] ?? '';
                $for_judgment = "According to the folowing criteria:" . $input['judge_prompt'] . " evaluate the provided answer to the following prompt: '". $input['prompt'] . "' Answer: '" .$input['model_response']. "'. Never forget to assign a score if asked on criteria.";
                    
                $data = [
                    'model' => $model_judge,
                    'max_completion_tokens' => (int) $input['tokens'],
                    'temperature' => $temperature,
                    'messages' => [
                        ['role' => 'assistant', 'content' => $provided_context ],
                        ['role' => 'user', 'content' => $for_judgment ],
                    ]
                ];
            
                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $input['openaiApiKey'],
                ];
            
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
                $response = curl_exec($ch);
                echo $response;

            } else {
                $json = array(
                  'Result' => "Not enough parameters given!"
                );
                echo json_encode($json);
            }
            
        } 
        elseif (in_array($input['judge'], $anthropic_judge_selector))
        {
            if(isset($input['anthropicApikey']) && ($input['model_response']!="")){
                
                $model_judge = $input['judge'];
                $url = 'https://api.anthropic.com/v1/messages';
                
                if($input['prompt_context']!=""){
                        $provided_context = "Context: '" . $input['prompt_context'] . "' User:'".$input['prompt']."'";
                    } else {
                        $provided_context = $input['prompt'];
                    }
                    
                $for_judgment = "According to the folowing criteria:" . $input['judge_prompt'] . " evaluate the provided answer to the following prompt: '". $input['prompt'] . "' Answer: '" .$input['model_response']. "'. Never forget to assign a score if asked on criteria.";
                    
                $data = [
                    'model' => $model_judge,
                    'max_tokens' => (int) $input['tokens'],
                    'temperature' => (float) $temperature,
                    'messages' => [
                        ['role' => 'user', 'content' => $for_judgment]
                    ]
                ];
                
                $headers = [
                    'Content-Type: application/json',
                    'X-Api-Key: ' . $input['anthropicApikey'],
                    'Anthropic-Version: 2023-06-01'
                ];
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $response = curl_exec($ch);
                echo $response;

                } else{
                    $json = array(
                      'Result' => "Not enough parameters given!"
                    );
                    echo json_encode($json);
                }
        }
        elseif (in_array($input['judge'], $aiml_judge_selector))
        {
            if(isset($input['aimlApikey']) && ($input['model_response']!="")){
                        
                $model_judge = $input['judge'];
                $url = 'https://api.aimlapi.com/chat/completions';
                
                $provided_context = $input['prompt_context'] ?? '';
                $for_judgment = "According to the folowing criteria:" . $input['judge_prompt'] . " evaluate the provided answer to the following prompt: '". $input['prompt'] . "' Answer: '" .$input['model_response']. "'. Never forget to assign a score if asked on criteria.";
                   
                $data = [
                    'model' => $model_judge,
                    'max_tokens' => (int) $input['tokens'],
                    'temperature' => (float) $temperature,
                    'messages' => [
                        ['role' => 'user', 'content' => $for_judgment]
                    ]
                ];
                
                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $input['aimlApikey'],
                ];
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $response = curl_exec($ch);
                echo $response;
                
            } else{
                $json = array(
                  'Result' => "Not enough parameters given!"
                );
                echo json_encode($json);
            } 
        }
        else
        {
            echo "Judge not defined!";
        }
    } else {
        $json = array(
          'Result' => "Missing LLM API key!"
        );
        echo json_encode($json);
    }
            
            
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>