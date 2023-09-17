<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use App\Models\Cita;

class VisitarExcel implements FromView, WithHeadings, WithEvents
{
    protected $headings; // Variable para los encabezados
    protected $data; // Variable para los datos

    public function __construct($headings, $data)
    {
        $this->headings = $headings;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('visitas-excel', [
            'headings' => $this->headings,
            'data' => $this->data,
        ]);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->setAutoFilter('A1:I1'); // Establece el rango de filtro
                foreach (range('A', 'I') as $column) {
                  $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}

