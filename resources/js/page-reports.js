/**
 * resources/js/page-reports.js
 * VERSI: ROLLING MONTH (Mundur 1 Bulan ke Belakang)
 */

const ReportPage = (function () {
    let state = {
        currentType: 'monthly',
        selectedDate: new Date(),
        viewingDate: new Date(),
        transactionDates: { expenses: [], incomes: [], activities: [] },
        wastefulDates: [],
        routes: {}
    };

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // Helper: Reset jam ke 00:00:00 murni
    function resetTime(date) {
        const d = new Date(date);
        d.setHours(0, 0, 0, 0);
        return d;
    }

    function renderInteractiveCalendar() {
        const grid = document.getElementById('interactiveCalendar');
        const header = document.getElementById('calMonthYear');
        
        if (!grid || !header) return;

        grid.innerHTML = '';
        header.textContent = `${monthNames[state.viewingDate.getMonth()]} ${state.viewingDate.getFullYear()}`;

        const viewYear = state.viewingDate.getFullYear();
        const viewMonth = state.viewingDate.getMonth();

        const firstDay = new Date(viewYear, viewMonth, 1).getDay();
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const daysInPrevMonth = new Date(viewYear, viewMonth, 0).getDate();

        // --- LOGIKA RANGE BARU (ROLLING BACKWARDS) ---
        let rangeStart, rangeEnd;
        const cleanSelected = resetTime(state.selectedDate);

        // End Date SELALU tanggal yang dipilih user
        rangeEnd = new Date(cleanSelected);
        
        // Start Date dihitung mundur
        rangeStart = new Date(cleanSelected);

        if (state.currentType === 'weekly') {
            // Mingguan: Mundur 6 hari (Total 7 hari)
            rangeStart.setDate(cleanSelected.getDate() - 6);
        } else {
            // Bulanan: Mundur 1 Bulan Penuh (Total ~30 hari)
            // Contoh: Pilih 3 Des -> Blok dari 3 Nov s/d 3 Des
            rangeStart.setMonth(cleanSelected.getMonth() - 1);
        }

        // Normalize jam agar kalkulasi akurat
        rangeEnd = resetTime(rangeEnd);
        rangeStart = resetTime(rangeStart);

        // 1. Padding Bulan Lalu
        for (let x = firstDay; x > 0; x--) {
            const cell = document.createElement('div');
            cell.className = 'h-10 flex items-center justify-center text-gray-600 cursor-default opacity-50 text-xs';
            cell.innerText = daysInPrevMonth - x + 1;
            grid.appendChild(cell);
        }

        // 2. Hari Aktif
        for (let i = 1; i <= daysInMonth; i++) {
            const currentLoopDate = new Date(viewYear, viewMonth, i);
            const cleanCurrent = resetTime(currentLoopDate);
            const dateKey = formatDate(cleanCurrent);

            // LOGIKA HIGHLIGHT: Cek Timestamp range
            const time = cleanCurrent.getTime();
            const isSelectedRange = time >= rangeStart.getTime() && time <= rangeEnd.getTime();

            // Visual Check
            const isExactDate = cleanCurrent.getTime() === cleanSelected.getTime();
            const isToday = cleanCurrent.getTime() === resetTime(new Date()).getTime();

            const btn = document.createElement('button');
            btn.type = 'button';

            let classes = "h-12 w-full rounded-lg text-sm font-medium transition flex flex-col items-center justify-center relative ";
            
            if (isSelectedRange) {
                // Highlight Orange
                classes += "bg-[#ff6b00] text-white shadow-lg shadow-orange-500/30 ";
                if (isExactDate) classes += "border-2 border-white font-bold scale-105 z-10 ";
            } else {
                // Normal
                classes += "text-gray-300 hover:bg-gray-700 ";
                if (isToday) classes += "border border-blue-500 text-blue-400 ";
            }
            btn.className = classes;

            // Dots Logic
            let dotsHtml = '<div class="flex gap-0.5 mt-0.5 h-1.5">';
            if (state.transactionDates.expenses && state.transactionDates.expenses.includes(dateKey)) dotsHtml += '<span class="w-1 h-1 rounded-full bg-red-500"></span>';
            if (state.transactionDates.incomes && state.transactionDates.incomes.includes(dateKey)) dotsHtml += '<span class="w-1 h-1 rounded-full bg-green-500"></span>';
            if (state.transactionDates.activities && state.transactionDates.activities.includes(dateKey)) dotsHtml += '<span class="w-1 h-1 rounded-full bg-blue-400"></span>';
            dotsHtml += '</div>';

            btn.innerHTML = `<span class="leading-none">${i}</span>${dotsHtml}`;

            btn.onclick = () => {
                state.selectedDate = new Date(viewYear, viewMonth, i);
                updateUIState();
                renderInteractiveCalendar();
            };

            grid.appendChild(btn);
        }
    }

    function updateUIState() {
        const isoDate = formatDate(state.selectedDate);
        document.getElementById('inputDate').value = isoDate;
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('displayDateText').innerText = state.selectedDate.toLocaleDateString('id-ID', options);
    }

    function updateRadioStyles() {
        const lblM = document.getElementById('lblMonthly');
        const lblW = document.getElementById('lblWeekly');
        if(!lblM || !lblW) return;

        const activeClass = "bg-[#ff6b00] text-white shadow-lg";
        const inactiveClass = "text-gray-400 hover:text-white";
        const baseClass = "flex-1 text-center cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition ";

        if (state.currentType === 'monthly') {
            lblM.className = baseClass + activeClass;
            lblW.className = baseClass + inactiveClass;
        } else {
            lblW.className = baseClass + activeClass;
            lblM.className = baseClass + inactiveClass;
        }
    }

    function renderResultCalendar() {
        const resGrid = document.getElementById('calendarGridResult');
        if (!resGrid) return; 

        const reportDateStr = document.getElementById('inputDate').value; 
        const reportDate = new Date(reportDateStr);
        const y = reportDate.getFullYear();
        const m = reportDate.getMonth();
        const first = new Date(y, m, 1).getDay();
        const days = new Date(y, m + 1, 0).getDate();

        resGrid.innerHTML = '';
        for(let i=0; i<first; i++) resGrid.appendChild(document.createElement('div'));
        
        for(let i=1; i<=days; i++) {
            const dateKey = `${y}-${String(m+1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;
            const cell = document.createElement('div');
            let bg = "text-gray-500";
            let dot = "";
            
            if (state.wastefulDates.includes(dateKey)) {
                dot = `<span class="absolute bottom-1 w-1.5 h-1.5 bg-red-500 rounded-full"></span>`;
                bg = "text-white font-bold bg-gray-700";
            } else if (state.transactionDates.expenses && state.transactionDates.expenses.includes(dateKey)) {
                dot = `<span class="absolute bottom-1 w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>`;
                bg = "text-gray-300";
            }

            cell.className = `h-8 w-full rounded flex items-center justify-center relative text-[10px] ${bg}`;
            cell.innerHTML = `<span>${i}</span>${dot}`;
            resGrid.appendChild(cell);
        }
    }

    function init(config) {
        state.currentType = config.type;
        state.selectedDate = new Date(config.date);
        state.viewingDate = new Date(config.date);
        state.transactionDates = config.dates;
        state.wastefulDates = config.wastefulDates || [];
        state.routes = config.routes || {};

        const btnPrev = document.getElementById('calPrev');
        if(btnPrev) btnPrev.onclick = () => {
            state.viewingDate.setMonth(state.viewingDate.getMonth() - 1);
            renderInteractiveCalendar();
        };

        const btnNext = document.getElementById('calNext');
        if(btnNext) btnNext.onclick = () => {
            state.viewingDate.setMonth(state.viewingDate.getMonth() + 1);
            renderInteractiveCalendar();
        };

        const radios = document.getElementsByName('type');
        radios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                state.currentType = e.target.value;
                updateRadioStyles();
                renderInteractiveCalendar();
            });
        });

        window.showLoading = function(form) {
            document.getElementById('btn-text').classList.add('hidden');
            document.getElementById('btn-loader').classList.remove('hidden');
            document.getElementById('btn-loader').classList.add('flex');
            form.querySelector('button[type="submit"]').disabled = true;
        }

        window.submitFilter = function() {
            const form = document.getElementById('mainForm');
            if(form) {
                form.method = 'GET';
                form.action = state.routes.index;
                form.submit();
            }
        }

        window.goToHistory = function() {
            if(state.routes.history) window.location.href = state.routes.history;
        }

        updateRadioStyles();
        renderInteractiveCalendar();
        renderResultCalendar();
    }

    return { init };
})();

window.ReportPage = ReportPage;