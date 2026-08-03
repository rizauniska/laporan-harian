<!-- Dedicated Embedded Print Stylesheet for PDF Print Preview -->
<style>
  #print-view {
    display: none;
  }
</style>
<style media="print">
  @page {
    margin: 10mm;
    size: A4 portrait;
  }

  /* Universal Text Color Reset for Print */
  *,
  *::before,
  *::after {
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
    text-shadow: none !important;
    box-shadow: none !important;
  }

  /* Hide screen UI controls, navbars, sidebars, footers, buttons */
  .app-header,
  .app-sidebar,
  .app-footer,
  .no-print,
  .filter-area,
  .stat-cards,
  .card-tools,
  .breadcrumb,
  .app-content-header,
  .tabulator,
  .card-header,
  button,
  .btn,
  .modal,
  .toast,
  .position-fixed {
    display: none !important;
  }

  /* Ketika mode cetak aktif (via JS trigger), sembunyikan .app-wrapper dan tampilkan #print-view */
  body.printing-active .app-wrapper,
  body.printing-laporan .app-wrapper {
    display: none !important;
  }

  body.printing-active #print-view,
  body.printing-laporan #print-view {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: static !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
    color: #000000 !important;
  }

  /* Fallback reset layout jika mencetak langsung dari browser (Ctrl+P) */
  html,
  body,
  html.layout-fixed,
  body.layout-fixed {
    background: #ffffff !important;
    font-family: Arial, sans-serif !important;
    font-size: 9pt !important;
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    height: auto !important;
    min-height: 0 !important;
    max-height: none !important;
    overflow: visible !important;
    position: static !important;
    color: #000000 !important;
  }

  .app-wrapper,
  .app-main,
  .app-content,
  .container-fluid,
  .card,
  .card-body {
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    height: auto !important;
    min-height: 0 !important;
    max-height: none !important;
    overflow: visible !important;
    position: static !important;
    float: none !important;
    flex: none !important;
    background: transparent !important;
    visibility: visible !important;
    opacity: 1 !important;
  }

  .table-responsive {
    overflow: visible !important;
  }

  /* Fallback Print Header & Printable Table */
  .print-header {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    text-align: center !important;
    border-bottom: 2px solid #000000 !important;
    padding-bottom: 8px !important;
    margin-bottom: 14px !important;
  }

  .print-header h2,
  .print-header h1 {
    font-size: 14pt !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    margin: 0 0 4px 0 !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  .print-header p {
    font-size: 9pt !important;
    color: #333333 !important;
    -webkit-text-fill-color: #333333 !important;
    margin: 2px 0 0 !important;
  }

  .print-only-table,
  #tabelParkirPrint,
  #tabelPrint {
    display: table !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 100% !important;
    border-collapse: collapse !important;
    font-size: 9pt !important;
    margin-top: 10px !important;
    border: 1.5px solid #000000 !important;
  }

  .print-only-table th,
  .print-only-table td,
  #tabelParkirPrint th,
  #tabelParkirPrint td,
  #tabelPrint th,
  #tabelPrint td {
    border: 1px solid #000000 !important;
    padding: 5px 8px !important;
    background: transparent !important;
    background-color: transparent !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
    visibility: visible !important;
    opacity: 1 !important;
  }

  .print-only-table thead tr,
  #tabelParkirPrint thead tr,
  #tabelPrint thead tr {
    border-bottom: 2.5px solid #000000 !important;
  }

  .print-only-table th,
  #tabelParkirPrint th,
  #tabelPrint th {
    font-weight: 800 !important;
    border-bottom: 2.5px solid #000000 !important;
    text-transform: uppercase !important;
    letter-spacing: .04em !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  .print-only-table .tr-total td,
  #tabelParkirPrint .tr-total td,
  #tabelPrint .tr-total td {
    border-top: 2.5px solid #000000 !important;
    border-bottom: 1.5px solid #000000 !important;
    font-weight: 800 !important;
    font-size: 10pt !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  .print-only-table .tr-gaji td,
  #tabelParkirPrint .tr-gaji td,
  #tabelParkirPrint .tr-gaji td {
    border-top: 1.5px dashed #000000 !important;
    font-weight: 700 !important;
    font-size: 9.5pt !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  /* Styling khusus Laporan.php #print-view */
  .pv-header {
    text-align: center !important;
    border-bottom: 2.5px solid #000000 !important;
    padding-bottom: 6px !important;
    margin-bottom: 12px !important;
  }

  .pv-header h1 {
    font-size: 14pt !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    margin: 0 0 4px 0 !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  .pv-meta {
    font-size: 8.5pt !important;
    color: #333333 !important;
    -webkit-text-fill-color: #333333 !important;
    font-weight: 600 !important;
  }

  .pv-columns {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 12px !important;
    margin-bottom: 12px !important;
  }

  .pv-section {
    border: 1.5px solid #000000 !important;
    border-radius: 4px !important;
    overflow: hidden !important;
  }

  .pv-section-title {
    background: #1a1a2e !important;
    color: #ffffff !important;
    -webkit-text-fill-color: #ffffff !important;
    font-weight: 800 !important;
    font-size: 9pt !important;
    padding: 5px 8px !important;
    text-transform: uppercase !important;
    letter-spacing: .03em !important;
    border-bottom: 1.5px solid #000000 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .pv-table {
    width: 100% !important;
    border-collapse: collapse !important;
    font-size: 8pt !important;
  }

  .pv-table td {
    padding: 3px 6px !important;
    border: 1px solid #000000 !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  .pv-table td:last-child {
    text-align: right !important;
    width: 115px !important;
    white-space: nowrap !important;
  }

  .pv-table .tr-section td {
    background: #e5e7eb !important;
    font-weight: 800 !important;
    font-size: 8pt !important;
    text-transform: uppercase !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
    border-top: 1.5px solid #000000 !important;
    border-bottom: 1.5px solid #000000 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .pv-table .tr-sub td:first-child {
    padding-left: 16px !important;
    color: #333333 !important;
    -webkit-text-fill-color: #333333 !important;
    font-style: italic !important;
  }

  .pv-table .tr-total td {
    font-weight: 800 !important;
    border-top: 2px solid #000000 !important;
    border-bottom: 1.5px solid #000000 !important;
    background: #f3f4f6 !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .pv-table .tr-saldo td {
    font-weight: 800 !important;
    font-size: 9.5pt !important;
    background: #d1fae5 !important;
    border: 2px solid #065f46 !important;
    color: #064e3b !important;
    -webkit-text-fill-color: #064e3b !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .txt-minus {
    color: #dc2626 !important;
    -webkit-text-fill-color: #dc2626 !important;
    font-weight: 700 !important;
  }

  .txt-plus {
    color: #16a34a !important;
    -webkit-text-fill-color: #16a34a !important;
    font-weight: 700 !important;
  }

  .pv-grand {
    margin-top: 10px !important;
  }

  .pv-grand table {
    width: 100% !important;
    border-collapse: collapse !important;
    font-size: 9pt !important;
    border: 1.5px solid #000000 !important;
  }

  .pv-grand td {
    padding: 5px 9px !important;
    border: 1.5px solid #000000 !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
  }

  .pv-grand td:last-child {
    text-align: right !important;
    white-space: nowrap !important;
  }

  .pv-grand .tr-grand-title td {
    background: #0f766e !important;
    color: #ffffff !important;
    -webkit-text-fill-color: #ffffff !important;
    font-weight: 800 !important;
    font-size: 9.5pt !important;
    text-transform: uppercase !important;
    border-bottom: 2px solid #000000 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .pv-grand .tr-grand-row td {
    background: #f0fdf4 !important;
    font-weight: 600 !important;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .pv-grand .tr-grand-total td {
    background: #bbf7d0 !important;
    font-weight: 800 !important;
    font-size: 12pt !important;
    color: #064e3b !important;
    -webkit-text-fill-color: #064e3b !important;
    border-top: 2.5px solid #000000 !important;
    border-bottom: 2.5px solid #000000 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  .pv-footer {
    margin-top: 14px !important;
    padding-top: 6px !important;
    border-top: 1px dashed #666666 !important;
    font-size: 7.5pt !important;
    color: #555555 !important;
    -webkit-text-fill-color: #555555 !important;
    text-align: center !important;
  }
</style>
