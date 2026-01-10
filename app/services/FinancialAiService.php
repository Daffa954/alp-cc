<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinancialAiService
{
    public function analyzeReport($data)
    {
        // DEBUG: Tandai awal proses
        Log::channel('daily')->info('🤖 [AI START] Memulai proses analisis.', [
            'user_id' => auth()->id() ?? 'guest',
            'timestamp' => now()->toDateTimeString()
        ]);

        return $this->callDeepSeek($data);
    }

    private function callDeepSeek($data)
    {
        $apiKey = env('DEEPSEEK_API_KEY');
        $url = 'https://openrouter.ai/api/v1/chat/completions';

        // 1. Bersihkan Data (Hemat Token)
        if (isset($data['waste_dates']))
            unset($data['waste_dates']);

        // 2. Build Prompt
        $prompt = $this->buildAnalysisPrompt($data);

        // DEBUG: Catat data yang dikirim dan Prompt final
        Log::channel('daily')->info('📤 [AI REQUEST] Data dikirim ke DeepSeek:', [
            'metrics_payload' => $data, // Data angka mentah
            'final_prompt_length' => strlen($prompt), // Panjang karakter prompt
            // 'full_prompt' => $prompt // Uncomment jika ingin liat full text (bisa panjang)
        ]);

        $startTime = microtime(true); // Catat waktu mulai

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])->post($url, [
                        'model' => 'deepseek/deepseek-r1-0528:free',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Anda adalah penasihat keuangan pribadi. Output wajib JSON.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.6,
                        'response_format' => ['type' => 'json_object']
                    ]);

            $duration = round(microtime(true) - $startTime, 2); // Hitung durasi

            // DEBUG: Cek status HTTP
            if ($response->failed()) {
                Log::channel('daily')->error('❌ [AI ERROR] Gagal menghubungi OpenRouter/DeepSeek.', [
                    'status' => $response->status(),
                    'duration' => $duration . 's',
                    'body' => $response->body() // Pesan error dari API
                ]);
                return null;
            }

            $responseBody = $response->json();
            $rawContent = $responseBody['choices'][0]['message']['content'] ?? '';

            // DEBUG: Catat respon sukses
            Log::channel('daily')->info('✅ [AI SUCCESS] Respon diterima.', [
                'duration' => $duration . 's',
                'raw_content_preview' => substr($rawContent, 0, 200) . '...', // Preview 200 huruf awal
                'token_usage' => $responseBody['usage'] ?? 'unknown'
            ]);

            return $this->parseJson($rawContent);

        } catch (\Exception $e) {
            // DEBUG: Catat jika terjadi Exception (Timeout / Koneksi putus)
            Log::channel('daily')->error('💥 [AI EXCEPTION] Terjadi kesalahan sistem.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }

    private function buildAnalysisPrompt($data)
    {
        $json = json_encode($data);

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
        2. 'analysis': Tulis 2-3 kalimat tajam dalam **BAHASA INDONESIA** tentang kesehatan keuangan user.
        3. 'recommendation': Berikan 3 saran aksi konkret dalam **BAHASA INDONESIA**.

        OUTPUT JSON SAJA (Tanpa Markdown, Tanpa tag <think>):
        {
            \"status\": \"safe|warning|danger\",
            \"analysis\": \"...\",
            \"recommendation\": \"...\"
        }";
    }

    private function parseJson($text)
    {
        // 1. Bersihkan Chain of Thought (<think>...</think>) dari DeepSeek R1
        $cleanText = preg_replace('/<think>[\s\S]*?<\/think>/', '', $text);

        // 2. Bersihkan Markdown Code Block
        $cleanText = str_replace(['```json', '```', 'JSON'], '', $cleanText);
        $cleanText = trim($cleanText);

        // DEBUG: Cek hasil pembersihan sebelum decode
        // Log::channel('daily')->debug('🧹 [AI PARSE] Text setelah dibersihkan:', ['text' => $cleanText]);

        if (preg_match('/\{[\s\S]*\}/', $cleanText, $matches)) {
            $decoded = json_decode($matches[0], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::channel('daily')->error('⚠️ [AI PARSE ERROR] JSON Invalid.', ['error' => json_last_error_msg()]);
                return null;
            }

            if (isset($decoded['status'])) {
                // Format rekomendasi array ke string bullet points
                if (isset($decoded['recommendation']) && is_array($decoded['recommendation'])) {
                    $decoded['recommendation'] = implode("\n- ", $decoded['recommendation']);
                }
                return $decoded;
            }
        }

        Log::channel('daily')->warning('⚠️ [AI PARSE FAIL] Format output tidak dikenali.', ['raw_text' => $text]);
        return null;
    }
}