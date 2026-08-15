/* ================================================================
   APP.JS – Frontend Client untuk Laporan Keuangan PHP + MySQL
   ================================================================ */

'use strict';

// ---------------------------------------------------------------
// STATE & UTILS
// ---------------------------------------------------------------

let currentLaporanId = null;
let isDirty = false;
let autoSaveTimer = null;
let expCounter = 0;

/** Format angka ke string lokal id-ID: 1000000 -> "1.000.000" */
function formatNum(num) {
  const n = Math.round(Number(num) || 0);
  return n.toLocaleString('id-ID');
}

/** Format dengan prefix Rp */
function fmt(num) {
  return 'Rp\u00A0' + formatNum(num);
}

/** Parse string angka/rupiah ke integer */
function parseVal(str) {
  if (!str && str !== 0) return 0;
  const cleaned = String(str).replace(/[^\d]/g, '');
  return parseInt(cleaned, 10) || 0;
}

/** Format input mata uang saat ketik dengan mempertahankan kursor */
function fmtCur(input) {
  const raw = String(input.value).replace(/[^\d]/g, '');
  if (!raw) { input.value = ''; calcAll(); return; }
  const num = parseInt(raw, 10) || 0;

  const oldLen = input.value.length;
  const selEnd = input.selectionEnd;
  const charsFromRight = oldLen - selEnd;

  input.value = formatNum(num);

  const newLen = input.value.length;
  const newPos = Math.max(0, newLen - charsFromRight);
  try { input.setSelectionRange(newPos, newPos); } catch (_) {}

  calcAll();
}

/** Escape string HTML untuk keamanan XSS */
function escHtml(str) {
  return String(str || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

/** Tampilkan Toast Notifikasi */
function showToast(msg, bgClass = 'text-bg-dark') {
  const toastEl = document.getElementById('appToast');
  const msgEl = document.getElementById('toastMsg');
  toastEl.className = `toast align-items-center ${bgClass}`;
  msgEl.textContent = msg;
  const bsToast = new bootstrap.Toast(toastEl);
  bsToast.show();
}

/** Tandai perubahan dan jalankan debounce auto-save */
function markDirty() {
  isDirty = true;
  const statusEl = document.getElementById('statusSimpan');
  statusEl.className = 'badge bg-warning text-dark';
  statusEl.textContent = 'Ada perubahan (belum disimpan)';

  calcAll();

  if (autoSaveTimer) clearTimeout(autoSaveTimer);
  autoSaveTimer = setTimeout(() => {
    saveLaporan(true); // silent auto save
  }, 1200);
}

// ---------------------------------------------------------------
// KALKULASI DOM
// ---------------------------------------------------------------

function calcAll() {
  calcApotek();
  calcPendaftaran();
}

function checkFridayCondition(tanggalStr) {
  if (!tanggalStr) return false;
  try {
    const d = new Date(tanggalStr + 'T00:00:00');
    const isFriday = d.getDay() === 5; // 5 = Jum'at
    const container = document.getElementById('containerJmDrAli');
    const badgeNotice = document.getElementById('badgeJumatNotice');

    if (container) {
      if (isFriday) {
        container.classList.remove('d-none');
        if (badgeNotice) {
          badgeNotice.className = 'badge bg-success';
          badgeNotice.textContent = '🟢 Hari Jum\'at (Aktif)';
        }
      } else {
        container.classList.add('d-none');
        if (badgeNotice) {
          badgeNotice.className = 'badge bg-secondary opacity-75';
          badgeNotice.textContent = 'JM dr. Ali hanya di Hari Jum\'at';
        }
      }
    }
    return isFriday;
  } catch(e) {
    return false;
  }
}

function calcApotek() {
  const kasAwal           = parseVal(document.getElementById('a-kasAwal').value);
  const resep             = parseVal(document.getElementById('a-resep').value);
  const bebas             = parseVal(document.getElementById('a-bebas').value);
  const jmDrZainuddin     = parseVal(document.getElementById('a-jmDrZainuddin').value);
  const jmDrAliProgram    = parseVal(document.getElementById('a-jmDrAliProgram').value);
  const jmDrAliNonProgram = parseVal(document.getElementById('a-jmDrAliNonProgram').value);
  const transfer          = parseVal(document.getElementById('a-transfer').value);

  const totalPenjualan = resep + bebas;
  const cash = Math.max(0, totalPenjualan - transfer);

  document.getElementById('a-cash').value = cash > 0 ? formatNum(cash) : '';
  document.getElementById('a-totalPenjualan').textContent = fmt(totalPenjualan);
  document.getElementById('a-expTransfer').textContent = fmt(transfer);

  // Live preview JM Bersih: tampilkan/sembunyikan sesuai nilai JM Ali Program
  const jmDrZainuddinBersih = Math.max(0, jmDrZainuddin - jmDrAliProgram);
  const bersihWrapper = document.getElementById('a-jmZainuddinBersihWrapper');
  const bersihEl      = document.getElementById('a-jmZainuddinBersih');
  if (bersihWrapper && bersihEl) {
    if (jmDrAliProgram > 0) {
      bersihWrapper.classList.remove('d-none');
      bersihEl.textContent = fmt(jmDrZainuddinBersih);
      // Tandai merah jika JM Ali Program > JM Zainuddin (hasil negatif)
      bersihEl.style.color = jmDrZainuddinBersih <= 0 ? '#dc3545' : '';
    } else {
      bersihWrapper.classList.add('d-none');
    }
  }

  let totalExpRandom = 0;
  document.querySelectorAll('#a-expList .exp-nominal').forEach(inp => {
    totalExpRandom += parseVal(inp.value);
  });

  const totalExp = transfer + totalExpRandom;
  // JM dr. Ali Non-Program menambah saldo apotek
  const saldo = kasAwal + cash + jmDrAliNonProgram - totalExpRandom;

  document.getElementById('a-totalExp').textContent = fmt(totalExp);
  const saldoEl = document.getElementById('a-saldo');
  saldoEl.textContent = fmt(saldo);
  saldoEl.style.color = saldo < 0 ? '#ff4d4d' : '#ffffff';

  const count = document.querySelectorAll('#a-expList .exp-item').length;
  document.getElementById('a-expCount').textContent = count + ' item';
}

function calcPendaftaran() {
  const kasAwal      = parseVal(document.getElementById('p-kasAwal').value);
  const fisio120Count= parseInt(document.getElementById('p-fisio120').value, 10) || 0;
  const fisio90Count = parseInt(document.getElementById('p-fisio90').value, 10)  || 0;
  const adminPBCount = parseInt(document.getElementById('p-adminPB').value, 10)   || 0;
  const adminGM      = parseVal(document.getElementById('p-adminGM').value);
  const parkir       = parseVal(document.getElementById('p-parkir').value);

  const fisio120 = fisio120Count * 120000;
  const fisio90  = fisio90Count * 90000;
  const adminPB  = adminPBCount * 15000;

  let totalLab = 0;
  document.querySelectorAll('#p-labList .lab-nominal').forEach(inp => {
    totalLab += parseVal(inp.value);
  });

  const subtotalFisio = fisio120 + fisio90;
  const totalPemasukan = subtotalFisio + totalLab + adminGM + adminPB + parkir;

  let totalExpRandom = 0;
  document.querySelectorAll('#p-expList .exp-nominal').forEach(inp => {
    totalExpRandom += parseVal(inp.value);
  });

  const saldo = kasAwal + totalPemasukan - totalExpRandom;

  document.getElementById('p-fisio120-total').textContent   = 'Total: ' + fmt(fisio120);
  document.getElementById('p-fisio90-total').textContent    = 'Total: ' + fmt(fisio90);
  document.getElementById('p-subtotalFisio').textContent    = fmt(subtotalFisio);
  document.getElementById('p-adminPB-total').textContent    = 'Total: ' + fmt(adminPB);
  document.getElementById('p-totalLab').textContent         = fmt(totalLab);
  document.getElementById('p-totalPemasukan').textContent   = fmt(totalPemasukan);
  document.getElementById('p-totalExp').textContent         = fmt(totalExpRandom);

  const saldoEl = document.getElementById('p-saldo');
  saldoEl.textContent = fmt(saldo);
  saldoEl.style.color = saldo < 0 ? '#ff4d4d' : '#ffffff';

  const labCount = document.querySelectorAll('#p-labList .lab-item').length;
  document.getElementById('p-labCount').textContent = labCount + ' item';

  const expCount = document.querySelectorAll('#p-expList .exp-item').length;
  document.getElementById('p-expCount').textContent = expCount + ' item';
}

// ---------------------------------------------------------------
// DYNAMIC ITEM HANDLERS (Lab & Pengeluaran)
// ---------------------------------------------------------------

function addApotekExp(ket = '', nominal = '') {
  const id = ++expCounter;
  const list = document.getElementById('a-expList');
  const div = document.createElement('div');
  div.className = 'input-group input-group-sm mb-2 exp-item';
  div.dataset.id = id;
  div.innerHTML = `
    <input type="text" class="form-control exp-nama" placeholder="Keterangan pengeluaran..." value="${escHtml(ket)}" oninput="markDirty()">
    <span class="input-group-text">Rp</span>
    <input type="text" class="form-control text-end-input exp-nominal" placeholder="0" value="${escHtml(nominal ? formatNum(nominal) : '')}" oninput="fmtCur(this); markDirty()" style="max-width:130px">
    <button class="btn btn-outline-danger" type="button" onclick="this.closest('.exp-item').remove(); markDirty();"><i class="bi bi-x"></i></button>
  `;
  list.appendChild(div);
  calcAll();
}

function addPendExp(ket = '', nominal = '') {
  const id = ++expCounter;
  const list = document.getElementById('p-expList');
  const div = document.createElement('div');
  div.className = 'input-group input-group-sm mb-2 exp-item';
  div.dataset.id = id;
  div.innerHTML = `
    <input type="text" class="form-control exp-nama" placeholder="Keterangan pengeluaran..." value="${escHtml(ket)}" oninput="markDirty()">
    <span class="input-group-text">Rp</span>
    <input type="text" class="form-control text-end-input exp-nominal" placeholder="0" value="${escHtml(nominal ? formatNum(nominal) : '')}" oninput="fmtCur(this); markDirty()" style="max-width:130px">
    <button class="btn btn-outline-danger" type="button" onclick="this.closest('.exp-item').remove(); markDirty();"><i class="bi bi-x"></i></button>
  `;
  list.appendChild(div);
  calcAll();
}

function addLabItem(nominal = '', focusInput = false) {
  const id = ++expCounter;
  const list = document.getElementById('p-labList');
  const num = list.querySelectorAll('.lab-item').length + 1;
  const div = document.createElement('div');
  div.className = 'input-group input-group-sm mb-2 lab-item';
  div.dataset.id = id;
  div.innerHTML = `
    <span class="input-group-text lab-num">Lab ${num}</span>
    <span class="input-group-text">Rp</span>
    <input type="text" class="form-control text-end-input lab-nominal" placeholder="0" value="${escHtml(nominal ? formatNum(nominal) : '')}" oninput="fmtCur(this); markDirty()">
    <button class="btn btn-outline-danger" type="button" onclick="removeLabItem(this.closest('.lab-item'))"><i class="bi bi-x"></i></button>
  `;
  list.appendChild(div);

  // Enter key → tambah baris baru dan pindah fokus ke input baru
  const inp = div.querySelector('.lab-nominal');
  inp.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      addLabItem('', true); // buat baris baru dengan autofocus
    }
  });

  if (focusInput) {
    // Tunggu DOM selesai render lalu fokuskan
    requestAnimationFrame(() => inp.focus());
  }

  calcAll();
}

function removeLabItem(el) {
  el.remove();
  renumberLab();
  markDirty();
}

function renumberLab() {
  document.querySelectorAll('#p-labList .lab-item').forEach((item, idx) => {
    const numSpan = item.querySelector('.lab-num');
    if (numSpan) numSpan.textContent = 'Lab ' + (idx + 1);
  });
}

// ---------------------------------------------------------------
// API / AJAX CALLS
// ---------------------------------------------------------------

// Tom Select instance (searchable dropdown riwayat)
let tomSelectLaporan = null;

// Semua tanggal laporan tersimpan (urut ascending) untuk navigasi prev/next
let allDates = [];

/** Update status aktif/nonaktif tombol Prev & Next */
function updateNavButtons(currentTanggal) {
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  if (!btnPrev || !btnNext) return;

  const idx = allDates.indexOf(currentTanggal);
  if (idx === -1) {
    btnPrev.disabled = true;
    btnNext.disabled = true;
    return;
  }
  // allDates urut ascending → prev = lebih kecil (idx-1), next = lebih besar (idx+1)
  btnPrev.disabled = (idx <= 0);
  btnNext.disabled = (idx >= allDates.length - 1);
}

async function fetchRiwayatList() {
  try {
    const res = await fetch('api/list.php');
    const json = await res.json();
    if (json.success && Array.isArray(json.data)) {
      // Simpan semua tanggal urut ascending untuk navigasi prev/next
      allDates = json.data.map(item => item.tanggal).reverse(); // list.php DESC → balik jadi ASC

      const options = [
        { value: '', text: '— Riwayat Laporan —' },
        ...json.data.map(item => ({
          value: item.tanggal,
          text: fmtTglIndo(item.tanggal)
        }))
      ];

      if (tomSelectLaporan) {
        // Sudah diinisialisasi: update options saja
        tomSelectLaporan.clearOptions();
        tomSelectLaporan.addOptions(options);
        tomSelectLaporan.setValue('', true);
      } else {
        // Inisialisasi Tom Select pertama kali
        tomSelectLaporan = new TomSelect('#selectLaporan', {
          options: options,
          valueField: 'value',
          labelField: 'text',
          searchField: 'text',
          placeholder: '— Riwayat Laporan —',
          allowEmptyOption: true,
          maxOptions: 400,
          onChange(val) {
            if (val) loadLaporan(val);
          },
          render: {
            no_results() {
              return '<div class="no-results">Laporan tidak ditemukan</div>';
            }
          }
        });
      }
    }
  } catch (err) {
    console.error('Gagal mengambil riwayat:', err);
  }
}

async function loadLaporan(tanggal) {
  if (!tanggal) return;

  try {
    const res = await fetch('api/load.php?tanggal=' + encodeURIComponent(tanggal));
    const data = await res.json();

    currentLaporanId = data.id || null;

    // Sync Tom Select ke tanggal yang dimuat
    if (tomSelectLaporan) {
      tomSelectLaporan.setValue(tanggal, true); // true = silent (tanpa trigger onChange)
    }

    // Update tombol prev/next
    updateNavButtons(tanggal);
    // Set UI active
    document.getElementById('welcomeScreen').classList.add('d-none');
    document.getElementById('mainContent').classList.remove('d-none');
    document.getElementById('inputTanggal').value = tanggal;
    document.getElementById('labelTanggal').textContent = fmtTglIndo(tanggal);

    checkFridayCondition(tanggal);

    // Apotek
    const a = data.apotek || {};
    document.getElementById('a-kasAwal').value           = a.kas_awal ? formatNum(a.kas_awal) : '1.000.000';
    document.getElementById('a-resep').value             = a.penjualan_resep ? formatNum(a.penjualan_resep) : '';
    document.getElementById('a-bebas').value             = a.penjualan_bebas ? formatNum(a.penjualan_bebas) : '';
    document.getElementById('a-jmDrZainuddin').value     = a.jm_dr_zainuddin ? formatNum(a.jm_dr_zainuddin) : (a.jm_dokter ? formatNum(a.jm_dokter) : '');
    document.getElementById('a-jmDrAliProgram').value    = a.jm_dr_ali_program ? formatNum(a.jm_dr_ali_program) : '';
    document.getElementById('a-jmDrAliNonProgram').value = a.jm_dr_ali_non_program ? formatNum(a.jm_dr_ali_non_program) : '';
    document.getElementById('a-transfer').value          = a.transfer ? formatNum(a.transfer) : '';

    const aExpList = document.getElementById('a-expList');
    aExpList.innerHTML = '';
    (data.pengeluaran_apotek || []).forEach(item => {
      addApotekExp(item.keterangan, item.nominal);
    });

    // Pendaftaran
    const p = data.pendaftaran || {};
    document.getElementById('p-kasAwal').value = p.kas_awal ? formatNum(p.kas_awal) : '600.000';
    document.getElementById('p-fisio120').value = p.fisio_120_pasien || '';
    document.getElementById('p-fisio90').value  = p.fisio_90_pasien || '';
    document.getElementById('p-adminPB').value   = p.admin_pasien_baru_count || '';
    document.getElementById('p-adminGM').value   = p.admin_gigi_mata ? formatNum(p.admin_gigi_mata) : '';
    document.getElementById('p-parkir').value    = p.parkir ? formatNum(p.parkir) : '';

    const pLabList = document.getElementById('p-labList');
    pLabList.innerHTML = '';
    (data.lab_items || []).forEach(item => {
      addLabItem(item.nominal);
    });

    const pExpList = document.getElementById('p-expList');
    pExpList.innerHTML = '';
    (data.pengeluaran_pend || []).forEach(item => {
      addPendExp(item.keterangan, item.nominal);
    });

    calcAll();
    buildRekap();

    isDirty = false;
    const statusEl = document.getElementById('statusSimpan');
    statusEl.className = 'badge bg-success';
    statusEl.textContent = data.found ? 'Tersimpan di Database' : 'Laporan Baru (Belum Disimpan)';

  } catch (err) {
    showToast('⚠️ Gagal memuat data dari server', 'text-bg-danger');
    console.error(err);
  }
}

async function saveLaporan(isSilent = false) {
  const tanggal = document.getElementById('inputTanggal').value;
  if (!tanggal) {
    showToast('Pilih tanggal laporan terlebih dahulu', 'text-bg-warning');
    return;
  }

  const payload = {
    tanggal: tanggal,
    apotek: {
      kas_awal: parseVal(document.getElementById('a-kasAwal').value),
      penjualan_resep: parseVal(document.getElementById('a-resep').value),
      penjualan_bebas: parseVal(document.getElementById('a-bebas').value),
      jm_dr_zainuddin: parseVal(document.getElementById('a-jmDrZainuddin').value),
      jm_dr_ali_program: parseVal(document.getElementById('a-jmDrAliProgram').value),
      jm_dr_ali_non_program: parseVal(document.getElementById('a-jmDrAliNonProgram').value),
      transfer: parseVal(document.getElementById('a-transfer').value)
    },
    pengeluaran_apotek: [],
    pendaftaran: {
      kas_awal: parseVal(document.getElementById('p-kasAwal').value),
      fisio_120_pasien: parseInt(document.getElementById('p-fisio120').value, 10) || 0,
      fisio_90_pasien: parseInt(document.getElementById('p-fisio90').value, 10)  || 0,
      admin_pasien_baru_count: parseInt(document.getElementById('p-adminPB').value, 10) || 0,
      admin_gigi_mata: parseVal(document.getElementById('p-adminGM').value),
      parkir: parseVal(document.getElementById('p-parkir').value)
    },
    lab_items: [],
    pengeluaran_pend: []
  };

  document.querySelectorAll('#a-expList .exp-item').forEach(item => {
    payload.pengeluaran_apotek.push({
      keterangan: item.querySelector('.exp-nama').value.trim(),
      nominal: parseVal(item.querySelector('.exp-nominal').value)
    });
  });

  document.querySelectorAll('#p-labList .lab-item').forEach(item => {
    payload.lab_items.push({
      nominal: parseVal(item.querySelector('.lab-nominal').value)
    });
  });

  document.querySelectorAll('#p-expList .exp-item').forEach(item => {
    payload.pengeluaran_pend.push({
      keterangan: item.querySelector('.exp-nama').value.trim(),
      nominal: parseVal(item.querySelector('.exp-nominal').value)
    });
  });

  try {
    const res = await fetch('api/save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();

    if (json.success) {
      currentLaporanId = json.id;
      isDirty = false;
      const statusEl = document.getElementById('statusSimpan');
      statusEl.className = 'badge bg-success';
      statusEl.textContent = 'Tersimpan di Database';

      if (!isSilent) {
        showToast('✅ Data laporan berhasil disimpan!', 'text-bg-success');
      }
      fetchRiwayatList();
    } else {
      showToast('❌ Gagal menyimpan: ' + (json.error || 'Error server'), 'text-bg-danger');
    }
  } catch (err) {
    showToast('❌ Gagal terhubung ke server database', 'text-bg-danger');
    console.error(err);
  }
}

async function deleteLaporan() {
  if (!currentLaporanId) {
    showToast('Laporan belum disimpan di database', 'text-bg-warning');
    return;
  }

  try {
    const res = await fetch('api/delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: currentLaporanId })
    });
    const json = await res.json();

    if (json.success) {
      showToast('✅ Laporan berhasil dihapus', 'text-bg-success');
      currentLaporanId = null;
      document.getElementById('mainContent').classList.add('d-none');
      document.getElementById('welcomeScreen').classList.remove('d-none');
      fetchRiwayatList();
    } else {
      showToast('❌ Gagal menghapus: ' + json.error, 'text-bg-danger');
    }
  } catch (err) {
    showToast('❌ Server error saat menghapus', 'text-bg-danger');
  }
}

// ---------------------------------------------------------------
// REKAP & PRINT & EXCEL
// ---------------------------------------------------------------

function buildRekap() {
  // === Apotek ===
  const aKasAwal           = parseVal(document.getElementById('a-kasAwal').value);
  const aResep             = parseVal(document.getElementById('a-resep').value);
  const aBebas             = parseVal(document.getElementById('a-bebas').value);
  const aJmDrZainuddinRaw  = parseVal(document.getElementById('a-jmDrZainuddin').value);
  const aJmDrAliProgram    = parseVal(document.getElementById('a-jmDrAliProgram').value);
  const aJmDrAliNonProgram = parseVal(document.getElementById('a-jmDrAliNonProgram').value);
  const aTransfer          = parseVal(document.getElementById('a-transfer').value);
  const aTotalPenj         = aResep + aBebas;
  const aCash              = Math.max(0, aTotalPenj - aTransfer);
  // JM dr. Zainuddin bersih = input - JM Ali Program
  const aJmDrZainuddinBersih = Math.max(0, aJmDrZainuddinRaw - aJmDrAliProgram);

  let aExpRandom = 0;
  const aExpRows = [];
  document.querySelectorAll('#a-expList .exp-item').forEach(item => {
    const ket = item.querySelector('.exp-nama').value.trim() || 'Pengeluaran';
    const nom = parseVal(item.querySelector('.exp-nominal').value);
    aExpRandom += nom;
    aExpRows.push({ ket, nom });
  });

  const aTotalExp = aTransfer + aExpRandom;
  // JM dr. Ali Non-Program menambah saldo apotek
  const aSaldo = aKasAwal + aCash + aJmDrAliNonProgram - aExpRandom;

  let aHtml = `
    <tr class="tr-section"><td colspan="2">Pemasukan</td></tr>
    <tr><td>Kas Awal</td><td class="text-end">${fmt(aKasAwal)}</td></tr>
    <tr><td>Penjualan Pakai Resep</td><td class="text-end">${fmt(aResep)}</td></tr>
    <tr><td>Penjualan Bebas</td><td class="text-end">${fmt(aBebas)}</td></tr>
    <tr class="tr-total"><td>Total Penjualan Apotek</td><td class="text-end text-success">${fmt(aTotalPenj)}</td></tr>
    ${aJmDrZainuddinRaw > 0 ? `<tr class="table-light text-muted"><td>↳ JM dr. Zai (input)</td><td class="text-end text-info-emphasis">${fmt(aJmDrZainuddinRaw)}</td></tr>` : ''}
    ${aJmDrAliProgram > 0 ? `<tr class="table-light text-muted"><td>&nbsp;&nbsp;− JM dr. Ali (Program)</td><td class="text-end text-warning-emphasis">−${fmt(aJmDrAliProgram)}</td></tr>` : ''}
    ${aJmDrZainuddinBersih > 0 ? `<tr class="table-light fw-semibold"><td>↳ JM dr. Zai (bersih)</td><td class="text-end text-info">${fmt(aJmDrZainuddinBersih)}</td></tr>` : ''}
    ${aJmDrAliNonProgram > 0 ? `<tr class="table-light text-muted"><td>↳ JM dr. Ali (non Program)</td><td class="text-end text-success-emphasis">${fmt(aJmDrAliNonProgram)}</td></tr>` : ''}
    <tr class="table-light text-muted"><td>↳ Bayar Cash</td><td class="text-end">${fmt(aCash)}</td></tr>
    <tr class="table-light text-muted"><td>↳ Bayar Transfer</td><td class="text-end">${fmt(aTransfer)}</td></tr>
    <tr class="tr-section"><td colspan="2">Pengeluaran</td></tr>
    <tr><td>Rekening Millenia</td><td class="text-end text-danger">${fmt(aTransfer)}</td></tr>
  `;
  aExpRows.forEach(r => {
    aHtml += `<tr><td>${escHtml(r.ket)}</td><td class="text-end text-danger">${fmt(r.nom)}</td></tr>`;
  });
  aHtml += `
    <tr class="tr-total"><td>Total Pengeluaran</td><td class="text-end text-danger">${fmt(aTotalExp)}</td></tr>
    <tr class="tr-saldo"><td>SALDO AKHIR APOTEK</td><td class="text-end">${fmt(aSaldo)}</td></tr>
  `;
  document.getElementById('r-apotek-body').innerHTML = aHtml;

  // === Pendaftaran ===
  const pKasAwal    = parseVal(document.getElementById('p-kasAwal').value);
  const pFisio120C  = parseInt(document.getElementById('p-fisio120').value, 10) || 0;
  const pFisio90C   = parseInt(document.getElementById('p-fisio90').value, 10)  || 0;
  const pAdminPBC   = parseInt(document.getElementById('p-adminPB').value, 10)   || 0;
  const pFisio120   = pFisio120C * 120000;
  const pFisio90    = pFisio90C * 90000;
  const pAdminPB    = pAdminPBC * 15000;
  const pAdminGM    = parseVal(document.getElementById('p-adminGM').value);
  const pParkir     = parseVal(document.getElementById('p-parkir').value);

  let pTotalLab = 0;
  const pLabRows = [];
  document.querySelectorAll('#p-labList .lab-item').forEach((item, idx) => {
    const nom = parseVal(item.querySelector('.lab-nominal').value);
    pTotalLab += nom;
    pLabRows.push({ nama: 'Lab ' + (idx + 1), nom });
  });

  let pExpRandom = 0;
  const pExpRows = [];
  document.querySelectorAll('#p-expList .exp-item').forEach(item => {
    const ket = item.querySelector('.exp-nama').value.trim() || 'Pengeluaran';
    const nom = parseVal(item.querySelector('.exp-nominal').value);
    pExpRandom += nom;
    pExpRows.push({ ket, nom });
  });

  const pTotalPemasukan = pFisio120 + pFisio90 + pTotalLab + pAdminGM + pAdminPB + pParkir;
  const pSaldo = pKasAwal + pTotalPemasukan - pExpRandom;

  let pHtml = `
    <tr class="tr-section"><td colspan="2">Pemasukan</td></tr>
    <tr><td>Kas Awal</td><td class="text-end">${fmt(pKasAwal)}</td></tr>
    ${pFisio120C > 0 ? `<tr><td>Fisioterapi Rp 120.000 (${pFisio120C} pasien)</td><td class="text-end">${fmt(pFisio120)}</td></tr>` : ''}
    ${pFisio90C > 0 ? `<tr><td>Fisioterapi Rp 90.000 (${pFisio90C} pasien)</td><td class="text-end">${fmt(pFisio90)}</td></tr>` : ''}
    ${pTotalLab > 0 ? `<tr class="tr-total"><td>Subtotal Laboratorium</td><td class="text-end text-success">${fmt(pTotalLab)}</td></tr>` : ''}
    ${pAdminGM > 0 ? `<tr><td>Admin Poli Gigi & Mata</td><td class="text-end">${fmt(pAdminGM)}</td></tr>` : ''}
    ${pAdminPBC > 0 ? `<tr><td>Admin Pasien Baru Rp 15.000 (${pAdminPBC} pasien)</td><td class="text-end">${fmt(pAdminPB)}</td></tr>` : ''}
    ${pParkir > 0 ? `<tr><td>Parkir</td><td class="text-end">${fmt(pParkir)}</td></tr>` : ''}
    <tr class="tr-total"><td>Total Pemasukan Cash</td><td class="text-end text-success">${fmt(pTotalPemasukan)}</td></tr>
    <tr class="tr-section"><td colspan="2">Pengeluaran</td></tr>
  `;
  pExpRows.forEach(r => {
    pHtml += `<tr><td>${escHtml(r.ket)}</td><td class="text-end text-danger">${fmt(r.nom)}</td></tr>`;
  });
  pHtml += `
    <tr class="tr-total"><td>Total Pengeluaran</td><td class="text-end text-danger">${fmt(pExpRandom)}</td></tr>
    <tr class="tr-saldo"><td>SALDO AKHIR PENDAFTARAN</td><td class="text-end">${fmt(pSaldo)}</td></tr>
  `;
  document.getElementById('r-pend-body').innerHTML = pHtml;

  // Ringkasan Gabungan Kedua Kasir
  const totKasAwal   = aKasAwal + pKasAwal;
  const totPemasukan = aTotalPenj + pTotalPemasukan;
  const totExp       = aTotalExp + pExpRandom;

  document.getElementById('r-total-kas-awal').textContent   = fmt(totKasAwal);
  document.getElementById('r-total-pemasukan').textContent  = fmt(totPemasukan);
  document.getElementById('r-total-pengeluaran').textContent = fmt(totExp);

  // Grand Total Saldo
  document.getElementById('r-grand-total').textContent = fmt(aSaldo + pSaldo);
}

function buildPrintView() {
  const tanggal = document.getElementById('inputTanggal').value;
  const tglStr  = fmtTglIndo(tanggal);
  const now     = new Date().toLocaleString('id-ID');

  const aKasAwal           = parseVal(document.getElementById('a-kasAwal').value);
  const aResep             = parseVal(document.getElementById('a-resep').value);
  const aBebas             = parseVal(document.getElementById('a-bebas').value);
  const aJmDrZainuddinRaw  = parseVal(document.getElementById('a-jmDrZainuddin').value);
  const aJmDrAliProgram    = parseVal(document.getElementById('a-jmDrAliProgram').value);
  const aJmDrAliNonProgram = parseVal(document.getElementById('a-jmDrAliNonProgram').value);
  const aTransfer          = parseVal(document.getElementById('a-transfer').value);
  const aTotalPenj         = aResep + aBebas;
  const aCash              = Math.max(0, aTotalPenj - aTransfer);
  // JM dr. Zainuddin bersih = input - JM Ali Program
  const aJmDrZainuddinBersih = Math.max(0, aJmDrZainuddinRaw - aJmDrAliProgram);

  let aExpRandom = 0;
  const aExpRows = [];
  document.querySelectorAll('#a-expList .exp-item').forEach(item => {
    const ket = item.querySelector('.exp-nama').value.trim() || 'Pengeluaran';
    const nom = parseVal(item.querySelector('.exp-nominal').value);
    aExpRandom += nom;
    aExpRows.push({ ket, nom });
  });
  const aTotalExp = aTransfer + aExpRandom;
  // JM dr. Ali Non-Program menambah saldo apotek
  const aSaldo = aKasAwal + aCash + aJmDrAliNonProgram - aExpRandom;

  const pKasAwal    = parseVal(document.getElementById('p-kasAwal').value);
  const pFisio120C  = parseInt(document.getElementById('p-fisio120').value, 10) || 0;
  const pFisio90C   = parseInt(document.getElementById('p-fisio90').value, 10)  || 0;
  const pAdminPBC   = parseInt(document.getElementById('p-adminPB').value, 10)   || 0;
  const pFisio120   = pFisio120C * 120000;
  const pFisio90    = pFisio90C * 90000;
  const pAdminPB    = pAdminPBC * 15000;
  const pAdminGM    = parseVal(document.getElementById('p-adminGM').value);
  const pParkir     = parseVal(document.getElementById('p-parkir').value);

  let pTotalLab = 0;
  document.querySelectorAll('#p-labList .lab-nominal').forEach(inp => {
    pTotalLab += parseVal(inp.value);
  });

  let pExpRandom = 0;
  const pExpRows = [];
  document.querySelectorAll('#p-expList .exp-item').forEach(item => {
    const ket = item.querySelector('.exp-nama').value.trim() || 'Pengeluaran';
    const nom = parseVal(item.querySelector('.exp-nominal').value);
    pExpRandom += nom;
    pExpRows.push({ ket, nom });
  });

  const pTotalPemasukan = pFisio120 + pFisio90 + pTotalLab + pAdminGM + pAdminPB + pParkir;
  const pSaldo = pKasAwal + pTotalPemasukan - pExpRandom;

  const totKasAwal   = aKasAwal + pKasAwal;
  const totPemasukan = aTotalPenj + pTotalPemasukan;
  const totExp       = aTotalExp + pExpRandom;

  const tr  = (cls, a, b) => `<tr class="${cls}"><td>${a}</td><td>${b}</td></tr>`;
  const trM = (a, b) => `<tr><td>${a}</td><td class="txt-minus">${fmt(b)}</td></tr>`;

  const tableApotek = `
    <div class="pv-section">
      <div class="pv-section-title">💊 Kasir Apotek</div>
      <table class="pv-table">
        ${tr('tr-section', 'PEMASUKAN', '')}
        ${tr('', 'Kas Awal', fmt(aKasAwal))}
        ${tr('', 'Penjualan Pakai Resep', fmt(aResep))}
        ${tr('', 'Penjualan Bebas', fmt(aBebas))}
        ${tr('tr-total', 'Total Penjualan', fmt(aTotalPenj))}
        ${aJmDrZainuddinRaw > 0 ? tr('tr-sub', '↳ JM dr. Zai (input)', fmt(aJmDrZainuddinRaw)) : ''}
        ${aJmDrAliProgram > 0 ? tr('tr-sub', '&nbsp;&nbsp;− JM dr. Ali Program', fmt(aJmDrAliProgram)) : ''}
        ${aJmDrZainuddinBersih > 0 ? tr('tr-sub', '↳ JM dr. Zai (bersih)', fmt(aJmDrZainuddinBersih)) : ''}
        ${aJmDrAliNonProgram > 0 ? tr('tr-sub', '↳ JM dr. Ali (non Program) [+]', fmt(aJmDrAliNonProgram)) : ''}
        ${tr('tr-sub', '↳ Bayar Cash', fmt(aCash))}
        ${tr('tr-sub', '↳ Bayar Transfer', fmt(aTransfer))}
        ${tr('tr-section', 'PENGELUARAN', '')}
        ${tr('', 'Rekening Millenia', fmt(aTransfer))}
        ${aExpRows.map(r => trM(r.ket, r.nom)).join('')}
        ${tr('tr-total', 'Total Pengeluaran', fmt(aTotalExp))}
        ${tr('tr-saldo', 'SALDO AKHIR', fmt(aSaldo))}
      </table>
    </div>
  `;

  const tablePendaftaran = `
    <div class="pv-section">
      <div class="pv-section-title">🏥 Kasir Pendaftaran</div>
      <table class="pv-table">
        ${tr('tr-section', 'PEMASUKAN', '')}
        ${tr('', 'Kas Awal', fmt(pKasAwal))}
        ${pFisio120C > 0 ? tr('', `Fisioterapi Rp 120.000 × ${pFisio120C} pasien`, fmt(pFisio120)) : ''}
        ${pFisio90C > 0 ? tr('', `Fisioterapi Rp 90.000 × ${pFisio90C} pasien`, fmt(pFisio90)) : ''}
        ${pTotalLab > 0 ? tr('', 'Total Laboratorium', fmt(pTotalLab)) : ''}
        ${pAdminGM > 0 ? tr('', 'Admin Poli Gigi & Mata', fmt(pAdminGM)) : ''}
        ${pAdminPBC > 0 ? tr('', `Admin Pasien Baru Rp 15.000 × ${pAdminPBC} pasien`, fmt(pAdminPB)) : ''}
        ${pParkir > 0 ? tr('', 'Parkir', fmt(pParkir)) : ''}
        ${tr('tr-total', 'Total Pemasukan Cash', fmt(pTotalPemasukan))}
        ${tr('tr-section', 'PENGELUARAN', '')}
        ${pExpRows.map(r => trM(r.ket, r.nom)).join('') || tr('', '(tidak ada)', '-')}
        ${tr('tr-total', 'Total Pengeluaran', fmt(pExpRandom))}
        ${tr('tr-saldo', 'SALDO AKHIR', fmt(pSaldo))}
      </table>
    </div>
  `;

  document.getElementById('print-view').innerHTML = `
    <div class="pv-header">
      <h1>Laporan Keuangan Harian</h1>
      <div class="pv-meta">${tglStr} | Dicetak: ${now}</div>
    </div>
    <div class="pv-columns">
      ${tableApotek}
      ${tablePendaftaran}
    </div>
    <div class="pv-grand">
      <table style="margin-bottom: 6px;">
        <tr class="tr-grand-title"><td colspan="3" style="text-align:center;">Total Gabungan Kedua Kasir</td></tr>
        <tr class="tr-section" style="text-align:center;">
          <td style="width:33.3%;">Kas Awal</td>
          <td style="width:33.3%;">Pemasukan</td>
          <td style="width:33.3%;">Pengeluaran</td>
        </tr>
        <tr>
          <td style="text-align:center; font-weight:700;">${fmt(totKasAwal)}</td>
          <td style="text-align:center; font-weight:700;" class="txt-plus">${fmt(totPemasukan)}</td>
          <td style="text-align:center; font-weight:700;" class="txt-minus">${fmt(totExp)}</td>
        </tr>
      </table>
      <table>
        <tr class="tr-grand-row"><td>Saldo Kasir Apotek</td><td>${fmt(aSaldo)}</td></tr>
        <tr class="tr-grand-row"><td>Saldo Kasir Pendaftaran</td><td>${fmt(pSaldo)}</td></tr>
        <tr class="tr-grand-total"><td>TOTAL SALDO KESELURUHAN</td><td>${fmt(aSaldo + pSaldo)}</td></tr>
      </table>
    </div>
    <div class="pv-footer">
      Dokumen ini digenerate otomatis oleh Sistem Laporan Keuangan Kasir PHP/MySQL
    </div>
  `;
}

function exportExcel() {
  if (typeof XLSX === 'undefined') {
    showToast('Library Excel belum dimuat', 'text-bg-danger');
    return;
  }

  const tanggal = document.getElementById('inputTanggal').value;
  const tglStr  = fmtTglIndo(tanggal);
  const wb      = XLSX.utils.book_new();

  // Data Apotek
  const aKasAwal           = parseVal(document.getElementById('a-kasAwal').value);
  const aResep            = parseVal(document.getElementById('a-resep').value);
  const aBebas            = parseVal(document.getElementById('a-bebas').value);
  const aJmDrZainuddin     = parseVal(document.getElementById('a-jmDrZainuddin').value);
  const aJmDrAliProgram    = parseVal(document.getElementById('a-jmDrAliProgram').value);
  const aJmDrAliNonProgram = parseVal(document.getElementById('a-jmDrAliNonProgram').value);
  const aTransfer          = parseVal(document.getElementById('a-transfer').value);
  const aTotalPenj         = aResep + aBebas;
  const aCash              = Math.max(0, aTotalPenj - aTransfer);

  let aExpRandom = 0;
  const aExpRows = [];
  document.querySelectorAll('#a-expList .exp-item').forEach(item => {
    const ket = item.querySelector('.exp-nama').value.trim() || 'Pengeluaran Apotek';
    const nom = parseVal(item.querySelector('.exp-nominal').value);
    aExpRandom += nom;
    aExpRows.push([ket, nom]);
  });
  const aTotalExp = aTransfer + aExpRandom;
  // JM dr. Ali Non-Program menambah saldo apotek
  const aSaldo    = aKasAwal + aCash + aJmDrAliNonProgram - aExpRandom;

  // Data Pendaftaran
  const pKasAwal    = parseVal(document.getElementById('p-kasAwal').value);
  const pFisio120C  = parseInt(document.getElementById('p-fisio120').value, 10) || 0;
  const pFisio90C   = parseInt(document.getElementById('p-fisio90').value, 10)  || 0;
  const pAdminPBC   = parseInt(document.getElementById('p-adminPB').value, 10)   || 0;
  const pFisio120   = pFisio120C * 120000;
  const pFisio90    = pFisio90C * 90000;
  const pFisioTotal = pFisio120 + pFisio90;
  const pAdminPB    = pAdminPBC * 15000;
  const pAdminGM    = parseVal(document.getElementById('p-adminGM').value);
  const pParkir     = parseVal(document.getElementById('p-parkir').value);

  let pTotalLab = 0;
  document.querySelectorAll('#p-labList .lab-nominal').forEach(inp => {
    pTotalLab += parseVal(inp.value);
  });

  let pExpRandom = 0;
  const pExpRows = [];
  document.querySelectorAll('#p-expList .exp-item').forEach(item => {
    const ket = item.querySelector('.exp-nama').value.trim() || 'Pengeluaran Pendaftaran';
    const nom = parseVal(item.querySelector('.exp-nominal').value);
    pExpRandom += nom;
    pExpRows.push([ket, nom]);
  });

  const pTotalPemasukan = pFisioTotal + pTotalLab + pAdminGM + pAdminPB + pParkir;
  const pSaldo          = pKasAwal + pTotalPemasukan - pExpRandom;

  // Ringkasan Gabungan
  const totalKasAwal   = aKasAwal + pKasAwal;
  const totalPemasukan = aTotalPenj + pTotalPemasukan;
  const totalPengeluaran = aTotalExp + pExpRandom;
  const totalSetor     = (totalKasAwal + totalPemasukan) - totalPengeluaran;

  // Baris-baris Sheet 1: Laporan Harian (Format Gambar Referensi)
  const wsData = [
    ['LAPORAN HARIAN', '', '', tglStr],
    ['Keterangan', 'Kas', 'Masuk', 'Keluar'],
    ['Kas Apotek', aKasAwal, '', ''],
    ['Kas Pendaftaran', pKasAwal, '', ''],
    [],
    ['Pemasukan', '', '', ''],
    ['Apotek', '', aTotalPenj, ''],
    aJmDrZainuddin > 0 ? ['  ↳ (JM dr. Zai)', '', aJmDrZainuddin, ''] : null,
    aJmDrAliProgram > 0 ? ['  ↳ (JM dr. Ali Program)', '', aJmDrAliProgram, ''] : null,
    aJmDrAliNonProgram > 0 ? ['  ↳ (JM dr. Ali non Program)', '', aJmDrAliNonProgram, ''] : null,
    ['Laboratorium', '', pTotalLab > 0 ? pTotalLab : '', ''],
    ['Fisiotherapy', '', pFisioTotal > 0 ? pFisioTotal : '', ''],
    ['Parkir', '', pParkir > 0 ? pParkir : '', ''],
    ['Admin Poli Gigi', '', pAdminGM > 0 ? pAdminGM : '', ''],
    ['Admin Klinik', '', pAdminPB > 0 ? pAdminPB : '', ''],
    [],
    ['Pengeluaran', '', '', '']
  ].filter(Boolean);

  if (aTransfer > 0) {
    wsData.push(['Nominal Transfer (Apotek)', '', '', aTransfer]);
  }
  aExpRows.forEach(r => {
    wsData.push([r[0], '', '', r[1]]);
  });
  pExpRows.forEach(r => {
    wsData.push([r[0], '', '', r[1]]);
  });

  wsData.push(
    [],
    ['Total', totalKasAwal, totalPemasukan, totalPengeluaran],
    ['Total Setor', totalSetor, '', ''],
    ['dr Zainuddin', '', '', 'dr Zainuddin']
  );

  const wsMain = XLSX.utils.aoa_to_sheet(wsData);

  // Column widths
  wsMain['!cols'] = [
    { wch: 28 }, // Keterangan
    { wch: 16 }, // Kas
    { wch: 16 }, // Masuk
    { wch: 16 }  // Keluar
  ];

  XLSX.utils.book_append_sheet(wb, wsMain, 'Laporan Harian');

  // Sheet 2: Rekap Rinci Per Kasir (opsional pelengkap)
  const wsApotekData = [
    ['LAPORAN RINCI KASIR APOTEK', ''],
    ['Tanggal: ' + tglStr, ''],
    [],
    ['PEMASUKAN', 'Nominal (Rp)'],
    ['Kas Awal', aKasAwal],
    ['Penjualan Resep', aResep],
    ['Penjualan Bebas', aBebas],
    ['Total Penjualan', aTotalPenj],
    ['↳ Bayar Cash', aCash],
    ['↳ Bayar Transfer', aTransfer],
    [],
    ['PENGELUARAN', 'Nominal (Rp)'],
    ['Rekening Millenia', aTransfer],
    ...aExpRows,
    ['Total Pengeluaran', aTotalExp],
    [],
    ['SALDO AKHIR APOTEK', aSaldo]
  ];
  const wsApotek = XLSX.utils.aoa_to_sheet(wsApotekData);
  wsApotek['!cols'] = [{ wch: 30 }, { wch: 18 }];
  XLSX.utils.book_append_sheet(wb, wsApotek, 'Kasir Apotek');

  const wsPendData = [
    ['LAPORAN RINCI KASIR PENDAFTARAN', ''],
    ['Tanggal: ' + tglStr, ''],
    [],
    ['PEMASUKAN', 'Nominal (Rp)'],
    ['Kas Awal', pKasAwal],
    [`Fisioterapi Rp 120.000 (${pFisio120C} pasien)`, pFisio120],
    [`Fisioterapi Rp 90.000 (${pFisio90C} pasien)`, pFisio90],
    ['Subtotal Lab', pTotalLab],
    ['Admin Poli Gigi & Mata', pAdminGM],
    [`Admin Pasien Baru Rp 15.000 (${pAdminPBC} pasien)`, pAdminPB],
    ['Parkir', pParkir],
    ['Total Pemasukan Cash', pTotalPemasukan],
    [],
    ['PENGELUARAN', 'Nominal (Rp)'],
    ...pExpRows,
    ['Total Pengeluaran', pExpRandom],
    [],
    ['SALDO AKHIR PENDAFTARAN', pSaldo]
  ];
  const wsPend = XLSX.utils.aoa_to_sheet(wsPendData);
  wsPend['!cols'] = [{ wch: 35 }, { wch: 18 }];
  XLSX.utils.book_append_sheet(wb, wsPend, 'Kasir Pendaftaran');

  XLSX.writeFile(wb, `Laporan_Harian_${tanggal}.xlsx`);
  showToast('✅ File Excel Laporan Harian berhasil dibuat!', 'text-bg-success');
}

function fmtTglIndo(dateStr) {
  if (!dateStr) return '-';
  try {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('id-ID', {
      day: 'numeric', month: 'long', year: 'numeric'
    });
  } catch (_) { return dateStr; }
}

// ---------------------------------------------------------------
// INITIALIZATION & EVENT LISTENERS
// ---------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
  fetchRiwayatList();

  document.getElementById('inputTanggal').addEventListener('change', (e) => {
    checkFridayCondition(e.target.value);
  });

  document.getElementById('btnMuat').addEventListener('click', () => {
    const tgl = document.getElementById('inputTanggal').value;
    if (tgl) loadLaporan(tgl);
  });

  // Tombol Prev (laporan sebelumnya)
  document.getElementById('btnPrev').addEventListener('click', () => {
    const currentTgl = document.getElementById('inputTanggal').value;
    const idx = allDates.indexOf(currentTgl);
    if (idx > 0) loadLaporan(allDates[idx - 1]);
  });

  // Tombol Next (laporan berikutnya)
  document.getElementById('btnNext').addEventListener('click', () => {
    const currentTgl = document.getElementById('inputTanggal').value;
    const idx = allDates.indexOf(currentTgl);
    if (idx >= 0 && idx < allDates.length - 1) loadLaporan(allDates[idx + 1]);
  });

  document.getElementById('selectLaporan').addEventListener('change', (e) => {
    if (e.target.value) {
      loadLaporan(e.target.value);
    }
  });

  /* NOTE: Tom Select onChange sudah ditangani di dalam fetchRiwayatList() */

  document.getElementById('btnSave').addEventListener('click', () => {
    saveLaporan(false);
  });

  document.getElementById('btnAddApotekExp').addEventListener('click', () => {
    addApotekExp();
  });

  document.getElementById('btnAddPendExp').addEventListener('click', () => {
    addPendExp();
  });

  document.getElementById('btnAddLab').addEventListener('click', () => {
    addLabItem();
  });

  document.getElementById('btnTabRekap').addEventListener('click', () => {
    buildRekap();
  });

  document.getElementById('btnCetak').addEventListener('click', () => {
    buildRekap();
    buildPrintView();
    document.body.classList.add('printing-laporan');
    window.print();
    setTimeout(() => document.body.classList.remove('printing-laporan'), 1000);
  });

  document.getElementById('btnExcel').addEventListener('click', () => {
    exportExcel();
  });

  document.getElementById('btnHapus').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('modalHapus'));
    modal.show();
  });

  document.getElementById('btnConfirmHapus').addEventListener('click', () => {
    deleteLaporan();
    const modalEl = document.getElementById('modalHapus');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
  });

  // ── Modal Rincian Transfer ──────────────────────────────────────
  let transferItemCounter = 0;

  /** Hitung ulang total di dalam modal dan update tampilan */
  function calcTransferModalTotal() {
    let total = 0;
    document.querySelectorAll('#transferItemList .transfer-item-nominal').forEach(inp => {
      total += parseVal(inp.value);
    });
    document.getElementById('transferModalTotal').textContent = fmt(total);
  }

  /** Tambah satu baris input transfer di dalam modal */
  function addTransferItem(keterangan = '', nominal = '', focusInput = false) {
    const id = ++transferItemCounter;
    const list = document.getElementById('transferItemList');
    const div = document.createElement('div');
    div.className = 'input-group input-group-sm mb-2 transfer-item';
    div.dataset.id = id;
    div.innerHTML = `
      <input type="text" class="form-control transfer-item-ket"
        placeholder="Keterangan (opsional, mis: BRI, Mandiri...)"
        value="${escHtml(keterangan)}"
        style="max-width: 45%;">
      <span class="input-group-text">Rp</span>
      <input type="text" class="form-control text-end transfer-item-nominal"
        placeholder="0"
        value="${escHtml(nominal ? formatNum(nominal) : '')}"
        autocomplete="off">
      <button class="btn btn-outline-danger" type="button" title="Hapus baris">
        <i class="bi bi-x"></i>
      </button>
    `;

    // Format angka saat mengetik & update total
    const nominalInp = div.querySelector('.transfer-item-nominal');
    nominalInp.addEventListener('input', function () {
      fmtCur(this);
      calcTransferModalTotal();
    });

    // Enter → buat baris baru
    nominalInp.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        addTransferItem('', '', true);
      }
    });

    // Hapus baris
    div.querySelector('button').addEventListener('click', () => {
      div.remove();
      calcTransferModalTotal();
    });

    list.appendChild(div);
    if (focusInput) {
      requestAnimationFrame(() => nominalInp.focus());
    }
    calcTransferModalTotal();
  }

  // Tombol buka modal Transfer
  document.getElementById('btnOpenTransferModal').addEventListener('click', () => {
    const list = document.getElementById('transferItemList');

    // Jika modal kosong, pre-fill dari nilai Transfer yang sudah ada di form
    if (list.children.length === 0) {
      const existingVal = parseVal(document.getElementById('a-transfer').value);
      addTransferItem('', existingVal > 0 ? existingVal : '', true);
    }

    const modal = new bootstrap.Modal(document.getElementById('modalTransfer'));
    modal.show();
  });

  // Tombol Tambah Baris di dalam modal
  document.getElementById('btnAddTransferItem').addEventListener('click', () => {
    addTransferItem('', '', true);
  });

  // Tombol Selesai — jumlahkan semua dan terapkan ke field Transfer
  document.getElementById('btnTransferDone').addEventListener('click', () => {
    let total = 0;
    document.querySelectorAll('#transferItemList .transfer-item-nominal').forEach(inp => {
      total += parseVal(inp.value);
    });

    // Set ke field Transfer utama dan trigger kalkulasi
    const transferInput = document.getElementById('a-transfer');
    transferInput.value = total > 0 ? formatNum(total) : '';
    calcAll();
    markDirty();

    // Tutup modal
    const modalEl = document.getElementById('modalTransfer');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();

    showToast(`✅ Total transfer Rp ${formatNum(total)} diterapkan ke form`, 'text-bg-success');
  });
  // ── Akhir Modal Rincian Transfer ───────────────────────────────

  // Load tanggal dari URL parameter jika ada, jika tidak pakai nilai default inputTanggal
  const urlParams = new URLSearchParams(window.location.search);
  const paramTanggal = urlParams.get('tanggal');
  
  if (paramTanggal) {
    document.getElementById('inputTanggal').value = paramTanggal;
    loadLaporan(paramTanggal);
  } else {
    const defaultTgl = document.getElementById('inputTanggal').value;
    if (defaultTgl) {
      loadLaporan(defaultTgl);
    }
  }
});
