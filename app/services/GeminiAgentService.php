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

    // --- PERBAIKAN 1: DEFINISI BASE URL ---
    // Kita gunakan model 'gemini-2.0-flash-exp' (Experimental)
    // Model ini memiliki kuota terpisah dari versi stabil, jadi lebih aman dari error 429
protected $baseUrl =
 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    // =========================================================================
    // FITUR 1: GENERATE REPORT (Untuk Halaman Laporan)
    // =========================================================================
    public function analyzeForReport($data)
    {
        // --- PERBAIKAN 2: GUNAKAN VARIABLE $this->baseUrl ---
        // Jangan hardcode URL lagi, ambil dari property di atas agar konsisten
        $url = $this->baseUrl;
        
        Log::info('Menghubungi Model: ' . $url);
        // Log::info('Data: ' . json_encode($data)); // Opsional: matikan jika log terlalu penuh

        $prompt = "Bertindaklah sebagai penasihat keuangan pribadi yang bijak dan empatik.
    
        PROFIL PENGGUNA:
        - Pekerjaan: " . ($data['user_job'] ?? '-') . "
        - Lokasi: " . ($data['user_city'] ?? '-') . " (Pertimbangkan biaya hidup di sini)
        - Sumber Pemasukan: " . ($data['income_sources'] ?? '-') . "
        
        DATA KEUANGAN PERIODE INI (" . $data['period_name'] . "):
        - Total Pemasukan: Rp " . number_format($data['total_income']) . "
        - Total Pengeluaran: Rp " . number_format($data['total_expense']) . "
        - Sisa Uang (Balance): Rp " . number_format($data['balance']) . "
        - Tren Pengeluaran: " . $data['trend_text'] . " dibanding periode lalu.
        
        TUGAS:
        1. Tentukan STATUS kesehatan keuangan (pilih satu: 'safe', 'warning', 'danger').
           - 'danger': Jika defisit parah, tidak ada pemasukan, atau pengeluaran tidak terkendali.
           - 'warning': Jika surplus tipis, terlalu banyak jajan, atau tren naik drastis.
           - 'safe': Jika cashflow positif dan terkendali.
        2. Berikan Analisis tajam.
        3. Berikan 3 Rekomendasi.
        4. Gunakan bahasa yang mudah dipahami orang awam.

        OUTPUT JSON WAJIB (Hanya JSON murni):
        {
            \"status\": \"safe|warning|danger\",
            \"analysis\": \"(Analisis 2-3 kalimat)\",
            \"recommendation\": \"(3 poin saran dipisah baris baru - )\"
        }";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("{$url}?key={$this->apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            if ($response->successful()) {
                $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];

                // Regex JSON (Lebih aman menangani respon AI yang "cerewet")
                if (preg_match('/\{[\s\S]*\}/', $rawText, $matches)) {
                    return json_decode($matches[0], true);
                }

                // Fallback cleaning
                $cleanText = str_replace(['```json', '```', 'JSON'], '', $rawText);
                return json_decode($cleanText, true);

            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Koneksi Error: ' . $e->getMessage());
            return null;
        }
    }

    // =========================================================================
    // FITUR 2: CHATBOT DENGAN TOOL CALLING
    // =========================================================================

    // A. Definisi Alat
    // private function getToolsSchema()
    // {
    //     return [
    //         'function_declarations' => [
    //             [
    //                 'name' => 'analyze_financial_health',
    //                 'description' => 'Gunakan ini jika user minta analisis keuangan/kesehatan/ringkasan umum.',
    //                 'parameters' => [
    //                     'type' => 'object',
    //                     'properties' => [
    //                         'period' => ['type' => 'string', 'enum' => ['weekly', 'monthly'], 'description' => 'Periode waktu']
    //                     ],
    //                     'required' => ['period']
    //                 ]
    //             ],
    //             [
    //                 'name' => 'check_category_spending',
    //                 'description' => 'Gunakan ini jika user tanya pengeluaran kategori tertentu.',
    //                 'parameters' => [
    //                     'type' => 'object',
    //                     'properties' => [
    //                         'category_name' => ['type' => 'string', 'description' => 'Nama kategori']
    //                     ],
    //                     'required' => ['category_name']
    //                 ]
    //             ],
    //             [
    //                 'name' => 'calculate_balance',
    //                 'description' => 'Gunakan ini jika user bertanya sisa uang, saldo, surplus, atau defisit.',
    //                 'parameters' => [
    //                     'type' => 'object',
    //                     'properties' => [
    //                         'period' => ['type' => 'string', 'enum' => ['weekly', 'monthly'], 'description' => 'Periode waktu']
    //                     ],
    //                     'required' => ['period']
    //                 ]
    //             ]
    //         ]
    //     ];
    // }

    // // B. Eksekusi Alat
    // private function executeTool($functionName, $args)
    // {
    //     $user = Auth::user();
    //     $now = Carbon::now();

    //     if ($functionName === 'analyze_financial_health') {
    //         $period = $args['period'] ?? 'monthly';
    //         $expenseQuery = Expense::where('user_id', $user->id);
    //         $incomeQuery = Income::where('user_id', $user->id);

    //         if ($period === 'weekly') {
    //             $range = [$now->startOfWeek(), $now->endOfWeek()];
    //             $expenseQuery->whereBetween('date', $range);
    //             $incomeQuery->whereBetween('date_received', $range);
    //             $label = "Minggu Ini";
    //         } else {
    //             $expenseQuery->whereMonth('date', $now->month)->whereYear('date', $now->year);
    //             $incomeQuery->whereMonth('date_received', $now->month)->whereYear('date_received', $now->year);
    //             $label = "Bulan Ini";
    //         }

    //         $totalExp = $expenseQuery->sum('amount');
    //         $totalInc = $incomeQuery->sum('amount');

    //         return [
    //             'periode' => $label,
    //             'total_expense' => $totalExp,
    //             'total_income' => $totalInc,
    //             'balance' => $totalInc - $totalExp,
    //             'status' => $totalExp > $totalInc ? 'DEFISIT (Boros)' : 'SURPLUS (Aman)'
    //         ];
    //     }

    //     if ($functionName === 'check_category_spending') {
    //         $cat = $args['category_name'] ?? '';
    //         $total = Expense::where('user_id', $user->id)
    //             ->whereMonth('date', $now->month)
    //             ->where('category', 'LIKE', "%{$cat}%")
    //             ->sum('amount');
    //         return ['kategori' => $cat, 'total_bulan_ini' => $total];
    //     }

    //     if ($functionName === 'calculate_balance') {
    //         $period = $args['period'] ?? 'monthly';
    //         $expenseQuery = Expense::where('user_id', $user->id);
    //         $incomeQuery = Income::where('user_id', $user->id);

    //         if ($period === 'weekly') {
    //             $range = [$now->startOfWeek(), $now->endOfWeek()];
    //             $expenseQuery->whereBetween('date', $range);
    //             $incomeQuery->whereBetween('date_received', $range);
    //             $label = "Minggu Ini";
    //         } else {
    //             $expenseQuery->whereMonth('date', $now->month)->whereYear('date', $now->year);
    //             $incomeQuery->whereMonth('date_received', $now->month)->whereYear('date_received', $now->year);
    //             $label = "Bulan Ini";
    //         }

    //         $totalExp = $expenseQuery->sum('amount');
    //         $totalInc = $incomeQuery->sum('amount');
    //         $balance = $totalInc - $totalExp;

    //         return [
    //             'periode' => $label,
    //             'pemasukan' => "Rp " . number_format($totalInc),
    //             'pengeluaran' => "Rp " . number_format($totalExp),
    //             'sisa_saldo' => "Rp " . number_format($balance),
    //             'pesan' => $balance >= 0 ? "Keuangan Aman" : "Awas Defisit"
    //         ];
    //     }

    //     return "Alat tidak ditemukan.";
    // }

    // // C. Chat Orchestrator
    // public function chat($userMessage)
    // {
    //     $payload = [
    //         'contents' => [['role' => 'user', 'parts' => [['text' => $userMessage]]]],
    //         'tools' => [$this->getToolsSchema()]
    //     ];

    //     try {
    //         // Menggunakan $this->baseUrl yang sudah didefinisikan di atas (gemini-2.0-flash-exp)
    //         $response = Http::withHeaders(['Content-Type' => 'application/json'])
    //             ->post("{$this->baseUrl}?key={$this->apiKey}", $payload);

    //         $data = $response->json();

    //         // Cek Error API
    //         if (isset($data['error'])) {
    //              Log::error("Gemini Chat Error: " . json_encode($data['error']));
    //              return "Maaf, sistem AI sedang sibuk. Silakan coba lagi nanti.";
    //         }

    //         if (!isset($data['candidates'][0]['content']['parts'][0]))
    //             return "Maaf, error koneksi AI.";

    //         $candidate = $data['candidates'][0]['content']['parts'][0];

    //         // Cek Function Call
    //         if (isset($candidate['functionCall'])) {
    //             $fName = $candidate['functionCall']['name'];
    //             $fArgs = $candidate['functionCall']['args'];

    //             $toolResult = $this->executeTool($fName, $fArgs);

    //             $history = $payload['contents'];
    //             $history[] = ['role' => 'model', 'parts' => [['functionCall' => $candidate['functionCall']]]];
    //             $history[] = ['role' => 'function', 'parts' => [['functionResponse' => ['name' => $fName, 'response' => ['content' => $toolResult]]]]];

    //             $finalRes = Http::withHeaders(['Content-Type' => 'application/json'])
    //                 ->post("{$this->baseUrl}?key={$this->apiKey}", ['contents' => $history]);

    //             return $finalRes->json()['candidates'][0]['content']['parts'][0]['text'];
    //         }

    //         return $candidate['text'];
    //     } catch (\Exception $e) {
    //         return "Maaf, sistem sedang sibuk.";
    //     }
    // }
}