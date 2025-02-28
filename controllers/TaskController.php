<?php

namespace Controllers;

use Model\Task;
use Model\Project;

class TaskController {
    public static function index() {

        $projectId = $_GET['url'];

        if(!$projectId) header('Location: /dashboard');
        
        $project = Project::where('url', $projectId);

        session_start();
        isAuth();

        if(!$project || $project->ownerId !== $_SESSION['id']) header('Location: /404');

        $tasks = Task::belongsTo('projectId', $project->id);

        echo json_encode(['tasks' => $tasks]);

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
                'message' => 'Tarea creada correctamente',
                'projectId' => $project->id
            ];
            
            echo json_encode($response);
            
        }
    }

    public static function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();
            isAuth();

            $projectId = $_POST['url'];

            $project = Project::where('url', $projectId);

            if(!$project || $project->ownerId !== $_SESSION['id']) {
                $response = [
                    'type' => 'error',
                    'message' => 'Error al actualizar la tarea'
                ];
                echo json_encode($response);
                return;
            } 

            $task = new Task($_POST);
            $task->projectId = $project->id;

            $result = $task->save();
            if($result) {
                $response = [
                    'type' => 'success',
                    'id' => $task->id,
                    'projectId' => $project->id,
                    'message' => 'Actualizado correctamente'
                ];
                echo json_encode(['response' => $response]);
            } 
        }
    }

    public static function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            $projectId = $_POST['url'];

            $project = Project::where('url', $projectId);

            if(!$project || $project->ownerId !== $_SESSION['id']) {
                $response = [
                    'type' => 'error',
                    'message' => 'Error al actualizar la tarea'
                ];
                echo json_encode($response);
                return;
            } 

            $task = new Task($_POST);
            $result = $task->delete();

            $result = [
                'result' => $result,
                'message' => 'Tarea eliminada correctamente',
                'type' => 'success'
            ];

            echo json_encode($result);
        }
    }

}