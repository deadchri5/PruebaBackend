<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Models\User;
use App\Models\task;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    public function addTask(Request $request) {
        //$POST['title'] and $POST['description']
        $title = $request->title;
        $description = $request->description;

        //Check if params exists
        if (isset($title) && isset($description)) {
            //Store task in db
            //equivalent to INSERT INTO MySQL
            try{
                DB::table('task')->insert(
                    array(
                        'title' => $title,
                        'description' => $description,
                        'FK_user' => 1,
                        'done' => 0
                    )
                );
            }
            catch(\Illuminate\Database\QueryException $e){
                $response = array (
                    'message'   =>  'App has throw an error ',
                    'error' => $e,
                    'code'  =>  500
                );
                return response()->json($response, $response['code']);
            }
            
            $response = array(
                'message' => 'Task added succesfully!',
                'code' => 200
            );
            return response()->json($response, $response['code']);
        } else {
            $response = array(
                'message' => 'You must fill all the fields before sending.',
                'code' => 400
            );
            return response()->json($response, $response['code']);
        }
    }

    public function getTasks() {
        try {
            $userTasks = DB::table('task')->orderBy('done')->get();
            $response = array (
                'tasks'   =>  $userTasks,
                'code'  =>  200
            );
            return response()->json($response, $response['code']); 
        }
        catch (\Illuminate\Database\QueryException $e) {
            $response = array (
                'message'   =>  'App has throw an error while getting database info',
                'error' => $e,
                'code'  =>  500
            );
            return response()->json($response, $response['code']); 
        }
    }

    public function deleteTask(Request $request) {
        $taskID = $request->id;
        if (isset($taskID) || !isNull($taskID)){
            try {
                $currentStatus = DB::table('task')->where('ID', $taskID)->get('done');
                if ($currentStatus[0]->done == 1)
                    DB::table('task')->where('ID', $taskID)->delete();
                else{
                    $response = array(
                        'message' => 'You cannot delete uncompleted tasks.',
                        'code' => 400
                    );
                    return response()->json($response, $response['code']);
                }
            }
            catch(\Illuminate\Database\QueryException $e) {
                $response = array(
                    'message' => 'An error has ocurred while api is attemp to delete a task from DB',
                    'error' => $e,
                    'code' => 500
                );
                return response()->json($response, $response['code']);
            }
            $response = array(
                'message' => 'Task '. $taskID. ' deleted.',
                'code' => 200
            );
            return response()->json($response, $response['code']);
        } else {
            $response = array(
                'message' => 'Endpoint cannot get the task id, try again.',
                'code' => 500
            );
            return response()->json($response, $response['code']);
        }
    }

    public function updateTaskStatus(Request $request){
        $taskID = $request->id;
        if (isset($taskID)){
            try{
                $currentStatus = DB::table('task')->where('ID', $taskID)->get('done');
                if ($currentStatus[0]->done == 0)
                    DB::table('task')->where('ID', $taskID)->update(['done' => 1]);
                else
                    DB::table('task')->where('ID', $taskID)->update(['done' => 0]);
            }
            catch(\Illuminate\Database\QueryException $e) {
                $response = array(
                    'message' => 'An error has ocurred while api was attemped to update a task status.',
                    'error' => $e,
                    'code' => 500
                );
                return response()->json($response, $response['code']);
            }
            $response = array(
                'message' => 'status of task '. $taskID. ' were updated.',
                'code' => 200
            );
            return response()->json($response, $response['code']);
        } else{
            $response = array(
                'message' => 'Endpoint cannot get the task id, try again.',
                'code' => 400
            );
            return response()->json($response, $response['code']);
        }
    }

    public function updateTask(Request $request) {
        $params = array(
            'id' => $request->id,
            'title' => $request->title,
            'description' => $request->description
        );
        $validateData = Validator::make($params, [
            'id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);
        if (!$validateData->fails()) {
            //Update task
            try{
                DB::table('task')
                ->where('ID', $params['id'])
                ->update(['title' => $params['title'], 'description' => $params['description']]);
            }
            catch(\Illuminate\Database\QueryException $e){
                $response = array(
                    'message' => 'An error has ocurred while api was attemped to update a task info.',
                    'error' => $e,
                    'code' => 500
                );
                return response()->json($response, $response['code']);
            }
            $response = array(
                'message' => 'Task '. $params['id'] . ' has been updated.',
                'code' => 200
            );
            return response()->json($response, $response['code']);
        } else {
            return response()->json($validateData->errors(), 400);
        }
    }

}
