-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS farmacia;

-- Usar la base de datos
USE farmacia;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL
);

-- Tabla de medicamentos
CREATE TABLE IF NOT EXISTS medicamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    importe DECIMAL(10, 2) NOT NULL
);

-- Tabla de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_cliente VARCHAR(100) NOT NULL,
    email_cliente VARCHAR(100) NOT NULL,
    medicamento VARCHAR(100) NOT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



--INSERT DE MEDICAMENTOS DE PRUEBA--

INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Paracetamol', 100, 5.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Ibuprofeno', 80, 7.50);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Amoxicilina', 50, 12.25);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Omeprazol', 60, 8.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Atorvastatina', 30, 15.75);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Aspirina', 120, 4.50);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Metformina', 40, 9.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Losartán', 25, 11.25);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Simvastatina', 35, 13.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Enalapril', 55, 6.50);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Diazepam', 15, 18.75);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Loratadina', 48, 7.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Tramadol', 22, 14.50);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Sertralina', 33, 10.25);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Cetirizina', 40, 6.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Escitalopram', 18, 15.50);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Ranitidina', 27, 9.25);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Clonazepam', 12, 12.99);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Warfarina', 10, 17.75);
INSERT INTO medicamentos (nombre, cantidad, importe) VALUES ('Furosemida', 38, 8.50);
