<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\Api\Init;
use Log;
use Throwable;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {

            $roleId = Init::getRoleId();

            $query = DB::table('operational.tb_declaration as d')
                ->join(
                    'operational.tb_declaration_log as l',
                    'd.declaration_log_id',
                    '=',
                    'l.id'
                )
                ->where('l.declaration_status_id', 7);

            /*
            |--------------------------------------------------------------------------
            | Filter Branch
            |--------------------------------------------------------------------------
            */

            if ($roleId != 1) {
                $query->where('d.branch_id', Init::getBranchId());
            }

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $totalPlafond = (clone $query)->sum('d.plafond');

            $totalPremium = (clone $query)->sum('d.premium');

            $totalDebitur = (clone $query)->count();

            /*
            |--------------------------------------------------------------------------
            | Summary Bulan Berjalan
            |--------------------------------------------------------------------------
            */

            $monthQuery = clone $query;

            $monthQuery
                ->whereYear('d.created_at', now()->year)
                ->whereMonth('d.created_at', now()->month);

            $totalPlafondMonth = (clone $monthQuery)->sum('d.plafond');

            $totalPremiumMonth = (clone $monthQuery)->sum('d.premium');

            $totalDebiturMonth = (clone $monthQuery)->count();

            /*
            |--------------------------------------------------------------------------
            | Deklarasi Pertahun
            |--------------------------------------------------------------------------
            */

            $yearly = (clone $query)
                ->select(
                    DB::raw('EXTRACT(YEAR FROM d.created_at) AS year'),
                    DB::raw('COUNT(*) AS debitur'),
                    DB::raw('SUM(d.plafond) AS plafond'),
                    DB::raw('SUM(d.premium) AS premium')
                )
                ->groupBy(DB::raw('EXTRACT(YEAR FROM d.created_at)'))
                ->orderBy(DB::raw('EXTRACT(YEAR FROM d.created_at)'))
                ->get()
                ->map(function ($item) {

                    return [

                        'year' => (int) $item->year,

                        'debitur' => (int) $item->debitur,

                        'plafond' => number_format($item->plafond, 0, ',', '.'),

                        'premium' => number_format($item->premium, 0, ',', '.'),

                    ];

                });

            /*
            |--------------------------------------------------------------------------
            | Debitur Per Kategori
            |--------------------------------------------------------------------------
            */

            $category = (clone $query)
                ->leftJoin(
                    'master.tb_debtor_category as c',
                    'd.debtor_category',
                    '=',
                    'c.id'
                )
                ->select(
                    'c.category_name',
                    DB::raw('COUNT(*) AS debitur'),
                    DB::raw('SUM(d.plafond) AS plafond'),
                    DB::raw('SUM(d.premium) AS premium')
                )
                ->groupBy('c.category_name')
                ->orderBy('c.category_name')
                ->get()
                ->map(function ($item) {

                    return [

                        'category_name' => $item->category_name,

                        'debitur' => (int) $item->debitur,

                        'plafond' => number_format($item->plafond, 0, ',', '.'),

                        'premium' => number_format($item->premium, 0, ',', '.'),

                    ];

                });

            /*
            |--------------------------------------------------------------------------
            | Claim Summary (Temporary)
            |--------------------------------------------------------------------------
            */

            $claimSummary = [

                'total_claim' => 0,

                'claim_process' => 0,

                'claim_reject' => 0,

                'claim_approve' => 0,

                'claim_paid' => 0,

            ];

            /*
            |--------------------------------------------------------------------------
            | Claim Per Kategori (Temporary)
            |--------------------------------------------------------------------------
            */

            $claimCategory = DB::table('master.tb_debtor_category')
                ->select('category_name')
                ->orderBy('id')
                ->get()
                ->map(function ($item) {

                    return [

                        'category_name' => $item->category_name,

                        'debitur' => 0,

                        'claim' => 0,

                    ];

                });

            return response()->json([

                'status' => 200,

                'message' => 'Success.',

                'data' => [

                    'summary' => [

                        'total_plafond' => number_format($totalPlafond, 0, ',', '.'),

                        'total_premium' => number_format($totalPremium, 0, ',', '.'),

                        'total_debitur' => $totalDebitur,

                        'total_plafond_month' => number_format($totalPlafondMonth, 0, ',', '.'),

                        'total_premium_month' => number_format($totalPremiumMonth, 0, ',', '.'),

                        'total_debitur_month' => $totalDebiturMonth,

                    ],

                    'yearly' => $yearly,

                    'category' => $category,

                    'claim_summary' => $claimSummary,

                    'claim_category' => $claimCategory,

                ]

            ], 200);

        } catch (Throwable $exception) {

            Log::error($exception);

            return response()->json([

                'status' => 500,

                'message' => 'Terjadi kesalahan! Silakan coba lagi.',

            ], 500);

        }
    }
}
