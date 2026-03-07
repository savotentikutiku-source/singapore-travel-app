<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Book extends Model
    {
        protected $fillable = ['title', 'author', 'isbn', 'image_url', 'description'];

        // 👇 これを追加！「一冊の本は、たくさんの感想（logs）を持っている」という設定
        public function logs()
        {
            //return $this->hasMany(BookLog::class);
            // 最新の読書ログが最初に来るように設定
            return $this->hasMany(BookLog::class)->latest();
        }
    }

