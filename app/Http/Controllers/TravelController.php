<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Checklist;
use App\Models\Expense; // 👈 この一行を追加！

class TravelController extends Controller
{
    // indexメソッドも修正して $expenses を画面に渡すようにしてください
    public function index() {
        $schedules = Schedule::orderBy('date')->orderBy('time')->get();
        $checklists = Checklist::all();
        $expenses = Expense::latest()->get(); // これを追加
        return view('travel.index', compact('schedules', 'checklists', 'expenses'));
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

    public function storeExpense(Request $request)
    {
        Expense::create([
            'title' => $request->title,
            'amount_sgd' => $request->amount_sgd,
            'amount_jpy' => $request->amount_sgd * 125, // 125円固定で換算
        ]);
        return redirect('/travel');
    }


}