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

class ClaimController extends Controller
{
    public function asset()
    {
        try {

            $debtor = DB::table('operational.tb_declaration as d')
                ->leftJoin(
                    'operational.tb_declaration_log as l',
                    'd.declaration_log_id',
                    '=',
                    'l.id'
                )
                ->leftJoin(
                    'operational.tb_claim as c',
                    'd.id',
                    '=',
                    'c.declaration_id'
                )
                ->select(
                    'd.id as declaration_id',
                    'd.policy_no',
                    'd.insured_name'
                )
                ->where('d.branch_id', Init::getBranchId())
                ->where('l.declaration_status_id', 7) // Polis Terbit
                ->whereNull('c.id')
                ->orderBy('d.insured_name')
                ->get();

            $document = DB::table('master.tb_claim_document')
                ->select(
                    'id',
                    'document_name',
                    'is_required'
                )
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Success.',
                'data' => [
                    'debtor' => $debtor,
                    'document' => $document,
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
            'declaration_id' => 'required|string',

            'incident_date' => 'required|date',

            'estimated_claim' => 'required',

            'description' => 'nullable|string',

            'document' => 'required|array|min:1',

            'document.*.document_id' => 'required|integer',

            'document.*.file_name' => 'required|string|max:255',

            'document.*.file' => 'required|string',
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
                'd.policy_no',
                'd.branch_id',
                'd.insured_name',
                'l.declaration_status_id'
            )
            ->where('d.id', $input['declaration_id'])
            ->where('d.branch_id', Init::getBranchId())
            ->first();

        if (empty($declaration)) {

            return response()->json([
                'status' => 404,
                'message' => 'Declaration tidak ditemukan.'
            ], 404);

        }

        /*
        |--------------------------------------------------------------------------
        | Hanya Polis Terbit
        |--------------------------------------------------------------------------
        */

        if ((int) $declaration->declaration_status_id != 7) {

            return response()->json([
                'status' => 422,
                'message' => 'Declaration belum memiliki polis.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | Tidak boleh double claim
        |--------------------------------------------------------------------------
        */

        if (
            DB::table('operational.tb_claim')
                ->where('declaration_id', $input['declaration_id'])
                ->exists()
        ) {

            return response()->json([
                'status' => 422,
                'message' => 'Claim sudah pernah dibuat.'
            ], 422);

        }

        DB::beginTransaction();

        try {

            $claimId = Init::createId();

            DB::table('operational.tb_claim')->insert([

                'id' => $claimId,

                'claim_no' => Init::generateClaimNo(),

                'declaration_id' => $declaration->id,

                'report_date' => now()->format('Y-m-d'),

                'incident_date' => Carbon::parse(
                    $input['incident_date']
                )->format('Y-m-d'),

                'estimated_claim' => str_replace(
                    ',',
                    '',
                    str_replace('.', '', $input['estimated_claim'])
                ),

                'description' => $input['description'] ?? null,

                'user_id_add' => Auth::user()->id,

                'created_at' => now(),

                'updated_at' => now(),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Upload Document
            |--------------------------------------------------------------------------
            */

            $destinationPath = 'upload/claim/' . $claimId . '/';

            $path = public_path($destinationPath);

            File::makeDirectory($path, 0777, true, true);

            $saveFile = function ($fileInput, $prefix) use ($path) {

                if (empty($fileInput)) {
                    return null;
                }

                $fileInfo = Init::decodeFile($fileInput);

                $fileName = str_replace(
                    "#",
                    " ",
                    $prefix . '.' . $fileInfo['extension']
                );

                file_put_contents(
                    $path . $fileName,
                    base64_decode($fileInfo['file'])
                );

                return $fileName;

            };

            foreach ($input['document'] as $document) {

                $fileName = $saveFile(
                    $document['file'],
                    strtoupper(
                        str_replace(
                            ' ',
                            '-',
                            trim($document['file_name'])
                        )
                    ) . '-' . Init::createId()
                );

                if ($fileName) {

                    $uploadId = Init::createId();

                    DB::table('operational.tb_upload')->insert([

                        'id' => $uploadId,

                        'claim_id' => $claimId,

                        'file_type' => $document['document_id'],

                        'file_name' => $fileName,

                        'file_path' => $destinationPath,

                        'user_id_add' => Auth::user()->id,

                        'dropbox_status' => 0,

                        'created_at' => now(),

                        'updated_at' => now(),

                    ]);

                    DB::table('operational.tb_claim_document')->insert([

                        'id' => Init::createId(),

                        'claim_id' => $claimId,

                        'document_id' => $document['document_id'],

                        'upload_id' => $uploadId,

                        'file_name' => $fileName,

                        'file_path' => $destinationPath,

                        'user_id_add' => Auth::user()->id,

                        'created_at' => now(),

                        'updated_at' => now(),

                    ]);

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Claim Log
            |--------------------------------------------------------------------------
            */

            $logId = Init::createId();

            DB::table('operational.tb_claim_log')->insert([
                'id' => $logId,
                'claim_id' => $claimId,
                'claim_status_id' => 1,
                'log_date' => now(),
                'note' => 'Draft',
                'user_id_add' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('operational.tb_claim')
                ->where('id', $claimId)
                ->update([
                    'claim_log_id' => $logId,
                ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Claim berhasil ditambahkan.',
                'data' => [
                    'claim_id' => $claimId,
                ]
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
