<?php

$host = "database";
$user = "root";
$password = "";
$database = "habit_tracker";
$port = 3306;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {

    $username = $_GET['username'];

    $sql = "SELECT * FROM habits WHERE username='$username'";
    $result = $conn->query($sql);

    $habits = [];

    while($row = $result->fetch_assoc()) {
        $habits[] = $row;
    }

    echo json_encode($habits);
}

if ($method == "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $username = $data['username'];
    $task_text = $data['task_text'];
    $is_done = $data['is_done'];

    $sql = "INSERT INTO habits (username, task_text, is_done)
            VALUES ('$username', '$task_text', '$is_done')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Habit added"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

$conn->close();

?>