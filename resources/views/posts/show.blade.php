<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">{{ $post->title }}</h1>
                    <p class="text-gray-600">{{ $post->text_content }}</p>
                    <p class="text-sm text-gray-500">by {{ $post->user->username }}</p>

                    @if($post->medias->isNotEmpty())
                        <img src="{{ $post->medias->first()->media_url }}" alt="Image for {{ $post->title }}" class="mt-4 max-w-xs max-h-xs">
                    @endif

                    @if ($post->tags->isNotEmpty())
                        <p class="mt-2">
                            タグ: 
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('posts.by.tag', $tag) }}" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">{{ $tag->name }}</a>
                            @endforeach
                        </p>
                    @endif

                    <p class="text-sm text-gray-500 mt-2">閲覧数: {{ $post->impression_count }}</p>

                    <div class="flex items-center mt-4">
                        <button 
                            id="like-button-{{ $post->id }}" 
                            class="text-blue-500 hover:underline"
                            onclick="toggleLike({{ $post->id }})">
                            イイネ
                        </button>
                        <span id="likes-count-{{ $post->id }}" class="ml-2">{{ $post->likes()->count() }}</span>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4">プロフィール</h2>
                        @if ($post->user->profile)
                            <p>{{ $post->user->profile->self_introduction }}</p>
                        @else
                            <p>このユーザーのプロフィールはありません。</p>
                        @endif
                    </div>

                    @if (Auth::id() === $post->user_id)
                        <div class="flex-col space-y-2 mt-4">
                            <a href="{{ route('posts.edit', $post) }}" class="block text-blue-500 hover:underline">編集</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('この投稿を削除してもよろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block text-red-500 hover:underline">削除</button>
                            </form>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline">投稿一覧へ戻る</a>
                    </div>

                    <!-- コメント一覧 -->
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4">コメント一覧</h2>
                        @forelse ($post->comments as $comment)
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">{{ $comment->user->username }}さんのコメント:</p>
                                <p>{{ $comment->text_content }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p>コメントはまだありません。</p>
                        @endforelse
                    </div>

                    <!-- コメントフォーム -->
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4">コメントを投稿する</h2>
                        <form action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea name="text_content" rows="4" class="w-full border border-gray-300 p-2 rounded-lg" required></textarea>
                                @error('text_content')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">コメントを投稿</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  const likesCount = document.getElementById(`likes-count-${postId}`);
                  likesCount.innerText = data.likes_count;
              });
        }
    </script>
</x-app-layout>
