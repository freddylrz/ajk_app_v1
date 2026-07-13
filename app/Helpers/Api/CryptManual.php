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
}