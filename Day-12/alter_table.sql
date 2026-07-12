-- ============================================================
-- alter_table.sql
-- Already have the Day 9 "student_management" database?
-- Run this instead of schema.sql — it just adds the one new
-- column Day 10 needs (updated_at), without touching your data.
-- ============================================================

USE student_management;

ALTER TABLE students
  ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
