<?php
namespace todolist;
$servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "todolist";
// Create connection
$conn = new \mysqli($servername, $username, $password, $dbname);
// Check connection
$status = [
    "status" => false,
    "message" => ""
];
if ($conn->connect_error) {
    $status = [
        "status" => false,
        "message" => $conn->connect_error
    ];
    echo json_encode($status);
    exit;
}
?>