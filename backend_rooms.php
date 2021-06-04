<?php
require_once 'db.php'; //підключаємо конект до mysql

$stmt = $db->prepare("SELECT * FROM  rooms ORDER BY name "  ); //запит для відбору всіх кімнат
$stmt->bindParam(':capacity', $_POST['capacity']); 
$stmt->execute();
$rooms = $stmt->fetchAll();

class Room
{
}

$result = array();

foreach ($rooms as $room) {
    $r = new Room();
    $r->id = $room['id'];
    $r->name = $room['name'];
    $r->capacity = $room['capacity'];
    $r->status = $room['status'];
    $result[] = $r;
}

header('Content-Type: application/json'); //цей рядок повинен бути першим у виводі інформації для передачі з сервера. Якщо до нього буде хоча б один пропуск, застосунок видасть помилку.
echo json_encode($result);
