<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{

    public function index()
    {
        $Topics = Topic::orderByDesc('id')->cursorPaginate(3);
        return response()->json([
            'status' => 'success',
            'topics' => $Topics,
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
            'slug' => Str::slug($request->title),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Topic created successfully',
            'topic' => $Topic,
        ]);
    }

    public function show($id)
    {
        $Topic = Topic::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'topic' => $Topic,
            'comments' => Comment::where('topic_id', $Topic->id)->orderByDesc('id')->cursorPaginate(20),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $Topic = Topic::findOrFail($id);
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
        $Topic = Topic::findOrFail($id);
        $Topic->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Topic deleted successfully',
            'topic' => $Topic,
        ]);
    }
}
