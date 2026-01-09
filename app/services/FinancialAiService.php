<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinancialAiService
{
    public function analyzeReport($data)
    {
        return $this->callDeepSeek($data);
    }

    private function callDeepSeek($data)
    {
        $apiKey = env('DEEPSEEK_API_KEY');
        
        // Hapus data detail tanggal yang tidak perlu dibaca AI
        if (isset($data['waste_dates'])) unset($data['waste_dates']);

        $prompt = $this->buildAnalysisPrompt($data);

        $response = Http::timeout(120)->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'deepseek/deepseek-r1:free',
            'messages' => [
                [
                    'role' => 'system', 
                    'content' => 'Anda adalah penasihat keuangan pribadi. Output wajib JSON.' // System prompt Bhs Indo
                ],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.6,
            'response_format' => ['type' => 'json_object']
        ]);

        if ($response->failed()) {
            Log::error("AI Error: " . $response->body());
            return null;
        }

        $rawText = $response->json()['choices'][0]['message']['content'] ?? '';
        return $this->parseJson($rawText);
    }

    private function buildAnalysisPrompt($data)
    {
        $json = json_encode($data);

        // Prompt dimodifikasi untuk instruksi Bahasa Indonesia
        return "Analisis data keuangan berikut (Mata Uang: IDR):
        {$json}

        Kamus Data:
        - 'income_profile': Jenis sumber pendapatan (PENTING untuk analisis risiko).
        - 'save_rate': % pendapatan yang ditabung. (Target > 20%).
        - 'top_cat': Kategori pengeluaran terbesar.
        - 'waste_count': Jumlah hari dengan pengeluaran tidak wajar (boros).
        - 'trend': Kenaikan pengeluaran dibanding periode lalu (+ boros, - hemat).

        Instruksi:
        1. Tentukan 'status': 'safe' (aman), 'warning' (waspada), atau 'danger' (bahaya).
           - Jika 'income_profile' adalah 'Fluktuatif', standar keamanan harus lebih ketat (wajib punya sisa uang lebih besar).
        2. 'analysis': Tulis 2-3 kalimat tajam dalam **BAHASA INDONESIA** tentang kesehatan keuangan user, sebutkan kategori terboros jika ada.
        3. 'recommendation': Berikan 3 saran aksi konkret dalam **BAHASA INDONESIA**.

        OUTPUT JSON SAJA (Tanpa Markdown):
        {
            \"status\": \"safe|warning|danger\",
            \"analysis\": \"... (Bahasa Indonesia)\",
            \"recommendation\": \"... (Bahasa Indonesia)\"
        }";
    }

    private function parseJson($text)
    {
        $text = preg_replace('/<think>[\s\S]*?<\/think>/', '', $text);
        $cleanText = str_replace(['```json', '```', 'JSON'], '', $text);
        
        if (preg_match('/\{[\s\S]*\}/', $cleanText, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (isset($decoded['status'])) {
                if (isset($decoded['recommendation']) && is_array($decoded['recommendation'])) {
                    $decoded['recommendation'] = implode("\n- ", $decoded['recommendation']);
                }
                return $decoded;
            }
        }
        return null;
    }
}