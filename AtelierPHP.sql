

CREATE DATABASE IF NOT EXISTS gestion_etudiants
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE gestion_etudiants;


-- Table: user
CREATE TABLE IF NOT EXISTS user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- store hashed passwords (password_hash)
    role     ENUM('admin', 'user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB;


-- Table: section
CREATE TABLE IF NOT EXISTS section (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    designation VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- Table: etudiant
CREATE TABLE IF NOT EXISTS etudiant (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    nom              VARCHAR(150) NOT NULL,
    image            VARCHAR(255),                   -- path to uploaded image file
    date_naissance   DATE,
    section_id       INT,
    CONSTRAINT fk_etudiant_section
        FOREIGN KEY (section_id) REFERENCES section(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;


-- Sample Data
-- Users (passwords are hashed versions of 'admin123' and 'user123')
INSERT INTO user (username, password, role) VALUES
    ('admin',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
    ('etudiant', '$2y$10$TKh8H1.PfuAo5AMslUOkKuobgb7Gb7i/bR4R.bkb3tFSrZ7Xm1CW', 'user');
-- Sections
INSERT INTO section (designation, description) VALUES
    ('GL2',  'Génie Logiciel - 2ème année'),
    ('RT2',  'Réseaux et Télécommunications - 2ème année'),
    ('IIA2', 'Informatique Industrielle et Automatique - 2ème année');
-- Students
INSERT INTO etudiant (nom, image, date_naissance, section_id) VALUES
    ('Ahmed Ben Ali',     NULL, '2003-05-14', 1),
    ('Sarra Trabelsi',    NULL, '2002-11-20', 1),
    ('Mohamed Chaabane',  NULL, '2003-02-08', 2),
    ('Ines Hamdi',        NULL, '2002-09-30', 2),
    ('Youssef Rekik',     NULL, '2003-07-17', 3);