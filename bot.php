<?php

//require 'index_web.php';
require 'vendor/autoload.php';
require 'includes/db.php';
require 'credentials/token.php';
require 'includes/password.php';
require 'post_controller.php';

use GuzzleHttp\Client;

class Bot {
    private $client;
    private $db;

    public function __construct($token, $db_name, $db_username, $db_password) {
        $this->client = new Client(['base_uri' => "https://api.telegram.org/bot$token/"]);
        $this->db = new Db($db_name, $db_username, $db_password);
    }

    public function handle($update) {
        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $text = $update['message']['text'];
            $firstName = $update['message']['chat']['first_name'];

            match ($text) {
                '/start' => $this->client->post('sendMessage', [
                    'form_params' => [
                        'chat_id' => $chatId,
                        'text' => greeting_message($firstName),
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => 'Add Task']],
                                [['text' => 'Update Task']],
                                [['text' => 'Delete Task']],
                                [['text' => 'Tasks List']]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ])
                    ]
                ]),
                'Add Task' => $this->client->post('sendMessage', [
                    'form_params' => [
                        'chat_id' => $chatId,
                        'text' => "Yangi taskni kiriting :",
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => '⬅️ ortga']]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ])
                    ]
                ]),
                'Update Task' => $this->client->post('sendMessage', [
                    'form_params' => [
                        'chat_id' => $chatId,
                        'text' => "Task ID sini kirititng :",
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => '⬅️ ortga']]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ])
                    ]
                ]),
                'Delete Task' => $this->client->post('sendMessage', [
                    'form_params' => [
                        'chat_id' => $chatId,
                        'text' => "Task ID sini kirititng :",
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => '⬅️ ortga']]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ])
                    ]
                ]),
                'Tasks List' => $this->client->post('sendMessage', [
                    'form_params' => [
                        'chat_id' => $chatId,
                        'text' => allTasks($this->db),
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [['text' => '⬅️ ortga']]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ])
                    ]
                ]),
                default => $this->client->post('sendMessage', [
                    'form_params' => [
                        'chat_id' => $chatId,
                        'text' => "Noma'lum buyrug'. Iltimos, menyudan birini tanlang."
                    ]
                ])
            };
        }
    }
}

function greeting_message($firstName) {
    $welcomeMessage = "Salom, $firstName! Bu yerda siz quyidagi komandalarni ishlatishingiz mumkin:\n";
    $welcomeMessage .= "/add_task - Yangi task qo'shish\n";
    $welcomeMessage .= "/update_task - Taskni o'zgartirish\n";
    $welcomeMessage .= "/delete_task - Taskni o'chirish\n";
    $welcomeMessage .= "/tasks_list - Barcha task lar\n";
    $welcomeMessage .= "Qo'shimcha funksiyalar tez orada qo'shiladi !\n";

    return $welcomeMessage;
}
