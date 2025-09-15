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
