-- Creating Tables
CREATE TABLE users (
    userID INT NOT NULL,
    FName VARCHAR(50) NOT NULL,
    LName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('user','admin') DEFAULT 'user',
    Genre1 VARCHAR(100),
    Genre2 VARCHAR(100),
    Genre3 VARCHAR(100)
);

CREATE TABLE author (
    authorID INT NOT NULL,
    authorName VARCHAR(100) NOT NULL
);

CREATE TABLE books (
    mmsID VARCHAR(20) NOT NULL,
    Title VARCHAR(150) NOT NULL,
    callNumber VARCHAR(50) NOT NULL,
    authorID INT NOT NULL
);

CREATE TABLE books_copy (
    Barcode VARCHAR(30) NOT NULL,
    mmsID VARCHAR(20) NOT NULL,
    status VARCHAR(30) NOT NULL
);

CREATE TABLE genres (
    genreID INT NOT NULL,
    genreName VARCHAR(50) NOT NULL
);

CREATE TABLE book_genre (
    mmsID VARCHAR(20) NOT NULL,
    genreID INT NOT NULL
);

CREATE TABLE prefers (
    userID INT NOT NULL,
    genreID INT NOT NULL
);

CREATE TABLE is_recommended (
    recID INT NOT NULL,
    userID INT NOT NULL,
    mmsID VARCHAR(20) NOT NULL
);

CREATE TABLE book_log (
    logID INT NOT NULL,
    mmsID VARCHAR(20) NOT NULL,
    Action VARCHAR(50) NOT NULL,
    performedBy INT NOT NULL,
    timeStamp DATETIME NOT NULL
);

-- Constraint for PRIMARY KEYS
ALTER TABLE users
ADD CONSTRAINT pk_users PRIMARY KEY (userID);

ALTER TABLE author
ADD CONSTRAINT pk_author PRIMARY KEY (authorID);

ALTER TABLE books
ADD CONSTRAINT pk_books PRIMARY KEY (mmsID);

ALTER TABLE books_copy
ADD CONSTRAINT pk_books_copy PRIMARY KEY (Barcode);

ALTER TABLE genres
ADD CONSTRAINT pk_genres PRIMARY KEY (genreID);

ALTER TABLE book_genre
ADD CONSTRAINT pk_book_genre PRIMARY KEY (mmsID, genreID);

ALTER TABLE prefers
ADD CONSTRAINT pk_prefers PRIMARY KEY (userID, genreID);

ALTER TABLE is_recommended
ADD CONSTRAINT pk_is_recommended PRIMARY KEY (recID);

ALTER TABLE book_log
ADD CONSTRAINT pk_book_log PRIMARY KEY (logID);

-- Constraint for FOREIGN KEYS
ALTER TABLE books
ADD CONSTRAINT fk_books_author
FOREIGN KEY (authorID) REFERENCES author(authorID);

ALTER TABLE books_copy
ADD CONSTRAINT fk_copy_books
FOREIGN KEY (mmsID) REFERENCES books(mmsID);

ALTER TABLE book_genre
ADD CONSTRAINT fk_bookgenre_books
FOREIGN KEY (mmsID) REFERENCES books(mmsID);

ALTER TABLE book_genre
ADD CONSTRAINT fk_bookgenre_genres
FOREIGN KEY (genreID) REFERENCES genres(genreID);

ALTER TABLE prefers
ADD CONSTRAINT fk_prefers_users
FOREIGN KEY (userID) REFERENCES users(userID);

ALTER TABLE prefers
ADD CONSTRAINT fk_prefers_genres
FOREIGN KEY (genreID) REFERENCES genres(genreID);

ALTER TABLE is_recommended
ADD CONSTRAINT fk_recommended_users
FOREIGN KEY (userID) REFERENCES users(userID);

ALTER TABLE is_recommended
ADD CONSTRAINT fk_recommended_books
FOREIGN KEY (mmsID) REFERENCES books(mmsID);

ALTER TABLE book_log
ADD CONSTRAINT fk_log_books
FOREIGN KEY (mmsID) REFERENCES books(mmsID);

ALTER TABLE book_log
ADD CONSTRAINT fk_log_users
FOREIGN KEY (PerformedBy) REFERENCES users(userID);
