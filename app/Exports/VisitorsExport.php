<?php

namespace App\Exports;

use App\Models\Visitor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitorsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected array $filters;
    protected ?Collection $rows;

    public function __construct(array $filters = [], ?Collection $rows = null)
    {
        $this->filters = $filters;
        $this->rows    = $rows;
    }

    public function collection(): Collection
    {
        // If a pre-built collection was passed (single visitor), use it directly
        if ($this->rows !== null) {
            return $this->rows->map(fn($v) => $this->mapRow($v));
        }

        $query = Visitor::query();

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('last_name',       'like', "%$s%")
                  ->orWhere('first_name',    'like', "%$s%")
                  ->orWhere('barangay',      'like', "%$s%")
                  ->orWhere('municipality',  'like', "%$s%")
                  ->orWhere('contact_number','like', "%$s%");
            });
        }
        if (!empty($this->filters['gender']))
            $query->where('gender', $this->filters['gender']);
        if (!empty($this->filters['municipality']))
            $query->where('municipality', $this->filters['municipality']);

        return $query->latest()->get()->map(fn($v) => $this->mapRow($v));
    }

    private function mapRow(Visitor $v): array
    {
        return [
            $v->full_name,
            $v->age,
            $v->gender,
            $v->barangay,
            $v->municipality,
            $v->province,
            $v->contact_number,
            $v->purpose === 'Others' ? ('Others — ' . $v->purpose_other) : $v->purpose,
            $v->created_at->format('M d, Y h:i A'),
        ];
    }

    public function headings(): array
    {
        return ['Full Name','Age','Gender','Barangay','Municipality','Province','Contact','Purpose of Visit','Date & Time'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2255A4']]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A'=>30,'B'=>6,'C'=>10,'D'=>20,'E'=>22,'F'=>18,'G'=>16,'H'=>35,'I'=>22];
    }
}
