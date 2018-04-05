<?php require "vendor/autoload.php";

$client = new GuzzleHttp\Client;

try {
    $response = $client->post('localhost:8000/oauth/token', [
        'form_params' => [
            'client_id' => 2,
            // The secret generated when you ran: php artisan passport:install
            'client_secret' => 'hlTXb6O9FONILGA7Ac3cwKOd9rfjByvyFziR1GJ2',
            'grant_type' => 'password',
            'nickname' => 'luca.ciotti@gmail.com',
            'password' => '112358',
            'scope' => '*',
        ]
    ]);

    // You'd typically save this payload in the session
    $auth = json_decode( (string) $response->getBody() );

    $response = $client->get('localhost:8000/api/users', [
        'headers' => [
            'Authorization' => 'Bearer '.$auth->access_token,
        ]
    ]);

    //$users = json_decode( (string) $response->getBody() );

    /* $todoList = "";
    foreach ($todos as $todo) {
        $todoList .= "<li>{$todo->task}".($todo->done ? '✅' : '')."</li>";
    } */

    echo "<ul>{$users}</ul>";

} catch (GuzzleHttp\Exception\BadResponseException $e) {
    echo "Unable to retrieve access token.";
}