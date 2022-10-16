<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\UserPoll;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'poll_slug' => 'required|string',
            'options' => 'required|array|min:1'
        ]);
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->first()], 400);

        $user = Auth::user();
        $group = $user->group()->first();
        $poll = Poll::where('slug', $request->poll_slug)->first();
        if (!$poll)
            return response()->json(['error' => 'چنین رای‌گیری وجود ندارد'], 404);
        if (UserPoll::where('user_id', $user->id)->where('poll_id', $poll->id)->exists())
            return response()->json(['error' => 'شما قبلا در این رای گیری شرکت کرده‌اید'], 403);
        if (!$poll->is_multi_option & sizeof($request->options) > 1)
            return response()->json(['error' => 'انتخاب چند گزینه ممکن نیست'], 400);
        $options = $poll->options()->whereIn('id', $request->options)->get();
        if (sizeof($options) != sizeof($request->options))
            return response()->json(['error' => 'چنین گزینه‌ای وجود ندارد'], 404);
        foreach ($request->options as $option) {
            Vote::create([
                'option_id' => $option,
                'group_id' => $group->id
            ]);
        }
        UserPoll::create([
            'poll_id' => $poll->id,
            'user_id' => $user->id
        ]);
        return response()->json(['message' => 'رای شما با موفقیت ثبت شد']);
    }
}
