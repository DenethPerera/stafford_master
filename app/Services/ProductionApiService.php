<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductionApiService
{
    /**
     * Get the configured Http client with Bearer Token auth.
     */
    protected function client()
    {
        $baseUrl = config('services.opma_production.base_url') ?: env('OPMA_PRODUCTION_API_URL');
        $token = config('services.opma_production.token') ?: env('OPMA_PRODUCTION_API_TOKEN');

        if (empty($baseUrl)) {
            // Log warning or throw exception if baseURL is missing in production,
            // but return a dummy client or throw helper.
            Log::warning('OPMA Production Base URL is not configured in .env');
        }

        return Http::withToken($token)
            ->baseUrl($baseUrl)
            ->acceptJson()
            ->timeout(10);
    }

    /**
     * GET /api/GetEmployees
     * Fetch list of live employees.
     */
    public function getEmployees(): array
    {
        try {
            $response = $this->client()->get('/api/GetEmployees');
            if ($response->successful()) {
                $data = $response->json();
                $employees = $data['data']['employees'] ?? [];
                
                return array_map(function ($emp) {
                    $emp['id'] = $emp['emp_id'] ?? $emp['id'] ?? null;
                    $emp['name'] = $emp['emp_name_with_initial'] ?? $emp['name'] ?? null;
                    $emp['designation'] = $emp['designation'] ?? '—';
                    $emp['email'] = $emp['email'] ?? '—';
                    return $emp;
                }, $employees);
            }
            Log::error('Failed to fetch employees from production API: ' . $response->status() . ' - ' . $response->body());
            return $this->getMockEmployees();
        } catch (\Exception $e) {
            Log::error('Exception fetching employees from production API: ' . $e->getMessage());
            return $this->getMockEmployees();
        }
    }

    /**
     * GET /api/GetCompanyList
     * Fetch list of live companies.
     */
    public function getCompanies(): array
    {
        try {
            $response = $this->client()->get('/api/GetCompanyList');
            if ($response->successful()) {
                $data = $response->json();
                $companies = $data['data']['companies'] ?? [];
                
                return array_map(function ($comp) {
                    $comp['id'] = $comp['id'] ?? $comp['Companyid'] ?? null;
                    $comp['name'] = $comp['name'] ?? $comp['Companyname'] ?? null;
                    $comp['code'] = $comp['code'] ?? $comp['Companycode'] ?? null;
                    return $comp;
                }, $companies);
            }
            Log::error('Failed to fetch companies from production API: ' . $response->status() . ' - ' . $response->body());
            return $this->getMockCompanies();
        } catch (\Exception $e) {
            Log::error('Exception fetching companies from production API: ' . $e->getMessage());
            return $this->getMockCompanies();
        }
    }

    /**
     * POST /api/GetDepartmentList
     * Fetch departments by company ID. Passes Companyid via form data.
     */
    public function getDepartments($companyId): array
    {
        try {
            $response = $this->client()
                ->asForm()
                ->post('/api/GetDepartmentList', [
                    'Companyid' => $companyId
                ]);
                
            if ($response->successful()) {
                $data = $response->json();
                $departments = $data['data']['departments'] ?? [];
                
                return array_map(function ($dept) {
                    $dept['id'] = $dept['id'] ?? $dept['Departmentid'] ?? null;
                    $dept['name'] = $dept['name'] ?? $dept['Departmentname'] ?? null;
                    $dept['head'] = $dept['head'] ?? $dept['Departmenthead'] ?? $dept['dep_head_emp_id'] ?? null;
                    return $dept;
                }, $departments);
            }
            Log::error('Failed to fetch departments from production API: ' . $response->status() . ' - ' . $response->body());
            return $this->getMockDepartments($companyId);
        } catch (\Exception $e) {
            Log::error('Exception fetching departments from production API: ' . $e->getMessage());
            return $this->getMockDepartments($companyId);
        }
    }

    /**
     * Mock data helpers to ensure the system is functional and doesn't crash 
     * if the production API endpoint credentials are unset or the external API is offline.
     */
    private function getMockEmployees(): array
    {
        return [
            ['id' => 101, 'name' => 'Kavisha Devs', 'designation' => 'Lead Engineer', 'email' => 'kavisha@opma.com', 'status' => 'Active', 'emp_department' => 11],
            ['id' => 102, 'name' => 'Sunil Shantha', 'designation' => 'Machine Operator', 'email' => 'sunil.s@opma.com', 'status' => 'Active', 'emp_department' => 12],
            ['id' => 103, 'name' => 'Mahesh Kumara', 'designation' => 'Shift Leader', 'email' => 'mahesh.k@opma.com', 'status' => 'Active', 'emp_department' => 12],
            ['id' => 104, 'name' => 'Nimal Perera', 'designation' => 'Quality Inspector', 'email' => 'nimal.p@opma.com', 'status' => 'Active', 'emp_department' => 13],
            ['id' => 105, 'name' => 'Saman Rajapakse', 'designation' => 'Supervisor', 'email' => 'saman.r@opma.com', 'status' => 'Active', 'emp_department' => 11],
        ];
    }

    private function getMockCompanies(): array
    {
        return [
            ['id' => 1, 'name' => 'OPMA Garments (Pvt) Ltd', 'code' => 'OPMA-G', 'country' => 'Sri Lanka'],
            ['id' => 2, 'name' => 'Brandix Apparels', 'code' => 'BX-APP', 'country' => 'Sri Lanka'],
            ['id' => 3, 'name' => 'MAS Holdings', 'code' => 'MAS-H', 'country' => 'Sri Lanka'],
        ];
    }

    private function getMockDepartments($companyId): array
    {
        $all = [
            1 => [
                ['id' => 11, 'name' => 'Cutting Dept', 'head' => 'Saman Kumara'],
                ['id' => 12, 'name' => 'Sewing Dept', 'head' => 'Anura Silva'],
                ['id' => 13, 'name' => 'QC & Packing', 'head' => 'Deepthi Perera'],
            ],
            2 => [
                ['id' => 21, 'name' => 'Embroidery Dept', 'head' => 'Janaka Bandara'],
                ['id' => 22, 'name' => 'Ironing & Finishing', 'head' => 'Rohan Dias'],
            ],
            3 => [
                ['id' => 31, 'name' => 'Lazer Guide Dept', 'head' => 'Ruwan Perera'],
                ['id' => 32, 'name' => 'Washing Plant', 'head' => 'Chathura Gunasekara'],
            ]
        ];

        return $all[$companyId] ?? [
            ['id' => 91, 'name' => 'General Administration', 'head' => 'System Manager'],
        ];
    }
}
