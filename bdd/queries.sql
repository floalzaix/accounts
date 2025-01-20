CREATE DATABASE accounts;

USE accounts;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id VARCHAR(50),
    name VARCHAR(100) NOT NULL UNIQUE,
    hash VARCHAR(256) NOT NULL,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS cat_hierarchy;
DROP TABLE IF EXISTS categories_level;
DROP TABLE IF EXISTS transactions_categories;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS accounts;

CREATE TABLE accounts (
    id VARCHAR(50),
    id_user VARCHAR(50),
    name VARCHAR(100) NOT NULL,
    nb_of_categories TINYINT NOT NULL,
    init_amount NUMERIC(10, 2) NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

/*ALTER TABLE accounts
ADD init_amount NUMERIC(10, 2) NULL DEFAULT NULL;*/

CREATE TABLE transactions (
    id VARCHAR(50),
    id_account VARCHAR(50),
    date DATE,
    title VARCHAR(100),
    bank_date DATE,
    amount INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_account) REFERENCES accounts(id)
);

DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    id VARCHAR(50),
    id_account VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    id_parent VARCHAR(50) NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_parent) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE transactions_categories (
    id_transaction VARCHAR(50),
    id_category VARCHAR(50),
    FOREIGN KEY (id_transaction) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (id_category) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE categories_level (
    id_cat VARCHAR(50),
    level TINYINT NOT NULL CHECK (level BETWEEN 1 AND 10),
    FOREIGN KEY (id_cat) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE cat_hierarchy (
    id_cat_parent vARCHAR(50),
    id_cat_child VARCHAR(50),
    FOREIGN KEY (id_cat_parent) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cat_child) REFERENCES categories(id) ON DELETE CASCADE
);

SELECT * FROM users;
SELECT * FROM accounts;

SELECT c1.id, c1.id_account, c1.name as "Name", c2.name as "Parent"
FROM categories c1
INNER JOIN categories c2 ON c1.id_parent=c2.id;

SELECT * FROM transactions;
SELECT * FROM transactions_categories;

SELECT c.name, cl.level
FROM categories_level cl
INNER JOIN categories c on cl.id_cat = c.id;

SELECT c2.name as "Parent", c1.name as "Child"
FROM cat_hierarchy
INNER JOIN categories c1 on cat_hierarchy.id_cat_child = c1.id
INNER JOIN categories c2 on cat_hierarchy.id_cat_parent = c2.id;

DELETE FROM categories;

SELECT table_name AS "Table",
       ROUND((data_length + index_length) / 1024 / 1024, 10) AS "Taille (MB)"
FROM information_schema.tables
WHERE table_schema = 'accounts';

FLUSH TABLES;
COMMIT;

