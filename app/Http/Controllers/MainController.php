<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pekerjaan;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index() {
        // Data untuk chart gender
        $genderData = Pegawai::select('jenis_kelamin', DB::raw('count(*) as count'))
            ->groupBy('jenis_kelamin')
            ->get()
            ->pluck('count', 'jenis_kelamin')
            ->toArray();

        $genderLabels = [];
        $genderCounts = [];
        if (isset($genderData['L'])) {
            $genderLabels[] = 'Laki-laki';
            $genderCounts[] = $genderData['L'];
        }
        if (isset($genderData['P'])) {
            $genderLabels[] = 'Perempuan';
            $genderCounts[] = $genderData['P'];
        }

        // Data untuk chart top 5 pekerjaan
        $topJobs = Pekerjaan::withCount('pegawai')
            ->orderBy('pegawai_count', 'desc')
            ->take(5)
            ->get();

        $jobLabels = $topJobs->pluck('nama')->toArray();
        $jobCounts = $topJobs->pluck('pegawai_count')->toArray();

        return view('index', compact('genderLabels', 'genderCounts', 'jobLabels', 'jobCounts'));
    }
}
