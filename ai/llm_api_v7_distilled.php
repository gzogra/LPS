<?php

session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit();
}else{
    $user_account = $_SESSION['name'];
}

header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$requestUri = $requestUri[3];


if ($requestMethod === 'POST' && isset($requestUri)) {
    
    $input = json_decode(file_get_contents('php://input'), true);
    $received_input = file_get_contents('php://input');
    
    switch ($requestUri) {
        
        case 'simple':
            $temperature = 0;
            if(isset($input['modelSelection'])){
                if($input['modelSelection']=="gpt-4o-mini")
                {
                    if ($input['prompt']!="")
                    {
                        $api_url_endpoint = "https://api.openai.com/v1/chat/completions";
                        $model = $input['modelSelection'];
                        
                        $provided_context = $input['prompt_context'] ?? '';

                        $data = [
                            'model' => $model,
                            'max_tokens' => (int) $input['tokens'],
                            'temperature' => (float) $temperature,
                            'top_p' => (float) $input['top_p'],
                            'messages' => [
                                ['role' => 'assistant', 'content' => $provided_context ],
                                ['role' => 'user', 'content' => $input['prompt'] ],
                            ]
                        ];
                    
                        $headers = [
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $input['openaiApiKey'],
                        ];
                    
                        $ch = curl_init($api_url_endpoint);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    
                        $response = curl_exec($ch);
                        
                        if($response !== false) {
                            $info = curl_getinfo($ch);
                        }

                        echo $response;

                    } else {
                        $json = array(
                          'Result' => "Not enough parameters given!"
                        );
                        echo json_encode($json);
                    }
                    
                } elseif ($input['modelSelection']=="claude-3-5-sonnet-20240620") 
                {
                    if(isset($input['anthropicApikey'])){

                    $model = $input['modelSelection'];
                    $api_url_endpoint = 'https://api.anthropic.com/v1/messages';
                    
                    if($input['prompt_context']!=""){
                            $provided_context = "Context: '" . $input['prompt_context'] . "' User:'".$input['prompt']."'";
                        } else {
                            $provided_context = $input['prompt'];
                        }
                        
                    $data = [
                        'model' => $model,
                        'max_tokens' => (int) $input['tokens'],
                        'temperature' => (float) $temperature,
                        'top_p' => (float) $input['top_p'],
                        'messages' => [
                            ['role' => 'user', 'content' => $provided_context]
                        ]
                    ];
                    
                    $headers = [
                        'Content-Type: application/json',
                        'X-Api-Key: ' . $input['anthropicApikey'],
                        'Anthropic-Version: 2023-06-01'
                    ];
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $api_url_endpoint);
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
                    
                } else {
                    if(isset($input['aimlApikey'])){
                        
                        $model = $input['modelSelection'];
                        $api_url_endpoint = "https://api.aimlapi.com/v1/chat/completions";
                        
                        if($input['prompt_context']!=""){
                            $provided_context = "Context: '" . $input['prompt_context'] . "' User:'".$input['prompt']."'";
                        } else {
                            $provided_context = $input['prompt'];
                        }
                            
                        $data = [
                            'model' => $model,
                            'max_tokens' => (int) $input['tokens'],
                            'temperature' => (float) $temperature,
                            'top_p' => (float) $input['top_p'],
                            'messages' => [
                                ['role' => 'user', 'content' => $provided_context]
                            ]
                        ];
                        
                        $headers = [
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $input['aimlApikey'],
                        ];
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $api_url_endpoint);
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
            } else {
                $json = array(
                  'Result' => "Missing LLM API key!",
                  "input_data"=>"$received_input)"
                );
                echo json_encode($json);
            }

            break;
        default:
            http_response_code(404);
            echo json_encode(["error" => "Endpoint not found Ohhh", "endpoint"=>"".var_dump($requestUri).""]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed", "uri"=>"$requestUri"]);
}
?>