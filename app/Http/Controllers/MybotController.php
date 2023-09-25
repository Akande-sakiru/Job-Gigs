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

    // public function WebhookHandler(Request $request)
    // {
    //     // Get the raw POST data from the request
    //     $data = $request->getContent();

    //     // Define the path to the log file
    //     $logFile = storage_path('logs/webhooksentdata.json');

    //     // Write the data to the log file
    //     file_put_contents($logFile, $data . PHP_EOL, FILE_APPEND);

    //     // Decode the JSON data
    //     $getData = json_decode($data, true);
    //     $userId = $getData['message']['from']['id'];
    //     $userMessage = $getData['message']['text'];

    //     if ($userMessage == 'hi' || $userMessage == 'Hi' || $userMessage == 'hello') {
    //         $botMessage = 'Hi there';
    //     }
    //     else if ($userMessage == 'Yr father') {
    //         $botMessage = 'oloshi Yr mama';
    //     }
    //     else {
    //         $botMessage = "I can't hear you, speak louder";
    //     }
    //     // Prepare the parameters for the Telegram API request
    //     $parameters = [
    //         'chat_id' => $userId,
    //         'text' => $botMessage,
    //         'parse_mode' => 'html',
    //     ];

    //     // Send the message to the Telegram API
    //     $botToken = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token
    //     $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";

    //     try {
    //         $response = Http::post($apiUrl, $parameters);

    //         if ($response->successful()) {
    //             return $response->body();
    //         } else {
    //             return "Error sending message to Telegram API";
    //         }
    //     } catch (\Exception $e) {
    //         return "Error: " . $e->getMessage();
    //     }
    // }

    // public function setWebhook()
    // {
    //     $BOT_TOKEN = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token
    //     $webhookUrl = 'https://biomed-backend.devdrizzy.online/api/webhook';

    //     $apiUrl = "https://api.telegram.org/bot$BOT_TOKEN/setWebhook?url=$webhookUrl";

    //     try {
    //         $response = Http::get($apiUrl);

    //         if ($response->successful()) {
    //             return $response->body();
    //         } else {
    //             return "Error setting webhook";
    //         }
    //     } catch (\Exception $e) {
    //         return "Error: " . $e->getMessage();
    //     }
    // }
    public function WebhookHandler(Request $request)
    {
        $botMessage = '';
        // Get the raw POST data from the request
        $data = $request->getContent();

        // Define the path to the log file
        $logFile = storage_path('logs/webhooksentdata.json');
        // $logFile1 = storage_path('logs/webhooksentdata1.json');
        // Log::channel('daily')->info('Received Request', $data);
        // Write the data to the log file
        file_put_contents($logFile, $data . PHP_EOL, FILE_APPEND);
        

        // Decode the JSON data
        $getData = json_decode($data, true);
        $userId = $getData['message']['from']['id'];
        $userMessage = strtolower($getData['message']['text']);
        $categories = [
            'student' => [
                'response' => 'You selected the Student category. Here are some resources for students:
            1. Make complaint
            2. Make complaint
            3. Make complaint'
            ],
            'teacher' => [
                
                'response' => 'You selected the Teacher category. Here are some resources for teachers: 
            1. Make complaint
            2. Make complaint
            3. Make complaint'
            ],
            'administration' => [
                
                'response' => 'You selected the Administration category. Here are some resources for administrators: 
            1. Make complaint
            2. Make complaint
            3. Make complaint'
            ],
        ];

        if ($userMessage == 'Hi' || $userMessage == '/start' || $userMessage == 'hello' || $userMessage == 'hi' || $userMessage == 'Hello') {
            // Initialize the default bot message
            $botMessage = 'Hi there! Please type a category to explore:';

            // Generate links to categories
            foreach ($categories as $category => $data) {
                $botMessage .= "\n $category";
            }
        }else{
                // Check if the user's message matches any category or question
                
               foreach ($categories as $categoryKey => $category) {
                    if ($userMessage === $categoryKey) {
                        $botMessage = $category['response'];
                        break;  // Exit the loop since a match was found
                    }
                    else{
                        $botMessage = "sorry,i don't understand you";
                    }
                }
   
        }


        // Prepare the parameters for the Telegram API request
        $parameters = [
            'chat_id' => $userId,
            'text' => $botMessage,
            'parse_mode' => 'html',
        ];

        // Send the message to the Telegram API
        $botToken = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token
        $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";

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


    public function getChannelInfo(){
        $BOT_TOKEN = '6605753719:AAGXAGHErCZk0i4ylKzQ7RGGX1NTPQJFNn8'; // Replace with your actual bot token
        $CHANNEL_ID = -1001944402217;
        $parameters =array(
            'chat_id'=>$CHANNEL_ID
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
  
}
