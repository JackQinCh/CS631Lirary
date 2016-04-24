-- Create Database
-- Create Library Database
CREATE DATABASE Library;
-- Create Tables
-- Create Publisher Table
CREATE TABLE PUBLISHER(
  PUBLISHERID INT NOT NULL AUTO_INCREMENT,
  PUBNAME VARCHAR(50) NOT NULL ,
  ADDRESS VARCHAR(50),
  PRIMARY KEY (PUBLISHERID)
);
-- Create Document Table
CREATE TABLE DOCUMENT(
  DOCID VARCHAR(10) NOT NULL,
  TITLE VARCHAR(100) NOT NULL ,
  PDATE YEAR(4),
  PUBLISHERID INT,
  PRIMARY KEY (DOCID),
  CONSTRAINT DOCPUBFK
  FOREIGN KEY (PUBLISHERID) REFERENCES PUBLISHER(PUBLISHERID)
    ON DELETE SET NULL ON UPDATE CASCADE
);
-- Create Book Table
CREATE TABLE BOOK(
  DOCID VARCHAR(10) NOT NULL ,
  ISBN VARCHAR(13) NOT NULL ,
  PRIMARY KEY (DOCID),
  CONSTRAINT BOOKDOCFK
  FOREIGN KEY (DOCID) REFERENCES DOCUMENT(DOCID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Author Table
CREATE TABLE AUTHOR (
  AUTHORID INT NOT NULL AUTO_INCREMENT,
  ANAME VARCHAR(20) NOT NULL ,
  PRIMARY KEY (AUTHORID)
);
-- Create Writes Table
CREATE TABLE WRITES (
  AUTHORID INT NOT NULL ,
  DOCID VARCHAR(10) NOT NULL ,
  PRIMARY KEY (AUTHORID,DOCID),
  CONSTRAINT AUWRITEFK
  FOREIGN KEY (AUTHORID) REFERENCES AUTHOR(AUTHORID)
    ON DELETE CASCADE ON UPDATE CASCADE ,
  CONSTRAINT WRITEDOCFK
  FOREIGN KEY (DOCID) REFERENCES BOOK(DOCID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Proceedings Table
CREATE TABLE PROCEEDINGS (
  DOCID VARCHAR(10) NOT NULL ,
  CDATE DATE NOT NULL ,
  CLOCATION VARCHAR(100) NOT NULL ,
  CEDITOR VARCHAR(50) NOT NULL ,
  PRIMARY KEY (DOCID),
  CONSTRAINT PRODOCFK
  FOREIGN KEY (DOCID) REFERENCES DOCUMENT(DOCID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Chief_editor Table
CREATE TABLE CHIEF_EDITOR (
  EDITOR_ID INT NOT NULL AUTO_INCREMENT,
  ENAME VARCHAR(30) NOT NULL ,
  PRIMARY KEY (EDITOR_ID)
);
-- Create Journal_volume Table
CREATE TABLE JOURNAL_VOLUME (
  DOCID VARCHAR(10) NOT NULL ,
  JVOLUME INT NOT NULL ,
  EDITOR_ID INT,
  PRIMARY KEY (DOCID),
  CONSTRAINT JOURDOCFK
  FOREIGN KEY (DOCID) REFERENCES DOCUMENT(DOCID)
    ON DELETE CASCADE ON UPDATE CASCADE ,
  CONSTRAINT JOURCEDTFK
  FOREIGN KEY (EDITOR_ID) REFERENCES CHIEF_EDITOR(EDITOR_ID)
    ON DELETE SET NULL ON UPDATE CASCADE
);
-- Create Journal_issue Table
CREATE TABLE JOURNAL_ISSUE (
  DOCID VARCHAR(10) NOT NULL ,
  ISSUE_NO INT NOT NULL ,
  SCOPE VARCHAR(50),
  PRIMARY KEY (DOCID,ISSUE_NO),
  CONSTRAINT ISSUEDOCFK
  FOREIGN KEY (DOCID) REFERENCES JOURNAL_VOLUME(DOCID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Inv_editor Table
CREATE TABLE INV_EDITOR (
  DOCID VARCHAR(10) NOT NULL ,
  ISSUE_NO INT NOT NULL ,
  IENAME VARCHAR(30) NOT NULL ,
  PRIMARY KEY (DOCID,ISSUE_NO,IENAME),
  CONSTRAINT INVDOCFK
  FOREIGN KEY (DOCID,ISSUE_NO) REFERENCES JOURNAL_ISSUE(DOCID,ISSUE_NO)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Branch Table
CREATE TABLE BRANCH (
  LIBID INT NOT NULL AUTO_INCREMENT,
  LNAME VARCHAR(30) NOT NULL ,
  LLOCATION VARCHAR(50) NOT NULL ,
  PRIMARY KEY (LIBID)
);
-- Create Copy Table
CREATE TABLE COPY (
  DOCID VARCHAR(10) NOT NULL ,
  COPYNO INT NOT NULL ,
  LIBID INT NOT NULL ,
  POSITION CHAR(6) NOT NULL UNIQUE,
  PRIMARY KEY (DOCID,COPYNO,LIBID),
  CONSTRAINT COPYDOCFK
  FOREIGN KEY (DOCID) REFERENCES DOCUMENT(DOCID)
    ON DELETE CASCADE ON UPDATE CASCADE ,
  CONSTRAINT COPYBRANCHFK
  FOREIGN KEY (LIBID) REFERENCES BRANCH(LIBID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Reader Table
CREATE TABLE READER (
  READERID VARCHAR(10) NOT NULL ,
  RTYPE VARCHAR(15),
  RNAME VARCHAR(30) NOT NULL ,
  ADDRESS VARCHAR(50),
  PRIMARY KEY (READERID)
);
-- Create Reserves Table
CREATE TABLE RESERVES (
  RESUMBER INT NOT NULL AUTO_INCREMENT,
  READERID VARCHAR(10) NOT NULL ,
  DOCID VARCHAR(10) NOT NULL ,
  COPYNO INT NOT NULL ,
  LIBID INT NOT NULL ,
  DTIME DATETIME NOT NULL ,
  PRIMARY KEY (RESUMBER),
  CONSTRAINT RESREADERFK
  FOREIGN KEY (READERID) REFERENCES READER(READERID)
    ON DELETE CASCADE ON UPDATE CASCADE ,
  CONSTRAINT RESCOPYFK
  FOREIGN KEY (DOCID,COPYNO,LIBID) REFERENCES COPY(DOCID, COPYNO, LIBID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Borrows Table
CREATE TABLE BORROWS (
  BORNUMBER INT NOT NULL AUTO_INCREMENT,
  READERID VARCHAR(10) NOT NULL ,
  DOCID VARCHAR(10) NOT NULL ,
  COPYNO INT NOT NULL ,
  LIBID INT NOT NULL ,
  BDTIME DATETIME NOT NULL ,
  RDTIME DATETIME,
  PRIMARY KEY (BORNUMBER),
  CONSTRAINT BORREADERFK
  FOREIGN KEY (READERID) REFERENCES READER(READERID)
    ON DELETE CASCADE ON UPDATE CASCADE ,
  CONSTRAINT BORCOPYFK
  FOREIGN KEY (DOCID,COPYNO,LIBID) REFERENCES COPY(DOCID,COPYNO,LIBID)
    ON DELETE CASCADE ON UPDATE CASCADE
);
-- Insert Datas
-- Insert Publisher Datas
INSERT INTO PUBLISHER
(PUBLISHERID, PUBNAME, ADDRESS)
VALUES
  (1, 'Crown Publishing Group', '1745 Broadway, New York, NY 10019'),
  (2, 'Wahida Clark Publishing', '60 Evergreen Pl # 904, East Orange, NJ 07018'),
  (3, 'Catholic Book Publishing Corporation', '77 West End Rd, Totowa, NJ 07512'),
  (4, 'Atticus Books', '20 Waverly Pl, Madison, NJ 07940'),
  (5, 'Enslow Publishers', '40 Industrial Rd, Berkeley Heights, NJ 07922'),
  (6, 'Atticus Books LLC', '39 Longview Ave, Madison, NJ 07940'),
  (7, 'New Horizon Press Publishers', '34 Church St, Liberty Corner, NJ 07938'),
  (8, 'New Jersey Hills Media Group', '19, 17 Morristown Rd, Bernardsville, NJ 07924'),
  (9, 'Publishers Circulation', '699 Washington St, Hackettstown, NJ 07840'),
  (10, 'Lehigh Valley Style', '3245 Freemansburg Ave, Palmer Township, PA 18045');
-- Insert Book Datas
INSERT INTO DOCUMENT (DOCID, TITLE, PDATE, PUBLISHERID) VALUES
  ('B1','Database : step-by-step','1990',10),
  ('B2','Database : principles, programming, performance','2001',1),
  ('B3','The Gene Expression Omnibus Database','2001',2),
  ('B4','Java : an introduction to problem solving & programming','2008',3),
  ('B5','Java : the complete reference','2007',4),
  ('B6','Java : how to program','2007',5),
  ('B7','Java : a beginner''s guide','2006',6),
  ('B8','Java : the complete reference, J2SE 5 edition','2005',4),
  ('B9','Python : the complete reference','2001',7),
  ('B10','C : From Theory to Practice','2014',8),
  ('B11','C : how to program','2010',9);
INSERT INTO AUTHOR (AUTHORID, ANAME) VALUES
  (1,'Gillenson, Mark L'),
  (2,'O''Neil, Patrick'),
  (3,'Clough, Emily'),(4,'Barrett, Tanya'),(5,'Mathe, Ewy'),(6,'Davis, Sean'),
  (7,'Savitch, Walter J.'),
  (8,'Schildt, Herbert'),
  (9,'Deitel, Paul J.'),
  (10,'Brown, Martin'),
  (11,'Tselikis, George S.');
INSERT INTO BOOK (DOCID, ISBN) VALUES
  ('B1','0471617598'),
  ('B2','1558602194'),
  ('B3','9781493935789'),
  ('B4','9780136130888'),
  ('B5','9780072263855'),
  ('B6','9780132222204'),
  ('B7','9780072263848'),
  ('B8','0072230738'),
  ('B9','007212718X'),
  ('B10','9781482214512'),
  ('B11','9780136123569');
INSERT INTO WRITES (AUTHORID, DOCID) VALUES
  (1,'B1'),
  (2,'B2'),
  (3,'B3'),(4,'B3'),(5,'B3'),(6,'B3'),
  (7,'B4'),
  (8,'B5'),
  (9,'B6'),
  (8,'B7'),
  (8,'B8'),
  (10,'B9'),
  (11,'B10'),
  (9,'B11');
-- Insert Journal Datas
INSERT INTO DOCUMENT (DOCID, TITLE, PDATE, PUBLISHERID) VALUES
  ('J1', 'The Beckman Report on Database Research', '2016', 2),
  ('J2', 'Disambiguating Databases','2015', 6),
  ('J3', 'A Library in the Palm of Your Hand: Mobile Services in Top 100 University Libraries','2015', 8),
  ('J4', 'THE EXCITEMENT OF DATABASE SYSTEMS','2015',1);
INSERT INTO CHIEF_EDITOR (EDITOR_ID, ENAME) VALUES
  (1, 'ABADI, DANIEL'),
  (2, 'RICHARDSON, RICK'),
  (4, 'Yan Quan Liu'),
  (5, 'DiDio, Laura');
INSERT INTO JOURNAL_VOLUME (DOCID, JVOLUME, EDITOR_ID) VALUES
  ('J1', 1, 1),
  ('J2', 2, 2),
  ('J3', 1, 4),
  ('J4', 3, 5);
INSERT INTO JOURNAL_ISSUE (DOCID, ISSUE_NO, SCOPE) VALUES
  ('J1', 1, 'CLOUD computing'),
  ('J1', 2, 'DATABASES'),
  ('J1', 3, 'RESEARCH'),
  ('J1', 4, 'SCALABILITY (Systems engineering)'),
  ('J1', 5, 'CONGRESSES'),
  ('J1', 6, 'BIG data -- Congresses'),
  ('J2', 1, 'CACHE memory'),
  ('J2', 2, 'HARD disks (Computer science)'),
  ('J2', 3, 'RELATIONAL databases'),
  ('J3', 1, 'ACADEMIC libraries'),
  ('J3', 2, 'SURVEYS'),
  ('J4', 1, 'DATABASES'),
  ('J4', 2, 'RESEARCH'),
  ('J4', 3, 'SOFTWARE analytics');
INSERT INTO INV_EDITOR (DOCID, ISSUE_NO, IENAME) VALUES
  ('J1', 1, 'AGRAWAL, RAKESH'),
  ('J1', 2, 'AILAMAKI, ANASTASIA'),
  ('J1', 3, 'BALAZINSKA, MAGDALENA'),
  ('J1', 3, 'BERNSTEIN, PHILIP A.'),
  ('J1', 4, 'CAREY, MICHAEL J.'),
  ('J1', 5, 'CHAUDHURI, SURAJIT'),
  ('J1', 6, 'DEAN, JEFFREY'),
  ('J1', 6, 'DOAN, ANHAI'),
  ('J1', 4, 'FRANKLIN, MICHAEL J.'),
  ('J1', 1, 'GEHRKE, JOHANNES'),
  ('J1', 2, 'JAGADISH, H. V.'),
  ('J2', 1, 'RICHARDSON, RICK'),
  ('J2', 2, 'JAGADISH, H. V.'),
  ('J2', 3, 'FRANKLIN, MICHAEL J.'),
  ('J3', 1, 'Yan Quan Liu'),
  ('J3', 2, 'Yan Quan Liu'),
  ('J3', 2, 'Briggs, Sarah'),
  ('J4', 1, 'DiDio, Laura'),
  ('J4', 2, 'DiDio, Laura'),
  ('J4', 3, 'DiDio, Laura');
-- Insert Proceedings Datas
INSERT INTO DOCUMENT (DOCID, TITLE, PDATE, PUBLISHERID) VALUES
  ('P1', 'Proceedings : actions, making of place', '2013', 3),
  ('P2', 'Modern architecture in East Africa around independence', '2005', 1),
  ('P3', 'Proceedings / IEEE/ACM SIGGRAPH Symposium on Volume Visualization and Graphics', '2002', 4),
  ('P4', 'Graphics Interface 2001', '2001', 8),
  ('P5', 'American Society of Civil Engineers', '1955', 9);
INSERT INTO PROCEEDINGS (DOCID, CDATE, CLOCATION, CEDITOR) VALUES
  ('P1', '2013-04-01', 'Philadelphia, Pa. : Tyler School of Art, Temple University', 'Oskey, Eric'),
  ('P2', '2005-07-27', 'Utrecht : ArchiAfrika', 'Mike'),
  ('P3', '2002-10-28', 'Piscataway, N.J.', 'SIGGRAPH'),
  ('P4', '2001-06-07', 'Toronto, Ont. : Canadian Human-Computer Communications Society', 'Watson, Benjamin'),
  ('P5', '2955-12-02', 'New York', 'American Society of Civil Engineers');
-- Insert Branch Datas
INSERT INTO BRANCH (LIBID, LNAME, LLOCATION) VALUES
  (1, 'Mike Library', 'Newark'),
  (2, 'Jone Library', 'Harrison'),
  (3, 'Jack Library', 'New York');

-- Insert Copy Datas
INSERT INTO COPY (DOCID, COPYNO, LIBID, POSITION) VALUES
  ('B1', 1, 1, '001B03'),
  ('B1', 2, 1, '002B03'),
  ('B1', 3, 2, '003B03'),
  ('B1', 4, 1, '004B03'),
  ('B1', 5, 1, '005B03'),
  ('B1', 6, 1, '006B03'),
  ('B1', 7, 3, '101B03'),
  ('B1', 8, 3, '502F03'),
  ('B1', 9, 2, '803B03'),
  ('B1', 10, 1, '804B03'),
  ('B1', 11, 1, '905B03'),
  ('B1', 12, 1, '976B03'),
  ('B2', 1, 2, '001A03'),
  ('B2', 2, 2, '002A03'),
  ('B2', 3, 2, '003A03'),
  ('B2', 4, 2, '004A03'),
  ('B3', 5, 3, '001A02'),
  ('B3', 6, 3, '002A02'),
  ('B3', 7, 3, '003A02'),
  ('B4', 1, 3, '001C03'),
  ('B5', 1, 1, '001F03'),
  ('B5', 2, 1, '002F03'),
  ('B5', 3, 1, '003F03'),
  ('B5', 4, 1, '004F03'),
  ('B5', 5, 1, '005F03'),
  ('B6', 1, 2, '001A09'),
  ('B6', 2, 3, '002A09'),
  ('B6', 3, 2, '003A09'),
  ('B6', 4, 2, '004A09'),
  ('B6', 5, 2, '005A09'),
  ('B7', 1, 2, '010A03'),
  ('B8', 1, 1, '012A03'),
  ('B8', 2, 1, '013A03'),
  ('B8', 3, 1, '014A03'),
  ('B8', 4, 1, '011A03'),
  ('B8', 5, 1, '002A13'),
  ('B9', 1, 1, '003A13'),
  ('B9', 2, 1, '104C03'),
  ('B9', 3, 1, '201A03'),
  ('B9', 4, 1, '012A83'),
  ('B10', 1, 3, '013F09'),
  ('B10', 2, 3, '004E03'),
  ('B10', 3, 2, '001E03'),
  ('B10', 4, 3, '002E03'),
  ('B11', 1, 3, '003E03'),
  ('B11', 2, 3, '004E83'),
  ('J1', 1, 2, '001G01'),
  ('J1', 2, 1, '002G01'),
  ('J1', 3, 2, '003G01'),
  ('J1', 4, 2, '004G01'),
  ('J2', 1, 2, '203B03'),
  ('J2', 2, 2, '204B03'),
  ('J3', 1, 3, '091B03'),
  ('J3', 2, 3, '092B03'),
  ('J3', 3, 3, '093B03'),
  ('J3', 4, 3, '094B03'),
  ('J4', 1, 1, '021A03'),
  ('J4', 2, 1, '022A03'),
  ('J4', 3, 1, '023A03'),
  ('P1', 1, 1, '024A03'),
  ('P1', 2, 1, '423A03'),
  ('P1', 3, 1, '024A83'),
  ('P1', 4, 3, '021B03'),
  ('P1', 5, 1, '122A03'),
  ('P1', 6, 1, '123A03'),
  ('P2', 1, 3, '004A18'),
  ('P2', 2, 3, '001A17'),
  ('P2', 3, 3, '002A14'),
  ('P2', 4, 3, '003A15'),
  ('P3', 1, 2, '064A16'),
  ('P4', 1, 3, '003A24'),
  ('P4', 2, 3, '004A23'),
  ('P4', 3, 2, '011A20'),
  ('P4', 4, 3, '042A43'),
  ('P4', 5, 3, '103A34'),
  ('P4', 6, 3, '084A67'),
  ('P5', 1, 1, '001A93'),
  ('P5', 2, 3, '002A93'),
  ('P5', 3, 3, '003A93'),
  ('P5', 4, 3, '004A93');
-- Insert Reader Datas
INSERT INTO READER (READERID, RTYPE, RNAME, ADDRESS) VALUES
  ('jz01', 'Student', 'Jack Qin', 'Harrison Ave., NJ');