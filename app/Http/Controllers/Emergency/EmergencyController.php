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
            $data = Emergency::with('district')
                ->orderBy('period', 'ASC')
                ->orderBy('year', 'ASC')
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
    public function show(Emergency $emergency)
    {
        //
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
}
