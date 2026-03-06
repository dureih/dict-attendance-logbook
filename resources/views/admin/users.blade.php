@extends('layouts.app')
@section('title', 'Admin Accounts — DICT')

@section('content')

<div class="nav-tabs">
  <a href="{{ route('admin.dashboard') }}" class="nav-tab">📋 Records</a>
  <a href="{{ route('admin.users.index') }}" class="nav-tab active">👥 Admin Accounts</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

  {{-- ADD NEW ADMIN --}}
  <div class="card">
    <div class="card-header">
      <h2>➕ Add New Admin Account</h2>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="form-group">
          <label class="form-label">Full Name <span class="req">*</span></label>
          <input type="text" name="name" class="form-input @error('name') error @enderror"
            placeholder="e.g. Juan Dela Cruz" value="{{ old('name') }}">
          @error('name') <p class="error-msg">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Email Address <span class="req">*</span></label>
          <input type="email" name="email" class="form-input @error('email') error @enderror"
            placeholder="e.g. juan@dict.gov.ph" value="{{ old('email') }}">
          @error('email') <p class="error-msg">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Role <span class="req">*</span></label>
          <select name="role" class="form-select">
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Password <span class="req">*</span></label>
          <input type="password" name="password" class="form-input @error('password') error @enderror"
            placeholder="Minimum 8 characters">
          @error('password') <p class="error-msg">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password <span class="req">*</span></label>
          <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password">
        </div>
        <div class="submit-row">
          <button type="submit" class="btn btn-primary">Create Account</button>
        </div>
      </form>
    </div>
  </div>

  {{-- EXISTING ADMINS --}}
  <div class="card">
    <div class="card-header card-header-red">
      <h2>👥 Existing Admin Accounts</h2>
    </div>
    <div class="card-body" style="padding:0;">
      <table style="width:100%;border-collapse:collapse;font-size:.87rem;">
        <thead>
          <tr style="background:var(--gray-100);">
            <th style="padding:12px 16px;text-align:left;font-size:.75rem;color:var(--blue);text-transform:uppercase;letter-spacing:.06em;">Name</th>
            <th style="padding:12px 16px;text-align:left;font-size:.75rem;color:var(--blue);text-transform:uppercase;letter-spacing:.06em;">Role</th>
            <th style="padding:12px 16px;text-align:left;font-size:.75rem;color:var(--blue);text-transform:uppercase;letter-spacing:.06em;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr style="border-bottom:1px solid var(--gray-100);">
            <td style="padding:12px 16px;">
              <strong>{{ $user->name }}</strong>
              <br><small style="color:var(--gray-400);">{{ $user->email }}</small>
            </td>
            <td style="padding:12px 16px;">
              <span class="badge {{ $user->role === 'superadmin' ? 'badge-superadmin' : 'badge-admin' }}">
                {{ ucfirst($user->role) }}
              </span>
            </td>
            <td style="padding:12px 16px;">
              @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm('Delete this admin account?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-danger">Delete</button>
                </form>
              @else
                <span style="font-size:.75rem;color:var(--gray-400);">You</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
