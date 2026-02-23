<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シンガポール旅のしおり</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/5082/5082531.png">
    <meta name="apple-mobile-web-app-title" content="SG旅しおり">
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-red-600 text-white p-4 shadow-lg">
        <h1 class="text-xl font-bold text-center">🇸🇬 Singapore Trip 2026</h1>
    </nav>

    <div class="max-w-md mx-auto p-4">
        
        <section class="mb-8">
            <h2 class="text-lg font-bold mb-3 border-l-4 border-red-600 pl-2">📅 スケジュール</h2>
            <form action="/travel/schedule" method="POST" class="mb-6 bg-white p-4 rounded-lg shadow">
                @csrf
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <input type="date" name="date" class="border p-2 rounded text-sm" required>
                    <input type="time" name="time" class="border p-2 rounded text-sm" required>
                </div>
                <input type="text" name="event" placeholder="予定を入力（例：チキンライスを食べる）" class="border p-2 rounded w-full mb-2 text-sm" required>
                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded font-bold">予定を追加</button>
            </form>
            <div class="bg-white rounded-lg shadow p-4">
                <section class="mb-8">
                    <h2 class="text-lg font-bold mb-3 border-l-4 border-red-600 pl-2">📅 スケジュール</h2>
                    
                    <div class="space-y-4">
                        @php $currentDate = null; @endphp
                        @forelse($schedules as $schedule)
                            {{-- 日付が変わるタイミングで日付の見出しを表示 --}}
                            @if($currentDate !== $schedule->date)
                                <div class="font-bold text-red-600 mt-4 text-sm">{{ $schedule->date }}</div>
                                @php $currentDate = $schedule->date; @endphp
                            @endif

                            <div class="bg-white rounded-lg shadow p-3 flex items-center">
                                <div class="w-16 text-xs text-gray-500 font-mono">{{ substr($schedule->time, 0, 5) }}</div>
                                <div class="flex-1 font-medium">{{ $schedule->event }}</div>
                            </div>
                        @empty
                            <div class="bg-white rounded-lg shadow p-4 text-gray-400 text-sm">予定はまだありません。</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-lg font-bold mb-3 border-l-4 border-red-600 pl-2">🎒 持ち物・お土産</h2>
            <form action="/travel/checklist" method="POST" class="mb-6 bg-white p-4 rounded-lg shadow">
                @csrf
                <input type="text" name="item_name" placeholder="持ち物・お土産を入力" class="border p-2 rounded w-full mb-2 text-sm" required>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold">リストに追加</button>
            </form>
            <div class="bg-white rounded-lg shadow p-4">
                @forelse($checklists as $item)
                    <div class="flex items-center py-3 border-b last:border-0">
                        {{-- チェックボックスに onclick を追加 --}}
                        <input type="checkbox" 
                            class="mr-3 h-6 w-6 rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                            data-id="{{ $item->id }}"
                            onclick="toggleStrikethrough(this)"
                            {{ $item->is_completed ? 'checked' : '' }}>
                        
                        {{-- 文字を <span> で囲み、IDの代わりにクラスなどで指定しやすくする --}}
                        <span class="text-lg transition-all duration-300 {{ $item->is_completed ? 'line-through text-gray-400' : 'text-gray-700' }}">
                            {{ $item->item_name }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm p-4">リストは空っぽです。</p>
                @endforelse
            </div>
        </section>

    <section>
        <h2 class="text-lg font-bold mb-3 border-l-4 border-red-600 pl-2">💰 為替計算（SGD ➔ JPY）</h2>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <input type="number" id="amount" placeholder="金額(SGD)を入力" class="border p-2 rounded w-full mb-2 text-lg font-bold text-center" oninput="convert()">
            <p class="text-3xl font-bold text-red-600">約 <span id="result">0</span> 円</p>
            <p class="text-xs text-gray-400 mt-2">レート設定：1 SGD = <input type="number" id="rate" value="125" class="w-12 border-b text-center"> 円</p>
        </div>
    </section>

    <section class="mt-8 mb-8">
        <h2 class="text-lg font-bold mb-3 border-l-4 border-yellow-500 pl-2">💰 出費メモ（記録）</h2>
        
        <form action="/travel/expense" method="POST" class="bg-white p-4 rounded-lg shadow mb-4">
            @csrf
            <input type="text" name="title" placeholder="項目（例：ランチ）" class="border p-2 rounded w-full mb-2" required>
            <div class="flex gap-2 mb-2">
                <input type="number" name="amount_sgd" placeholder="金額(SGD)" class="border p-2 rounded w-full" required>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded font-bold">記録</button>
            </div>
        </form>

        <div class="bg-white rounded-lg shadow p-4">
            @php $totalJpy = 0; @endphp
            @foreach($expenses ?? [] as $expense)
                <div class="flex justify-between border-b py-2 last:border-0">
                    <span>{{ $expense->title }}</span>
                    <span class="font-bold">{{ number_format($expense->amount_jpy) }} 円 <span class="text-xs text-gray-400">({{ $expense->amount_sgd }} SGD)</span></span>
                </div>
                @php $totalJpy += $expense->amount_jpy; @endphp
            @endforeach
            <div class="mt-4 pt-2 border-t-2 border-double text-right font-bold text-lg">
                合計: {{ number_format($totalJpy) }} 円
            </div>
        </div>
    </section>

    <script>
        function convert() {
            const amount = document.getElementById('amount').value;
            const rate = document.getElementById('rate').value;
            const result = Math.floor(amount * rate);
            document.getElementById('result').innerText = result.toLocaleString();
        }

        // --- 【ここから追加！】チェックボックスの線を引く関数 ---
        function toggleStrikethrough(checkbox) {
            // 1. 画面の見た目を先に変える（サクサク感を出すため）
            const textSpan = checkbox.nextElementSibling;
            if (checkbox.checked) {
                textSpan.style.textDecoration = "line-through";
                textSpan.style.color = "#9ca3af";
            } else {
                textSpan.style.textDecoration = "none";
                textSpan.style.color = "#374151";
            }

            // 2. プロモード：裏側でデータベースへ送信！
            const itemId = checkbox.getAttribute('data-id'); // 埋め込んだIDを取得
            
            fetch(`/travel/checklist/${itemId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravelのセキュリティバリアを通過するための鍵
                }
            }).then(response => {
                if (!response.ok) {
                    alert('通信エラーが発生しました。ネット環境を確認してください。');
                    // 失敗したら画面を更新して元の状態に戻す
                    window.location.reload(); 
                }
            });
        }
    </script>
    </div>

</body>
</html>