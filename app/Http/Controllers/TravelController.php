<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Checklist;

class TravelController extends Controller
{
    public function index()
    {
        // データベースから予定と持ち物を全部取ってくる
        $schedules = Schedule::orderBy('date')->orderBy('time')->get();
        $checklists = Checklist::all();

        // 画面（travel.index）にデータを渡して表示する
        return view('travel.index', compact('schedules', 'checklists'));
    }

    // クラスの中に以下のメソッドを追加してください
    public function storeSchedule(Request $request)
    {
        // データベースに保存
        Schedule::create([
            'date' => $request->date,
            'time' => $request->time,
            'event' => $request->event,
        ]);

        // 画面をリロードする
        return redirect('/travel');
    }

    public function storeChecklist(Request $request)
    {
        Checklist::create([
            'item_name' => $request->item_name,
            'is_completed' => false,
        ]);

        return redirect('/travel');
    }

    // プロモード：Ajaxでチェック状態を切り替える処理
    public function toggleChecklist($id)
    {
        // IDから該当の持ち物データを探す
        $checklist = Checklist::findOrFail($id);
        
        // 今の状態を「反転」させる（trueならfalse、falseならtrueに）
        $checklist->is_completed = !$checklist->is_completed;
        
        // データベースに保存
        $checklist->save();

        // 画面の移動はせず、「成功したよ」という結果だけをJSON（データ）で返す
        return response()->json(['success' => true, 'is_completed' => $checklist->is_completed]);
    }
}