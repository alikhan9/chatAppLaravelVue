<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Group::create([
            'owner' => auth()->user()->id,
            'name' => request()->group_name
        ]);

        return redirect()->back();
    }

    public function join()
    {
        GroupMember::create([
            'user_id' => auth()->user()->id,
            'group_id' => request()->group_id
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        dd($group);
        $group->delete();
        return redirect()->back();
    }
}
