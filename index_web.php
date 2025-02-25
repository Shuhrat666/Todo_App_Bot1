<?php

require 'includes/db_connection.php';
require 'credentials/token.php';

$status=isset($_POST['check']) ? 1 : 0;
$newtask=isset($_POST['task']) ? trim($_POST['task']) : null;
$updatedtask=isset($_POST['updated']) ? trim($_POST['updated']) : null;
$updatedid=isset($_POST['upid']) ? trim($_POST['upid']) : null;
$deletedid=isset($_POST['delid']) ? trim($_POST['delid']) : null;

if(!empty($newtask)){
	$stmt = $pdo->prepare("insert into tasks (task, status) values (:task,:status)");
	$stmt->execute(['task' => $newtask, 'status'=>$status]);
}
if(!empty($updatedtask) && !empty($updatedid)){
    $stmt = $pdo->prepare("update tasks set task= :task, status= :status where id= :id");
    $stmt->execute(['task' => $updatedtask, 'status'=>$status, 'id'=>$updatedid]);
    }
if (!empty($deletedid)) {
    $stmt = $pdo->prepare("delete from tasks WHERE id = :id");
    $stmt->execute(['id' => $deletedid]);
}

$default_records_per_page = 10;
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : $default_records_per_page;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks;");
$stmt->execute();
$total_records = $stmt->fetchColumn();

if (isset($_GET['records_per_page']) && $_GET['records_per_page'] === 'all') {
    $records_per_page = $total_records; 
} 

$total_pages = $records_per_page === $total_records ? 1 : ceil($total_records / $records_per_page);
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

$stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY id ASC LIMIT :offset, :records_per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll();



?>

<html>
<head>
    <title>Todo App</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 50%;
        }
        input[type="checkbox"] {
            margin-left: 10px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h1>Todo App</h1>
<form action="/index.php" method="post">
    <input type="text" name="task" placeholder="Enter task">
    <label for="box">Done</label>
    <input type="checkbox" name="check" id="box" value="1"><br>
    <button type="submit">Save</button><br>
    <label for="up">Update section (By id):</label><br>
    <input type="number" name="upid" id="up">
    <input type="text" name="updated" id="up">
    <label for="upbox">Done</label>
    <input type="checkbox" name="check" id="upbox" value="1"><br>
    <button type="submit">Update</button><br>
    <label for="del">Delete section (By id):</label><br>
    <input type="number" name="delid" id="del">
    <button type="submit">Delete</button>
</form>

<form method="get" action="index_web.php">
    <label for="records_per_page">Records per page:</label>
    <select id="records_per_page" name="records_per_page">
        <option value="5" <?php echo $records_per_page == 5 ? 'selected' : '' ?>>5</option>
        <option value="10" <?php echo $records_per_page == 10 ? 'selected' : '' ?>>10</option>
        <option value="20" <?php echo $records_per_page == 20 ? 'selected' : '' ?>>20</option>
        <option value="50" <?php echo $records_per_page == 50 ? 'selected' : '' ?>>50</option>
        <option value="all" <?php echo $records_per_page == $total_records && $_GET['records_per_page'] === 'all' ? 'selected' : ''; ?>>All</option>
    </select>
    <button type="submit">Set</button>
</form>

<ul>
    <?php foreach ($tasks as $task): ?>
        <?php $status = $task['status'] ? '✅' : '🟢'; ?>
        <li><?php echo "{$task['id']} $status " . htmlspecialchars($task['task']); ?></li>
    <?php endforeach; ?>
</ul>


<div>
    <?php if ($current_page > 1): ?>
        <a href="?page=<?php echo $current_page - 1; ?>&records_per_page=<?php echo $records_per_page; ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&records_per_page=<?php echo $records_per_page; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <a href="?page=<?php echo $current_page + 1; ?>&records_per_page=<?php echo $records_per_page; ?>">Next</a>
    <?php endif; ?>
</div>

</body>
</html>
