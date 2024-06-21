<?php
namespace index;
include "action.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Action = new action();
    
        if (isset($_POST['task_id'])) {
            $Action->completetask();
        } elseif (isset($_POST['delete_task_id'])) {
            $Action->deletetask();
        } else {
            $Action->addtask();
        }
    }
    

?>