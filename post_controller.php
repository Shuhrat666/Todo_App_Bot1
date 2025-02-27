<?php

function allTasks($db) {
    $tasks = $db->tasksList();  
    foreach ($tasks as $task) {
        echo $task['id'].$task['task'].'|'. $task['status']."\n";
    }
}
