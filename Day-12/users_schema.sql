-- ============================================================
-- users_schema.sql
-- Run this in phpMyAdmin, inside the student_management database,
-- to create the users table the login system checks against.
-- ============================================================

USE student_management;

CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,
    last_login  DATETIME      NULL DEFAULT NULL,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Seed one admin account so you have something to log in with.
-- NOTE: the password is stored in PLAIN TEXT here on purpose —
-- this bootcamp session focuses on the login/session FLOW.
-- Password hashing (password_hash / password_verify) is covered
-- in a later session and should replace this before going live.
--
-- Login with:
--   email:    admin@scrumdigital.com
--   password: admin123
-- ------------------------------------------------------------
INSERT INTO users (name, email, password)
VALUES ('Piyush Sharma', 'admin@scrumdigital.com', 'admin123');
