<?php
namespace index;
use mysqli;

class action{
    public $conn;
    public $stmt;
    public $status = [
        "status" => false,
        "message" => ""
    ];
    
    public function __construct()
    {   
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "todolist";
        $this->conn = new mysqli($servername, $username, $password, $dbname);
    }

    public function addtask(){
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
            $task = $_POST['task'];
            $description = $_POST['description']; // Get description data

            if (empty($task)) {
                $status = [
                    "status" => false,
                    "message" => "Task must not be empty"
                ];
                echo json_encode($status);
                exit;
            }
            if ($this->conn->connect_error) {
                $status = [
                    "status" => false,
                    "message" => $this->conn->connect_error
                ];
                echo json_encode($status);
                exit;
            }
            $sql = "INSERT INTO todo (task, description) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                $status = [
                    "status" => false,
                    "message" => "Error preparing statement: " . $this->conn->error
                ];
                echo json_encode($status);
                exit;
            }

            $stmt->bind_param("ss", $task, $description); 
            if ($stmt->execute()) {
                $status = [
                    "status" => true,
                    "message" => "Task added successfully"
                ];
                echo json_encode($status);
                
            } else {
                $status = [
                    "status" => false,
                    "message" => "Error executing query: " . $this->stmt->error
                ];
                echo json_encode($status);
                exit;
            }

            // Close prepared statement and connection
            $stmt->close();
            $this->conn->close();
        } 
        else {
            $status = [
                "status" => false,
                "message" => "Invalid request"
            ];
            echo json_encode($status);
            exit;
    }
}

// Check if the request is AJAX
    public function deletetask(){
// Check if the request is AJAX
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task_id'])) {
            // Sanitize the input
            $task_id = intval($_POST['delete_task_id']);

            // Establish database connection
            if ($this->conn->connect_error) {
                $status = [
                    "status"=> false,
                    "message" => "Connection failed: " . $this->conn->connect_error
                ];
                echo json_encode($status);
                exit;
            }

            // Prepare and execute the SQL statement
            $sql = "DELETE FROM todo WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $task_id);

            if ($stmt->execute()) {
                $status = [
                    "status"=> true,
                    "message" => "Task deleted successfully"
                ];
                echo json_encode($status);
                exit;
                        // Return a JSON response

            } else {
                $status = [
                    "status"=> false,
                    "message" => "Error deleting task"
                ];
                echo json_encode($status);
                exit;
                // Return a JSON response with error message
            }

            // Close statement and database connection
            $stmt->close();
            $this->conn->close();
        } else {
            // Return a JSON response with error message for invalid request
            $status = [
                "status"=> false,
                "message" => "Invalid request"
            ];
            echo json_encode($status);
            exit;
            // Return a JSON response with error message for invalid request
        }
    }

    public function completetask(){
        // Check if the request is AJAX
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
            // Sanitize the input
            $task_id = intval($_POST['task_id']);
            // Establish database connection
            if ($this->conn->connect_error) {
                $status = [
                    "status"=> false,
                    "message" => "Connection failed: " . $this->conn->connect_error
                ];
                echo json_encode($status);
                exit;
            }
            // Prepare and execute the SQL statement          
            $sql = "UPDATE todo SET status = 1 WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $task_id);

            if ($stmt->execute()) {
                $status = [
                    "status"=> true,
                    "message" => "Task completed successfully"
                ];
                echo json_encode($status);
                exit;
                // Return a JSON response 
            } else {
                // Return a JSON response with error message
                $status = [
                    "status"=> false,
                    "message" => "Error completing task"
                ];
                echo json_encode($status);
                exit;
            }

            // Close statement and database connection
            $stmt->close();
            $this->conn->close();
        } 
        else {
            // Return a JSON response with error message for invalid request
            $status = [
                "status"=> false,
                "message" => "Invalid request"
            ];
            echo json_encode($status);
            exit;
        }
    }
}
?>



