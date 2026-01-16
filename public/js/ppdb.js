/**
 * PPDB Online - Interaction Layer (No JS-injected CSS)
 */

class PPDBApp {
    constructor() {
        // CSS JS dihapus, gunakan CSS eksternal
        // this.injectGlobalStyles();

        this.normalizeLayout();
        this.enhanceBootstrapUI();
        this.initEventListeners();
        this.updateLiveStats();
    }

    /* =========================
     * LAYOUT NORMALIZATION
     * ========================= */
    normalizeLayout() {
        const container = document.querySelector('.container');
        if (container && !container.classList.contains('main-content')) {
            container.classList.add('main-content');
        }
    }

    /* =========================
     * BOOTSTRAP UI ENHANCEMENT
     * ========================= */
    enhanceBootstrapUI() {
        // Auto wrap tables
        document.querySelectorAll('table').forEach(table => {
            if (!table.parentElement.classList.contains('table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });
    }

    /* =========================
     * FORM & INPUT HANDLING
     * ========================= */
    initEventListeners() {
        document.addEventListener('input', (e) => {
            // NISN formatting
            if (e.target.name === 'nisn') {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 10);
            }

            // Score validation
            if (e.target.classList.contains('subject-score')) {
                let value = parseFloat(e.target.value);
                if (value > 100) e.target.value = 100;
                if (value < 0) e.target.value = 0;
                this.updateScoreComparison(e.target);
            }
        });
    }

    updateScoreComparison(input) {
        const value = parseFloat(input.value) || 0;
        const threshold = 75;

        const parent = input.closest('.mb-3') || input.parentElement;
        let indicator = parent.querySelector('.score-indicator');

        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'score-indicator small';
            parent.appendChild(indicator);
        }

        indicator.innerHTML = value >= threshold
            ? `<span class="text-success fw-semibold">▲ Di atas rata-rata</span>`
            : `<span class="text-danger fw-semibold">▼ Di bawah rata-rata</span>`;
    }

    /* =========================
     * LIVE DATA
     * ========================= */
    updateLiveStats() {
        setInterval(() => this.fetchLiveStats(), 30000);
    }

    async fetchLiveStats() {
        try {
            const res = await fetch('/api/stats');
            if (!res.ok) return;
            const data = await res.json();

            document.querySelectorAll('[data-stat]').forEach(el => {
                const key = el.dataset.stat;
                if (data[key] !== undefined) {
                    el.textContent = data[key];
                }
            });
        } catch (_) {
            // silent fail
        }
    }

    /* =========================
     * VISUAL EFFECTS
     * ========================= */
    showDominoEffect(studentA, studentB, fromMajor, toMajor) {
        const html = `
            <div class="domino-effect-alert alert alert-warning mb-3">
                <strong>Domino Effect</strong><br>
                ${studentA} masuk ${fromMajor}<br>
                ${studentB} tergeser ke ${toMajor}
            </div>
        `;

        const container = document.querySelector('.main-content');
        if (!container) return;

        const div = document.createElement('div');
        div.innerHTML = html;
        container.prepend(div);

        setTimeout(() => div.remove(), 5000);
    }
}

/* =========================
 * BOOTSTRAP INIT
 * ========================= */
document.addEventListener('DOMContentLoaded', () => {
    window.ppdbApp = new PPDBApp();

    if (location.pathname.includes('/ranking')) {
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 60000);
    }
});
