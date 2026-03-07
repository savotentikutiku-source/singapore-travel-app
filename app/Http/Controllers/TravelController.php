<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Checklist;
use App\Models\Expense; // 👈 この一行を追加！

class TravelController extends Controller
{
    // indexメソッドも修正して $expenses を画面に渡すようにしてください
    public function index()
    {
        $schedules = Schedule::orderBy('date')->orderBy('time')->get();
        $checklists = Checklist::all();
        $expenses = Expense::latest()->get(); // これを追加
        // 既存のスケジュールや持ち物を取得している処理の下に追記
        $preparations = \App\Models\Preparation::all();
        return view('travel.index', compact('schedules', 'checklists', 'expenses', 'preparations'));
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

    // 出費メモの追加処理
    public function storeExpense(Request $request)
    {
        $expense = new \App\Models\Expense();
        $expense->title = $request->title;
        
        // データベースの列名はamount_sgdですが、中身は入力された外貨として保存します
        $expense->amount_sgd = $request->amount_local; 
        
        // 通貨名（EURなど）を保存
        $expense->currency = $request->currency;
        
        // 日本円は「入力された外貨 × 入力されたレート」で計算して保存
        $expense->amount_jpy = $request->amount_local * $request->rate;
        
        $expense->save();

        return back();
    }

    // スケジュール削除処理
    public function destroySchedule($id)
    {
        // データベースから該当IDのスケジュールを探す
        // ※ 'Schedule' モデルを使っている前提です。ファイルの先頭に use App\Models\Schedule; があるか確認してください。
        $schedule = \App\Models\Schedule::find($id);

        // 見つかれば削除する
        if ($schedule) {
            $schedule->delete();
        }

        // 元の画面に戻る
        return back();
    }

    // 持ち物・お土産の削除処理
    public function destroyChecklist($id)
    {
        // Checklistモデルを探して削除
        // ※ファイルの先頭に use App\Models\Checklist; があるか確認してください
        $item = \App\Models\Checklist::find($id);

        if ($item) {
            $item->delete();
        }

        return back();
    }

    // 出費メモの削除処理
    public function destroyExpense($id)
    {
        // Expenseモデルを探して削除
        $expense = \App\Models\Expense::find($id);

        if ($expense) {
            $expense->delete();
        }

        return back();
    }

    // 準備リストの追加
    public function storePreparation(Request $request)
    {
        $item = new \App\Models\Preparation();
        $item->item_name = $request->item_name;
        $item->save();
        return back();
    }

    // 準備リストの削除
    public function destroyPreparation($id)
    {
        $item = \App\Models\Preparation::find($id);
        if ($item) {
            $item->delete();
        }
        return back();
    }

    // 準備リストのチェック切り替え
    public function togglePreparation($id)
    {
        $item = \App\Models\Preparation::find($id);
        if ($item) {
            $item->is_completed = !$item->is_completed;
            $item->save();
        }
        return response()->json(['success' => true]);
    }


}