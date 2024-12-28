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
    name VARCHAR(100),
    nb_of_categories TINYINT,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

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
    PRIMARY KEY (id)
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
SELECT * FROM categories;
SELECT * FROM transactions;
SELECT * FROM transactions_categories;
SELECT * FROM categories_level;

INSERT INTO categories(id, id_account, name) VALUES ("blabla", "account_677000c130801", "Test1");
INSERT INTO categories(id, id_account, name) VALUES ("blabla3", "account_677000c130801", "Test2");
INSERT INTO categories(id, id_account, name) VALUES ("blabla2", "account_677001023926c", "Test1");
INSERT INTO categories(id, id_account, name) VALUES ("blabla1", "account_677001023926c", "Test2");

DELETE FROM categories_level;
INSERT INTO categories_level(id_cat, level) VALUES ("blabla", 1);
INSERT INTO categories_level(id_cat, level) VALUES ("blabla3", 2);
INSERT INTO categories_level(id_cat, level) VALUES ("blabla2", 2);
INSERT INTO categories_level(id_cat, level) VALUES ("blabla1", 1);

INSERT INTO cat_hierarchy(id_cat_parent, id_cat_child) VALUES ("blabla1", "blabla2");

