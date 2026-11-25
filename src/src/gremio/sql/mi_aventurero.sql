-- mi_aventurero.sql · INSERT del aventurero propio
-- Hash generado con: docker compose exec www php -r "echo password_hash('Roble2026', PASSWORD_DEFAULT), PHP_EOL;"

INSERT INTO aventureros (usuario, nombre, clase, password_hash) VALUES
('roble', 'Roble <el Explorador>', 'Explorador', '$2y$10$Kw0VW7AmSAxdEPrFiMDHT.CsdqPQfY9GQBK38YYBovgGsMiALq/Um');
