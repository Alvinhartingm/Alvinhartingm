<?php
namespace App\Models;

class Lead
{
    public static function all(\PDO $pdo, int $tenantId): array
    {
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE tenant_id = ?");
        $stmt->execute([$tenantId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
