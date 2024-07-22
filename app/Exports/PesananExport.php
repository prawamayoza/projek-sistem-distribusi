<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PesananExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pesanan::with('pelanggan')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Pelanggan',
            'Alamat Pelanggan',
            'Tanggal Pesanan',
            'Total Pesanan',
        ];
    }

    /**
     * @param mixed $pesanan
     *
     * @return array
     */
    public function map($pesanan): array
    {
        static $id = 1;

        return [
            $id++,
            $pesanan->pelanggan->name,
            $pesanan->pelanggan->alamat,
            $pesanan->tanggal,
            $pesanan->total,
        ];
    }
}