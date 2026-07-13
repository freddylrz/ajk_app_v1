<?php

namespace App\Helpers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Log;
use Throwable;

Class Init
{
    public static function createId(): string
    {
        return now()->format('ymdHisu') . random_int(1000, 9999);
    }
    public static function generateDeclarationNo()
    {
        $prefix = 'DEC' . now()->format('ym');

        $lastNo = DB::table('operational.tb_declaration')
            ->where('declaration_no', 'like', $prefix . '%')
            ->orderByDesc('declaration_no')
            ->value('declaration_no');

        $running = empty($lastNo) ? 1 : ((int) substr($lastNo, -6)) + 1;

        return $prefix . str_pad($running, 6, '0', STR_PAD_LEFT);
    }

    public static function getBranchId()
    {
        return DB::table('user_branch')
            ->where('user_id', Auth::id())
            ->value('branch_id');
    }

    public static function decodeFile($data)
    {
        try {
            if (!str_contains($data, ';base64,')) {
                return ['status' => false];
            }

            $base64Parts = explode(';base64,', $data);
            $metadata = $base64Parts[0] ?? null;
            $base64File = $base64Parts[1] ?? null;

            if (!$metadata || !$base64File) {
                return ['status' => false];
            }

            $fileType = explode(':', $metadata)[1] ?? '';

            $extensionMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'application/pdf' => 'pdf',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                'text/plain' => 'txt',
                'application/msword' => 'doc',
                "application/csv" => 'csv',
                "application/xls" => 'xls',
                "application/xlsx" => 'xlsx',
                "application/docx" => 'docx',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'application/vnd.ms-excel' => 'xls',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                'text/csv' => 'csv',
            ];

            $extension = $extensionMap[$fileType] ?? 'jpg';

            $fileName = 'bpr_file';

            if (preg_match('/name=([^;]+)/', $metadata, $matches)) {
                $rawFileName = urldecode($matches[1]);
                $fileName = pathinfo($rawFileName, PATHINFO_FILENAME);
                $fileName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $fileName);
            }

            return [
                'status'    => true,
                'file'      => $base64File,
                'extension' => $extension,
                'file_name' => $fileName,
                'mime_type' => $fileType
            ];
        } catch (Throwable $e) {
            Log::error($e);
            return ['status' => false];
        }
    }
}