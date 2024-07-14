<!-- resources/views/posts/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">Create Post</h1>
                    
                    <!-- 投稿作成フォーム -->
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">タイトル</label>
                            <input type="text" name="title" class="form-control" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="text_content">説明</label>
                            <textarea name="text_content" class="form-control" id="text_content" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">イメージ</label>
                            <input type="file" name="image" class="form-control-file" id="image">
                        </div>
                        <div class="form-group">
                            <label for="tags">タグ</label>
                            <select name="tags[]" class="form-control" id="tags" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">投稿</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
