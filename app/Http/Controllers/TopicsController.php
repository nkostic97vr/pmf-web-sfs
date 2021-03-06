<?php

namespace App\Http\Controllers;

use Auth;
use Validator;

use App\Post;
use App\Board;
use App\Forum;
use App\Topic;
use App\ReadTopic;

class TopicsController extends Controller {

    public function show($board_address, $topic_slug) {
        $board = get_board($board_address);

        if (!$board->is_admin() && !$board->is_visible)
            return alert_redirect(route('website.index'), 'info', 'Forum trenutno nije vidljiv.');

        $topicQ = $board->topics();
        if ($board->is_admin())
            $topicQ = $topicQ->withTrashed();
        $topic = $topicQ->where('topics.slug', $topic_slug)->firstOrFail();

        // Ne dozvoli pristup ako je obrisan forum ili kategorija.
        if ($forum = $topic->forum()->firstOrFail())
            if ($forum->parent_id)
                $forum->parent()->firstOrFail();
        $topic->category()->firstOrFail();

        $posts = ($board->is_admin() ? Post::withTrashed() : Post::query())
                ->where('topic_id', $topic->id)->orderBy('created_at', 'asc')
                ->paginate();

        $vars = [
            'topic' => $topic,
            'posts' => $posts,
            'forum' => $topic->forum,
            'solution' => $topic->solution(),
            'last_post' => $topic->last_post(),
            'first_post' => $topic->first_post(),
            'topic_starter' => $topic->starter(),
            'category' => $topic->forum->category,
            'parent_forum' => $topic->forum->parent,
        ];

        if ($topic->forum->parent_id) {
            $vars['parent'] = Forum::findOrFail($topic->forum->parent_id);
        }

        if (Auth::check() && !$topic->is_old()) {
            ReadTopic::firstOrCreate(['user_id' => Auth::id(), 'topic_id' => $topic->id]);
        }

        return view('public.topic')->with($vars);
    }

    public function lock($board_address, $id) {
        $topic = Topic::findOrFail($id);
        $topic->is_locked = !$topic->is_locked;
        $topic->save();
        return redirect()->back();
    }

    public function store() {
        $request = request();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous(). '#scform')->withErrors($validator)->withInput();
        }

        $topic = new Topic;
        $topic->title = $request->title;
        $topic->slug = str_slug($topic->title);
        $topic->forum_id = $request->forum_id;
        $topic->save();

        $topic->slug = unique_slug($topic->title, $topic->id);
        $topic->save();

        $post = new Post;
        $post->content = $request->content;
        $post->topic_id = $topic->id;
        $post->user_id = Auth::id();
        $post->save();

        return redirect(route_topic_show($topic));
    }

    public function update_title($id) {
        $request = request();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $topic = Topic::findOrFail($id);
        $topic->title = $request->title;
        $topic->slug = unique_slug($topic->title, $topic->id);
        $topic->save();

        return redirect()->back();
    }

    public function update_solution($id) {
        $request = request();

        $topic = Topic::findOrFail($id);
        $topic->solution_id = $request->solution_id;
        $topic->save();

        return redirect()->back();
    }
}
