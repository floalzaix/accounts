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
    FOREIGN KEY (id_transaction) REFERENCES transactions(id),
    FOREIGN KEY (id_category) REFERENCES categories(id)
);


