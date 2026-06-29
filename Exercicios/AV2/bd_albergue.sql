CREATE DATABASE IF NOT EXISTS bd_albergue;
USE bd_albergue;

CREATE TABLE IF NOT EXISTS quartos (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  categoria VARCHAR(50) NOT NULL,
  titulo VARCHAR(100) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  capacidade INT(11) NOT NULL,
  imagem VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS reservas (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  quarto_id INT(11) NOT NULL,
  cliente_nome VARCHAR(100) NOT NULL,
  cliente_email VARCHAR(100) NOT NULL,
  data_checkin DATE NOT NULL,
  data_checkout DATE NOT NULL,
  qtd_pessoas INT(11) NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL,
  data_transacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO quartos (categoria, titulo, preco, capacidade, imagem) VALUES
('especial', 'Dormitório Misto Premium', 85.00, 6, 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&auto=format&fit=crop&q=80'),
('especial', 'Quarto Duplo Casal', 220.00, 2, 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=600&auto=format&fit=crop&q=80'),
('especial', 'Dormitório Ar Condicionado', 95.00, 8, 'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=600&auto=format&fit=crop&q=80'),
('especial', 'Suíte Família Santa Teresa', 350.00, 4, 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&auto=format&fit=crop&q=80'),
('padrao', 'Dormitório Econômico', 55.00, 8, 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&auto=format&fit=crop&q=80'),
('padrao', 'Quarto Beliche Simples', 110.00, 2, 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=600&auto=format&fit=crop&q=80'),
('padrao', 'Dormitório 4 Camas', 75.00, 4, 'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=600&auto=format&fit=crop&q=80'),
('padrao', 'Quarto Individual Coletivo', 90.00, 1, 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=600&auto=format&fit=crop&q=80');