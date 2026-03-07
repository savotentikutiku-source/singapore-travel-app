<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイ本棚 & 読書記録</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 pb-20">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">📚 マイ本棚</h1>

        <div class="bg-white p-6 rounded-2xl shadow-sm mb-8">
            <h2 class="text-lg font-bold mb-4">新しい本を登録</h2>
            <form action="/books/register" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="isbn" placeholder="ISBNコードを入力 (例: 9784058025147)" 
                       class="flex-1 border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                    登録
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($books as $book)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-5">
                <div class="w-32 flex-shrink-0 mx-auto sm:mx-0">
                    @if($book->image_url)
                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="w-full h-auto rounded-lg shadow-md">
                    @else
                        <div class="w-full h-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">No Image</div>
                    @endif
                </div>

                <div class="flex-1">
                    <h3 class="font-bold text-xl text-gray-800 leading-tight mb-1">{{ $book->title }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $book->author }}</p>

                    <form action="/books/{{ $book->id }}/log" method="POST" class="bg-gray-50 p-3 rounded-xl mb-4">
                        @csrf
                        <div class="flex flex-col gap-2">
                            <input type="date" name="read_at" value="{{ date('Y-m-d') }}" 
                                   class="text-sm border border-gray-200 p-2 rounded-lg">
                            <textarea name="comment" rows="2" placeholder="感想やメモ..." 
                                      class="text-sm border border-gray-200 p-2 rounded-lg"></textarea>
                            <button type="submit" class="bg-green-500 text-white text-xs font-bold py-2 rounded-lg hover:bg-green-600 transition">
                                記録を追加
                            </button>
                        </div>
                    </form>

                    <div class="space-y-3">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Reading Logs</p>
                        @foreach($book->logs as $log)
                        <div class="text-sm border-l-2 border-green-400 pl-3 py-1">
                            <span class="text-xs text-gray-400 font-mono">{{ $log->read_at }}</span>
                            <p class="text-gray-700 mt-1">{{ $log->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>