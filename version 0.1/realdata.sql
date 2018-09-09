LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/myfiles/mybooksystem/Book List -Sheet1.csv'
INTO TABLE `MainTable`
FIELDS TERMINATED BY '\t'
(SharickID, BookNameUK, BookNameCN, AuthorNameCN, Publisher,OtherNotes);
