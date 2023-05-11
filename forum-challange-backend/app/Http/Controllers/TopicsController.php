<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTopicRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
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

    public function store(StoreTopicRequest $request)
    {
        $validated = $request->validated();

        $Topic = Topic::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'slug' => Str::slug($validated['title']),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Topic created successfully',
            'topic' => $Topic,
        ]);
    }

    public function show(Topic $topic)
    {
        return response()->json([
            'status' => 'success',
            'topic' => $topic,
            'comments' => Comment::where('topic_id', $topic->id)->orderByDesc('id')->cursorPaginate(20),
        ]);
    }

    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $validated = $request->validated();

        $topic->title = $validated['title'];
        $topic->description = $validated['description'];
        $topic->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Topic updated successfully',
            'topic' => $topic,
        ]);
    }

    public function delete(DeleteTopicRequest $request, Topic $topic)
    {
        $topic->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Topic deleted successfully',
            'topic' => $topic,
        ]);
    }
}
