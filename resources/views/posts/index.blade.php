<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">Posts</h1>
                    
                    <!-- 検索フォーム -->
                    <form action="{{ route('posts.search') }}" method="GET" class="mb-4">
                        <input type="text" name="query" value="{{ request('query') }}" placeholder="検索..." class="border border-gray-300 rounded-md py-1 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit" class="ml-2 bg-blue-500 text-black rounded-md px-3 py-1">検索する</button>
                    </form>

                    @if(isset($query))
                        <p class="mb-4">検索結果: <strong>{{ $resultCount }}</strong> 件</p>
                    @endif
                    
                    <!-- 投稿一覧 -->
                    @foreach ($posts as $post)
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold">
                                <a href="{{ route('posts.show', $post) }}" class="text-blue-500 hover:underline" onclick="incrementImpression({{ $post->id }})">{{ $post->title }}</a>
                            </h2>
                            <p class="text-gray-600">{{ $post->text_content }}</p>
                            <p class="text-sm text-gray-500">投稿者: {{ $post->user->username }}</p>

                            <!-- 投稿画像 -->
                            @if($post->medias->isNotEmpty())
                                <img src="{{ $post->medias->first()->media_url }}" alt="Image for {{ $post->title }}" class="mt-4 max-w-xs max-h-xs">
                            @endif
                            
                            <!-- タグの表示 -->
                            @if ($post->tags->isNotEmpty())
                                <p class="mt-2">
                                    タグ: 
                                    @foreach ($post->tags as $tag)
                                        <a href="{{ route('posts.by.tag', $tag) }}" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">{{ $tag->name }}</a>
                                    @endforeach
                                </p>
                            @endif

                            <!-- インプレッション数の表示 -->
                            <p class="text-sm text-gray-500 mt-2">閲覧数: {{ $post->impression_count }}</p>

                            <!-- イイネボタン -->
                            <div class="flex items-center mt-4">
                                <button 
                                    id="like-button-{{ $post->id }}" 
                                    class="text-blue-500 hover:underline"
                                    onclick="toggleLike({{ $post->id }})">
                                    イイネ
                                </button>
                                <span id="likes-count-{{ $post->id }}" class="ml-2">{{ $post->likes()->count() }}</span>
                            </div>

                            <!-- 編集・削除ボタン -->
                            <div class="flex-col space-y-2 mt-4">
                                <a href="{{ route('posts.edit', $post) }}" class="block text-blue-500 hover:underline">編集</a>
                                
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block text-red-500 hover:underline">削除</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- 投稿作成ページへのリンク -->
                    <div class="mt-6">
                        <a href="{{ route('posts.create') }}" class="text-blue-500 hover:underline">新規</a>
                    </div>
                    
                    <!-- ページネーション -->
                    <div class="mt-4">
                        {{ $posts->links() }} <!-- ページネーションリンクを表示 -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function incrementImpression(postId) {
            fetch(`/posts/${postId}/impression`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
        }

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
