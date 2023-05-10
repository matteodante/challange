<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicsController extends Controller
{

    public function index()
    {
        $Topics = Topic::all();
        return response()->json([
            'status' => 'success',
            'Topics' => $Topics,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $Topic = Topic::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Topic created successfully',
            'topic' => $Topic,
        ]);
    }

    public function show($id)
    {
        $Topic = Topic::find($id);
        return response()->json([
            'status' => 'success',
            'topic' => $Topic,
            'comments' => $Topic->comments->cursorPaginate(20),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $Topic = Topic::find($id);
        $Topic->title = $request->title;
        $Topic->description = $request->description;
        $Topic->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Topic updated successfully',
            'topic' => $Topic,
        ]);
    }

    public function destroy($id)
    {
        $Topic = Topic::find($id);
        $Topic->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Topic deleted successfully',
            'topic' => $Topic,
        ]);
    }
}
