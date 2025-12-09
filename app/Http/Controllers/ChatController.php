<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiAgentService;

class ChatController extends Controller
{
    // Fungsi ini dipanggil saat user kirim pesan di Chatbox
    public function send(Request $request, GeminiAgentService $ai)
    {
        $message = $request->input('message');
        
        // Panggil method Chat di Service (Native Tool Calling)
        $reply = $ai->chat($message);
        
        // Format bold text dari Markdown ke HTML
        $formatted = nl2br(preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $reply));

        return response()->json(['reply' => $formatted]);
    }
}