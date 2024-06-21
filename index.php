<?php
namespace index;
require_once('vendor/autoload.php');
$con = mysqli_connect('localhost', 'root', '', 'todolist');
$res = mysqli_query($con, "select * from todo");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do List</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <style>
        .container h1 img {
            width: 25px;
            height: auto;
            border-radius: 50%;
            margin-left: 10px;
        }
         .task-container {
            height: 300px;
            overflow-y: scroll;
        }
        .task-container::-webkit-scrollbar {
            display: none;
        }
        .task-container {
            -ms-overflow-style: none;  
            scrollbar-width: none; 
        }
        h1 {
            color: green;
        }
        tbody {
            color: green;
        }
        thead {
            font-weight: bolder;
            color: green;
        }
        hr {
            width: 250px;
        }
        .add:hover {
            background-color: lightgreen;
            color: black;
        }
        body {
            font-family: "Lucida Console", "Courier New", monospace;
        }
        .container{
                max-width: 800px;
        }
        button{
            text-align: center;
        }
        .button-container {
            margin-left: 105px;
        }
        .task {
            width: 500px;
            justify-content: center;
            margin-right: auto;
            margin-left: auto;
        }
        
    </style>
</head>
<body>
<script src="main.js"></script>
    <div class="container" id="Container">
        <h1>To-Do List<img src="icon.png" alt=""></h1>
        <form id="taskForm" name="taskForm" method="post" onsubmit="return addTask()">
            <input type="text" id="taskInput" name="task" placeholder="Enter task">
            <hr>
            <input type="text" id="descriptionInput" name="description" placeholder="Enter description">
            <button type="submit" class="add"><B>Add Task</B></button>
            <hr style="width: 550px;">
        </form>   
        <div class="task-container" id="taskContainer">
            <?php
            // Establish database connection
            include 'connect.php';
        // Prepare and execute SQL statement
            $stmt = $conn->prepare("SELECT id, task, description, status, created_at FROM todo ORDER BY id DESC");
            $stmt->execute();
            $stmt->bind_result($id, $task, $description, $status, $created_at);
            $data = array();
            // Fetch data and store in array
            while ($stmt->fetch()) {
                $data[] = array(
                    "id" => $id,
                    "task" => $task,
                    "description" => $description, 
                    "status" => $status,
                    "created_at" => $created_at
                );
            }
            // Output tasks
            foreach ($data as $row) {
                echo "<div class='task'>";
                if ($row['status']) {
                    echo "<hr>";
                    echo "<span><b><del>" . htmlspecialchars($row['task']) . "</del></b></span>";
                    echo "<hr>";
                    echo "<span><del>" . htmlspecialchars($row['description']) . "<del></span>";
                    echo "<hr>";
                }
                else{
                    echo "<hr>";
                    echo "<span><b>" . htmlspecialchars($row['task']) . "</b></span>";
                    echo "<hr>";
                    echo "<span>" . htmlspecialchars($row['description']) . "</span>";
                    echo "<hr>";
                }
                echo "<div class='button-container'>";
                echo "<form action='delete_task.php' method='post'>";
                echo "<input type='hidden' name='task_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='button' onclick='deleteTask(" . $row['id'] . ")'><B>Delete</B></button>";
                echo "</form>";
                echo "<a href='edit.php?id=" . htmlspecialchars($row['id']) . "' onclick='editTask(" . $row['id'] . ")'><button ><B>Edit</B></button></a>";

                if (!$row['status']) {
                echo "<form action='complete_task.php' method='post'>";
                echo "<input type='hidden' name='task_id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='button' onclick='completeTask(" . $row['id'] . ")'><B>Complete</B></button>";
                echo "</form>";
                }
                echo "</div>";
                echo "</div>";
            }
            // Close statement and connection
            $stmt->close();
            $conn->close();
            // Output data in JSON format
            ?>
            <style>
                span {
                    color: green;
                }
            </style>
            <button onclick="toggle(this);" class="add">Hide Table</button>
            <hr>
            <div class="Task" id="Task">
                <table id="taskTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th><b>Task</b></th>
                            <th><b>Description</b></th>
                            <th><b>Status</b></th>
                            <th><b>Created at</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($res)) { ?>
                            <tr>
                                <td><?php echo $row['task']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <hr style="width: 550px;">
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="//cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    </div>
    
    <script>
        $(document).ready(function(){
                $('#taskTable').DataTable();
                
            });
        /*$(document).ready(function(){
            $("button").click(function(){
                $(".task").css("color", "red").slideUp(2000).slideDown(2000);
            });
        });*/
        
function reloadTaskList() {
    $.ajax({
        url: "index.php",
        type: "POST",
        success: function(response) {
            document.getElementById("Container").innerHTML = response;
            $('#taskTable').DataTable();

            // Reinitialize DataTable after updating task list
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred while reloading the task list.");
        }
    });
}       
        let toggle = button => {
            let element = document.getElementById("Task");
            let hidden = element.getAttribute("hidden");

            if (hidden) {
            element.removeAttribute("hidden");
            button.innerText = "Hide table";
            } else {
            element.setAttribute("hidden", "hidden");
            button.innerText = "Show table";
            }
        }
    </script>
</body>
</html>
