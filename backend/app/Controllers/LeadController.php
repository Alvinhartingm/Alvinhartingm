<?php
namespace App\Controllers;

use App\Models\Lead;

class LeadController
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $tenantId = (int)($_GET['tenant_id'] ?? 1);
        $leads = Lead::all($this->pdo, $tenantId);
        header('Content-Type: application/json');
        echo json_encode($leads);
    }
}
