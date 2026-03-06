<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VisitorsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Visitor::query();

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('last_name',      'like', "%$s%")
                  ->orWhere('first_name',   'like', "%$s%")
                  ->orWhere('barangay',     'like', "%$s%")
                  ->orWhere('municipality', 'like', "%$s%")
                  ->orWhere('contact_number','like',"%$s%");
            });
        }
        if (!empty($this->filters['gender']))
            $query->where('gender', $this->filters['gender']);
        if (!empty($this->filters['municipality']))
            $query->where('municipality', $this->filters['municipality']);

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            '#',
            'Full Name',
            'Age',
            'Gender',
            'Barangay',
            'Municipality',
            'Province',
            'Contact Number',
            'Date & Time Logged',
        ];
    }

    public function map($visitor): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $visitor->full_name,
            $visitor->age,
            $visitor->gender,
            $visitor->barangay,
            $visitor->municipality,
            $visitor->province,
            $visitor->contact_number,
            $visitor->created_at->format('M d, Y h:i A'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1a3a6b']],
            ],
        ];
    }
}
