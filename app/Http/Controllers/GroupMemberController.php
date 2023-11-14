<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        GroupMember::create([
            'user_id' => request()->user_id,
            'group_id' => request()->group_id
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        GroupMember::where('user_id', '=', auth()->user()->id)->where('group_id', '=', request()->group_id)->delete();
        return true;
    }
}
