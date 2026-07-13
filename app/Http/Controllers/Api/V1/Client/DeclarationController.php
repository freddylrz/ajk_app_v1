<?php

namespace App\Http\Controllers\Api\V1\Client;

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
            'keyword' => 'nullable|string|max:100',
            'status_id' => 'nullable|integer',
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
                ->where('d.branch_id', Init::getBranchId())
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

                    DB::raw("TO_CHAR(d.birth_date,'DD-MM-YYYY') as birth_date"),

                    DB::raw("TO_CHAR(d.plafond,'FM999,999,999,999,999,990') as plafond"),

                    'l.declaration_status_id as status_id',
                    's.event_name as status_name',

                    DB::raw("TO_CHAR(d.created_at,'DD-MM-YYYY HH24:MI') as created_at")
                );

            if ($r->type == 1) {

                $query->whereIn('l.declaration_status_id', [
                    1,
                    2,
                    3,
                    4,
                    5,
                    6
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

            if (!empty($r->status_id)) {

                $query->where('l.declaration_status_id', $r->status_id);

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

    public function asset()
    {
        try {

            $debtCategory = DB::table('master.tb_debtor_category')
                ->select(
                    'id',
                    'category_name'
                )
                ->orderBy('id')
                ->get();

                

            return response()->json([
                'status' => 200,
                'message' => 'Success.',
                'data' => [
                    'debt_category' => $debtCategory,
                    'gender' => [
                        [
                            'id' => 1,
                            'name' => 'Laki-laki'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Perempuan'
                        ]
                    ]                    
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

    public function insert(Request $r)
    {
        $input = $r->all();

        $validator = Validator::make($input, [
            'policy_no' => 'nullable|string|max:30',
            'insured_name' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'gender_id' => 'required|integer|in:1,2',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'phone_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'ktp_address' => 'nullable|string',
            'domicile_address' => 'nullable|string',
            'debtor_category_id' => 'required|integer',
            'company_name' => 'nullable|string|max:255',
            'position_name' => 'nullable|string|max:150',
            'account_no' => 'required|string|max:50',
            'pk_no' => 'required|string|max:100',
            'tenor' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'plafond' => 'required',
            'rate' => 'required',
            'premium' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        if (
            !DB::table('master.tb_debtor_category')
                ->where('id', $input['debtor_category_id'])
                ->exists()
        ) {

            return response()->json([
                'status' => 422,
                'message' => 'Kategori debitur tidak ditemukan.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            $declarationId = Init::createId();

            DB::table('operational.tb_declaration')->insert([
                'id' => $declarationId,
                'declaration_no' => Init::generateDeclarationNo(),
                'policy_no' => trim($input['policy_no'] ?? ''),
                'branch_id' => Init::getBranchId(),
                'insured_name' => trim($input['insured_name']),
                'nik' => preg_replace('/\D/', '', $input['nik']),
                'gender_id' => $input['gender_id'],
                'birth_place' => trim($input['birth_place'] ?? ''),
                'birth_date' => Carbon::parse($input['birth_date'])->format('Y-m-d'),
                'phone_no' => preg_replace('/\D/', '', $input['phone_no'] ?? ''),
                'email' => trim($input['email'] ?? ''),
                'ktp_address' => $input['ktp_address'] ?? null,
                'domicile_address' => $input['domicile_address'] ?? null,
                'debtor_category' => $input['debtor_category_id'] ?? null,
                'company_name' => $input['company_name'] ?? null,
                'position_name' => $input['position_name'] ?? null,
                'account_no' => trim($input['account_no']),
                'pk_no' => trim($input['pk_no']),
                'tenor' => $input['tenor'],
                'start_date' => Carbon::parse($input['start_date'])->format('Y-m-d'),
                'end_date' => Carbon::parse($input['end_date'])->format('Y-m-d'),
                'plafond' => str_replace(',', '', str_replace('.', '', $input['plafond'])),
                'rate' => str_replace(',', '.', $input['rate']),
                'premium' => str_replace(',', '', str_replace('.', '', $input['premium'])),
                'user_id_add' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $destinationPath = 'upload/declaration/' . $declarationId . '/';
            $path = public_path($destinationPath);

            File::makeDirectory($path, 0777, true, true);

            $saveFile = function ($fileInput, $prefix) use ($path) {

                if (empty($fileInput)) {
                    return null;
                }

                $fileInfo = Init::decodeFile($fileInput);

                $fileName = str_replace("#", " ", $prefix . '.' . $fileInfo['extension']);

                file_put_contents($path . $fileName, base64_decode($fileInfo['file']));

                return $fileName;
            };

            /*
            |--------------------------------------------------------------------------
            | Upload KTP
            |--------------------------------------------------------------------------
            */

            $uploads = collect([
                [
                    'input' => $input['ktp_file'] ?? null,
                    'prefix' => 'KTP-' . now()->format('ymdHis'),
                    'type' => 1,
                ],
            ])->map(function ($item) use ($saveFile, $declarationId, $destinationPath) {

                $fileName = $saveFile($item['input'], $item['prefix']);

                return $fileName ? [
                    'id' => Init::createId(),
                    'declaration_id' => $declarationId,
                    'file_type' => $item['type'],
                    'file_name' => $fileName,
                    'file_path' => $destinationPath,
                    'user_id_add' => Auth::user()->id,
                    'dropbox_status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ] : null;

            })->filter()->values()->toArray();

            if ($uploads) {
                DB::table('operational.tb_upload')->insert($uploads);
            }

            if (!empty($input['debtor_file'])) {

                foreach ($input['debtor_file'] as $file) {

                    $fileName = $saveFile(
                        $file,
                        'DEBITUR-' . now()->format('ymdHis') . '-' . Str::random(5)
                    );

                    if ($fileName) {

                        DB::table('operational.tb_upload')->insert([
                            'id' => Init::createId(),
                            'declaration_id' => $declarationId,
                            'file_type' => 2,
                            'file_name' => $fileName,
                            'file_path' => $destinationPath,
                            'user_id_add' => Auth::user()->id,
                            'dropbox_status' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    }
                }

            }

            $logId = Init::createId();

            DB::table('operational.tb_declaration_log')->insert([
                'id' => $logId,
                'declaration_id' => $declarationId,
                'declaration_status_id' => 1,
                'log_date' => now(),
                'note' => 'Draft',
                'user_id_add' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('operational.tb_declaration')
                ->where('id', $declarationId)
                ->update([
                    'declaration_log_id' => $logId
                ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Declaration berhasil ditambahkan.',
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

    public function update(Request $r)
    {
        $input = $r->all();

        $validator = Validator::make($input, [
            'id' => 'required|string',
            'policy_no' => 'nullable|string|max:30',
            'insured_name' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'gender_id' => 'required|integer|in:1,2',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'phone_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'ktp_address' => 'nullable|string',
            'domicile_address' => 'nullable|string',
            'debtor_category_id' => 'required|integer',
            'company_name' => 'nullable|string|max:255',
            'position_name' => 'nullable|string|max:150',
            'account_no' => 'required|string|max:50',
            'pk_no' => 'required|string|max:100',
            'tenor' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'plafond' => 'required',
            'rate' => 'required',
            'premium' => 'required',

            'declaration_status_id' => 'required|integer',
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
            ->where('d.branch_id', Init::getBranchId())
            ->first();

        if (empty($declaration)) {

            return response()->json([
                'status' => 404,
                'message' => 'Declaration tidak ditemukan.'
            ], 404);

        }

        if (in_array($declaration->declaration_status_id, [7, 99])) {

            return response()->json([
                'status' => 422,
                'message' => 'Declaration tidak dapat diubah.'
            ], 422);

        }

        if (
            !DB::table('master.tb_debtor_category')
                ->where('id', $input['debtor_category_id'])
                ->exists()
        ) {

            return response()->json([
                'status' => 422,
                'message' => 'Kategori debitur tidak ditemukan.'
            ], 422);

        }

        DB::beginTransaction();

        try {

            DB::table('operational.tb_declaration')
                ->where('id', $input['id'])
                ->update([

                    'policy_no' => trim($input['policy_no'] ?? ''),
                    'branch_id' => Init::getBranchId(),
                    'insured_name' => trim($input['insured_name']),
                    'nik' => preg_replace('/\D/', '', $input['nik']),
                    'gender_id' => $input['gender_id'],
                    'birth_place' => trim($input['birth_place'] ?? ''),
                    'birth_date' => Carbon::parse($input['birth_date'])->format('Y-m-d'),

                    'phone_no' => preg_replace('/\D/', '', $input['phone_no'] ?? ''),
                    'email' => trim($input['email'] ?? ''),

                    'ktp_address' => $input['ktp_address'] ?? null,
                    'domicile_address' => $input['domicile_address'] ?? null,

                    'debtor_category' => $input['debtor_category_id'],
                    'company_name' => $input['company_name'] ?? null,
                    'position_name' => $input['position_name'] ?? null,

                    'account_no' => trim($input['account_no']),
                    'pk_no' => trim($input['pk_no']),

                    'tenor' => $input['tenor'],

                    'start_date' => Carbon::parse($input['start_date'])->format('Y-m-d'),
                    'end_date' => Carbon::parse($input['end_date'])->format('Y-m-d'),

                    'plafond' => str_replace(',', '', str_replace('.', '', $input['plafond'])),
                    'rate' => str_replace(',', '.', $input['rate']),
                    'premium' => str_replace(',', '', str_replace('.', '', $input['premium'])),

                    'user_id_update' => Auth::user()->id,
                    'updated_at' => now(),

                ]);

            $destinationPath = 'upload/declaration/' . $input['id'] . '/';
            $path = public_path($destinationPath);

            File::makeDirectory($path, 0777, true, true);

            $saveFile = function ($fileInput, $prefix) use ($path) {

                if (empty($fileInput)) {
                    return null;
                }

                $fileInfo = Init::decodeFile($fileInput);

                $fileName = str_replace("#", " ", $prefix . '.' . $fileInfo['extension']);

                file_put_contents($path . $fileName, base64_decode($fileInfo['file']));

                return $fileName;
            };

            if (!empty($input['ktp_file'])) {

                DB::table('operational.tb_upload')
                    ->where('declaration_id', $input['id'])
                    ->where('file_type', 1)
                    ->whereNull('deleted_at')
                    ->update([
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);

                $fileName = $saveFile(
                    $input['ktp_file'],
                    'KTP-' . Init::createId()
                );

                if ($fileName) {

                    DB::table('operational.tb_upload')->insert([
                        'id' => Init::createId(),
                        'declaration_id' => $input['id'],
                        'file_type' => 1,
                        'file_name' => $fileName,
                        'file_path' => $destinationPath,
                        'user_id_add' => Auth::user()->id,
                        'dropbox_status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                }

            }

            if (!empty($input['debtor_file'])) {

                DB::table('operational.tb_upload')
                    ->where('declaration_id', $input['id'])
                    ->where('file_type', 2)
                    ->whereNull('deleted_at')
                    ->update([
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);

                foreach ($input['debtor_file'] as $file) {

                    $fileName = $saveFile(
                        $file,
                        'DEBITUR-' . Init::createId()
                    );

                    if ($fileName) {

                        DB::table('operational.tb_upload')->insert([

                            'id' => Init::createId(),
                            'declaration_id' => $input['id'],
                            'file_type' => 2,
                            'file_name' => $fileName,
                            'file_path' => $destinationPath,
                            'user_id_add' => Auth::user()->id,
                            'dropbox_status' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),

                        ]);

                    }

                }

            }

            if (
                !DB::table('operational.tb_declaration_status')
                    ->where('id', $input['declaration_status_id'])
                    ->exists()
            ) {

                DB::rollBack();

                return response()->json([
                    'status' => 422,
                    'message' => 'Status declaration tidak ditemukan.'
                ], 422);

            }

            if ((int) $input['declaration_status_id'] != 1) {

                $logId = Init::createId();

                DB::table('operational.tb_declaration_log')->insert([

                    'id' => $logId,
                    'declaration_id' => $input['id'],
                    'declaration_status_id' => $input['declaration_status_id'],
                    'log_date' => now(),
                    'note' => $input['note'] ?? '-',
                    'user_id_add' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),

                ]);

                DB::table('operational.tb_declaration')
                    ->where('id', $input['id'])
                    ->update([
                        'declaration_log_id' => $logId,
                        'updated_at' => now(),
                    ]);

            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data declaration berhasil diperbarui.'
            ], 200);

        } catch (Throwable $exception) {

            DB::rollBack();

            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.'
            ], 500);
        }
    }

    public function validation(Request $r)
    {
        $input = $r->all();

        $validator = Validator::make($input, [
            'id' => 'required|string',
            'status_id' => 'required|integer',
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

        if ((int) $declaration->declaration_status_id != 3) {

            return response()->json([
                'status' => 422,
                'message' => 'Declaration tidak dalam proses validasi SPV.'
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
                2 => 'Declaration berhasil dikembalikan ke Operator.',
                5 => 'Declaration berhasil dikirim ke TuguBro.',
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
                ->where('d.branch_id', Init::getBranchId())
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

    public function premiumCalculation(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'birth_date' => 'required|date',
            'start_date' => 'required|date',
            'tenor' => 'required|integer|min:1',
            'plafond' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {

            $birthDate = Carbon::parse($r->birth_date);
            $startDate = Carbon::parse($r->start_date);

            $ageMonth = $birthDate->diffInMonths($startDate);

            $age = (int) ceil($ageMonth / 12);

            $jw = (int) ceil($r->tenor / 12);

            $endDate = $startDate
                ->copy()
                ->addMonths($r->tenor)
                ->format('Y-m-d');

            $rate = DB::table('master.tb_rate')
                ->where('age', $age)
                ->where('jw', $jw)
                ->value('rate');

            if (empty($rate)) {

                return response()->json([
                    'status' => 422,
                    'message' => 'Rate tidak ditemukan.'
                ], 422);

            }

            $plafond = (float) str_replace(
                ',',
                '',
                str_replace('.', '', $r->plafond)
            );

            $premium = round(
                $plafond * ($rate / 1000)
            );

            return response()->json([
                'status' => 200,
                'message' => 'Success.',
                'data' => [

                    'age' => $age,

                    'jw' => $jw,

                    'rate' => $rate,

                    'premium' => number_format($premium, 0, ',', '.'),

                    'end_date' => Carbon::parse($endDate)
                        ->format('d-m-Y'),

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
