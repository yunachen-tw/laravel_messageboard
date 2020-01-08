<?php

namespace App\Http\Controllers;

use App\Board;
use App\Message;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function showList()
    {
        $boards = Board::orderBy('updated_at', 'desc')->paginate(15);
        session()->forget('account');
        return view('board.boardlist', ['boards' => $boards]);
    }
    public function showUserBoardlist($user_id)
    {
        $boards = Board::where('user_id', $user_id)->orderBy('updated_at', 'desc')->paginate(15);
        session()->flash('account', $boards->first()->user->account);
        return view('board.boardlist', ['boards' => $boards]);
    }
    public function showMessage($board_id)
    {
        $scores = [];
        $board = Board::find($board_id);
        $messages = $board->messages()->paginate(15);
        foreach ($messages as $message) {
            $scores[$message->message_id] = $message->scores;
        }
        return view('board.message', ['messages' => $messages, 'board' => $board, 'scores' => $scores]);
    }
    public function showManage()
    {
        $boards = Auth::user()->boards;
        return view('board.manage', ['boards' => $boards]);
    }
    public function deleteBoard($board_id)
    {
        $board = Board::find($board_id);
        $owner_id = $board->user_id;
        // 刪除者是版主才能執行刪除動作
        if ($owner_id != Auth::user()->user_id) {
            $result['error'] = true;
            $result['message'] = '非版主無法刪除此留言板！';
            return response()->json($result, 403);
        }
        $dele_result = $board->delete();
        if (!$dele_result) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '刪除失敗');
            $result['error'] = true;
            $result['message'] = '刪除失敗';
            return response()->json($result, 403);
        }
        session()->flash('alert-info', 'success');
        session()->flash('result', '刪除成功');
        $result['error'] = false;
        $result['message'] = '刪除成功';
        return response()->json($result, 200);
    }
    public function addBoard(Request $request)
    {
        $validatedData = $request->validate([
            'board_title' => 'required|max:20',
            'board_describe' => 'required|max:500',
        ]);
        $title = htmlspecialchars($request->input('board_title'));
        $describe = htmlspecialchars($request->input('board_describe'));
        // 計算目前使用者有幾個留言板, 超過3個則顯示錯誤訊息
        $boards = Auth::user()->boards;
        if (3 <= count($boards)) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '使用者最多僅能擁有3個留言板！');
            $result['error'] = true;
            $result['message'] = '使用者最多僅能擁有3個留言板！';
            return response()->json($result, 403);
        }
        $insert_result = Board::insert([
            'title' => $title,
            'describe' => $describe,
            'user_id' => Auth::user()->user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        if (FALSE == $insert_result) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '新增失敗');
            $result['error'] = true;
            $result['message'] = '新增失敗';
            return response()->json($result, 403);
        }
        session()->flash('alert-info', 'success');
        session()->flash('result', '新增成功');
        $result['error'] = false;
        $result['message'] = '新增成功';
        return response()->json($result, 200);
    }
    public function addMessage(Request $request)
    {
        $validatedData = $request->validate([
            'board_id' => 'required',
            'message_content' => 'required|max:140',
        ]);
        $board_id = $request->input('board_id');
        $message_content = htmlspecialchars($request->input('message_content'));
        $insert_result = Message::insert([
            'user_id' => Auth::user()->user_id,
            'board_id' => $board_id,
            'content' => $message_content,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        if (FALSE == $insert_result) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '留言失敗');
            $result['error'] = true;
            $result['message'] = '留言失敗';
            return response()->json($result, 403);
        }
        // 留言後更新 board 的最後更新時間
        $board_result = Board::find($board_id)->update(['updated_at' => date('Y-m-d H:i:s')]);
        if (FALSE == $board_result) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '留言版時間更新失敗');
            $result['error'] = true;
            $result['message'] = '留言版時間更新失敗';
            return response()->json($result, 403);
        }
        session()->flash('alert-info', 'success');
        session()->flash('result', '留言成功');
        $result['error'] = false;
        $result['message'] = '留言成功';
        return response()->json($result, 200);
    }
    public function deleteMessage($message_id)
    {
        $message = Message::find($message_id);
        // 刪除者不是留言的人也不是版主的話就無法刪除
        if (!$message->deletable(Auth::user()->user_id)) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '非留言者或版主無法刪除此留言！');
            $result['error'] = true;
            $result['message'] = '非留言者或版主無法刪除此留言！';
            return response()->json($result, 403);
        }
        $dele_result = $message->delete();
        if (!$dele_result) {
            session()->flash('alert-info', 'danger');
            session()->flash('result', '刪除失敗');
            $result['error'] = true;
            $result['message'] = '刪除失敗';
            return response()->json($result, 403);
        }
        session()->flash('alert-info', 'success');
        session()->flash('result', '刪除成功');
        $result['error'] = false;
        $result['message'] = '刪除成功';
        return response()->json($result, 200);
    }
    public function scoreMessage(Request $request, $message_id)
    {
        $score = $request->input('score');
        // 若是第一次評分, 分數差就是這次給的分數
        $score_change = $score;
        // 取得之前評分的分數, 如果有平過分要重新計算分數差
        $search = Score::where('message_id', $message_id)->where('user_id', Auth::user()->user_id)->first();
        if ($search) {
            // 計算此次評分改變的分數總共是差多少
            $score_change = $score - $search->score;
        }
        $update_result = Score::updateOrCreate(
            ['message_id' => $message_id, 'user_id' => Auth::user()->user_id],
            ['score' => $score]
        );
        if (!$update_result) {
            $result['error'] = true;
            $result['message'] = '評分失敗';
            return response()->json($result, 403);
        }
        $result['error'] = false;
        $result['message'] = '評分成功';
        $result['data']['score_change'] = $score_change;
        return response()->json($result, 200);
    }
}
