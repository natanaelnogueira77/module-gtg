INSERT INTO config (meta, value) VALUES 
    ('style', 'light'), 
    ('login_img', 'storage/users/user1/plain-blue-background.jpg');

INSERT INTO usuario_tipo (id, name_sing, name_plur, created_at, updated_at) VALUES 
    (1, 'Administrador', 'Administradores', '2022-11-10 00:00:00', '2022-11-10 00:00:00'), 
    (2, 'Usuário', 'Usuários', '2022-11-10 00:00:00', '2022-11-10 00:00:00');
    
-- Senha é "starter"
INSERT INTO usuario (id, utip_id, name, password, email, slug, token, created_at, updated_at) VALUES 
    (1, 1, 'Admin', '$2y$10$ySA6iwTKmK.yfnNTvy3jWuG7jWUSaX.Neu93m5OdNJJtgndviO/ti', 'admin@projectexample.com', 'adm', '', '2022-11-10 00:00:00', '2022-11-10 00:00:00');