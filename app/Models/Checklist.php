<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    // 保存を許可する項目を指定
    protected $fillable = ['item_name', 'is_completed', 'assignee'];
}