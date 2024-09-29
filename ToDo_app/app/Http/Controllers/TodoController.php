<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = auth()->user()->todos;

        return response()->json($todos, 200);
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        
        $todo = Todo::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'completed' => $validated['completed'] ?? false,
            'user_id' => auth()->id(), 
        ]);

        return response()->json($todo, 201);
    }

    public function show($id)
    {
        $todo = auth()->user()->todos()->find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        return response()->json($todo, 200);
    }

    
    public function update(Request $request, $id)
    {
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        
        $todo = auth()->user()->todos()->find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

       
        $todo->update($validated);

        return response()->json($todo, 200);
    }

    
    public function destroy($id)
    {
        
        $todo = auth()->user()->todos()->find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }
        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully'], 200);
    }
}
