<?php

namespace Controllers;

use Model\Task;
use Model\Project;

class TaskController {
    public static function index() {
        
    }

    public static function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();
            isAuth();

            $projectId = $_POST['projectId'];

            $project = Project::where('url', $projectId);

            if(!$project || $project->ownerId !== $_SESSION['id']) {
                $response = [
                    'type' => 'error',
                    'message' => 'Error al crear la tarea'
                ];
                echo json_encode($response);
            } 
            
            // All good to create the task
            $task = new Task($_POST);
            $task->projectId = $project->id;
            $result = $task->save();
            $response = [
                'type' => 'success',
                'id' => $result['id'],
                'message' => 'Tarea creada correctamente'
            ];
            echo json_encode($response);
            
        }
    }

    public static function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }
    }

    public static function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }
    }

}