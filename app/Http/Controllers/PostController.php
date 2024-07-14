<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cloudinary;

class PostController extends Controller
{
    public function index(Post $post, Request $request)
    {
        $query = $request->input('query');
    
        // 検索クエリがある場合は検索結果を、ない場合は全ての投稿を取得
        $posts = $query ? 
            $post->search($query)->paginate(10) : 
            $post->with('medias', 'user', 'tags')->paginate(10);
    
        return view('posts.index', compact('posts'));
    }


    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    public function store(Request $request, Media $media)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',
            'text_content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'array'
        ]);

        // Postモデルの作成
        $post = Post::create([
            'title' => $request->title,
            'text_content' => $request->text_content,
            'user_id' => auth()->id(),
        ]);
      
        // 画像アップロードとURL取得
        if ($request->file('image')) {
            $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $input = ["post_id" => $post->id, "media_url" => $image_url, "media_type" => 'image'];
            $media->fill($input)->save();
        }

        // タグの同期
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        $post->increment('impression_count');
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',
            'text_content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'array'
        ]);

        // Postモデルの更新
        $post->update([
            'title' => $request->title,
            'text_content' => $request->text_content,
        ]);

        // 画像のアップロードと更新
        if ($request->hasFile('image')) {
            $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // 既存のMediaを削除して新しいものを追加
            $post->medias()->delete();
            Media::create([
                'post_id' => $post->id,
                'media_type' => 'image',
                'media_url' => $image_url
            ]);
        }

        // タグの同期
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index');
    }
    
    public function showPostsByTag(Tag $tag)
    {
        $posts = $tag->posts;
        return view('posts.index', compact('posts'));
    }
    
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        // 検索クエリをタグ、投稿者名、タイトルで検索し、ページネーションを追加
        $posts = Post::where('title', 'like', "%{$query}%")
                     ->orWhereHas('tags', function ($q) use ($query) {
                         $q->where('name', 'like', "%{$query}%");
                     })
                     ->orWhereHas('user', function ($q) use ($query) {
                         $q->where('username', 'like', "%{$query}%");
                     })
                     ->with('medias', 'user', 'tags')
                     ->paginate(10);
    
        // 検索結果の件数を取得
        $resultCount = $posts->total();
    
        return view('posts.index', compact('posts', 'query', 'resultCount'));
    }


}
