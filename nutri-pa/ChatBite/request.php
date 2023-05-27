<?php

require __DIR__ . '/vendor/autoload.php';

use Orhanerday\OpenAi\OpenAi;

$open_ai = new OpenAi('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');

$prompt = 'Você é um chatbot e se chama Chat-Bite especialista em nutrição e somente responde este tipo de pergunta' . $_POST['prompt'];

$complete = $open_ai->completion([
    'model' => 'text-davinci-003',
    'prompt' => $prompt,
    'temperature' => 0.1,
    'max_tokens' => 2500,
    'top_p' => 0.3,
    'frequency_penalty' => 0.5,
    'presence_penalty' => 0
]);

echo $complete;

?>