<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinancialAiService
{
    // =========================================================================
    // 1. MAIN ENTRY POINT (Gerbang Utama)
    // =========================================================================
    /**
     * Menganalisis laporan keuangan menggunakan AI.
     * * @param array $data Data keuangan user.
     * @param string|null $preferredDriver Pilihan user ('gemini', 'deepseek', atau null).
     */
    public function analyzeReport($data, $preferredDriver = null)
    {
        // Prioritas: 1. Pilihan User, 2. Config .env, 3. Default 'auto'
        $driver = $preferredDriver ?? env('AI_DEFAULT_DRIVER', 'auto');

        Log::info("🤖 AI Request dimulai. Driver: " . strtoupper($driver));
        Log::info("📊 DATA MENTAH DARI CONTROLLER:\n" . json_encode($data, JSON_PRETTY_PRINT));
        // MODE AUTO: Coba Gemini dulu, jika gagal fallback ke DeepSeek
        if ($driver === 'auto') {
            try {
                return $this->callGemini($data);
            } catch (\Exception $e) {
                Log::warning("⚠️ Gemini Gagal/Limit ({$e->getMessage()}). Switch ke DeepSeek...");
                return $this->callDeepSeek($data);
            }
        }

        // MODE MANUAL: Paksa DeepSeek
        if ($driver === 'deepseek') {
            return $this->callDeepSeek($data);
        }

        // MODE MANUAL: Paksa Gemini
        return $this->callGemini($data);
    }


    private function callGemini($data)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent';

        $prompt = $this->buildPrompt($data);

        // PERBAIKAN: Tambahkan timeout(60) agar tidak error jika Gemini lambat
        $response = Http::timeout(60)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("{$url}?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

        if ($response->failed()) {
            throw new \Exception("Gemini API Error: " . $response->body());
        }

        $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return $this->parseJson($rawText);
    }

    // =========================================================================
    // 3. DRIVER: DEEPSEEK (Via OpenRouter)
    // =========================================================================
    private function callDeepSeek($data)
    {
        $apiKey = env('DEEPSEEK_API_KEY');
        $url = 'https://openrouter.ai/api/v1/chat/completions';

        $prompt = $this->buildPrompt($data);

        // PERBAIKAN: Tambahkan timeout(120) (2 menit)
        // DeepSeek R1 butuh waktu lama untuk "thinking process"
        $response = Http::timeout(120)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
                'Content-Type' => 'application/json',
            ])->post($url, [
                    'model' => 'deepseek/deepseek-r1-0528:free', // Versi Free sering antri/lambat
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful financial assistant. Return ONLY valid JSON.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                    'response_format' => ['type' => 'json_object']
                ]);

        if ($response->failed()) {
            Log::error("DeepSeek/OpenRouter Error: " . $response->body());
            return null;
        }

        $rawText = $response->json()['choices'][0]['message']['content'] ?? '';
        return $this->parseJson($rawText);
    }
    // =========================================================================
    // 4. HELPER: PROMPT BUILDER (Agar Konsisten)
    // =========================================================================
    private function buildPrompt($data)
    {
        // Default text jika data kosong
        $incomeType = $data['income_type'] ?? 'Tidak Diketahui';

        return "Bertindaklah sebagai penasihat keuangan pribadi yang bijak.
    
        PROFIL PENGGUNA:
        - Pekerjaan: " . ($data['user_job'] ?? '-') . "
        - Lokasi: " . ($data['user_city'] ?? '-') . "
        - Sumber Pemasukan: " . ($data['income_sources'] ?? '-') . "
        - TIPE PENDAPATAN: " . $incomeType . " (PENTING: Gunakan ini untuk menentukan strategi risiko).
        
        DATA KEUANGAN PERIODE INI (" . $data['period_name'] . "):
        - Total Pemasukan: Rp " . number_format($data['total_income']) . "
        - Total Pengeluaran: Rp " . number_format($data['total_expense']) . "
        - Sisa Uang (Balance): Rp " . number_format($data['balance']) . "
        - Tren Pengeluaran: " . $data['trend_text'] . "
        
        TUGAS:
        1. Tentukan STATUS (pilih satu: 'safe', 'warning', 'danger').
           - Pertimbangkan Tipe Pendapatan: Jika 'Tidak Tetap' dan sisa uang tipis, status harus lebih waspada dibanding yang 'Gaji Rutin'.
        2. Analisis singkat (2-3 kalimat tajam).
        3. 3 Rekomendasi konkret (poin-poin).

        OUTPUT JSON WAJIB (Hanya JSON murni tanpa markdown):
        {
            \"status\": \"safe|warning|danger\",
            \"analysis\": \"...\",
            \"recommendation\": \"(gabungkan 3 poin saran menjadi satu string paragraf atau dipisah baris baru)\"
        }";
    }

    // =========================================================================
    // 5. HELPER: JSON PARSER (Pembersih Respon AI)
    // =========================================================================
    private function parseJson($text)
    {
        // 1. Bersihkan markdown code block (```json ... ```)
        $cleanText = str_replace(['```json', '```', 'JSON'], '', $text);

        // 2. Ambil hanya bagian yang ada di dalam kurung kurawal {}
        if (preg_match('/\{[\s\S]*\}/', $cleanText, $matches)) {
            $json = json_decode($matches[0], true);

            // Validasi: Pastikan JSON valid dan punya key 'status'
            if (isset($json['status'])) {
                // Konversi rekomendasi array ke string (jika AI mengembalikan array)
                if (isset($json['recommendation']) && is_array($json['recommendation'])) {
                    $json['recommendation'] = implode("\n- ", $json['recommendation']);
                }
                return $json;
            }
        }

        Log::error("Gagal parse JSON dari AI. Raw text: " . substr($text, 0, 100) . "...");
        return null;
    }
}