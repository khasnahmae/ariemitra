<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Dummy Destinasi Wisata
        $destinasiData = [
            [
                'nama_destinasi' => 'Celosia',
                'lokasi'         => 'Semarang',
                'htm_per_orang'  => 35000,
            ],
            [
                'nama_destinasi' => 'Muncul Fun Tubing',
                'lokasi'         => 'Semarang',
                'htm_per_orang'  => 50000,
            ],
            [
                'nama_destinasi' => 'Tropikana Waterpark',
                'lokasi'         => 'Cirebon',
                'htm_per_orang'  => 50000,
            ],
        ];

        foreach ($destinasiData as $item) {
            DB::table('destinasi')->updateOrInsert(
                [
                    'nama_destinasi' => $item['nama_destinasi'],
                    'lokasi'         => $item['lokasi'],
                ],
                [
                    'uuid'           => (string) Str::uuid(),
                    'htm_per_orang'  => $item['htm_per_orang'],
                    'keterangan'     => null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }

        // 2. Data Dummy Jenis Armada
        $armadaData = [
            [
                'jenis_armada'   => 'Lucky Trans',
                'kapasitas'      => 35,
                'sewa_inc_solar' => 3000000, // 3jt
            ],
            [
                'jenis_armada'   => 'Nariyama Trans',
                'kapasitas'      => 39,
                'sewa_inc_solar' => 3000000, // 3jt
            ],
            [
                'jenis_armada'   => 'Nariyama Trans',
                'kapasitas'      => 50,
                'sewa_inc_solar' => 4200000, // 4,2jt
            ],
            [
                'jenis_armada'   => 'Pangeran',
                'kapasitas'      => 37,
                'sewa_inc_solar' => 3000000, // 3jt
            ],
            [
                'jenis_armada'   => 'Pangeran',
                'kapasitas'      => 50,
                'sewa_inc_solar' => 4200000, // 4,2jt
            ],
        ];

        foreach ($armadaData as $item) {
            DB::table('armada')->updateOrInsert(
                [
                    'jenis_armada' => $item['jenis_armada'],
                    'kapasitas'    => $item['kapasitas'],
                ],
                [
                    'uuid'           => (string) Str::uuid(),
                    'sewa_inc_solar' => $item['sewa_inc_solar'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }

        // 3. Data Dummy Tarif Rute Tol
        $ruteTolData = [
            [
                'nama_rute'   => 'Pemalang - Bawen',
                'tarif_total' => 450000, // 450k
            ],
            [
                'nama_rute'   => 'Pemalang - Cirebon',
                'tarif_total' => 450000, // 450k
            ],
            [
                'nama_rute'   => 'Pemalang - Yogya',
                'tarif_total' => 700000, // 700k
            ],
        ];

        foreach ($ruteTolData as $item) {
            DB::table('rute_tol')->updateOrInsert(
                [
                    'nama_rute'   => $item['nama_rute'],
                ],
                [
                    'uuid'        => (string) Str::uuid(),
                    'tarif_total' => $item['tarif_total'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}
