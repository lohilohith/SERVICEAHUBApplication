<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$prompt = $data['prompt'] ?? '';

if(!$prompt){
    echo json_encode(['reply'=>'Please type a message!']); exit;
}

// ---- REPLACE WITH YOUR OPENAI API KEY ----
$apiKey = 'YOUR_OPENAI_API_KEY';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer '.$apiKey
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model"=>"gpt-3.5-turbo",
    "messages"=>[["role"=>"user","content"=>$prompt]],
    "temperature"=>0.7
]));

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);
$reply = $response['choices'][0]['message']['content'] ?? 'Sorry, I could not respond.';

echo json_encode(['reply'=>trim($reply)]);
