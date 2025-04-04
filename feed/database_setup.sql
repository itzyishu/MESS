-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS mess_management;

-- Use the database
USE mess_management;

-- Create the mess_feedback table
CREATE TABLE IF NOT EXISTS mess_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    registration_number VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    block VARCHAR(20) NOT NULL,
    room VARCHAR(20) NOT NULL,
    mess_type VARCHAR(20) NOT NULL,
    form_date DATE NOT NULL,
    breakfast_suggestions TEXT,
    lunch_suggestions TEXT,
    snack_suggestions TEXT,
    dinner_suggestions TEXT,
    suggestion_days VARCHAR(100),
    mass_feasibility VARCHAR(10),
    repeat_frequency VARCHAR(20),
    additional_remarks TEXT,
    submission_date DATETIME NOT NULL
);