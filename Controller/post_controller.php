<?php

require '../includes/db.php';
function greeting_message($chatId, $firstName) {
    $welcomeMessage = "Salom, $firstName! Bu yerda siz quyidagi komandalarni ishlatishingiz mumkin:\n";
    $welcomeMessage .= "/add_task - Yangi task qo'shish\n";
    $welcomeMessage .= "/update_task - Taskni o'zgartirish\n";
    $welcomeMessage .= "/delete_task - Taskni o'chirish\n";
    $welcomeMessage .= "/tasks_list - Barcha task lar\n";
    $welcomeMessage .= "Qo'shimcha funksiyalar tez orada qo'shiladi !\n";

    return $welcomeMessage;
}

function allTasks($db) {
    $tasks = $db->tasksList();  
    foreach ($tasks as $task) {
        echo $task['id'].$task['task'].'|'. $task['status']."\n";
    }
}
