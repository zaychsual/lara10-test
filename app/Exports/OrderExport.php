<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $exportData = collect([]);

        /**
         * SET HEADER
         */
        $header = [
            'Nomor Invoice',
            'Nama Produk',
            'Jumlah Pemesanan',
            'Harga Jual',
            'Total Harga',
            'Tanggal Pembelian',
            'Status Order'
        ];

        $exportData->push($header);

        $i = 1;
        foreach ($this->data as $row) {
            $exportData->push([
                $row->order['no_inv'] ?? '',
                $row->product['title'] ?? '',
                $row->qty ?? 0,
                formatIdr($row->price) ?? 0,
                formatIdr($row->total_price) ?? 0,
                Carbon::parse($row->created_at)->format('d-m-y'),
                ucwords($row->order['status']),
            ]);

            $i++;
        }

        //return data
        return $exportData;
    }
}
