/**
 * ============================================
 * HELP SYSTEM ENGINE - Koperasi Application
 * Interactive help, guided tours & documentation
 * ============================================
 */
(function () {
    'use strict';

    // URL → HelpContent key mapping
    // Setiap halaman create/update/detail punya konten bantuan sendiri
    var routeMap = {
        'panel/dashboard':                          'dashboard',
        'panel':                                    'dashboard',
        // Master Data - Pengguna
        'panel/masterData/daftarPengguna':           'daftarPengguna',
        'panel/masterData/tambahPengguna':           'tambahPengguna',
        'panel/masterData/updatePengguna':           'updatePengguna',
        // Master Data - Instansi
        'panel/masterData/daftarInstansi':            'daftarInstansi',
        'panel/masterData/tambahInstansi':            'tambahInstansi',
        'panel/masterData/updateInstansi':            'updateInstansi',
        // Master Data - Hak Akses
        'panel/masterData/daftarHakAkses':            'daftarHakAkses',
        'panel/masterData/tambahHakAkses':            'tambahHakAkses',
        'panel/masterData/updateHakAkses':            'updateHakAkses',
        // Anggota
        'panel/anggota/daftarAnggota':               'daftarAnggota',
        'panel/anggota/tambahAnggota':               'tambahAnggota',
        'panel/anggota/updateAnggota':               'updateAnggota',
        'panel/anggota/detailAnggota':               'detailAnggota',
        // Simpanan
        'panel/simpanan/daftarSimpanan':              'daftarSimpanan',
        'panel/simpanan/transaksiSimpanan':           'transaksiSimpanan',
        'panel/simpanan/approvalSimpanan':            'approvalSimpanan',
        'panel/simpanan/jenisSimpanan':               'jenisSimpanan',
        'panel/simpanan/tambahJenisSimpanan':         'tambahJenisSimpanan',
        'panel/simpanan/updateJenisSimpanan':         'updateJenisSimpanan',
        // Pinjaman
        'panel/pinjaman/daftarPinjaman':              'daftarPinjaman',
        'panel/pinjaman/pengajuanPinjaman':           'pengajuanPinjaman',
        'panel/pinjaman/approvalPinjaman':            'approvalPinjaman',
        'panel/pinjaman/persetujuanPinjaman':         'persetujuanPinjaman',
        'panel/pinjaman/pencairanPinjaman':           'pencairanPinjaman',
        'panel/pinjaman/angsuranPinjaman':            'angsuranPinjaman',
        'panel/pinjaman/bayarAngsuran':               'bayarAngsuran',
        'panel/pinjaman/jenisPinjaman':               'jenisPinjaman',
        'panel/pinjaman/tambahJenisPinjaman':         'tambahJenisPinjaman',
        'panel/pinjaman/updateJenisPinjaman':         'updateJenisPinjaman',
        // Kas
        'panel/kas/kasKoperasi':                     'kasKoperasi',
        'panel/kas/transaksiKas':                    'transaksiKas',
        'panel/kas/mutasiKas':                       'mutasiKas',
        // Laporan
        'panel/laporan/laporanSimpanan':              'laporanSimpanan',
        'panel/laporan/laporanPinjaman':              'laporanPinjaman',
        'panel/laporan/laporanKas':                   'laporanKas',
        'panel/laporan/laporanAnggota':               'laporanAnggota',
        'panel/laporan/laporanKeuangan':              'laporanKeuangan',
        // SHU
        'panel/shu/dataSHU':                         'dataSHU',
        'panel/shu/perhitunganSHU':                  'perhitunganSHU',
        'panel/shu/hitungSHU':                       'hitungSHU',
        'panel/shu/distribusiSHU':                   'distribusiSHU',
        'panel/shu/bayarSHU':                        'bayarSHU',
        'panel/shu/laporanSHU':                      'laporanSHU',
        // Notifikasi
        'panel/notifikasi/daftarNotifikasi':          'daftarNotifikasi',
        'panel/notifikasi/dataNotifikasi':            'daftarNotifikasi',
        'panel/notifikasi/kirimNotifikasi':           'kirimNotifikasi',
        'panel/notifikasi/pengaturanNotifikasi':      'pengaturanNotifikasi',
        // Pengaturan
        'panel/pengaturan/pengaturanSistem':          'pengaturanSistem',
        'panel/pengaturan/logAktivitas':              'logAktivitas',
        // Profile
    };

    function detectPage() {
        var path = window.location.pathname.replace(/^\//, '').replace(/\/$/, '');
        // Exact match
        if (routeMap[path]) {
            console.log('[HelpSystem] Page detected (exact):', path, '→', routeMap[path]);
            return routeMap[path];
        }
        // Progressive fallback: strip trailing segments (for URLs with params like /update/5)
        var parts = path.split('/');
        for (var i = parts.length; i >= 2; i--) {
            var tryPath = parts.slice(0, i).join('/');
            if (routeMap[tryPath]) {
                console.log('[HelpSystem] Page detected (fallback):', path, '→', routeMap[tryPath]);
                return routeMap[tryPath];
            }
        }
        console.log('[HelpSystem] No page match for:', path);
        return null;
    }

    /* ============================
       HelpSystem Constructor
       ============================ */
    function HelpSystem() {
        this.isOpen = false;
        this.isTourActive = false;
        this.tourSteps = [];
        this.tourIndex = 0;
        this.currentPage = detectPage();
        this.build();
        this.bind();
    }

    /* ---- Build all DOM elements ---- */
    HelpSystem.prototype.build = function () {
        // FAB
        this.fab = el('button', { className: 'help-fab', id: 'helpFab', title: 'Bantuan' });
        this.fab.innerHTML = '<i class="fas fa-question"></i>';
        document.body.appendChild(this.fab);

        // Overlay
        this.overlay = el('div', { className: 'help-drawer-overlay', id: 'helpOverlay' });
        document.body.appendChild(this.overlay);

        // Drawer
        this.drawer = el('div', { className: 'help-drawer', id: 'helpDrawer' });
        this.drawer.innerHTML =
            '<div class="help-drawer-header">' +
                '<div class="help-drawer-title"><i class="fas fa-life-ring"></i> Panduan Bantuan</div>' +
                '<button class="help-drawer-close" id="helpClose">&times;</button>' +
            '</div>' +
            '<div class="help-drawer-body" id="helpBody">' +
                '<div class="help-search-box">' +
                    '<i class="fas fa-search help-search-icon"></i>' +
                    '<input type="text" class="help-search-input" id="helpSearch" placeholder="Cari bantuan...">' +
                '</div>' +
                '<div id="helpContent"></div>' +
            '</div>';
        document.body.appendChild(this.drawer);

        // Tour highlight
        this.tourHighlight = el('div', { className: 'help-tour-highlight', id: 'tourHighlight' });
        document.body.appendChild(this.tourHighlight);

        // Tour tooltip
        this.tourTooltip = el('div', { className: 'help-tour-tooltip', id: 'tourTooltip' });
        this.tourTooltip.innerHTML =
            '<div class="tour-step-badge" id="tourBadge"></div>' +
            '<div class="tour-title" id="tourTitle"></div>' +
            '<div class="tour-desc" id="tourDesc"></div>' +
            '<div class="tour-nav">' +
                '<button class="tour-btn tour-btn-skip" id="tourSkip">Lewati</button>' +
                '<div>' +
                    '<button class="tour-btn tour-btn-prev" id="tourPrev"><i class="fas fa-chevron-left"></i> Sebelumnya</button>' +
                    '<button class="tour-btn tour-btn-next" id="tourNext">Selanjutnya <i class="fas fa-chevron-right"></i></button>' +
                '</div>' +
            '</div>';
        document.body.appendChild(this.tourTooltip);
    };

    /* ---- Bind events ---- */
    HelpSystem.prototype.bind = function () {
        var self = this;
        this.fab.onclick = function () { self.toggle(); };
        this.overlay.onclick = function () { self.close(); };
        q('#helpClose').onclick = function () { self.close(); };
        q('#tourSkip').onclick = function () { self.endTour(); };
        q('#tourPrev').onclick = function () { self.prevStep(); };
        q('#tourNext').onclick = function () { self.nextStep(); };
        q('#helpSearch').oninput = function () { self.search(this.value); };
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (self.isTourActive) self.endTour();
                else if (self.isOpen) self.close();
            }
        });
    };

    /* ---- Open / Close / Toggle ---- */
    HelpSystem.prototype.toggle = function () {
        this.isOpen ? this.close() : this.open();
    };
    HelpSystem.prototype.open = function () {
        this.render();
        this.drawer.classList.add('open');
        this.overlay.classList.add('open');
        this.fab.style.display = 'none';
        this.isOpen = true;
    };
    HelpSystem.prototype.close = function () {
        this.drawer.classList.remove('open');
        this.overlay.classList.remove('open');
        this.fab.style.display = '';
        this.isOpen = false;
    };

    /* ---- Render drawer content ---- */
    HelpSystem.prototype.render = function () {
        var box = q('#helpContent');
        var data = this.getData();
        var html = '';

        if (data) {
            // Page header
            html += '<div class="help-page-info">' +
                '<div class="help-page-title"><i class="' + (data.icon || 'fas fa-file') + '"></i> ' + data.title + '</div>' +
                '<div class="help-page-desc">' + data.description + '</div>' +
            '</div>';

            // Tour button
            if (data.tour && data.tour.length) {
                html += '<div class="help-quick-actions">' +
                    '<div class="quick-action-item" id="startTourBtn">' +
                        '<i class="fas fa-route"></i>' +
                        '<span>Mulai Tur Panduan</span>' +
                    '</div>' +
                '</div>';
            }

            // Sections
            if (data.sections) {
                for (var i = 0; i < data.sections.length; i++) {
                    html += buildSection(data.sections[i], i);
                }
            }
        } else {
            html += '<div class="help-page-info">' +
                '<div class="help-page-title"><i class="fas fa-info-circle"></i> Panduan Umum</div>' +
                '<div class="help-page-desc">Tidak ada panduan spesifik untuk halaman ini. Silakan lihat panduan umum di bawah ini.</div>' +
            '</div>';
        }

        // Datatables help for pages with tables
        var dtContent = window.HelpContent ? window.HelpContent['_datatables'] : null;
        if (dtContent && document.getElementById('table')) {
            for (var j = 0; j < dtContent.sections.length; j++) {
                html += buildSection(dtContent.sections[j], 900 + j);
            }
        }

        box.innerHTML = html;
        this.bindSections();

        // Tour button event
        var tourBtn = q('#startTourBtn');
        var self = this;
        if (tourBtn) {
            tourBtn.onclick = function () { self.close(); self.startTour(); };
        }
    };

    HelpSystem.prototype.getData = function () {
        if (!window.HelpContent || !this.currentPage) return null;
        return window.HelpContent[this.currentPage] || null;
    };

    /* ---- Build a section HTML ---- */
    function buildSection(sec, idx) {
        var id = 'hs_' + idx;
        var h = '<div class="help-section" data-searchtext="' + stripHtml(sec.title || '').toLowerCase() + ' ' + stripHtml(sec.content || '').toLowerCase() + '">';
        h += '<div class="help-section-title" data-target="' + id + '">' +
                '<span><i class="' + (sec.icon || 'fas fa-info-circle') + '"></i> ' + sec.title + '</span>' +
                '<i class="fas fa-chevron-down help-section-chevron"></i>' +
             '</div>';
        h += '<div class="help-section-body" id="' + id + '">';

        // Steps
        if (sec.steps && sec.steps.length) {
            h += '<div class="help-steps">';
            for (var i = 0; i < sec.steps.length; i++) {
                h += '<div class="help-step">' +
                        '<div class="step-number">' + (i + 1) + '</div>' +
                        '<div class="step-text">' + sec.steps[i] + '</div>' +
                     '</div>';
            }
            h += '</div>';
        }

        // Rich content
        if (sec.content) {
            h += sec.content;
        }

        h += '</div></div>';
        return h;
    }

    /* ---- Collapsible sections ---- */
    HelpSystem.prototype.bindSections = function () {
        var titles = this.drawer.querySelectorAll('.help-section-title');
        for (var i = 0; i < titles.length; i++) {
            titles[i].addEventListener('click', function () {
                var targetId = this.getAttribute('data-target');
                var body = document.getElementById(targetId);
                if (!body) return;
                var wasOpen = body.classList.contains('open');

                // Close all
                var allBodies = document.querySelectorAll('.help-section-body');
                var allTitles = document.querySelectorAll('.help-section-title');
                for (var k = 0; k < allBodies.length; k++) allBodies[k].classList.remove('open');
                for (var k = 0; k < allTitles.length; k++) allTitles[k].classList.remove('active');

                if (!wasOpen) {
                    body.classList.add('open');
                    this.classList.add('active');
                }
            });
        }

        // Auto-open first section
        var first = this.drawer.querySelector('.help-section-body');
        var firstT = this.drawer.querySelector('.help-section-title');
        if (first) first.classList.add('open');
        if (firstT) firstT.classList.add('active');
    };

    /* ---- Search ---- */
    HelpSystem.prototype.search = function (query) {
        var q = query.toLowerCase().trim();
        var secs = this.drawer.querySelectorAll('.help-section');
        for (var i = 0; i < secs.length; i++) {
            var txt = secs[i].textContent.toLowerCase();
            secs[i].style.display = (!q || txt.indexOf(q) >= 0) ? '' : 'none';
        }
    };

    /* ==========================
       GUIDED TOUR
       ========================== */
    HelpSystem.prototype.startTour = function () {
        var data = this.getData();
        if (!data || !data.tour || !data.tour.length) {
            console.log('[HelpSystem] No tour data for page:', this.currentPage);
            return;
        }
        console.log('[HelpSystem] Starting tour:', data.title, '| Steps:', data.tour.length, '| Page key:', this.currentPage);
        this.tourSteps = data.tour;
        this.tourIndex = 0;
        this.isTourActive = true;
        document.body.classList.add('help-tour-active');
        this.showStep();
    };

    HelpSystem.prototype.endTour = function () {
        this.isTourActive = false;
        document.body.classList.remove('help-tour-active');
        this.tourHighlight.style.display = 'none';
        this.tourTooltip.style.display = 'none';
        var targets = document.querySelectorAll('.help-tour-target');
        for (var i = 0; i < targets.length; i++) targets[i].classList.remove('help-tour-target');
    };

    HelpSystem.prototype.nextStep = function () {
        if (this.tourIndex >= this.tourSteps.length - 1) { this.endTour(); return; }
        this.tourIndex++;
        this.showStep();
    };

    HelpSystem.prototype.prevStep = function () {
        if (this.tourIndex <= 0) return;
        this.tourIndex--;
        this.showStep();
    };

    HelpSystem.prototype.showStep = function () {
        var step = this.tourSteps[this.tourIndex];
        if (!step) { this.endTour(); return; }

        var target = document.querySelector(step.selector);
        var total = this.tourSteps.length;
        var cur = this.tourIndex;

        // Badge
        q('#tourBadge').textContent = 'Langkah ' + (cur + 1) + ' dari ' + total;
        q('#tourTitle').textContent = step.title;
        q('#tourDesc').textContent = step.desc;

        // Button states
        q('#tourPrev').style.display = cur === 0 ? 'none' : '';
        var nextBtn = q('#tourNext');
        if (cur === total - 1) {
            nextBtn.innerHTML = '<i class="fas fa-check"></i> Selesai';
            nextBtn.style.background = 'linear-gradient(135deg, #50cd89, #2bb673)';
        } else {
            nextBtn.innerHTML = 'Selanjutnya <i class="fas fa-chevron-right"></i>';
            nextBtn.style.background = '';
        }

        // Clear old targets
        var oldTargets = document.querySelectorAll('.help-tour-target');
        for (var i = 0; i < oldTargets.length; i++) oldTargets[i].classList.remove('help-tour-target');

        if (target) {
            target.classList.add('help-tour-target');
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });

            var self = this;
            setTimeout(function () {
                var rect = target.getBoundingClientRect();
                var pad = 10;

                // Position highlight
                self.tourHighlight.style.display = 'block';
                self.tourHighlight.style.top = (rect.top + window.scrollY - pad) + 'px';
                self.tourHighlight.style.left = (rect.left + window.scrollX - pad) + 'px';
                self.tourHighlight.style.width = (rect.width + pad * 2) + 'px';
                self.tourHighlight.style.height = (rect.height + pad * 2) + 'px';

                // Position tooltip
                self.tourTooltip.style.display = 'block';
                self.positionTooltip(rect);
            }, 400);
        } else {
            // Element not found — center tooltip
            this.tourHighlight.style.display = 'none';
            this.tourTooltip.style.display = 'block';
            this.tourTooltip.style.top = '50%';
            this.tourTooltip.style.left = '50%';
            this.tourTooltip.style.transform = 'translate(-50%, -50%)';
            this.tourTooltip.className = 'help-tour-tooltip';
        }
    };

    HelpSystem.prototype.positionTooltip = function (rect) {
        var tt = this.tourTooltip;
        var winH = window.innerHeight;
        var winW = window.innerWidth;
        var gap = 18;

        // Reset
        tt.style.transform = '';
        tt.className = 'help-tour-tooltip';

        // Measure tooltip
        tt.style.visibility = 'hidden';
        tt.style.display = 'block';
        var ttW = tt.offsetWidth;
        var ttH = tt.offsetHeight;
        tt.style.visibility = '';

        // Prefer bottom
        if (rect.bottom + gap + ttH < winH) {
            tt.classList.add('arrow-top');
            tt.style.top = (rect.bottom + window.scrollY + gap) + 'px';
            tt.style.left = clamp(rect.left + window.scrollX + rect.width / 2 - ttW / 2, 12, winW - ttW - 12) + 'px';
        }
        // Try top
        else if (rect.top - gap - ttH > 0) {
            tt.classList.add('arrow-bottom');
            tt.style.top = (rect.top + window.scrollY - gap - ttH) + 'px';
            tt.style.left = clamp(rect.left + window.scrollX + rect.width / 2 - ttW / 2, 12, winW - ttW - 12) + 'px';
        }
        // Fallback center
        else {
            tt.style.top = '50%';
            tt.style.left = '50%';
            tt.style.transform = 'translate(-50%, -50%)';
        }
    };

    /* ---- Helpers ---- */
    function el(tag, attrs) {
        var e = document.createElement(tag);
        if (attrs) { for (var k in attrs) e[k] = attrs[k]; }
        return e;
    }
    function q(sel) { return document.querySelector(sel); }
    function clamp(v, min, max) { return Math.max(min, Math.min(v, max)); }
    function stripHtml(s) { return (s || '').replace(/<[^>]*>/g, ''); }

    /* ---- Boot ---- */
    function boot() {
        if (window.location.pathname.indexOf('panel') < 0 &&
            window.location.pathname.indexOf('/dashboard') < 0) return;
        window.helpSystem = new HelpSystem();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
