<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Facades\DB;
// use App\Models\product;

class MybotController extends Controller
{
    //
    // function getData(){
    //  $product= product::all();
    //  return view('home', ['products' => $product]);
    // }
    public function getBotInfo()
    {
        $BOT_TOKEN = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token

        $apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/getMe";

        try {
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['ok'] == true) {
                    $botName = $data['result']['username'];
                    $botId = $data['result']['id'];

                    return "Bot name: $botName <br> Bot ID: $botId";
                } else {
                    return "Error getting bot info";
                }
            } else {
                return "Error getting bot info";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getWebhookInfo()
    {
        $BOT_TOKEN = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token

        $apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/getWebhookInfo";

        try {
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                return $response->body();
            } else {
                return "Error getting webhook info";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function handleWebhook(Request $request)
    {
        // Get the raw POST data from the request
        $data = $request->getContent();

        // Define the path to the log file
        $logFile = storage_path('logs/webhooksentdata.json');

        // Write the data to the log file
        file_put_contents($logFile, $data . PHP_EOL, FILE_APPEND);

        return response()->json(['message' => 'Webhook data received and logged.']);
    }
    public function setWebhook()
    {
        $BOT_TOKEN = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token
        $webhookUrl = 'https://biomed-backend.devdrizzy.online/api/webhook';

        $apiUrl = "https://api.telegram.org/bot$BOT_TOKEN/setWebhook?url=$webhookUrl";

        try {
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                return $response->body();
            } else {
                return "Error setting webhook";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getChannelInfo()
    {
        $BOT_TOKEN = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token
        $CHANNEL_ID = -1001944402217;
        $parameters = array(
            'chat_id' => $CHANNEL_ID
        );
        $apiUrl = "https://api.telegram.org/bot{$BOT_TOKEN}/getChat";

        try {
            $response = Http::post($apiUrl, $parameters);

            if ($response->successful()) {
                return $response->body();
            } else {
                return "Error sending message to Telegram API";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function WebhookHandler(Request $request)
    {
        // Get the raw POST data from the request
        $data = $request->getContent();
        $logFile = storage_path('logs/webhooksentdata.json');
        file_put_contents($logFile, $data . PHP_EOL, FILE_APPEND);
    
        // Decode the JSON data
        $getData = json_decode($data, true);
        $userId = '';
        $userMessage = isset($getData['message']) ? strtolower($getData['message']['text']) : '';
        $categories = [
            'student' => [
                'response' => 'You selected the Student category. Here are some resources for students',
                'buttons' => [
                    ['text' => 'Result', 'callback_data' => 'result'],
                    ['text' => 'Admission', 'callback_data' => 'admission'],
                    ['text' => 'Bursary', 'callback_data' => 'bursary']
                ]
            ],
            'teacher' => [
                'response' => 'You selected the Teacher category. Here are some resources for students',
                'buttons' => [
                    ['text' => 'Attendance', 'callback_data' => 'attendance'],
                    ['text' => 'Result', 'callback_data' => 'result'],
                    ['text' => 'Class Register', 'callback_data' => 'class_register'],
                ]
            ],
            'parent' => [
                'response' => 'You selected the Parent category. Here are some resources for students',
                'buttons' => [
                    ['text' => 'Result', 'callback_data' => 'result'],
                    ['text' => 'Admission', 'callback_data' => 'admission'],
                    ['text' => 'Bursary', 'callback_data' => 'bursary'],
                ]
            ],
            'administrator' => [
                'response' => 'You selected the Administrator category. Here are some resources for students',
                'buttons' => [
                    ['text' => 'Result', 'callback_data' => 'result'],
                    ['text' => 'Admission', 'callback_data' => 'admission'],
                    ['text' => 'Bursary', 'callback_data' => 'bursary'],
                    ['text' => 'Attendance', 'callback_data' => 'attendance'],
                    ['text' => 'Registration', 'callback_data' => 'registration'],
                ]
            ],
        ];
    
        // Initialize the default bot message
        $botMessage = '';
    
        // Generate links to categories with inline keyboard buttons
        $keyboard = [
            'inline_keyboard' => [],
        ];
    
        if ($userMessage == 'hi' || $userMessage == '/start' || $userMessage == 'hello') {
            $userId = $getData['message']['from']['id'];
            $botMessage = 'Hi there! Please type a category to explore:';
    
            foreach ($categories as $category => $data) {
                $keyboard['inline_keyboard'][] = [
                    ['text' => ucfirst($category), 'callback_data' => $category],
                ];
            }
        } 
        else if (isset($getData['callback_query'])) {
            // Handle button click
            $buttonMessage = $getData['callback_query']['data'];
            $userId = $getData['callback_query']['from']['id'];
            $botToken = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8';
            $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";
            
            foreach ($categories as $categoryKey => $category) {
                if ($buttonMessage === $categoryKey) {
                    $botMessage = $category['response'];
                    // $keyboard['inline_keyboard'][] = [['text' => ucfirst('test'), 'callback_data' => 'test']];
                    foreach($category['buttons'] as $key => $button) {
                        $keyboard['inline_keyboard'][] = [['text' => ucfirst($button['text']), 'callback_data' => $button['callback_data']]];
                    }
                    break;
                } else {
                    $botMessage = "Sorry, I don't understand you.";
                }
            }
        }
        else {
            $botMessage = "Sorry, I don't understand yougggggggg.";
        }
    
        $botToken = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8';
        $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";
    
        $parameters = [
            'chat_id' => $userId,
            'text' => $botMessage,
            'parse_mode' => 'html',
            'reply_markup' => json_encode($keyboard),
        ];
    
        try {
            $response = Http::post($apiUrl, $parameters);
    
            if ($response->successful()) {
                return $response->body();
            } else {
                return "Error sending message to Telegram API";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
