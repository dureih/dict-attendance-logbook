<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DICT Attendance Log — {{ now()->format('M d, Y') }}</title>
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #2c3250; margin: 0; padding: 20px; }
  .header { display: flex; align-items: center; margin-bottom: 16px; border-bottom: 3px solid #1a3a6b; padding-bottom: 12px; }
  .header-text h1 { font-size: 16px; color: #1a3a6b; margin: 0 0 2px; }
  .header-text p { font-size: 9px; color: #5a6480; margin: 0; }
  .meta { font-size: 9px; color: #5a6480; margin-bottom: 12px; }
  .accent { height: 4px; background: linear-gradient(to right, #C0392B, #f0b429, #3b7dd8); margin-bottom: 14px; border-radius: 2px; }
  table { width: 100%; border-collapse: collapse; }
  thead tr { background-color: #1a3a6b; }
  thead th { color: white; padding: 7px 8px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: .05em; }
  tbody tr:nth-child(even) { background-color: #f7f8fc; }
  tbody tr { border-bottom: 1px solid #dde1ec; }
  tbody td { padding: 6px 8px; vertical-align: middle; }
  .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 8px; font-weight: bold; }
  .male { background: #dbeafe; color: #1e40af; }
  .female { background: #fce7f3; color: #9d174d; }
  .other { background: #fef3c7; color: #92400e; }
  .footer { margin-top: 20px; font-size: 8px; color: #9ba5bf; text-align: center; border-top: 1px solid #dde1ec; padding-top: 10px; }
</style>
</head>
<body>

<div class="header">
  <div class="header-text">
    <h1>DICT Attendance Log Book</h1>
    <p>Department of Information and Communications Technology — San Jose, Antique, Philippines</p>
  </div>
</div>
<div class="accent"></div>

<div class="meta">
  Generated: {{ now()->format('F d, Y h:i A') }} &nbsp;·&nbsp; Total Records: {{ $visitors->count() }}
</div>

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
      <th>Date Logged</th>
    </tr>
  </thead>
  <tbody>
    @forelse($visitors as $i => $visitor)
    <tr>
      <td>{{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</td>
      <td><strong>{{ $visitor->full_name }}</strong></td>
      <td>{{ $visitor->age }}</td>
      <td>
        @php $gc = ['Male'=>'male','Female'=>'female','Prefer not to say'=>'other']; @endphp
        <span class="badge {{ $gc[$visitor->gender] ?? 'other' }}">{{ $visitor->gender }}</span>
      </td>
      <td>{{ $visitor->barangay }}</td>
      <td>{{ $visitor->municipality }}</td>
      <td>{{ $visitor->province }}</td>
      <td>{{ $visitor->contact_number }}</td>
      <td>{{ $visitor->created_at->format('M d, Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;padding:20px;color:#9ba5bf;">No records found.</td></tr>
    @endforelse
  </tbody>
</table>

<div class="footer">
  DICT Attendance Log Book &nbsp;·&nbsp; San Jose, Antique &nbsp;·&nbsp; Printed {{ now()->format('F d, Y') }}
</div>

</body>
</html>
