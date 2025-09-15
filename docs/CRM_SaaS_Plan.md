# Plan de Desarrollo CRM SaaS

## 1. Arquitectura y Tecnologías
### 1.1 Stack tecnológico gratuito
- **Backend:** PHP 8.2 + microframework MVC propio (compatible con cPanel).
- **Base de datos:** MariaDB 10.
- **Frontend:** HTML5, Bootstrap 5, JavaScript nativo.
- **IA:** Librería [php-ai/php-ml](https://github.com/php-ai/php-ml) para modelos predictivos.
- **Control de versiones:** Git + GitHub.

```json
{
  "require": {
    "php": "^8.2",
    "php-ai/php-ml": "^0.10"
  }
}
```

### 1.2 Esquema de base de datos multitenant
```sql
CREATE TABLE tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','manager','user') DEFAULT 'user',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT,
    name VARCHAR(100),
    email VARCHAR(100),
    status VARCHAR(50) DEFAULT 'new',
    score FLOAT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);
```

### 1.3 Estructura MVC
```php
// backend/app/Controllers/LeadController.php
namespace App\Controllers;
use App\Models\Lead;
class LeadController {
    private \PDO $pdo;
    public function __construct(\PDO $pdo) { $this->pdo = $pdo; }
    public function index(): void {
        $tenantId = (int)($_GET['tenant_id'] ?? 1);
        $leads = Lead::all($this->pdo, $tenantId);
        header('Content-Type: application/json');
        echo json_encode($leads);
    }
}
```

### 1.4 IA en el pipeline
```php
// backend/ai/LeadScoring.php
namespace App\AI;
use Phpml\Classification\LogisticRegression;
class LeadScoring {
    public function score(array $lead): float {
        $samples = [[0,0],[0,1],[1,0],[1,1]];
        $labels  = [0,0,0,1];
        $classifier = new LogisticRegression();
        $classifier->train($samples, $labels);
        return $classifier->predict([$lead['has_budget'], $lead['contacted']]);
    }
}
```

## 2. Módulos Funcionales
1. **Clientes y contactos.**
2. **Leads con scoring automático.**
3. **Productos y servicios.**
4. **Pipeline de ventas / oportunidades.**
5. **Cotizaciones con PDF (dompdf).**
6. **Tareas y calendario.**
7. **Reportes y dashboard con Chart.js.**

### 2.1 Gestión de leads con scoring
```php
// Uso en LeadController
global $pdo;
$scoring = new \App\AI\LeadScoring();
$score = $scoring->score(['has_budget'=>1,'contacted'=>0]);
```

### 2.2 Pipeline de ventas
```sql
CREATE TABLE deals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT,
    lead_id INT,
    stage ENUM('nuevo','propuesta','negociación','cerrado'),
    amount DECIMAL(10,2),
    expected_close DATE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (lead_id) REFERENCES leads(id)
);
```

### 2.3 Cotizaciones y documentos
```php
// Generar PDF con dompdf
generateQuotePdf($quoteData);
```

### 2.4 Dashboard ejecutivo
```javascript
const chart = new Chart(ctx, {
  type: 'bar',
  data: { labels: stages, datasets: [{ data: totals }] }
});
```

## 3. SaaS y Multiusuario
- Suscripciones con Stripe Checkout (sin coste fijo).
- Roles: admin, manager, user.
- Aislamiento por `tenant_id` en todas las tablas.
- Autenticación por sesiones PHP + hashed passwords.

```php
// middleware/checkTenant.php
if ($_SESSION['tenant_id'] !== $resourceTenantId) {
    http_response_code(403);
    exit('Acceso denegado');
}
```

## 4. Interfaz de Usuario
```html
<!-- frontend/index.html -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div id="lead-table" class="mt-5"></div>
```

## 5. Cronograma
| Fase | Módulo | Fechas |
|------|--------|--------|
| 1 | Configuración inicial y BD | 17-23 Feb 2025 |
| 2 | Leads y contactos | 24 Feb - 9 Mar 2025 |
| 3 | Pipeline y oportunidades | 10-23 Mar 2025 |
| 4 | Productos y cotizaciones | 24 Mar - 6 Abr 2025 |
| 5 | Tareas, dashboard e IA | 7-27 Abr 2025 |
| 6 | Suscripciones y despliegue MVP | 28 Abr - 11 May 2025 |

## 6. Despliegue en cPanel
```bash
# Via cPanel Git
$ git clone https://github.com/empresa/crm-saas.git
```

## 7. IA y análisis predictivo
- API gratuita: [HuggingFace Inference](https://huggingface.co/inference-api).
- Algoritmo: regresión logística para scoring de leads.
- Recomendaciones automáticas calculadas en cada cambio de etapa.
