<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ai\Agents\EscrowCopilot;

class EscrowChatController extends Controller
{
    public function handleMessage(Request $request)
    {
        $user = Auth::user();

        $userState = [
            'name' => $user->name,
            'role' => 'buyer & seller',
            'current_step' => 'Create Selling Request',
            'last_error' => 'none',
        ];

        $agent = new EscrowCopilot($userState);

        return $agent->stream($request->input('message'));
    }
}
