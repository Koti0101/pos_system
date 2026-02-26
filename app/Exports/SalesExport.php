<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Sale::with('user', 'items.product')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Sale ID',
            'Date & Time',
            'Cashier',
            'Total Amount',
            'Amount Paid',
            'Balance',
            'Payment Method',
            'Items (Qty x Product)'
        ];
    }

    public function map($sale): array
    {
        $itemsList = $sale->items->map(function ($item) {
            return $item->quantity . ' x ' . ($item->product->name ?? 'N/A');
        })->implode(', ');

        return [
            $sale->id,
            $sale->created_at->format('Y-m-d H:i:s'),
            $sale->user->name ?? 'Unknown',
            number_format($sale->total_amount, 2),
            number_format($sale->amount_paid, 2),
            number_format($sale->balance, 2),
            ucfirst($sale->payment_method),
            $itemsList,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}