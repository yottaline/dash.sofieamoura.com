<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected $client;
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->client = new Client();
        $this->botToken = '7376915935:AAHsHdkos2awyayD_0QyhX8XwlhJQD7LS3U';
        $this->chatId = '4232852781';
    }

    public function sendMessage($message, $buttonUrl)
    {

        $response = Http::post("https://api.telegram.org/bot7376915935:AAHsHdkos2awyayD_0QyhX8XwlhJQD7LS3U/sendMessage", [
            'chat_id' => '7279961590',
            'text' => $message,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => 'Accept Request', 'url' => $buttonUrl]
                ]]
            ])
        ]);

        return $response->json();
    }
}