<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class GeminiAgentService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    // =========================================================================
    // FITUR 1: GENERATE REPORT (Untuk Halaman Laporan)
    // Menggunakan Context Injection & Structured Output (JSON)
    // =========================================================================
    // app/Services/GeminiAgentService.php

public function analyzeForReport($data)
{
    // KITA GUNAKAN GEMINI 2.0 FLASH (Sesuai daftar akun Anda)
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';
    \Illuminate\Support\Facades\Log::info('Menghubungi: ' . $url);

    $prompt = "Bertindaklah sebagai penasihat keuangan.
    DATA USER:
    - Pengeluaran: Rp " . number_format($data['total_current']) . "
    - Tren: " . $data['trend_text'] . "
    - Rata-rata Harian: Rp " . number_format($data['avg_daily']) . "
    
    OUTPUT JSON WAJIB (Tanpa Markdown):
    {
        \"analysis\": \"(Analisis singkat 2 kalimat)\",
        \"recommendation\": \"(3 poin saran singkat dipisah baris baru)\"
    }";

    try {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post("{$url}?key={$this->apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

        if ($response->successful()) {
            $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
            
            // Bersihkan markdown json jika ada
            $cleanText = str_replace(['```json', '```', 'JSON'], '', $rawText);
            
            return json_decode($cleanText, true);
        } else {
            // Log Error Detail
            \Illuminate\Support\Facades\Log::error('Gemini 2.0 Error: ' . $response->body());
            return null;
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Koneksi Error: ' . $e->getMessage());
        return null;
    }
}

    // =========================================================================
    // FITUR 2: CHATBOT DENGAN TOOL CALLING (Untuk Tanya Jawab)
    // =========================================================================

    // A. Definisi Alat
    private function getToolsSchema()
    {
        return [
            'function_declarations' => [
                [
                    'name' => 'analyze_financial_health',
                    'description' => 'Gunakan ini jika user minta analisis keuangan/kesehatan/ringkasan.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'period' => ['type' => 'string', 'enum' => ['weekly', 'monthly'], 'description' => 'Periode waktu']
                        ],
                        'required' => ['period']
                    ]
                ],
                [
                    'name' => 'check_category_spending',
                    'description' => 'Gunakan ini jika user tanya pengeluaran kategori tertentu.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'category_name' => ['type' => 'string', 'description' => 'Nama kategori']
                        ],
                        'required' => ['category_name']
                    ]
                ]
            ]
        ];
    }

    // B. Eksekusi Alat (Laravel Logic)
    private function executeTool($functionName, $args)
    {
        $user = Auth::user();
        $now = Carbon::now();

        if ($functionName === 'analyze_financial_health') {
            $period = $args['period'] ?? 'monthly';
            $expenseQuery = Expense::where('user_id', $user->id);
            $incomeQuery = Income::where('user_id', $user->id);

            if ($period === 'weekly') {
                $range = [$now->startOfWeek(), $now->endOfWeek()];
                $expenseQuery->whereBetween('date', $range);
                $incomeQuery->whereBetween('date_received', $range);
                $label = "Minggu Ini";
            } else {
                $expenseQuery->whereMonth('date', $now->month)->whereYear('date', $now->year);
                $incomeQuery->whereMonth('date_received', $now->month)->whereYear('date_received', $now->year);
                $label = "Bulan Ini";
            }

            $totalExp = $expenseQuery->sum('amount');
            $totalInc = $incomeQuery->sum('amount');

            return [
                'periode' => $label,
                'total_expense' => $totalExp,
                'total_income' => $totalInc,
                'status' => $totalExp > $totalInc ? 'DEFISIT (Boros)' : 'SURPLUS (Aman)'
            ];
        }

        if ($functionName === 'check_category_spending') {
            $cat = $args['category_name'] ?? '';
            $total = Expense::where('user_id', $user->id)
                ->whereMonth('date', $now->month)
                ->where('category', 'LIKE', "%{$cat}%")
                ->sum('amount');
            return ['kategori' => $cat, 'total_bulan_ini' => $total];
        }

        return "Alat tidak ditemukan.";
    }

    // C. Chat Orchestrator
    public function chat($userMessage)
    {
        $payload = [
            'contents' => [['role' => 'user', 'parts' => [['text' => $userMessage]]]],
            'tools' => [$this->getToolsSchema()]
        ];

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}?key={$this->apiKey}", $payload);

            $data = $response->json();

            if (!isset($data['candidates'][0]['content']['parts'][0]))
                return "Maaf, error koneksi AI.";

            $candidate = $data['candidates'][0]['content']['parts'][0];

            // Cek Function Call
            if (isset($candidate['functionCall'])) {
                $fName = $candidate['functionCall']['name'];
                $fArgs = $candidate['functionCall']['args'];

                // 1. Jalankan Tool
                $toolResult = $this->executeTool($fName, $fArgs);

                // 2. Kirim Balik ke Gemini
                $history = $payload['contents'];
                $history[] = ['role' => 'model', 'parts' => [['functionCall' => $candidate['functionCall']]]];
                $history[] = ['role' => 'function', 'parts' => [['functionResponse' => ['name' => $fName, 'response' => ['content' => $toolResult]]]]];

                $finalRes = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->post("{$this->baseUrl}?key={$this->apiKey}", ['contents' => $history]);

                return $finalRes->json()['candidates'][0]['content']['parts'][0]['text'];
            }

            return $candidate['text'];
        } catch (\Exception $e) {
            return "Maaf, sistem sedang sibuk.";
        }
    }
}