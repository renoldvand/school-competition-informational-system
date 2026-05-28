<?php
  function admin_sidebar_html($active_page = 'dashboard', $admin_username = 'Admin') {
    $items = [
      ['id' => 'dashboard',    'label' => 'Dashboard',      'icon' => '&#9632;', 'href' => '../admin_dashboard/admin_dashboard.php'],
      ['id' => 'comps',        'label' => 'Kelola Lomba',    'icon' => '&#9733;', 'href' => '../admin_comps/admin_comps.php'],
      ['id' => 'comp_add',     'label' => 'Tambah Lomba',    'icon' => '+',      'href' => '../admin_comp_form/admin_comp_form.php'],
      ['id' => 'students',     'label' => 'Kelola Siswa',    'icon' => '&#9679;', 'href' => '../admin_students/admin_students.php'],
      ['id' => 'regs',         'label' => 'Pendaftaran',     'icon' => '&#9998;', 'href' => '../admin_registrations/admin_registrations.php'],
      ['id' => 'announcements','label' => 'Pengumuman',      'icon' => '&#9993;', 'href' => '../admin_announcements/admin_announcements.php'],
    ];

    $nav_html = '';
    foreach ($items as $item) {
      $active = ($item['id'] === $active_page) ? ' active' : '';
      $nav_html .= <<<HTML
        <a href="{$item['href']}" class="nav-item{$active}">
          <span class="nav-icon">{$item['icon']}</span>
          <span class="nav-label">{$item['label']}</span>
        </a>
      HTML;
    }

    $html = <<<HTML
      <aside id="admin_sidebar">
        <div id="sidebar_brand">
          <img id="sidebar_logo" src="../../assets/images/logo_scis.png" alt="SCIS">
          <p>Panel Admin</p>
        </div>
        <nav id="sidebar_nav">$nav_html</nav>
        <div id="sidebar_bottom">
          <a href="../../pages/home/home.php" class="nav-item">
            <span class="nav-icon">&#8962;</span>
            <span class="nav-label">Ke Website</span>
          </a>
          <a href="../admin_logout/admin_logout.php" class="nav-item nav-logout">
            <span class="nav-icon">&#10140;</span>
            <span class="nav-label">Keluar</span>
          </a>
        </div>
      </aside>
    HTML;
    echo $html;
  }

  function admin_sidebar_css() {
    echo <<<HTML
      <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');
        :root {
          --navy:#0f2044;--navy-mid:#1a3260;--blue:#2a52a0;--gold:#e6b94a;--gold-light:#f5d98a;
          --bg:#eceef4;--white:#ffffff;--text:#1a1f2e;--text-muted:#5a6278;
          --shadow-sm:0 2px 8px rgba(15,32,68,0.10);--shadow-md:0 6px 24px rgba(15,32,68,0.14);
          --radius:12px;--danger:#d94040;--success:#1a6630;
        }
        * { margin:0;padding:0;box-sizing:border-box; }
        body { min-height:100vh;display:flex;background:var(--bg);font-family:'DM Sans',sans-serif;color:var(--text); }
        #admin_sidebar {
          width:240px;position:fixed;top:0;left:0;height:100vh;
          background:linear-gradient(180deg,var(--navy) 0%,var(--navy-mid) 100%);
          display:flex;flex-direction:column;z-index:200;
          border-right:1px solid rgba(230,185,74,0.15);
        }
        #sidebar_brand { padding:24px 22px 18px;border-bottom:1px solid rgba(255,255,255,0.08);text-align:center; }
        #sidebar_logo { height:42px;width:auto;object-fit:contain; }
        #sidebar_brand p { font-size:0.72rem;color:rgba(255,255,255,0.45);margin-top:4px;text-transform:uppercase;letter-spacing:1.2px;font-weight:600; }
        #sidebar_nav { flex:1;padding:14px 10px;display:flex;flex-direction:column;gap:2px;overflow-y:auto; }
        #sidebar_bottom { padding:10px;border-top:1px solid rgba(255,255,255,0.08);display:flex;flex-direction:column;gap:2px; }
        .nav-item { display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:9px;text-decoration:none;color:rgba(255,255,255,0.6);font-size:0.85rem;font-weight:500;transition:all 0.18s; }
        .nav-item:hover { background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.9); }
        .nav-item.active { background:rgba(230,185,74,0.15);color:var(--gold);font-weight:600; }
        .nav-icon { width:20px;text-align:center;font-size:0.85rem;flex-shrink:0; }
        .nav-label { white-space:nowrap; }
        .nav-logout { color:rgba(255,255,255,0.4); }
        .nav-logout:hover { color:#ff6b6b;background:rgba(255,80,80,0.1); }
        #admin_content { margin-left:240px;flex:1;padding:32px 36px;min-height:100vh; }
        .page-title { font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--navy);margin-bottom:6px; }
        .page-subtitle { font-size:0.9rem;color:var(--text-muted);margin-bottom:28px; }
        .stat-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:32px; }
        .stat-card { background:var(--white);border-radius:var(--radius);padding:22px 24px;box-shadow:var(--shadow-sm);border:1px solid rgba(42,82,160,0.06); }
        .stat-card .stat-number { font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--navy); }
        .stat-card .stat-label { font-size:0.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-top:4px;font-weight:600; }
        .stat-card.gold .stat-number { color:var(--gold); }
        .stat-card.blue .stat-number { color:var(--blue); }
        .stat-card.green .stat-number { color:var(--success); }
        .data-table { width:100%;border-collapse:collapse;background:var(--white);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid rgba(42,82,160,0.06); }
        .data-table thead { background:var(--navy); }
        .data-table th { padding:13px 16px;text-align:left;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.8);text-transform:uppercase;letter-spacing:0.5px; }
        .data-table td { padding:12px 16px;font-size:0.85rem;border-bottom:1px solid rgba(42,82,160,0.06); }
        .data-table tbody tr:hover { background:rgba(42,82,160,0.03); }
        .data-table tbody tr:last-child td { border-bottom:none; }
        .badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;letter-spacing:0.5px;text-transform:uppercase; }
        .badge-open { background:rgba(34,139,34,0.1);color:var(--success); }
        .badge-closed { background:rgba(120,120,120,0.12);color:#5a5a5a; }
        .badge-pending { background:rgba(230,185,74,0.15);color:#8a6000; }
        .badge-accepted { background:rgba(34,139,34,0.1);color:var(--success); }
        .badge-rejected { background:rgba(217,64,64,0.1);color:var(--danger); }
        .badge-info { background:rgba(42,82,160,0.1);color:var(--blue); }
        .btn { display:inline-block;padding:8px 18px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all 0.18s; }
        .btn-primary { background:var(--blue);color:var(--white); }
        .btn-primary:hover { background:var(--navy-mid); }
        .btn-gold { background:var(--gold);color:var(--navy); }
        .btn-gold:hover { background:var(--gold-light); }
        .btn-danger { background:transparent;color:var(--danger);border:1.5px solid rgba(217,64,64,0.25); }
        .btn-danger:hover { background:rgba(217,64,64,0.08);border-color:rgba(217,64,64,0.45); }
        .btn-sm { padding:6px 14px;font-size:0.75rem; }
        .btn-row { display:flex;gap:8px;flex-wrap:wrap; }
        .empty-state { text-align:center;padding:48px 20px;color:var(--text-muted); }
        .form-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px 24px; }
        .form-group { display:flex;flex-direction:column;gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { font-size:0.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.4px; }
        .form-group input,.form-group select,.form-group textarea {
          padding:10px 14px;border:1.5px solid rgba(42,82,160,0.16);border-radius:9px;
          font-family:'DM Sans',sans-serif;font-size:0.9rem;color:var(--text);background:var(--bg);outline:none;transition:border-color 0.18s,box-shadow 0.18s;
        }
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus { border-color:var(--blue);background:var(--white);box-shadow:0 0 0 3px rgba(42,82,160,0.1); }
        .form-group textarea { resize:vertical;min-height:100px; }
        .form-group select { appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%235a6278' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px; }
        .form-card { background:var(--white);border-radius:var(--radius);padding:28px 32px;box-shadow:var(--shadow-sm);border:1px solid rgba(42,82,160,0.06); }
        .form-actions { grid-column:1/-1;display:flex;gap:12px;margin-top:8px; }
        .link-section { border:1px solid rgba(42,82,160,0.1);border-radius:9px;padding:16px 20px;margin-top:4px; }
        .link-section h4 { font-size:0.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:10px;font-weight:600; }
        .link-row,.ext-link-row { display:grid;grid-template-columns:1fr 2fr auto;gap:8px;margin-bottom:8px;align-items:center; }
        .link-row input,.ext-link-row input { padding:8px 12px;font-size:0.85rem; }
        .msg { padding:12px 18px;border-radius:9px;font-size:0.85rem;font-weight:500;margin-bottom:20px; }
        .msg-success { background:rgba(34,139,34,0.1);color:var(--success);border:1px solid rgba(34,139,34,0.2); }
        .msg-error { background:rgba(217,64,64,0.1);color:var(--danger);border:1px solid rgba(217,64,64,0.2); }
        .checkbox-label { display:flex!important;align-items:center;gap:8px;text-transform:none!important;font-size:0.9rem!important;color:var(--text)!important;cursor:pointer; }
        .checkbox-label input[type="checkbox"] { width:18px;height:18px;accent-color:var(--blue); }

        /* ── Custom Confirm Popup ── */
        #confirm_overlay {
          position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,32,68,0.45);
          z-index:9999;display:flex;align-items:center;justify-content:center;
          animation:fadeInPopup 0.15s ease;
        }
        @keyframes fadeInPopup { from{opacity:0;} to{opacity:1;} }
        #confirm_box {
          background:var(--white);border-radius:var(--radius);padding:28px 32px;
          max-width:400px;width:90%;box-shadow:0 12px 40px rgba(15,32,68,0.25);
          text-align:center;
        }
        #confirm_box p { font-size:0.95rem;color:var(--text);margin-bottom:22px;line-height:1.6; }
        #confirm_btns { display:flex;gap:10px;justify-content:center; }
        #confirm_yes { padding:9px 28px;background:var(--danger);color:var(--white);border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:600;cursor:pointer;transition:background 0.18s; }
        #confirm_yes:hover { background:#b52e2e; }
        #confirm_no { padding:9px 28px;background:transparent;color:var(--text-muted);border:1.5px solid rgba(42,82,160,0.2);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.18s; }
        #confirm_no:hover { border-color:var(--navy);color:var(--navy); }
      </style>
      <script>
        function confirmPopup(message, callback) {
          var existing = document.getElementById('confirm_overlay');
          if (existing) existing.remove();
          var overlay = document.createElement('div');
          overlay.id = 'confirm_overlay';
          overlay.innerHTML = '<div id="confirm_box"><p>' + message + '</p><div id="confirm_btns"><button id="confirm_yes">Ya, Lanjutkan</button><button id="confirm_no">Batal</button></div></div>';
          document.body.appendChild(overlay);
          document.getElementById('confirm_yes').onclick = function() { overlay.remove(); if (callback) callback(); };
          document.getElementById('confirm_no').onclick = function() { overlay.remove(); };
          overlay.addEventListener('click', function(e) { if (e.target === overlay) overlay.remove(); });
        }
        document.addEventListener('click', function(e) {
          var el = e.target.closest('[data-confirm]');
          if (el) { e.preventDefault(); confirmPopup(el.getAttribute('data-confirm'), function() { location.href = el.href; }); }
        });
      </script>
    HTML;
  }
?>