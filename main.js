function addTask() {
    var taskInput = document.getElementById("taskInput").value.trim();
    var descriptionInput = document.getElementById("descriptionInput").value.trim();
    if (taskInput === "") {
        alert("Please enter a task!");
        return false;
    }
    $.ajax({
        url: "task.php",
        type: "POST",
        data: { task: taskInput, description: descriptionInput },
        success: function(response) {
            response = JSON.parse(response);
            console.log(response);
            if (response.status == true) {
                reloadTaskList();
            } else {
                alert(response.message);

            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred while adding the task.");
        }
    });
    return false;
    
}

function deleteTask(taskId) {
        // alert(2);
    if (confirm("Are you sure you want to delete this task?")) {
        $.ajax({
            url: "task.php",
            type: "POST",
            data: { delete_task_id: taskId },
            success: function(response) {
                response = JSON.parse(response);
                console.log(response);
                if (response.status == true) {
                        reloadTaskList();
                } else {
                    alert(response.message);

                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

function completeTask(taskId) {
    if (confirm("Are you sure you want to mark this task as complete?")) {
        $.ajax({
            url: "task.php",
            type: "POST",
            data: { task_id: taskId },
            success: function(response) {
                response = JSON.parse(response);
                console.log(response);
                if (response.status == true) {
                        reloadTaskList();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}
function editTask(taskId) {
    if (confirm("Are you sure you want to edit this task?")) {
        $.ajax({
            url: "edit.php",
            type: "POST",
            data: { task_id: taskId, task: task, description: description },
            success: function(response) {
                response = JSON.parse(response);
                console.log(response);
                if (response.status == true) {
                        reloadTaskList();
                } else {
                    alert(response.message);

                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred while adding the task.");
            }
        });
    }
}
