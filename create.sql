-- Creating Tables
CREATE TABLE users (
    userID INT AUTO_INCREMENT,
    FName VARCHAR(50) NOT NULL,
    LName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('user','admin') DEFAULT 'user',
    PRIMARY KEY (userID)
);

CREATE TABLE author (
    authorID INT AUTO_INCREMENT,
    authorName VARCHAR(100) NOT NULL,
    PRIMARY KEY (authorID)
);

CREATE TABLE books (
    mmsID VARCHAR(20) NOT NULL,
    Title VARCHAR(150) NOT NULL,
    callNumber VARCHAR(50) NOT NULL,
    authorID INT NOT NULL,
    PRIMARY KEY (mmsID),
    CONSTRAINT fk_books_author
        FOREIGN KEY (authorID) REFERENCES author(authorID)
        ON DELETE CASCADE
);

CREATE TABLE books_copy (
    Barcode VARCHAR(30) NOT NULL,
    mmsID VARCHAR(20) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (Barcode),
    CONSTRAINT fk_copy_books
        FOREIGN KEY (mmsID) REFERENCES books(mmsID)
        ON DELETE CASCADE
);

CREATE TABLE genres (
    genreID INT AUTO_INCREMENT,
    genreName VARCHAR(50) NOT NULL,
    PRIMARY KEY (genreID)
);

CREATE TABLE book_genre (
    mmsID VARCHAR(20) NOT NULL,
    genreID INT NOT NULL,
    PRIMARY KEY (mmsID, genreID),
    CONSTRAINT fk_bookgenre_books
        FOREIGN KEY (mmsID) REFERENCES books(mmsID)
        ON DELETE CASCADE,
    CONSTRAINT fk_bookgenre_genres
        FOREIGN KEY (genreID) REFERENCES genres(genreID)
        ON DELETE CASCADE
);

CREATE TABLE prefers (
    userID INT NOT NULL,
    genreID INT NOT NULL,
    PRIMARY KEY (userID, genreID),
    CONSTRAINT fk_prefers_users
        FOREIGN KEY (userID) REFERENCES users(userID)
        ON DELETE CASCADE,
    CONSTRAINT fk_prefers_genres
        FOREIGN KEY (genreID) REFERENCES genres(genreID)
        ON DELETE CASCADE
);

CREATE TABLE is_recommended (
    recID INT AUTO_INCREMENT,
    userID INT NOT NULL,
    mmsID VARCHAR(20) NOT NULL,
    PRIMARY KEY (recID),
    CONSTRAINT fk_recommended_users
        FOREIGN KEY (userID) REFERENCES users(userID)
        ON DELETE CASCADE,
    CONSTRAINT fk_recommended_books
        FOREIGN KEY (mmsID) REFERENCES books(mmsID)
        ON DELETE CASCADE
);

CREATE TABLE book_log (
    logID INT AUTO_INCREMENT,
    mmsID VARCHAR(20) NOT NULL,
    Action VARCHAR(50) NOT NULL,
    performedBy INT NOT NULL,
    timeStamp DATETIME NOT NULL,
    PRIMARY KEY (logID),
    CONSTRAINT fk_log_books
        FOREIGN KEY (mmsID) REFERENCES books(mmsID)
        ON DELETE CASCADE,
    CONSTRAINT fk_log_users
        FOREIGN KEY (performedBy) REFERENCES users(userID)
        ON DELETE CASCADE
);