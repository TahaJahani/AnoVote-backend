<?php

namespace App\Http\Controllers;

use App\Http\Resources\PollResource;
use App\Http\Resources\ResultResource;
use App\Models\Poll;
use App\Models\UserPoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PollController extends Controller
{
    public function listAll(Request $request) {
        // TODO: check accessibility
        $polls = Poll::with('options')->get();
        return PollResource::collection($polls);
    }

    public function make(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'string',
            'is_multi_option' => 'required|boolean',
            "access_time" => 'required|date',
            "end_time" => 'required|date',
            "show_mode" => ['required', Rule::in(Poll::$SHOW_MODES)],
            'options' => 'required|min:1|array',
            'options.*' => 'required|string'
        ]);
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->first()], 400);

        $poll = Poll::create($request->only([
            'title', 'description', 'is_multi_option',
            'access_time', 'end_time', "show_mode"
        ]));
        $poll->options()->createMany(array_map(function ($item) {
            return ["text" => $item];
        }, $request->options));
        return response()->json(['slug' => $poll->slug]);
    }

    public function get($slug) {
        $poll = Poll::where('slug', $slug)->first();
        if ($poll)
            return PollResource::make($poll);
        return abort(404);
    }

    public function results($slug) {
        $poll = Poll::withoutGlobalScope('active')->where('slug', $slug)->first();
        $user = Auth::user();
        if (!$poll)
            return abort(404);
        if ($poll->show_mode == 'hidden' && !$user->tokenCan('admin'))
            return response()->json(['error' => 'شما اجازه دیدن نتایج این رای‌گیری را ندارید'], 403);
        if ($poll->show_mode == 'after_finish' && now()->isBefore($poll->end_time))
            return response()->json(['error' => 'نمایش نتایج تا پیش از اتمام رای‌گیری ممکن نیست'], 400);

        $has_voted = UserPoll::where('user_id', $user->id)->where('poll_id', $poll->id)->exists();
        if (!$user->tokenCan('admin') && !$has_voted)
            return response()->json(['error' => 'برای مشاهده نتایج، ابتدا رای خود را ثبت کنید'], 400);


        return ResultResource::collection($poll->options()->with('votes')->get());
    }
}
