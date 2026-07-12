-- ============================================================
-- schema.sql
-- Run this in phpMyAdmin (Import tab, or paste into the SQL tab)
-- to create the database and table used by this project.
-- ============================================================

CREATE DATABASE IF NOT EXISTS student_management
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE student_management;

CREATE TABLE IF NOT EXISTS students (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)  NOT NULL,
    email           VARCHAR(150)  NOT NULL UNIQUE,
    branch          VARCHAR(50)   NOT NULL,
    cgpa            DECIMAL(3,2)  NOT NULL,

    -- Extra fields from the "Improve Your Project Tonight" assignment
    course          VARCHAR(100)  NULL,
    address         TEXT          NULL,
    photo           VARCHAR(255)  NULL,   -- stores a filename/path, not the actual image
    date_registered TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,

    -- Bonus field from Day 10, Module 1: records the last time a row was edited.
    -- ON UPDATE CURRENT_TIMESTAMP means MySQL refreshes this automatically
    -- every time we run an UPDATE query on a row — no PHP code needed for it.
    updated_at      TIMESTAMP     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);
