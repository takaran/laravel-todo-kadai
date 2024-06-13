<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Goal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TodoController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Goal $goal)
    {
        $request->validate([
            'content' => 'required',
            'tag_ids' => 'array', // tag_ids が配列であることをバリデーション
            'tag_ids.*' => 'exists:tags,id', // 各 tag_id が存在することをバリデーション
        ]);

        $todo = new Todo();
        $todo->content = $request->input('content');
        $todo->user_id = Auth::id();
        $todo->goal_id = $goal->id;
        $todo->done = false;
        $todo->save();

        // ユーザーが送信したタグを同期
        $todo->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('goals.index');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal, Todo $todo)
    {
        $request->validate([
            'content' => 'required',
            'tag_ids' => 'array', // tag_ids が配列であることをバリデーション
            'tag_ids.*' => 'exists:tags,id', // 各 tag_id が存在することをバリデーション
        ]);

        $todo->content = $request->input('content');
        $todo->user_id = Auth::id();
        $todo->goal_id = $goal->id;
        $todo->done = $request->boolean('done', $todo->done);
        $todo->save();

        // ユーザーが送信したタグを同期
        $todo->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('goals.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $goal, Todo $todo)
    {
        $todo->delete();

        return redirect()->route('goals.index');
    }
}
