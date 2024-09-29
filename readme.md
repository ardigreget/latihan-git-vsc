
Database
DB Name : u934654818_akses
User : u934654818_akses
Pass : Sqn^y^8r

CREATE DATABASE IF NOT EXISTS testingwebsite;

USE testingwebsite;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    token VARCHAR(255) UNIQUE,
    is_premium BOOLEAN DEFAULT FALSE
);