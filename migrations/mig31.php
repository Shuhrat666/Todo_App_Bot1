<?php include 'includes/db.php'; 
require '../includes/password.php'?>

<?php
$pdo = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_username, $db_password);
$stmt=$pdo->prepare(query:"CREATE table tasks(id INT PRIMARY KEY  auto_increment, task varchar(50), status tinyint(1));");
$stmt->execute();
printf("Created successsfully (Table 'tasks')!\n");

?>