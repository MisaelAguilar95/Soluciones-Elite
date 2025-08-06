<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class LoansExport implements FromCollection, WithHeadings, WithMapping
{
    protected $loans;

    // Recibir los préstamos cuando se crea la instancia
    public function __construct($loans)
    {
        $this->loans = $loans;
    }

    public function collection()
    {
        return $this->loans;
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Usuario',
            'Monto',
            'Interés',
            'Restante',
            'Estado',
            'Fecha Inicio',
            'Fecha Creación',
        ];
    }

    public function map($loan): array
    {
        $fechaInicio = $loan->fecha_inicio instanceof Carbon
            ? $loan->fecha_inicio
            : Carbon::parse($loan->fecha_inicio);

        $createdAt = $loan->created_at instanceof Carbon
            ? $loan->created_at
            : Carbon::parse($loan->created_at);

        return [
            $loan->client->nombre,
            $loan->client->user ? $loan->client->user->name : 'Sin usuario',
            $loan->monto,
            $loan->interes,
            $loan->restante,
            ucfirst($loan->estado),
            $fechaInicio->format('d/m/Y'),
            $createdAt->format('d/m/Y'),
        ];
    }
}
