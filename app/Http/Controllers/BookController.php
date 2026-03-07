<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use App\Models\BookLog;

class BookController extends Controller
{
    // 本の一覧と入力画面を表示
    public function index()
    {
        // 本をすべて取得し、それぞれの読書ログも一緒に読み込む
        $books = Book::with('logs')->latest()->get();
        // latest() を入れるだけで、新しく登録した順（作成日順）に並び替わります
        $books = Book::with('logs')->latest()->get();
        return view('books.index', compact('books'));
    }

    // ISBNで本を検索して保存
    public function register(Request $request)
    {
        $isbn = str_replace('-', '', $request->isbn);
        $response = Http::get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}");
        $data = $response->json();

        if (isset($data['items'][0]['volumeInfo'])) {
            $info = $data['items'][0]['volumeInfo'];
            
            Book::updateOrCreate(
                ['isbn' => $isbn],
                [
                    // 全ての項目に ?? (NULL合体演算子) をつけて、データがなくてもエラーにしない
                    'title' => $info['title'] ?? 'タイトル不明',
                    'author' => $info['authors'][0] ?? '不明',
                    // imageLinks が存在するかチェックしてから thumbnail を取得する
                    'image_url' => isset($info['imageLinks']) ? ($info['imageLinks']['thumbnail'] ?? null) : null,
                    'description' => $info['description'] ?? '',
                ]
            );
        }

        return redirect()->back();
    }

    // 読書ログ（日付と感想）を追加
    public function addLog(Request $request, $id)
    {
        BookLog::create([
            'book_id' => $id,
            'read_at' => $request->read_at,
            'comment' => $request->comment,
        ]);

        return redirect()->back();
    }
}