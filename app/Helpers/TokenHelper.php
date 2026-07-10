<?php

if (!function_exists('decryptToken')) {
    function decryptToken($encodedData)
    {
        // Perform Base64 decoding multiple times
        $decodedData = json_decode(base64_decode($encodedData), true);

        if (!isset($decodedData['iv']) || !isset($decodedData['value'])) {
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
        // $decrypted = json_decode($decrypted, true);

        // dd($decrypted);
        return json_encode([
            'verified' => true,
            'data' => $decrypted
        ]);
    }
}
