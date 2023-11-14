<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function profile(Group $group)
    {
        return Inertia::render('GroupProfile', [
            'group' => $group,
            'members' => $group->members,
            'user_friends' => auth()->user()->friends
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $group = Group::create([
            'owner' => auth()->user()->id,
            'name' => request()->group_name
        ]);

        return $group;
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
        $group->delete();
        return redirect()->back();
    }
}
