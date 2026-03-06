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
          <tr>
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
            <td>
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
@endsection
