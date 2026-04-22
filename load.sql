/* You will have to run SHOW VARIABLES LIKE 'secure_file_priv'; then move csv files into that folder
and finally paste the filepath of csv file in the path portion of LOAD DATA INFILE command*/

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/users_data.csv"
INTO TABLE users
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/authors.csv"
INTO TABLE author
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/genre.csv"
INTO TABLE genres
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/book_data.csv"
INTO TABLE books
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS;

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/books_copy.csv"
INTO TABLE books_copy
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS;


LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/book_genre.csv"
INTO TABLE book_genre
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS;


LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/prefers.csv"
INTO TABLE prefers
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/is_recommended.csv"
INTO TABLE is_recommended
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS;

LOAD DATA INFILE "D:/CS 4347/project/data_for_project/csv/dummy2/book_log.csv"
INTO TABLE book_log
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS;