<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if ($request->title) {
            $tasks = Task::where('title', 'like', '%' . $request->title . '%')->orderBy('created_at', 'desc')->get();
            return response()->json($tasks);
        }
        $tasks = Task::orderBy('created_at', 'desc')->get();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->save();
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->save();
        return response()->json($task);
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
