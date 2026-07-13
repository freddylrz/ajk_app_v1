<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;
use File;
use App\Helpers\Api\Init;
use Log;
use Throwable;

class DeclarationController extends Controller
{
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
                    "gender" => [
                        [
                            "id" => 1,
                            "name" => "Laki-laki"
                        ],
                        [
                            "id" => 2,
                            "name" => "Perempuan"
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
            'branch_id' => 'required|integer',
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
                'branch_id' => $input['branch_id'],
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

            /*
            |--------------------------------------------------------------------------
            | Upload Debitur
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Declaration Log
            |--------------------------------------------------------------------------
            */

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
            'branch_id' => 'required|integer',
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

        // if (
        //     !DB::table('master.tb_branch')
        //         ->where('branch_id', $input['branch_id'])
        //         ->exists()
        // ) {

        //     return response()->json([
        //         'status' => 422,
        //         'message' => 'Cabang tidak ditemukan.'
        //     ], 422);

        // }

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
                    'branch_id' => $input['branch_id'],
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

            /*
            |--------------------------------------------------------------------------
            | Update Upload KTP
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Update Upload Debitur
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Update Status
            |--------------------------------------------------------------------------
            */

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
}
