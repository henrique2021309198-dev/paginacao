CREATE TABLE IF NOT EXISTS totens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO totens (nome, codigo, ativo)
VALUES
    ('Totem Entrada', 'totem-entrada', 1),
    ('Totem Bar', 'totem-bar', 1),
    ('Totem Lounge', 'totem-lounge', 1)
ON DUPLICATE KEY UPDATE
    nome = VALUES(nome),
    ativo = VALUES(ativo);