<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookLog extends Model
{
    //// 👇 この一行を追加！これで保存が許可されます
    protected $fillable = ['book_id', 'read_at', 'comment'];

    // 本とのつながり（逆方向）もついでに書いておくと後で便利です
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
