<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function call_reports(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'month_period' => 'required',
                'year' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->error_json("Failed to create Emergency data", $validator->errors(), 400);
            }

            $totalByMonth = [
                'disconnect_call' => 0,
                'prank_call' => 0,
                'education_call' => 0,
                'emergency_call' => 0,
                'abandoned' => 0,
            ];

            $statByMonth = Call::with('detail')
                ->where([
                    ['month_period', '=', $request->month_period],
                    ['year', '=', $request->year],
                ])
                ->first();

            foreach ($statByMonth->detail as $data) {
                $totalByMonth['disconnect_call'] += $data->disconnect_call;
                $totalByMonth['prank_call'] += $data->prank_call;
                $totalByMonth['education_call'] += $data->education_call;
                $totalByMonth['emergency_call'] += $data->emergency_call;
                $totalByMonth['abandoned'] += $data->abandoned;
            }

            $statByYear = Call::with('detail')
                ->where([
                    ['year', '=', $request->year],
                ])
                ->get();

            $totalByYear = [
                'disconnect_call' => 0,
                'prank_call' => 0,
                'education_call' => 0,
                'emergency_call' => 0,
                'abandoned' => 0,
            ];

            foreach ($statByYear as $month) {
                foreach ($month->detail as $detail) {
                    $totalByYear['disconnect_call'] += $detail->disconnect_call;
                    $totalByYear['prank_call'] += $detail->prank_call;
                    $totalByYear['education_call'] += $detail->education_call;
                    $totalByYear['emergency_call'] += $detail->emergency_call;
                    $totalByYear['abandoned'] += $detail->abandoned;
                }
            }

            $grafik_month = collect([]);
            $bar_grafik_month = collect([]);

            foreach ($statByMonth->detail as $detail) {
                $date = sprintf('2024-%s-%s', '09', str_pad($detail->day, 2, '0', STR_PAD_LEFT));
                $total = $detail->disconnect_call + $detail->prank_call + $detail->education_call + $detail->emergency_call + $detail->abandoned;

                $grafik_month->push([
                    'x' => $date,
                    'y' => $total
                ]);
            }

            foreach ($totalByMonth as $key => $value) {
                $name = explode('_', $key);

                if (count($name) > 1) {
                    $bar_grafik_month->push([
                        'x' => ucfirst($name[0]) . " " . ucfirst($name[1]),
                        'y' => $value
                    ]);
                } else {
                    $bar_grafik_month->push([
                        'x' => strtoupper($name[0]),
                        'y' => $value
                    ]);
                }
            }

            // Group by year and month_period, and calculate totals
            $formattedResult = $statByYear->map(function ($call) {
                $totals = $call->detail->reduce(function ($carry, $detail) {
                    return [
                        'total_disconnect_call' => $carry['total_disconnect_call'] + $detail->disconnect_call,
                        'total_prank_call' => $carry['total_prank_call'] + $detail->prank_call,
                        'total_education_call' => $carry['total_education_call'] + $detail->education_call,
                        'total_emergency_call' => $carry['total_emergency_call'] + $detail->emergency_call,
                        'total_abandoned' => $carry['total_abandoned'] + $detail->abandoned,
                    ];
                }, [
                    'total_disconnect_call' => 0,
                    'total_prank_call' => 0,
                    'total_education_call' => 0,
                    'total_emergency_call' => 0,
                    'total_abandoned' => 0,
                ]);

                return [
                    'year' => $call->year,
                    'month_period' => $call->month_period,
                    'total_disconnect_call' => $totals['total_disconnect_call'],
                    'total_prank_call' => $totals['total_prank_call'],
                    'total_education_call' => $totals['total_education_call'],
                    'total_emergency_call' => $totals['total_emergency_call'],
                    'total_abandoned' => $totals['total_abandoned'],
                ];
            });

            $result = collect([
                'by_month' => $totalByMonth,
                'by_year' => $totalByYear,
                'grafik_month' => $grafik_month,
                'bar_grafik_month' => $bar_grafik_month,
                'total' => $formattedResult
            ]);

            return $this->success_json('Successfully get dashboard', $result);
        } catch (\Throwable $th) {
            return $this->error_json("Report not Found!", $th->getMessage(), 400);
        }
    }
}
