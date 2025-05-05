<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
/**
 * display all tasks
 */
    public function index()
{
    $user = Auth::user();
    return response()->json($user->tasks);  
}

/**
 * to add new task and store in db
 */

public function store(Request $request)
{
    $request->validate([
        'text' => 'required|string',
        'date' => 'required|date',
    ]);

    $task = new Task();
    $task->text = $request->text;
    $task->date = $request->date;
    $task->user_id = request()->user()->id;
    $task->save();

    return response()->json($task, 201);
}
/**
     * Display the specified task.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
   
        if (Auth::id() !== $task->user_id) {
            return response()->json(['error' => 'User is not authorized'], 403);
        }

        return response()->json($task);
    }

    /**
     * Update the specified task.
     */

     public function update(Request $request, string $id)
     {
         try {
             $task = Task::findOrFail($id);
     
     
             if ($request->user()->id !== $task->user_id) {
                 return response()->json(['error' => 'User is not authorized'], 403);
             }
     
          
             $validated = $request->validate([
                 'text' => 'sometimes|required|string|max:255',
                 'date' => 'sometimes|required|date|after_or_equal:today',
                 'is_complete' => 'sometimes|boolean',
             ]);
     
             // Update only the fields present in the request
             if (isset($validated['text'])) {
                 $task->text = $validated['text'];
                 $task->is_complete = false;
             }
             if (isset($validated['date'])) {
                 $task->date = $validated['date'];
                 $task->is_complete = false;
             }
             if (isset($validated['is_complete'])) {
                 $task->is_complete = $validated['is_complete'];
             }
     
             $task->save(); 
     
             return response()->json($task);
     
         } catch (ModelNotFoundException $e) {
             return response()->json(['error' => 'Task not found.'], 404);
     
         } catch (\Illuminate\Validation\ValidationException $e) {
             return response()->json(['error' => $e->getMessage()], 422);
     
         } catch (\Exception $e) {
             return response()->json([
                 'error' => 'Something went wrong.',
                 'details' => $e->getMessage()
             ], 500);
         }
     }
     
     
    
    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        if (Auth::id() !== $task->user_id) {
            return response()->json(['error' => 'User is not authorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }

    /**
     * Toggle completion status for all tasks of the authenticated user.
     */
    public function completeAll()
    {
        $userId = Auth::id();

        $allComplete = Task::where('user_id', $userId)
            ->where('is_complete', false)
            ->count() === 0;
    
        $newStatus = !$allComplete;
    
        Task::where('user_id', $userId)->update(['is_complete' => $newStatus]);
    
        return response()->json([
            'message' => $newStatus
                ? 'All tasks marked as completed'
                : 'All tasks marked as incomplete',
            'newStatus' => $newStatus ,
        ]);
    }

    /**
     * Delete all tasks of the authenticated user.
     */
    public function deleteAll()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        Task::where('user_id', $user->id)->delete();
    
        return response()->json(['message' => 'All tasks deleted successfully']);
    }
}