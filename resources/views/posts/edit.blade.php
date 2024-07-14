<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">Edit Post</h1>
                    
                    <!-- 投稿編集フォーム -->
                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">タイトル</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{ $post->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="text_content">説明文</label>
                            <textarea name="text_content" class="form-control" id="text_content" rows="3" required>{{ $post->text_content }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">イメージ</label>
                            <input type="file" name="image" class="form-control-file" id="image">
                        </div>
                        <div class="form-group">
                            <label for="tags">タグ</label>
                            <select name="tags[]" class="form-control" id="tags" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- プロフィール情報 -->
                        <div class="form-group">
                            <label for="introduction">自己紹介</label>
                            <textarea name="introduction" class="form-control" id="introduction" rows="3">{{ optional(auth()->user()->profile)->introduction }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">上書き</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
