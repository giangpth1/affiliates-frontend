<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class ApiService
{
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.api.base_url');
        $this->timeout = config('services.api.timeout', 30);
    }

    protected function request(): \Illuminate\Http\Client\PendingRequest
    {
        $request = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson();

        $token = session('access_token');
        if ($token) {
            $request->withToken($token);
        }

        return $request;
    }

    protected function handleUnauthorized(): bool
    {
        $refreshToken = session('refresh_token');
        if (!$refreshToken) {
            return false;
        }

        try {
            $response = Http::baseUrl($this->baseUrl)
                ->post('/auth/refresh/', ['refresh' => $refreshToken]);

            if ($response->successful()) {
                session(['access_token' => $response->json('access')]);
                return true;
            }
        } catch (\Exception $e) {
        }

        session()->forget(['access_token', 'refresh_token', 'user']);
        return false;
    }

    public function register(array $data): array
    {
        $response = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson()
            ->post('/auth/register/', $data);

        if ($response->successful()) {
            $this->storeTokens($response->json());
        }

        return $this->handleResponse($response);
    }

    public function login(string $email, string $password): array
    {
        $response = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson()
            ->post('/auth/login/', [
                'email' => $email,
                'password' => $password,
            ]);

        if ($response->successful()) {
            $this->storeTokens($response->json());
        }

        return $this->handleResponse($response);
    }

    public function logout(): void
    {
        try {
            $this->request()->post('/auth/logout/', [
                'refresh' => session('refresh_token'),
            ]);
        } catch (\Exception $e) {
        }

        session()->forget(['access_token', 'refresh_token', 'user']);
    }

    public function getCurrentUser(): ?array
    {
        $response = $this->request()->get('/auth/me/');

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->get('/auth/me/');
        }

        return $response->successful() ? $response->json() : null;
    }

    protected function storeTokens(array $data): void
    {
        session([
            'access_token' => $data['access'],
            'refresh_token' => $data['refresh'],
            'user' => $data['user'],
        ]);
    }

    public function getProducts(int $page = 1, int $pageSize = 20): array
    {
        $response = $this->request()->get('/products/', [
            'page' => $page,
            'page_size' => $pageSize,
        ]);

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->get('/products/', [
                'page' => $page,
                'page_size' => $pageSize,
            ]);
        }

        return $this->handleResponse($response);
    }

    public function getProduct(string $id): array
    {
        $response = $this->request()->get("/products/{$id}/");

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->get("/products/{$id}/");
        }

        return $this->handleResponse($response);
    }

    public function deleteProduct(string $id, string $shopId): array
    {
        $response = $this->request()->delete("/products/{$id}/", [
            'shop_id' => $shopId,
        ]);

        return $this->handleResponse($response);
    }

    public function createLink(string $url, string $userId): array
    {
        $payload = ['url' => $url, 'user_id' => $userId];
        $response = $this->request()->post('/links/', $payload);

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->post('/links/', $payload);
        }

        return $this->handleResponse($response);
    }

    public function getLink(string $id): array
    {
        $response = $this->request()->get("/links/{$id}/");

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->get("/links/{$id}/");
        }

        return $this->handleResponse($response);
    }

    public function getLinks(int $page = 1, int $pageSize = 100): array
    {
        $response = $this->request()->get('/links/', [
            'page' => $page,
            'page_size' => $pageSize,
        ]);

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->get('/links/', [
                'page' => $page,
                'page_size' => $pageSize,
            ]);
        }

        return $this->handleResponse($response);
    }

    public function search(string $query, array $filters = []): array
    {
        $params = array_merge(['q' => $query], $filters);
        $response = $this->request()->get('/search/', $params);

        if ($response->status() === 401 && $this->handleUnauthorized()) {
            $response = $this->request()->get('/search/', $params);
        }

        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): array
    {
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->json('detail') ?? 'Có lỗi xảy ra',
            'status' => $response->status(),
        ];
    }
}
