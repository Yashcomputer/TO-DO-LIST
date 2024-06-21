<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todolist";

$status = [
    "status"=> false,
    "message" => ""
];

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $status = [
        "status"=> false,
        "message" => "Connection failed: " . $conn->connect_error
    ];
    echo json_encode($status);
    exit;
}

// Initialize variables
$id = $task = "";
$id = $description = "";


// Retrieve task details based on ID
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM todo WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $task = $row['task'];
        $description = $row['description'];

    } else {
        $status = [
            "status"=> false,
            "message" => "Task Not Found "
        ];
        echo json_encode($status);
        exit;
    }
}

// Update task in database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $description = $_POST['description'];
    $sql = "UPDATE todo SET task='$task', description='$description' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        // Redirect to task list after successful update

        $status = [
            "status"=> true,
            "message" => "Task Updated Successfully"
        ];
        echo json_encode($status);
        header("Location: index.php");

        exit;
    } else {
        $status = [
            "status"=> false,
            "message" => "Error updating task: " . $conn->error
        ];
        echo json_encode($status);
        exit;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #edit {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Task</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="task" value="<?php echo $task; ?>">
            <input type="text" name="description" value="<?php echo $description; ?>">
            <button type="submit" id="edit">Update Task</button>
        </form>
    </div>
</body>
</html>