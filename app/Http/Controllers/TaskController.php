<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Auth;

class TaskController extends Controller
{
    public function __construct()
	{
	    // $this->middleware('auth');
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Task::with('user')->with('subtask')->get();
        $tasks = Task::all();
        $users = User::select('name', 'id')->get();

        return view('task.index',compact('data', 'users', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task)
    {
        $posting = $task::create($request->all());
        return response()->json(['message' => 'Task has been sucessfully saved', 'data' => $posting]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        try {
            $posting = $task->update($request->all());
            return response()->json(['message' => 'Task has been sucessfully saved', 'data' => $posting]);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    /**
     * Remove temporary the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function softDelete($id)
    {
        $note = Task::destroy($id);
        if ($note) {
            $response =  response()->json(['message' => 'Successfully deleted', 'data' => $note]);
        } else {
            $response =  response()->json(['code' => 404,'message' => 'Not found','success' => false,]);
        }
    
        return $response;
    }
    
     /**
     * Restore the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $note = Task::onlyTrashed()->find($id);

        if (!is_null($note)) {
            $note->restore();
            $response =  response()->json(['message' => 'Successfully restored', 'data' => $note]);
        } else {
            $response =  response()->json(['code' => 404,'message' => 'Not found','success' => false,]);
        }

        return response($response);
    }

    /**
     * Display a trash listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function softDeleted()
    {
        $data = Task::onlyTrashed()->get();
        return view('task.deleted',compact('data'));
    }

    /**
     * Display a listing on the chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function chartIndex()
    {
        $data = Task::all();
        $users = DB::table('users as u')
             ->select(DB::raw('u.id, name, SUM(CASE WHEN status = "Complete" THEN 1 ELSE 0 END) AS completed'))
             ->leftJoin('tasks as t', 't.user_id', '=', 'u.id')
             ->whereNull('deleted_at')
             ->groupBy('name')
             ->groupBy('u.id')
             ->get();

        return view('task.chart',compact('data', 'users'));
    }

    /**
     * Display a listing of the noraml user resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDashboard()
    {
        $data = Task::with('user')->where('user_id', Auth::user()->id)->get();
        $users = User::select('name', 'id')->get();

        return view('task.dashboard',compact('data', 'users'));
    }
}
