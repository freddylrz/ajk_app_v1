<?php

namespace App\Helpers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Log;
use Throwable;

Class CryptManual
{
    public static function convertLongIntegersToStringFromJson(string $jsonData): string {
        // Decode JSON menjadi array
        $data = json_decode($jsonData, true);

        // Pastikan data yang di-decode adalah array
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON data provided.');
        }

        // Iterasi untuk setiap key pada data
        foreach ($data as $key => $items) {
            // Pastikan value dari key adalah array yang dapat diolah
            if (is_array($items)) {
                $data[$key] = collect($items)->map(function ($item) {
                    // Periksa apakah item adalah array (atau object dari hasil query database)
                    if (is_array($item)) {
                        foreach ($item as $k => $v) {
                            // Periksa apakah nilai adalah integer atau string angka dengan panjang > 16
                            if (is_numeric($v) && strlen((string)$v) > 16) {
                                // Ubah ke string jika angka lebih dari 16 digit
                                $item[$k] = (string)$v;
                            }
                        }
                    }
                    return $item;
                })->toArray();
            }
        }

        // Encode kembali menjadi JSON dan pastikan formatnya tetap seperti input awal
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }


    public static function encryption($data)
    {
        $data = self::convertLongIntegersToStringFromJson(json_encode($data));

        $key = base64_decode('0mswVJNCqLc1JCm1YLw61Y80YzkbLBb0DIkiNaK/HJ0=');  // APP_KEY yang ada di .env Laravel

        // Generate IV acak 16 byte (128-bit) untuk mode AES-256-CBC
        $iv = openssl_random_pseudo_bytes(16);

        // Enkripsi data
        $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        // Gabungkan IV dan ciphertext dalam format JSON
        $result = [
            'iv' => base64_encode($iv),          // Encode IV menjadi base64
            'value' => base64_encode($encryptedData) // Encode ciphertext menjadi base64
        ];

        // Encode seluruh hasil dalam base64 agar mudah dikirim
        return base64_encode(json_encode($result));
    }

    public static function decryption($encodedData)
    {
        $decodedData = json_decode(base64_decode($encodedData), true);

        if (!isset($decodedData['iv']) || !isset($decodedData['value']))
        {
            return response()->json([
                'status'  => 200,
                'message' => "Invalid payload.",
            ], 500);
        }

        // Ambil IV dan ciphertext
        $iv = base64_decode($decodedData['iv']);
        $ciphertext = base64_decode($decodedData['value']);

        // Kunci yang digunakan untuk enkripsi, pastikan menggunakan kunci yang sama dengan yang digunakan di frontend
        $key = base64_decode('0mswVJNCqLc1JCm1YLw61Y80YzkbLBb0DIkiNaK/HJ0=');  // APP_KEY yang ada di .env Laravel

        // Dekripsi menggunakan AES-256-CBC
        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        $decrypted = json_decode($decrypted, true);

        return $decrypted;
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