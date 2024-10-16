<?php

namespace App\Http\Controllers\Emergency;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmergencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Emergency::groupBy('period', 'year')
                ->selectRaw('
                    period,
                    year,
                    SUM(kecelakaan) as kecelakaan,
                    SUM(kebakaran) as kebakaran,
                    SUM(ambulan_gratis) as ambulan_gratis,
                    SUM(pln) as pln,
                    SUM(mobil_jenazah) as mobil_jenazah,
                    SUM(penanganan_hewan) as penanganan_hewan,
                    SUM(keamanan) as keamanan,
                    SUM(kriminal) as kriminal,
                    SUM(bencana_alam) as bencana_alam,
                    SUM(kdrt) as kdrt,
                    SUM(gawat_darurat_lain) as gawat_darurat_lain
                ')
                ->get();

            return $this->success_json("Successfully get Emergency", $data);
        } catch (\Throwable $th) {
            return $this->error_json("Failed to get Emergency", $th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period'                => 'required',
            'period_date'           => 'required',
            'year'                  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error_json("Failed to create Emergency data", $validator->errors(), 400);
        }

        if (!$request->detail) {
            return $this->error_json("Failed to create emergency reports", "Need Detail Data", 400);
        }
        $collectionEmergencies = collect([]);

        foreach ($request->detail as $data) {
            $find = Emergency::where([
                ['period', '=', $request->period],
                ['year', '=', $request->year],
                ['district_id', '=', $data['district_id']],
            ])->first();

            if ($find) {
                return $this->error_json("Emergency Period is exist", $find, 422);
            }

            $collectionEmergencies->push([
                'period' => $request->period,
                'period_date' => $request->period_date,
                'year' => $request->year,
                'district_id' => $data['district_id'],
                'kecelakaan' => $data['kecelakaan'],
                'kebakaran' => $data['kebakaran'],
                'ambulan_gratis' => $data['ambulan_gratis'],
                'pln' => $data['pln'],
                'mobil_jenazah' => $data['mobil_jenazah'],
                'penanganan_hewan' => $data['penanganan_hewan'],
                'keamanan' => $data['keamanan'],
                'kriminal' => $data['kriminal'],
                'bencana_alam' => $data['bencana_alam'],
                'kdrt' => $data['kdrt'],
                'gawat_darurat_lain' => $data['gawat_darurat_lain'],
            ]);
        }

        try {
            $create = Emergency::insert($collectionEmergencies->toArray());

            if ($create) {
                return $this->success_json("Successfully create emergency data", $create);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Failed to create emergency data", $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = Emergency::with('district')
                ->where('id', $id)
                ->first();

            return $this->success_json("Successfully get Emergency", $data);
        } catch (\Throwable $th) {
            return $this->error_json("Failed to get Emergency", $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show_by_period($month_period, $year)
    {
        try {
            $data = Emergency::with('district')
                ->where([
                    ['period', '=', $month_period],
                    ['year', '=', $year],
                ])
                ->get();

            return $this->success_json("Successfully get Emergency", $data);
        } catch (\Throwable $th) {
            return $this->error_json("Failed to get Emergency", $th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $find = Emergency::where('id', $id)->first();

        if (!$find) {
            return $this->error_json("Emergency not found!", $find, 404);
        }

        $validator = Validator::make($request->all(), [
            'period'                => 'required',
            'year'                  => 'required|numeric',
            'district_id'           => 'required|numeric',
            'kecelakaan'            => 'required|numeric',
            'kebakaran'             => 'required|numeric',
            'ambulan_gratis'        => 'required|numeric',
            'pln'                   => 'required|numeric',
            'mobil_jenazah'         => 'required|numeric',
            'penanganan_hewan'      => 'required|numeric',
            'keamanan'              => 'required|numeric',
            'kriminal'              => 'required|numeric',
            'bencana_alam'          => 'required|numeric',
            'kdrt'                  => 'required|numeric',
            'gawat_darurat_lain'    => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error_json("Failed to create Emergency data", $validator->errors(), 400);
        }

        try {
            $update = $find->update([
                'period'                => $request->period,
                'year'                  => $request->year,
                'district_id'           => $request->district_id,
                'kecelakaan'            => $request->kecelakaan,
                'kebakaran'             => $request->kebakaran,
                'ambulan_gratis'        => $request->ambulan_gratis,
                'pln'                   => $request->pln,
                'mobil_jenazah'         => $request->mobil_jenazah,
                'penanganan_hewan'      => $request->penanganan_hewan,
                'keamanan'              => $request->keamanan,
                'kriminal'              => $request->kriminal,
                'bencana_alam'          => $request->bencana_alam,
                'kdrt'                  => $request->kdrt,
                'gawat_darurat_lain'    => $request->gawat_darurat_lain,
            ]);

            if ($update) {
                return $this->success_json("Successfully update emergency data", $update);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Failed to update emergency data", $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $find = Emergency::where('id', $id)->first();

        if (!$find) {
            return $this->error_json("Emergency not found!", $find, 404);
        }

        try {
            $delete = $find->delete();

            if ($delete) {
                return $this->success_json("Successfully delete emergency", $delete);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Failed to delete emergency", $th->getMessage(), 500);
        }
    }

    /**
     * export the specified resource from storage.
     */
    public function export_data()
    {
        try {
            $data = Emergency::with('district')
                ->orderBy('year')
                ->orderByRaw('FIELD(period, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")')
                ->get();

            return $this->success_json("Successfully export data", $data);
        } catch (\Throwable $th) {
            return $this->error_json("Failed to export data", $th->getMessage(), 500);
        }
    }
}
