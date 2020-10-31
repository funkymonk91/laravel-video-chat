<?php

namespace App\Http\Controllers;

use App\Events\UserConnectedToRoom;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Pusher\Pusher;

class RoomController extends Controller
{
    public function create()
    {
        $room = Room::create();
        return redirect(route('room.show', $room->id));
    }    

    public function show(Room $room, Request $request)
    {
        $user = $request->user();
        $others = User::where('id', '!=', $user->id)->pluck('name', 'id');

        return Inertia::render('Room/Show', [
            'user' => collect($request->user()->only(['id', 'name'])),
            'room' => $room
        ]);
    }
}
