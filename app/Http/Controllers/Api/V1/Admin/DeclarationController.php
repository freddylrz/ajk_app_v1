<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use File;
use App\Helpers\Api\Init;
use Log;
use Throwable;

class DeclarationController extends Controller
{
    public function list(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'type' => 'required|integer|in:1,2',
            'keyword' => 'nullable|string|max:100',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {

            $page = $r->page ?? 1;
            $limit = $r->limit ?? 10;
            $keyword = strtoupper(trim($r->keyword ?? ''));

            $query = DB::table('operational.tb_declaration as d')
                ->leftJoin('master.tb_branch as b', 'd.branch_id', '=', 'b.branch_id')
                ->leftJoin('operational.tb_declaration_log as l', 'd.declaration_log_id', '=', 'l.id')
                ->leftJoin('operational.tb_declaration_status as s', 'l.declaration_status_id', '=', 's.id')
                ->select(
                    'd.id',
                    'd.declaration_no',
                    'd.policy_no',

                    'd.branch_id',
                    'b.branch_name',

                    'd.insured_name',
                    'd.nik',

                    DB::raw("
                        CASE
                            WHEN d.gender_id = 1 THEN 'Laki-laki'
                            WHEN d.gender_id = 2 THEN 'Perempuan'
                            ELSE '-'
                        END AS gender_desc
                    "),

                    DB::raw("TO_CHAR(d.birth_date, 'DD-MM-YYYY') AS birth_date"),

                    DB::raw("TO_CHAR(d.plafond, 'FM999,999,999,999,999,990') AS plafond"),

                    'd.rate',

                    DB::raw("TO_CHAR(d.premium, 'FM999,999,999,999,999,990') AS premium"),

                    'l.declaration_status_id as status_id',
                    's.event_name as status_name',

                    DB::raw("TO_CHAR(d.created_at, 'DD-MM-YYYY HH24:MI') AS created_at")
                );

            if ($r->type == 1) {

                $query->whereIn('l.declaration_status_id', [
                    4,
                    5,
                    6,
                ]);

            } else {

                $query->where('l.declaration_status_id', 7);

            }

            if (!empty($keyword)) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw('UPPER(d.declaration_no) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('UPPER(d.policy_no) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('UPPER(d.insured_name) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('UPPER(d.nik) LIKE ?', ["%{$keyword}%"]);

                });

            }

            $total = (clone $query)->count();

            $data = $query
                ->orderByDesc('d.created_at')
                ->offset(($page - 1) * $limit)
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Success.',
                'data' => $data,
                'pagination' => [
                    'page' => (int) $page,
                    'limit' => (int) $limit,
                    'total' => $total,
                    'total_page' => ceil($total / $limit),
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

    public function detail(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {

            $data = DB::table('operational.tb_declaration as d')
                ->leftJoin('master.tb_branch as b', 'd.branch_id', '=', 'b.branch_id')
                ->leftJoin('operational.tb_declaration_log as l', 'd.declaration_log_id', '=', 'l.id')
                ->leftJoin('operational.tb_declaration_status as s', 'l.declaration_status_id', '=', 's.id')
                ->leftJoin('master.tb_debtor_category as dc', 'd.debtor_category', '=', 'dc.id')
                ->select(
                    'd.id',
                    'd.declaration_no',
                    'd.policy_no',

                    'd.branch_id',
                    'b.branch_name',

                    'd.insured_name',
                    'd.nik',
                    'd.gender_id',
                    DB::raw("
                        CASE
                            WHEN d.gender_id = 1 THEN 'Laki-laki'
                            WHEN d.gender_id = 2 THEN 'Perempuan'
                            ELSE '-'
                        END AS gender_desc
                    "),
                    'd.birth_place',
                    DB::raw("TO_CHAR(d.birth_date, 'DD-MM-YYYY') AS birth_date"),

                    'd.phone_no',
                    'd.email',

                    'd.ktp_address',
                    'd.domicile_address',

                    'd.debtor_category as debtor_category_id',
                    'dc.category_name as debtor_category_name',

                    'd.company_name',
                    'd.position_name',

                    'd.account_no',
                    'd.pk_no',

                    'd.tenor',
                    DB::raw("TO_CHAR(d.start_date, 'DD-MM-YYYY') AS start_date"),
                    DB::raw("TO_CHAR(d.end_date, 'DD-MM-YYYY') AS end_date"),

                    DB::raw("TO_CHAR(d.plafond, 'FM999,999,999,999,999,990') AS plafond"),
                    'd.rate',
                    DB::raw("TO_CHAR(d.premium, 'FM999,999,999,999,999,990') AS premium"),

                    'l.declaration_status_id as status_id',
                    's.event_name as status_name',
                )
                ->where('d.id', $r->id)
                ->first();

            if (empty($data)) {

                return response()->json([
                    'status' => 404,
                    'message' => 'Declaration tidak ditemukan.'
                ], 404);

            }

            $ktp = DB::table('operational.tb_upload')
                ->select(
                    'id',
                    'file_name',
                    'file_path'
                )
                ->where('declaration_id', $r->id)
                ->where('file_type', 1)
                ->whereNull('deleted_at')
                ->first();

            $debitur = DB::table('operational.tb_upload')
                ->select(
                    'id',
                    'file_name',
                    'file_path'
                )
                ->where('declaration_id', $r->id)
                ->where('file_type', 2)
                ->whereNull('deleted_at')
                ->orderBy('created_at')
                ->get();

            $logs = DB::table('operational.tb_declaration_log as l')
                ->leftJoin('operational.tb_declaration_status as s', 'l.declaration_status_id', '=', 's.id')
                ->leftJoin('users as u', 'l.user_id_add', '=', 'u.id')
                ->select(
                    'l.id',
                    'l.declaration_status_id as status_id',
                    's.event_name as status_name',
                    'l.note',
                    'u.display_name as user_name',

                    DB::raw("TO_CHAR(l.log_date, 'DD-MM-YYYY HH24:MI') AS log_date")
                )
                ->where('l.declaration_id', $r->id)
                ->orderBy('l.created_at')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Success.',
                'data' => [
                    'declaration' => $data,
                    'upload' => [
                        'ktp' => $ktp,
                        'debitur' => $debitur,
                    ],
                    'logs' => $logs,
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

    public function validation(Request $r)
    {
        $input = $r->all();

        $validator = Validator::make($input, [
            'id' => 'required|string',
            'status_id' => 'required|integer|in:4,7,99',
            'note' => 'nullable|string|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $declaration = DB::table('operational.tb_declaration as d')
            ->leftJoin(
                'operational.tb_declaration_log as l',
                'd.declaration_log_id',
                '=',
                'l.id'
            )
            ->select(
                'd.id',
                'd.declaration_log_id',
                'l.declaration_status_id'
            )
            ->where('d.id', $input['id'])
            ->first();

        if (empty($declaration)) {

            return response()->json([
                'status' => 404,
                'message' => 'Declaration tidak ditemukan.'
            ], 404);

        }

        if ((int) $declaration->declaration_status_id != 5) {

            return response()->json([
                'status' => 422,
                'message' => 'Declaration tidak dalam proses validasi TuguBro.'
            ], 422);

        }

        DB::beginTransaction();

        try {

            $logId = Init::createId();

            DB::table('operational.tb_declaration_log')->insert([
                'id' => $logId,
                'declaration_id' => $declaration->id,
                'declaration_status_id' => $input['status_id'],
                'log_date' => now(),
                'note' => $input['note'] ?? '-',
                'user_id_add' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('operational.tb_declaration')
                ->where('id', $declaration->id)
                ->update([
                    'declaration_log_id' => $logId,
                    'user_id_update' => Auth::user()->id,
                    'updated_at' => now(),
                ]);

            DB::commit();

            $message = match ((int) $input['status_id']) {
                4 => 'Declaration berhasil dikembalikan ke Operator.',
                7 => 'Polis berhasil diterbitkan.',
                99 => 'Declaration berhasil dibatalkan.',
                default => 'Status berhasil diperbarui.',
            };

            return response()->json([
                'status' => 200,
                'message' => $message,
            ], 200);

        } catch (Throwable $exception) {

            DB::rollBack();

            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);

        }
    }
}
