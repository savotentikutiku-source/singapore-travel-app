<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    // 以下の1行を付け加えることで、保存の許可を出します
    protected $fillable = ['date', 'time', 'event', 'location'];
}