CREATE DATABASE accounts;

USE accounts;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id VARCHAR(50),
    name VARCHAR(100) NOT NULL UNIQUE,
    hash VARCHAR(256) NOT NULL,
    PRIMARY KEY (id)
);

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
    id_user VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE transactions_categories (
    id_transaction VARCHAR(50),
    id_category VARCHAR(50),
    FOREIGN KEY (id_transaction) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (id_category) REFERENCES categories(id) ON DELETE CASCADE
);

SELECT * FROM users;
SELECT * FROM accounts;
SELECT * FROM categories;
SELECT * FROM transactions;
SELECT * FROM transactions_categories;

INSERT INTO categories(id, id_user, name) VALUES ("blabla", "user_676c51f37b3b1", "Test1");
INSERT INTO categories(id, id_user, name) VALUES ("blabla3", "user_676c51f37b3b1", "Test2");

DELETE FROM categories WHERE name="other";