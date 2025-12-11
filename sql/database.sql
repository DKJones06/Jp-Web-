-- ------------------------------------
-- USERS
-- ------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------
-- ROLES
-- ------------------------------------
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(100) NOT NULL UNIQUE
);

-- ------------------------------------
-- PERMISSIONS
-- ------------------------------------
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission_key VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255)
);

-- ------------------------------------
-- ROLE → PERMISSIONS (many-to-many)
-- ------------------------------------
CREATE TABLE IF NOT EXISTS role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    permission_id INT NOT NULL
);

-- ------------------------------------
-- CATEGORIES
-- ------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL
);

-- ------------------------------------
-- NOTES
-- ------------------------------------
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category_id INT DEFAULT NULL,
    author_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- ------------------------------------
-- COMMENTS
-- ------------------------------------
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    is_deleted TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------
-- WARNINGS
-- ------------------------------------
CREATE TABLE IF NOT EXISTS warnings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    warning_text TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------
-- TIMEOUTS
-- ------------------------------------
CREATE TABLE IF NOT EXISTS timeouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_by INT NOT NULL,
    reason TEXT NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------
-- UPLOADS (bruges til billeder/diagrammer)
-- ------------------------------------
CREATE TABLE IF NOT EXISTS uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------
-- DEFAULT PERMISSIONS
-- ------------------------------------
INSERT IGNORE INTO permissions (permission_key, description) VALUES
('manage_users', 'Administrer brugere'),
('manage_roles', 'Rediger roller & rettigheder'),
('manage_notes', 'Opret, rediger og slet noter'),
('manage_categories', 'Administrer kategorier'),
('moderate_comments', 'Slet kommentarer'),
('warn_user', 'Giv advarsler'),
('timeout_user', 'Giv timeout');

-- ------------------------------------
-- ALTER (hvis avatar ikke findes)
-- ------------------------------------
ALTER TABLE users
ADD COLUMN avatar VARCHAR(255) NULL AFTER password;
