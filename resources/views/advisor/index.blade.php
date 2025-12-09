<x-app-layout>
    <x-slot name="header-title">AI Advisor</x-slot>
    <x-slot name="header-subtitle">Tanya jawab tentang keuanganmu</x-slot>

    <div class="max-w-4xl mx-auto py-6 h-[calc(100vh-140px)] flex flex-col">
        
        <div class="flex-1 bg-gray-800 rounded-t-2xl border border-gray-700 p-4 overflow-y-auto custom-scrollbar relative" id="chatContainer">
            
            <div class="flex items-start mb-4">
                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center mr-3">
                    <i class="fas fa-robot text-[#ff6b00]"></i>
                </div>
                <div class="bg-gray-700 text-gray-200 px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] text-sm">
                    Halo {{ Auth::user()->name }}! Saya asisten keuangan pribadimu. <br>
                    Kamu bisa tanya seperti:
                    <ul class="list-disc ml-4 mt-2 text-gray-400">
                        <li>"Analisis keuangan saya bulan ini"</li>
                        <li>"Berapa pengeluaran saya buat makan?"</li>
                        <li>"Apakah saya boros di transportasi?"</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="bg-gray-900 p-4 border-t border-gray-700 rounded-b-2xl">
            <form id="chatForm" class="flex gap-3">
                @csrf
                <input type="text" id="userInput" 
                    class="flex-1 bg-gray-800 text-white border border-gray-600 rounded-xl px-4 py-3 focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] placeholder-gray-500"
                    placeholder="Tanya sesuatu tentang keuanganmu..." autocomplete="off">
                
                <button type="submit" id="sendBtn" class="bg-gradient-to-r from-[#ff6b00] to-[#ff8c42] text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition flex items-center disabled:opacity-50">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const chatContainer = document.getElementById('chatContainer');
        const chatForm = document.getElementById('chatForm');
        const userInput = document.getElementById('userInput');
        const sendBtn = document.getElementById('sendBtn');

        function appendMessage(text, isUser) {
            const div = document.createElement('div');
            div.className = isUser ? 'flex items-end justify-end mb-4' : 'flex items-start mb-4';
            
            const avatar = isUser ? '' : `
                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center mr-3 shrink-0">
                    <i class="fas fa-robot text-[#ff6b00]"></i>
                </div>`;

            const bubbleClass = isUser 
                ? 'bg-[#ff6b00] text-white rounded-2xl rounded-tr-none' 
                : 'bg-gray-700 text-gray-200 rounded-2xl rounded-tl-none';

            div.innerHTML = `
                ${avatar}
                <div class="${bubbleClass} px-4 py-3 max-w-[80%] text-sm leading-relaxed shadow-sm">
                    ${text}
                </div>
            `;
            
            chatContainer.appendChild(div);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = userInput.value.trim();
            if (!message) return;

            // 1. Tampilkan pesan user
            appendMessage(message, true);
            userInput.value = '';
            userInput.disabled = true;
            sendBtn.disabled = true;

            // 2. Tampilkan Loading Bubble
            const loadingId = 'loading-' + Date.now();
            const loadingHtml = `
                <div id="${loadingId}" class="flex items-start mb-4">
                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center mr-3 shrink-0">
                        <i class="fas fa-robot text-[#ff6b00]"></i>
                    </div>
                    <div class="bg-gray-700 text-gray-400 px-4 py-3 rounded-2xl rounded-tl-none text-xs italic animate-pulse">
                        Sedang memeriksa data...
                    </div>
                </div>`;
            chatContainer.insertAdjacentHTML('beforeend', loadingHtml);
            chatContainer.scrollTop = chatContainer.scrollHeight;

            try {
                // 3. Kirim ke Server
                const response = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                
                // 4. Hapus loading & Tampilkan balasan
                document.getElementById(loadingId).remove();
                
                if (data.status === 'success') {
                    appendMessage(data.reply, false);
                } else {
                    appendMessage("Maaf, terjadi kesalahan pada server.", false);
                }

            } catch (error) {
                document.getElementById(loadingId).remove();
                appendMessage("Gagal terhubung. Cek koneksi internet.", false);
            } finally {
                userInput.disabled = false;
                sendBtn.disabled = false;
                userInput.focus();
            }
        });
    </script>
    @endpush
</x-app-layout>