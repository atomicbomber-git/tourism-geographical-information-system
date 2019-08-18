<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Point;

class AnalysisController extends Controller
{
    public function result()
    {
        /*
            Melakukan validasi terhadap data input untuk memastikan bahwa semua data tersebut tersedia
            dalam bentuk bilangan
        */
        $data = $this->validate(request(), [
            'visitor_count' => 'required|numeric',
            'fee' => 'required|numeric',
            'facility_count' => 'required|numeric',
        ]);

        $aspects = [
            'visitor_count' => 'J. Pengunjung',
            'fee' => 'Harga',
            'facility_count' => 'J. Fasilitas'
        ];

        $aspect_comparisons = collect();
        $aspect_comparison_sums = collect();

        /*
            Menghitung rasio nilai aspek di dalam tabel dengan membagi setiap
            nilai aspek dengan pasangannya
        */
        foreach ($data as $key_h => $value_h) {
            $aspect_comparisons[$key_h] = collect(); // Mulai dengan data kosong
            foreach ($data as $key_r => $value_r) {
                $ratio = $value_r / $value_h; // Menghitung nilai perbandingan prioritas aspek
                $aspect_comparisons[$key_h][$key_r] = $ratio; // Memasukkan nilai yang telah dihitung ke dalam array $aspect_comparisons
                $aspect_comparison_sums[$key_r] = ($aspect_comparison_sums[$key_r] ?? 0 /* Jika total masih belum ada, mulai dari nol, jika sudah ada, pakai total sebelumnya */)
                    + $ratio; /* Tambah total dengan nilai rasio */
            }
        }

        /*
            Melakukan proses normalisasi nilai aspek dengan menjumlahkan setiap baris
            dari nilai rasio yang dihitung pada tahap sebelumnya dan kemudain membagi
            setiap nilai rasio tersebut dengan hasil penjumlahan mereka masing-masing
        */
        $normalized_aspect_comparisons = $aspect_comparisons
            ->map(function($aspect_comparison) use($aspect_comparison_sums) {
                return $aspect_comparison->map(function($value, $key) use($aspect_comparison_sums) {
                    return $value / $aspect_comparison_sums[$key]; /* Bagi semua nilai dengan nilai total untuk setiap aspek */
                });
            });
        $aspect_priorities = $normalized_aspect_comparisons->map(function ($priorities) {
            return $priorities->average(); /* Rata-tatakan setiap nilai prioritas untu setiap aspek */
        });

        /* Menarik data seluruh lokasi wisata dari database */
        $points = Point::query()
            ->isSite()
            ->select('id', 'name')
            ->with('site:id,point_id,fee,visitor_count,facility_count')
            ->get()
            ->keyBy('id');

        foreach ($points as $point) {
            /*
                Kedua baris dibawah ini bertujuan untuk membalik nilai harga;
                Contoh untuk harga tiket masuk 1000, maka nilai dibalik (diinverskan) menjadi 1 / 1000
                Hal ini dilakukan karena nilai tiket masuk prioritasnya harus lebih rendah seiring naiknya harga
            */
            $point->site["fee_original"] = $point->site["fee"];
            $point->site["fee"] = 1 / ($point->site["fee"] != 0 ? $point->site["fee"] : 1);
        }

        /* Melakukan perhitungan nilai perbandingan lokal untuk setiap aspek */
        $local_comparisons = collect(); // Mulai dengan data kosong
        $local_comparison_sums = collect(); // Mulai dengan data kosong

        foreach ($aspects as $aspect_key => $aspect_name) {

            $data = collect(); // Mulai dengan data kosong
            $sums = collect(); // Mulai dengan data kosong

            foreach ($points as $point /* Point -> Titik ini */ ) { /* Lakukan untuk setiap titik wisata yang ada: */
                $comparisons = collect();

                foreach ($points as $another_point /* Another point -> Titik lain untuk dibandingkan */) {
                    $another_site_val = ($another_point->site->$aspect_key != 0 ? $another_point->site->$aspect_key : 1);
                    $comparison = $point->site->$aspect_key / $another_site_val; /* Hitung nilai rasio perbandingan untuk setiap situs selain situs ini */
                    $comparisons->put($another_point->id, $comparison);
                    $sums->put($another_point->id, $comparison + ($sums[$another_point->id] ?? 0)); /* Menghitung nilai total untuk data pada akhir baris */
                }

                $data->put($point->id, $comparisons);
            }

            $local_comparisons->put($aspect_key, $data);
            $local_comparison_sums->put($aspect_key, $sums);
        }

         /* Melakukan perhitungan nilai prioritas lokal untuk setiap aspek */
        $local_priorities = collect();

        foreach ($local_comparisons as $aspect_key => $comparison_list) {
            $data = collect();

            foreach ($comparison_list as $point_id => $comparisons) {
                $comparison_data = collect();

                foreach ($comparisons as $c_point_id => $comparison) {
                    /* Menghitung nilai perbandingan untuk untuk setiap data perbadingan lokal yang telah dihitung sebelumnya */
                    $comparison_data->put($c_point_id, $comparison / $local_comparison_sums[$aspect_key][$c_point_id]);
                }

                $data->put($point_id, $comparison_data);
            }

            $local_priorities->put($aspect_key, $data);
        }

        /* Menggabungkan seluruh data prioritas lokal pada setiap aspek ke dalam satu tabel, dan menghitung nilai akhir */
        $overall_priorities = collect();
        foreach ($points as $point) {
            $record = collect();
            foreach ($aspects as $aspect_key => $aspect_name) { /* Lakukan operasi untuk setiap aspek */
                $record->put($aspect_key,
                    /* Kalikan setiap nilai rata-rata pada prioritas lokal dengan nilai prioritas aspek */
                    $local_priorities[$aspect_key][$point->id]->average()
                    * $aspect_priorities[$aspect_key]
                );
            }
            /* Mentotalkan nilai untuk setiap baris */
            $record["sum"] = $record->sum();
            $overall_priorities[$point->id] = $record;
        }
        $overall_priorities = $overall_priorities->sortByDesc("sum"); /* Mengurutkan data dari yang prioritasnya paling tinggi hingga yang paling rendah */

        return view(
            'site-analysis.result',
            compact('points', 'overall_priorities', 'aspects', 'local_comparisons', 'local_comparison_sums', 'local_priorities', 'aspect_comparisons', 'aspect_comparison_sums', 'normalized_aspect_comparisons', 'aspect_priorities')
        );
    }

    public function create()
    {
        /* Menampilkan halaman formulir */
        $aspects = [
            'fee' => 'Harga',
            'visitor_count' => 'J. Pengunjung',
            'facility_count' => 'J. Fasilitas'
        ];

        return view('site-analysis.create', compact('aspects'));
    }
}
