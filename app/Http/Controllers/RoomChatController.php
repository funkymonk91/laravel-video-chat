<?php

namespace App\Http\Controllers;

use App\Events\RoomMessage;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomChatController extends Controller
{
    public function store(Request $request, Room $room)
    {
        broadcast(new RoomMessage(auth()->user(), $room, $request->message))->toOthers();
    }
}
