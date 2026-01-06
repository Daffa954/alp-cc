/**
 * resources/js/page-reports.js
 */

const ReportPage = (function () {
    // State Variables
    let state = {
        currentType: 'monthly',
        selectedDate: new Date(),
        viewingDate: new Date(),
        transactionDates: { expenses: [], incomes: [], activities: [] },
        wastefulDates: [],
        routes: {}
    };

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    // --- UTILS ---
    function formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // --- RENDER KALENDER ---
    function renderInteractiveCalendar() {
        const grid = document.getElementById('interactiveCalendar');
        const header = document.getElementById('calMonthYear');
        
        if (!grid || !header) return;

        grid.innerHTML = '';
        header.textContent = `${monthNames[state.viewingDate.getMonth()]} ${state.viewingDate.getFullYear()}`;

        const year = state.viewingDate.getFullYear();
        const month = state.viewingDate.getMonth();

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        let rangeStart, rangeEnd;
        if (state.currentType === 'monthly') {
            rangeStart = new Date(state.selectedDate.getFullYear(), state.selectedDate.getMonth(), 1);
            rangeEnd = new Date(state.selectedDate.getFullYear(), state.selectedDate.getMonth() + 1, 0);
        } else {
            // MINGGUAN: Mundur 7 hari ke belakang
            rangeEnd = new Date(state.selectedDate);
            rangeEnd.setHours(23, 59, 59, 999);
            rangeStart = new Date(state.selectedDate);
            rangeStart.setDate(state.selectedDate.getDate() - 6);
            rangeStart.setHours(0, 0, 0, 0);
        }

        // Padding
        for (let x = firstDay; x > 0; x--) {
            const cell = document.createElement('div');
            cell.className = 'h-10 flex items-center justify-center text-gray-600 cursor-default opacity-50 text-xs';
            cell.innerText = daysInPrevMonth - x + 1;
            grid.appendChild(cell);
        }

        // Hari Aktif
        for (let i = 1; i <= daysInMonth; i++) {
            const dateToCheck = new Date(year, month, i);
            const dateKey = formatDate(dateToCheck);

            const isSelectedRange = dateToCheck >= rangeStart && dateToCheck <= rangeEnd;
            const isExactDate = dateToCheck.toDateString() === state.selectedDate.toDateString();
            const isToday = dateToCheck.toDateString() === new Date().toDateString();

            const btn = document.createElement('button');
            btn.type = 'button';

            let classes = "h-12 w-full rounded-lg text-sm font-medium transition flex flex-col items-center justify-center relative ";
            if (isSelectedRange) {
                classes += "bg-[#ff6b00] text-white shadow-lg shadow-orange-500/30 ";
                if (isExactDate) classes += "border-2 border-white font-bold scale-105 z-10 ";
            } else {
                classes += "text-gray-300 hover:bg-gray-700 ";
                if (isToday) classes += "border border-blue-500 text-blue-400 ";
            }
            btn.className = classes;

            // Dots Logic
            let dotsHtml = '<div class="flex gap-0.5 mt-0.5 h-1.5">';
            if (state.transactionDates.expenses && state.transactionDates.expenses.includes(dateKey)) {
                dotsHtml += '<span class="w-1 h-1 rounded-full bg-red-500"></span>';
            }
            if (state.transactionDates.incomes && state.transactionDates.incomes.includes(dateKey)) {
                dotsHtml += '<span class="w-1 h-1 rounded-full bg-green-500"></span>';
            }
            if (state.transactionDates.activities && state.transactionDates.activities.includes(dateKey)) {
                dotsHtml += '<span class="w-1 h-1 rounded-full bg-blue-400"></span>';
            }
            dotsHtml += '</div>';

            btn.innerHTML = `<span class="leading-none">${i}</span>${dotsHtml}`;

            btn.onclick = () => {
                state.selectedDate = new Date(year, month, i);
                updateUIState();
                renderInteractiveCalendar();
            };

            grid.appendChild(btn);
        }
    }

    // --- UI HELPERS ---
    function updateUIState() {
        const isoDate = formatDate(state.selectedDate);
        document.getElementById('inputDate').value = isoDate;
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('displayDateText').innerText = state.selectedDate.toLocaleDateString('id-ID', options);
    }

    function updateRadioStyles() {
        const lblM = document.getElementById('lblMonthly');
        const lblW = document.getElementById('lblWeekly');
        if(!lblM || !lblW) return; // Guard clause

        const activeClass = "bg-[#ff6b00] text-white shadow-lg";
        const inactiveClass = "text-gray-400 hover:text-white";

        // Reset base class
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
        // Render padding
        for(let i=0; i<first; i++) resGrid.appendChild(document.createElement('div'));
        
        // Render days
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
        const btnNext = document.getElementById('calNext');

        if(btnPrev) {
            btnPrev.onclick = () => {
                state.viewingDate.setMonth(state.viewingDate.getMonth() - 1);
                renderInteractiveCalendar();
            };
        }
        if(btnNext) {
            btnNext.onclick = () => {
                state.viewingDate.setMonth(state.viewingDate.getMonth() + 1);
                renderInteractiveCalendar();
            };
        }

        const radios = document.getElementsByName('type');
        radios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                state.currentType = e.target.value;
                updateRadioStyles();
                renderInteractiveCalendar();
            });
        });

        // Global Helpers
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

// =========================================================
// PENTING: Attach ke Window agar bisa dipanggil di Blade
// =========================================================
window.ReportPage = ReportPage;