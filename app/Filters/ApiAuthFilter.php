<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (empty($header) || !str_starts_with($header, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.'
                ]);
        }

        $token = substr($header, 7);

        try {
            $key = getenv('JWT_SECRET_KEY') ?: 'medicom-secret-key-2025';
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Simpan data user ke request agar bisa diakses di controller
            $request->userData = $decoded;
        } catch (\Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak valid atau sudah expired.',
                    'detail'  => $e->getMessage()
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
