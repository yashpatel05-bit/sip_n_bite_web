<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private $apiKey = 'AIzaSyB-5n7jCJ0XYskMeaoEZn8ckgMARgTLwZ0'; // Given by user

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');

        $systemPrompt = "You are an AI assistant for 'Sip N Bite Café'. You must ONLY answer questions related to the café, food recommendations, our menu, table bookings, coffee, offers, and dining. If the user asks about anything else (like live scores, coding, politics, etc.), politely decline and steer the conversation back to food and our café. Keep answers short, friendly, and helpful.";

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nUser: " . $userMessage]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I'm sorry, I couldn't process that.";
                return response()->json([
                    'success' => true,
                    'reply' => $reply
                ]);
            }

            return response()->json([
                'success' => false,
                'reply' => 'I am currently unable to reach the kitchen! Please try again later.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'reply' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }
}
