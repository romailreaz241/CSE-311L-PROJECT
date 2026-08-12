<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { 
    http_response_code(200); 
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); 
    echo json_encode(['error' => 'POST only']); 
    exit; 
}

define('GEMINI_API_KEY', 'AIzaSyC2LxArUngTQHisGaqFNvpVL0uhZeByMH4');

$input = json_decode(file_get_contents('php://input'), true);
$userMsg = trim($input['message'] ?? '');
$history = $input['history'] ?? [];
$testCtx = $input['test_context'] ?? '';

if (!$userMsg) { 
    echo json_encode(['error' => 'Empty message']); 
    exit; 
}

$systemPrompt = <<<SYS
You are MediBot, a helpful assistant for MediSync medical test booking system in Dhaka, Bangladesh.

Help patients understand medical tests by explaining:
- What each test does and why it's needed
- How to prepare for the test
- What happens during the procedure
- How long it takes
- What results mean
- Any precautions or side effects

Be warm, reassuring, and use simple language. Don't diagnose or recommend treatments. Only explain tests.
Keep responses concise but complete. End with: "Would you like to know more about this test or another test?"
SYS;

$contents = [];
foreach ($history as $h) {
    if (isset($h['role'], $h['content']) && in_array($h['role'], ['user', 'assistant'])) {
        $role = ($h['role'] === 'assistant') ? 'model' : 'user';
        $contents[] = [
            'role' => $role,
            'parts' => [['text' => (string)$h['content']]]
        ];
    }
}


$finalMsg = $userMsg;
if ($testCtx) {
    $finalMsg = "[User viewing context: {$testCtx}]\n\n{$userMsg}";
}
$contents[] = [
    'role' => 'user',
    'parts' => [['text' => $finalMsg]]
];


$payload = json_encode([
    'systemInstruction' => [
        'parts' => [['text' => $systemPrompt]]
    ],
    'contents' => $contents,
    'generationConfig' => [
        'maxOutputTokens' => 600,
        'temperature' => 0.5
    ]
]);
 $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT => 30,
  
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0 
]);

$raw = curl_exec($ch);


if (curl_errno($ch)) {
    echo json_encode([
        'reply' => "⚠️ **SERVER ERROR:** " . curl_error($ch), 
        'fallback' => false
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($raw, true);

if ($httpCode !== 200 || !isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    $googleError = isset($data['error']['message']) ? $data['error']['message'] : $raw;
    echo json_encode([
        'reply' => "⚠️ **API ERROR (HTTP $httpCode):** " . $googleError, 
        'fallback' => false
    ]);
    exit;
}


echo json_encode(['reply' => $data['candidates'][0]['content']['parts'][0]['text'], 'fallback' => false]);


function getFallbackReply(string $msg, string $ctx): string {
    return "Offline mode active.";
}