<?php


use App\Models\Book; // 👈 これを一番上に追加
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TravelController;



Route::get('/', function () {
    return redirect('/travel'); // 自動的に /travel へ転送する
});


Route::get('/travel', [TravelController::class, 'index']);

Route::post('/travel/schedule', [TravelController::class, 'storeSchedule']);

// コントローラー名はご自身の環境に合わせて変更してください（例：TravelControllerなど）
Route::delete('/travel/schedule/{id}', [App\Http\Controllers\TravelController::class, 'destroySchedule']);

Route::delete('/travel/checklist/{id}', [App\Http\Controllers\TravelController::class, 'destroyChecklist']);

Route::delete('/travel/expense/{id}', [App\Http\Controllers\TravelController::class, 'destroyExpense']);

Route::post('/travel/checklist', [TravelController::class, 'storeChecklist']);

Route::patch('/travel/checklist/{id}/toggle', [TravelController::class, 'toggleChecklist']);

Route::post('/travel/expense', [TravelController::class, 'storeExpense']);

use Illuminate\Support\Facades\Http; // 👈 これがAPI通信の魔法の道具

Route::get('/book-test', function () {
    // Google APIにISBNで問い合わせる
    $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=isbn:9784058025147');
    
    // データを配列に変換
    $bookData = $response->json();
    
    // 中身を画面にドバっと表示して確認（デバッグ）
    dd($bookData); 
});


Route::get('/book-save-test', function () {
    $isbn = '9784058025147';
    $response = Http::get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}");
    $data = $response->json();

    // 取得したデータから必要な部分を抽出
    $info = $data['items'][0]['volumeInfo'];

    // データベースに保存！
    $book = Book::create([
        'title' => $info['title'],
        'author' => $info['authors'][0] ?? '不明',
        'isbn' => $isbn,
        'image_url' => $info['imageLinks']['thumbnail'] ?? null,
        'description' => $info['description'] ?? '',
    ]);

    return "「{$book->title}」を本棚に保存しました！";
});

// 本の一覧画面
Route::get('/books', [BookController::class, 'index']);
// 本の登録処理
Route::post('/books/register', [BookController::class, 'register']);
// 読書ログの追加処理
Route::post('/books/{id}/log', [BookController::class, 'addLog']);

// 旅行の準備リスト関連のルート
Route::post('/travel/preparation', [App\Http\Controllers\TravelController::class, 'storePreparation']);
Route::delete('/travel/preparation/{id}', [App\Http\Controllers\TravelController::class, 'destroyPreparation']);
Route::patch('/travel/preparation/{id}/toggle', [App\Http\Controllers\TravelController::class, 'togglePreparation']);