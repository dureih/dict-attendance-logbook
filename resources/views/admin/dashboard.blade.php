@extends('layouts.app')
@section('title', 'Admin Dashboard — DICT')

@section('content')

{{-- STATS --}}
<div class="stats-row">
  <div class="stat-card">
    <div class="stat-icon blue">👥</div>
    <div class="stat-info"><p>Total</p><strong>{{ $totalCount }}</strong></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue">♂</div>
    <div class="stat-info"><p>Male</p><strong>{{ $maleCount }}</strong></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon red">♀</div>
    <div class="stat-info"><p>Female</p><strong>{{ $femaleCount }}</strong></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon yellow">📍</div>
    <div class="stat-info"><p>Municipalities</p><strong>{{ $municipalityCount }}</strong></div>
  </div>
</div>

{{-- RECORDS CARD --}}
<div class="card">
  <div class="card-header card-header-red">
    <h2>
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      Records Database
    </h2>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('admin.export.excel', request()->query()) }}" class="btn btn-green btn-sm">
        📊 Export Excel
      </a>
      <a href="{{ route('admin.export.pdf', request()->query()) }}" class="btn btn-sm" style="background:#dc2626;color:#fff;">
        📄 Export PDF
      </a>
    </div>
  </div>
  <div class="card-body">

    {{-- FILTER FORM --}}
    <form method="GET" action="{{ route('admin.dashboard') }}">
      <div class="filter-bar">
        <span style="font-size:.78rem;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;">🔍 Filter:</span>
        <div class="filter-wrap">
          <span class="search-icon">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          </span>
          <input type="text" name="search" class="filter-input"
            placeholder="Search name, address, contact..."
            value="{{ request('search') }}">
        </div>
        <select name="gender" class="filter-select" onchange="this.form.submit()">
          <option value="">All Genders</option>
          @foreach(['Male','Female','Prefer not to say'] as $g)
            <option value="{{ $g }}" {{ request('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
          @endforeach
        </select>
        <select name="municipality" class="filter-select" onchange="this.form.submit()">
          <option value="">All Municipalities</option>
          @foreach($municipalities as $m)
            <option value="{{ $m }}" {{ request('municipality') == $m ? 'selected' : '' }}>{{ $m }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-blue btn-sm">Search</button>
        @if(request()->hasAny(['search','gender','municipality']))
          <a href="{{ route('admin.dashboard') }}" class="btn btn-sm" style="background:var(--gray-200);color:var(--gray-800);">Clear</a>
        @endif
        <span class="record-count">Showing <strong>{{ $visitors->count() }}</strong> of <strong>{{ $totalCount }}</strong> records</span>
      </div>
    </form>

    {{-- TABLE --}}
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Barangay</th>
            <th>Municipality</th>
            <th>Province</th>
            <th>Contact</th>
            <th>Date &amp; Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($visitors as $i => $visitor)
          <tr class="clickable-row" onclick="openModal({
            id: '{{ $visitor->id }}',
            num: '{{ str_pad($visitors->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}',
            full_name: '{{ addslashes($visitor->full_name) }}',
            age: '{{ $visitor->age }}',
            gender: '{{ $visitor->gender }}',
            barangay: '{{ addslashes($visitor->barangay) }}',
            municipality: '{{ addslashes($visitor->municipality) }}',
            province: '{{ addslashes($visitor->province) }}',
            contact: '{{ $visitor->contact_number }}',
            purpose: '{{ addslashes($visitor->purpose ?? '') }}',
            purpose_other: '{{ addslashes($visitor->purpose_other ?? '') }}',
            date: '{{ $visitor->created_at->format('M d, Y') }}',
            time: '{{ $visitor->created_at->format('h:i A') }}'
          })">
            <td><span class="entry-num">{{ str_pad($visitors->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}</span></td>
            <td><strong>{{ $visitor->full_name }}</strong></td>
            <td>{{ $visitor->age }}</td>
            <td>
              @php $gc = ['Male'=>'badge-male','Female'=>'badge-female','Prefer not to say'=>'badge-other']; @endphp
              <span class="badge {{ $gc[$visitor->gender] ?? 'badge-other' }}">{{ $visitor->gender }}</span>
            </td>
            <td>{{ $visitor->barangay }}</td>
            <td>{{ $visitor->municipality }}</td>
            <td>{{ $visitor->province }}</td>
            <td>{{ $visitor->contact_number }}</td>
            <td style="font-size:.78rem;color:var(--gray-600);">{{ $visitor->created_at->format('M d, Y h:i A') }}</td>
            <td onclick="event.stopPropagation()">
              <form method="POST" action="{{ route('admin.visitors.destroy', $visitor) }}"
                onsubmit="return confirm('Delete this record?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10">
              <div class="no-records">
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p>No records found.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:20px;">
      {{ $visitors->links() }}
    </div>

  </div>
</div>
{{-- VISITOR DETAIL MODAL --}}
<div id="visitorModal" class="modal-overlay" onclick="closeModal()">
  <div class="modal-box" onclick="event.stopPropagation()">

    {{-- Header --}}
    <div class="modal-header">
      <div class="modal-header-left">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span>Visitor Record</span>
        <span id="modalNum" class="modal-entry-num"></span>
      </div>
      <button class="modal-close" onclick="closeModal()">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    {{-- Body --}}
    <div class="modal-body">

      {{-- Full Name --}}
      <div class="modal-name-row">
        <div class="modal-avatar" id="modalAvatar"></div>
        <div>
          <div class="modal-full-name" id="modalFullName"></div>
          <div id="modalGenderBadge" style="margin-top:5px;"></div>
        </div>
      </div>

      <div class="modal-divider"></div>

      {{-- Info Grid --}}
      <div class="modal-grid">
        <div class="modal-field">
          <span class="modal-label">Age</span>
          <span class="modal-value" id="modalAge"></span>
        </div>
        <div class="modal-field">
          <span class="modal-label">Contact Number</span>
          <span class="modal-value" id="modalContact"></span>
        </div>
        <div class="modal-field" style="grid-column:1/-1;">
          <span class="modal-label">Address</span>
          <span class="modal-value" id="modalAddress"></span>
        </div>
        <div class="modal-field" style="grid-column:1/-1;">
          <span class="modal-label">Purpose of Visit</span>
          <span class="modal-value" id="modalPurpose"></span>
        </div>
      </div>

      <div class="modal-divider"></div>

      {{-- Date & Time --}}
      <div class="modal-datetime-row">
        <div class="modal-datetime-item">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          <span id="modalDate"></span>
        </div>
        <div class="modal-datetime-item">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          <span id="modalTime"></span>
        </div>
      </div>

    </div>

    {{-- Footer Export Buttons --}}
    <div class="modal-footer">
      <a id="modalExcelBtn" href="#" class="btn-modal-export btn-modal-excel">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h4a2 2 0 012 2v2"/></svg>
        Export Excel
      </a>
      <a id="modalPdfBtn" href="#" class="btn-modal-export btn-modal-pdf">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h4a2 2 0 012 2v2"/></svg>
        Export PDF
      </a>
    </div>

  </div>
</div>

<style>
/* ── CLICKABLE ROW ── */
.clickable-row { cursor: pointer; transition: background .15s; }
.clickable-row:hover { background: #EFF6FF !important; }

/* ── MODAL OVERLAY ── */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 1000;
  background: rgba(15, 23, 42, 0.45);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.modal-overlay.open { display: flex; animation: fadeIn .2s ease; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

/* ── MODAL BOX ── */
.modal-box {
  background: #fff;
  border-radius: 14px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 20px 60px rgba(0,0,0,.22);
  animation: slideUp .22s ease;
  overflow: hidden;
}
@keyframes slideUp { from { transform: translateY(28px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

/* ── MODAL HEADER ── */
.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  background: var(--blue, #2255a4);
}
.modal-header-left {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #fff;
  font-weight: 700;
  font-size: .95rem;
}
.modal-entry-num {
  background: rgba(255,255,255,.2);
  border-radius: 20px;
  padding: 2px 10px;
  font-size: .78rem;
  font-weight: 700;
  letter-spacing: .05em;
}
.modal-close {
  background: rgba(255,255,255,.15);
  border: none;
  border-radius: 7px;
  padding: 5px 7px;
  cursor: pointer;
  color: #fff;
  display: flex;
  align-items: center;
  transition: background .15s;
}
.modal-close:hover { background: rgba(255,255,255,.3); }

/* ── MODAL BODY ── */
.modal-body { padding: 22px 24px 24px; }
.modal-name-row { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
.modal-avatar {
  width: 54px; height: 54px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--blue, #2255a4), #60a5fa);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; font-weight: 800; color: #fff;
  flex-shrink: 0;
}
.modal-full-name { font-size: 1.1rem; font-weight: 800; color: var(--gray-800, #1e293b); }
.modal-divider { border: none; border-top: 1.5px solid #f1f5f9; margin: 16px 0; }
.modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.modal-field { display: flex; flex-direction: column; gap: 3px; }
.modal-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--gray-400, #94a3b8); }
.modal-value { font-size: .93rem; font-weight: 600; color: var(--gray-800, #1e293b); }
.modal-datetime-row { display: flex; gap: 20px; flex-wrap: wrap; }
.modal-datetime-item { display: flex; align-items: center; gap: 6px; font-size: .83rem; color: var(--gray-600, #475569); }
/* ── MODAL FOOTER ── */
.modal-footer {
  display: flex;
  gap: 10px;
  padding: 14px 24px;
  background: #f8fafc;
  border-top: 1.5px solid #f1f5f9;
}
.btn-modal-export {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 8px; font-size: .85rem;
  font-weight: 700; text-decoration: none; transition: all .15s;
  border: none; cursor: pointer;
}
.btn-modal-excel { background: #16a34a; color: #fff; }
.btn-modal-excel:hover { background: #15803d; }
.btn-modal-pdf   { background: #dc2626; color: #fff; }
.btn-modal-pdf:hover   { background: #b91c1c; }
</style>

<script>
function openModal(v) {
  const gc = { 'Male': 'badge-male', 'Female': 'badge-female', 'Prefer not to say': 'badge-other' };
  document.getElementById('modalNum').textContent = '#' + v.num;
  document.getElementById('modalFullName').textContent = v.full_name;
  document.getElementById('modalAvatar').textContent = v.full_name.charAt(0).toUpperCase();
  document.getElementById('modalAge').textContent = v.age + ' years old';
  document.getElementById('modalContact').textContent = v.contact || '—';
  document.getElementById('modalAddress').textContent = v.barangay + ', ' + v.municipality + ', ' + v.province;
  document.getElementById('modalPurpose').textContent = v.purpose === 'Others' && v.purpose_other ? 'Others — ' + v.purpose_other : (v.purpose || '—');
  document.getElementById('modalDate').textContent = v.date;
  document.getElementById('modalTime').textContent = v.time;
  const badge = document.getElementById('modalGenderBadge');
  badge.innerHTML = '<span class="badge ' + (gc[v.gender] || 'badge-other') + '">' + v.gender + '</span>';
  // Set per-visitor export URLs
  document.getElementById('modalExcelBtn').href = '/admin/export/excel/visitor/' + v.id;
  document.getElementById('modalPdfBtn').href   = '/admin/export/pdf/visitor/'   + v.id;
  document.getElementById('visitorModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeModal() {
  document.getElementById('visitorModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection
