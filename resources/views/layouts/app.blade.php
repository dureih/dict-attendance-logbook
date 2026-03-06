<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'DICT Attendance Log Book')</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Playfair+Display:wght@600;700&family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
  :root {
    --red:#C0392B; --red-dark:#962d22; --red-light:#e74c3c;
    --blue:#1a3a6b; --blue-mid:#2255a4; --blue-light:#3b7dd8;
    --yellow:#f0b429; --yellow-light:#fcd462;
    --white:#ffffff; --off-white:#f7f8fc;
    --gray-100:#eef0f5; --gray-200:#dde1ec;
    --gray-400:#9ba5bf; --gray-600:#5a6480; --gray-800:#2c3250;
    --shadow-sm:0 2px 8px rgba(26,58,107,.08);
    --shadow-md:0 4px 20px rgba(26,58,107,.13);
    --shadow-lg:0 8px 40px rgba(26,58,107,.18);
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'Source Sans 3',sans-serif;background:var(--off-white);color:var(--gray-800);min-height:100vh;}

  /* HEADER */
  .site-header{background:linear-gradient(135deg,var(--blue) 0%,var(--blue-mid) 60%,var(--red) 100%);padding:0;position:relative;overflow:hidden;}
  .site-header::before{content:'';position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:var(--yellow);opacity:.08;border-radius:50%;}
  .header-inner{display:flex;align-items:center;justify-content:space-between;padding:18px 40px;max-width:1400px;margin:0 auto;position:relative;z-index:1;}
  .header-logo{display:flex;align-items:center;gap:16px;}
  .logo-emblem{width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 3px var(--yellow);flex-shrink:0;overflow:hidden;}
  .logo-emblem img{width:56px;height:56px;object-fit:contain;border-radius:50%;}
  .logo-text h1{font-family:'Bebas Neue',sans-serif;font-size:1.55rem;color:var(--white);line-height:1.1;letter-spacing:.03em;}
  .logo-text p{font-size:.78rem;color:var(--yellow-light);letter-spacing:.1em;text-transform:uppercase;font-weight:600;margin-top:2px;}
  .header-right{display:flex;align-items:center;gap:12px;}
  .date-display{text-align:right;color:rgba(255,255,255,.75);font-size:.82rem;line-height:1.5;}
  .date-display strong{color:var(--yellow-light);display:block;font-size:.95rem;}
  .btn-header{background:var(--yellow);color:var(--blue);border:none;padding:10px 22px;border-radius:6px;font-family:'Source Sans 3',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;letter-spacing:.04em;text-transform:uppercase;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.15);display:inline-flex;align-items:center;gap:7px;text-decoration:none;}
  .btn-header:hover{background:var(--yellow-light);transform:translateY(-1px);}
  .btn-header.outline{background:transparent;color:var(--white);border:1px solid rgba(255,255,255,.4);}
  .btn-header.outline:hover{background:rgba(255,255,255,.1);}
  .accent-bar{height:5px;background:linear-gradient(90deg,var(--red) 0%,var(--yellow) 40%,var(--blue-light) 100%);}

  /* ALERTS */
  .alert{padding:12px 18px;border-radius:8px;margin-bottom:20px;font-size:.92rem;font-weight:600;display:flex;align-items:center;gap:10px;}
  .alert-success{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;}
  .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}

  /* MAIN */
  .main-container{max-width:1100px;margin:36px auto;padding:0 24px;}
  .section-title{font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--blue);margin-bottom:20px;display:flex;align-items:center;gap:10px;}
  .section-title::after{content:'';flex:1;height:2px;background:linear-gradient(90deg,var(--blue-light),transparent);border-radius:2px;}
  .title-dot{width:10px;height:10px;background:var(--yellow);border-radius:50%;flex-shrink:0;}

  /* CARDS */
  .card{background:var(--white);border-radius:12px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:32px;border:1px solid var(--gray-200);}
  .card-header{background:linear-gradient(90deg,var(--blue) 0%,var(--blue-mid) 100%);padding:16px 28px;display:flex;align-items:center;justify-content:space-between;}
  .card-header h2{color:var(--white);font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;display:flex;align-items:center;gap:10px;}
  .card-header-red{background:linear-gradient(90deg,var(--red-dark) 0%,var(--red) 100%);}
  .card-body{padding:28px;}

  /* FORMS */
  .form-group{margin-bottom:20px;}
  .form-label{display:block;font-size:.78rem;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;}
  .req{color:var(--red);margin-left:2px;}
  .form-input,.form-select{width:100%;padding:10px 14px;border:1.5px solid var(--gray-200);border-radius:7px;font-family:'Source Sans 3',sans-serif;font-size:.93rem;color:var(--gray-800);background:var(--off-white);transition:all .2s;outline:none;}
  .form-input:focus,.form-select:focus{border-color:var(--blue-light);background:var(--white);box-shadow:0 0 0 3px rgba(59,125,216,.12);}
  .form-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%232255a4' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:32px;}
  .form-input.error{border-color:var(--red);}
  .error-msg{color:var(--red);font-size:.78rem;margin-top:4px;}
  .form-divider{border:none;border-top:1.5px solid var(--gray-100);margin:22px 0;}
  .cols-4{display:grid;grid-template-columns:2fr 2fr .8fr 1fr;gap:14px;}
  .cols-3{display:grid;grid-template-columns:1fr 1.2fr 1.2fr;gap:14px;}
  .cols-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
  @media(max-width:700px){.cols-4,.cols-3,.cols-2{grid-template-columns:1fr;}}
  .hint{color:var(--gray-400);font-size:.72rem;margin-top:3px;}

  /* BUTTONS */
  .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:7px;font-family:'Source Sans 3',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;letter-spacing:.04em;text-transform:uppercase;transition:all .2s;border:none;text-decoration:none;}
  .btn-primary{background:linear-gradient(135deg,var(--red),var(--red-light));color:var(--white);box-shadow:0 3px 12px rgba(192,57,43,.3);}
  .btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(192,57,43,.4);}
  .btn-blue{background:linear-gradient(135deg,var(--blue),var(--blue-mid));color:var(--white);}
  .btn-blue:hover{transform:translateY(-1px);}
  .btn-green{background:#059669;color:var(--white);}
  .btn-green:hover{background:#047857;}
  .btn-sm{padding:5px 12px;font-size:.78rem;}
  .btn-danger{background:none;border:1px solid #fca5a5;color:var(--red);padding:4px 10px;border-radius:5px;font-size:.75rem;font-weight:700;cursor:pointer;transition:all .15s;}
  .btn-danger:hover{background:var(--red);color:var(--white);border-color:var(--red);}
  .submit-row{display:flex;justify-content:flex-end;margin-top:8px;}

  /* STATS */
  .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
  @media(max-width:600px){.stats-row{grid-template-columns:1fr 1fr;}}
  .stat-card{background:var(--off-white);border:1px solid var(--gray-200);border-radius:9px;padding:14px 18px;display:flex;align-items:center;gap:12px;}
  .stat-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
  .stat-icon.blue{background:#dbeafe;} .stat-icon.red{background:#fee2e2;} .stat-icon.yellow{background:#fef3c7;} .stat-icon.green{background:#dcfce7;}
  .stat-info p{font-size:.74rem;color:var(--gray-600);text-transform:uppercase;letter-spacing:.06em;font-weight:600;}
  .stat-info strong{font-size:1.4rem;color:var(--blue);font-weight:700;line-height:1.1;}

  /* FILTER BAR */
  .filter-bar{display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;align-items:center;}
  .filter-wrap{position:relative;flex:1;min-width:200px;}
  .filter-wrap .search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);pointer-events:none;}
  .filter-input{width:100%;padding:10px 14px 10px 38px;border:1.5px solid var(--gray-200);border-radius:7px;font-family:'Source Sans 3',sans-serif;font-size:.93rem;outline:none;background:var(--off-white);}
  .filter-input:focus{border-color:var(--red-light);background:var(--white);}
  .filter-select{padding:10px 32px 10px 12px;border:1.5px solid var(--gray-200);border-radius:7px;font-family:'Source Sans 3',sans-serif;font-size:.88rem;background:var(--off-white);outline:none;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7' viewBox='0 0 10 7'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23C0392B' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;}
  .record-count{font-size:.82rem;color:var(--gray-600);margin-left:auto;white-space:nowrap;}
  .record-count strong{color:var(--blue);}

  /* TABLE */
  .table-wrap{overflow-x:auto;border-radius:8px;border:1px solid var(--gray-200);}
  table{width:100%;border-collapse:collapse;font-size:.87rem;min-width:760px;}
  thead tr{background:linear-gradient(90deg,var(--blue),var(--blue-mid));}
  thead th{color:var(--white);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.07em;padding:12px 14px;text-align:left;white-space:nowrap;}
  tbody tr{border-bottom:1px solid var(--gray-100);transition:background .15s;}
  tbody tr:hover{background:#eef4fc;}
  tbody td{padding:11px 14px;color:var(--gray-800);vertical-align:middle;}
  .badge{display:inline-block;padding:3px 10px;border-radius:12px;font-size:.75rem;font-weight:700;letter-spacing:.04em;}
  .badge-male{background:#dbeafe;color:#1e40af;}
  .badge-female{background:#fce7f3;color:#9d174d;}
  .badge-other{background:#fef3c7;color:#92400e;}
  .badge-admin{background:#dbeafe;color:#1e40af;}
  .badge-superadmin{background:#fef3c7;color:#92400e;}
  .entry-num{font-size:.75rem;font-weight:700;color:var(--gray-400);background:var(--gray-100);padding:3px 8px;border-radius:4px;font-family:monospace;}
  .no-records{text-align:center;padding:44px 20px;color:var(--gray-400);}

  /* NAV TABS */
  .nav-tabs{display:flex;gap:4px;margin-bottom:24px;}
  .nav-tab{padding:9px 20px;border-radius:7px 7px 0 0;font-size:.88rem;font-weight:700;text-decoration:none;color:var(--gray-600);background:var(--gray-100);border:1px solid var(--gray-200);border-bottom:none;transition:all .2s;}
  .nav-tab.active{background:var(--white);color:var(--blue);border-color:var(--gray-200);}

  /* FOOTER */
  .site-footer{background:var(--blue);color:rgba(255,255,255,.5);text-align:center;padding:18px 20px;font-size:.78rem;letter-spacing:.03em;margin-top:40px;}
  .site-footer strong{color:var(--yellow-light);}
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <div class="header-logo">
      <div class="logo-emblem">
        <img src="{{ asset('images/dict-logo.png') }}" alt="DICT Logo">
      </div>
      <div class="logo-text">
        <h1>DICT Attendance Log Book</h1>
        <p>Department of Information and Communications Technology</p>
      </div>
    </div>
    <div class="header-right">
      <div class="date-display">
        <strong id="liveDate">—</strong>
        <span id="liveTime">—</span>
      </div>
      @auth
        <a href="{{ route('admin.dashboard') }}" class="btn-header outline">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" class="btn-header outline">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn-header">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
          Admin Login
        </a>
      @endauth
    </div>
  </div>
</header>
<div class="accent-bar"></div>

<main class="main-container">
  @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error">❌ {{ session('error') }}</div>
  @endif

  @yield('content')
</main>

<footer class="site-footer">
  <strong>DICT Attendance Log Book</strong> &nbsp;·&nbsp; San Jose, Antique, Philippines &nbsp;·&nbsp; Department of Information and Communications Technology
</footer>

<script>
  function updateClock() {
    const now = new Date();
    const opts = { weekday:'short', year:'numeric', month:'short', day:'numeric' };
    document.getElementById('liveDate').textContent = now.toLocaleDateString('en-PH', opts);
    document.getElementById('liveTime').textContent = now.toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
  }
  setInterval(updateClock, 1000);
  updateClock();
</script>
</body>
</html>
