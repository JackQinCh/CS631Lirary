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
  DOCID INT NOT NULL AUTO_INCREMENT,
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
  DOCID INT NOT NULL ,
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
  DOCID INT NOT NULL ,
  PRIMARY KEY (AUTHORID,DOCID),
  CONSTRAINT AUWRITEFK
  FOREIGN KEY (AUTHORID) REFERENCES AUTHOR(AUTHORID)
  ON DELETE CASCADE ON UPDATE CASCADE ,
  CONSTRAINT WRITEDOCFK
  FOREIGN KEY (DOCID) REFERENCES DOCUMENT(DOCID)
  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Proceedings Table
CREATE TABLE PROCEEDINGS (
  DOCID INT NOT NULL ,
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
  DOCID INT NOT NULL ,
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
  DOCID INT NOT NULL ,
  ISSUE_NO INT NOT NULL ,
  SCOPE VARCHAR(50),
  PRIMARY KEY (DOCID,ISSUE_NO),
  CONSTRAINT ISSUEDOCFK
  FOREIGN KEY (DOCID) REFERENCES JOURNAL_VOLUME(DOCID)
  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Create Inv_editor Table
CREATE TABLE INV_EDITOR (
  DOCID INT NOT NULL ,
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
  DOCID INT NOT NULL ,
  COPYNO INT NOT NULL ,
  LIBID INT NOT NULL ,
  POSITION CHAR(6) NOT NULL ,
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
  DOCID INT NOT NULL ,
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
  DOCID INT NOT NULL ,
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
  (1,'Database : step-by-step','1990',10),
  (2,'Database : principles, programming, performance','2001',1),
  (3,'The Gene Expression Omnibus Database','2001',2),
  (4,'Java : an introduction to problem solving & programming','2008',3),
  (5,'Java : the complete reference','2007',4),
  (6,'Java : how to program','2007',5),
  (7,'Java : a beginner''s guide','2006',6),
  (8,'Java : the complete reference, J2SE 5 edition','2005',4),
  (9,'Python : the complete reference','2001',7),
  (10,'C : From Theory to Practice','2014',8),
  (11,'C : how to program','2010',9);
INSERT INTO AUTHOR (AUTHORID, ANAME) VALUES
  (1,'Gillenson, Mark L'),
  (2,'O''Neil, Patrick'),
  (3,'Clough, Emily'),(4,'Barrett, Tanya'),(5,'Mathé, Ewy'),(6,'Davis, Sean'),
  (7,'Savitch, Walter J.'),
  (8,'Schildt, Herbert'),
  (9,'Deitel, Paul J.'),
  (10,'Brown, Martin'),
  (11,'Tselikis, George S.');
INSERT INTO BOOK (DOCID, ISBN) VALUES
  (1,'0471617598'),
  (2,'1558602194'),
  (3,'9781493935789'),
  (4,'9780136130888'),
  (5,'9780072263855'),
  (6,'9780132222204'),
  (7,'9780072263848'),
  (8,'0072230738'),
  (9,'007212718X'),
  (10,'9781482214512'),
  (11,'9780136123569');
INSERT INTO WRITES (AUTHORID, DOCID) VALUES
  (1,1),
  (2,2),
  (3,3),(4,3),(5,3),(6,3),
  (7,4),
  (8,5),
  (9,6),
  (8,7),
  (8,8),
  (10,9),
  (11,10),
  (9,11);
-- Insert Journal Datas
INSERT INTO DOCUMENT (DOCID, TITLE, PDATE, PUBLISHERID) VALUES
  (12, 'The Beckman Report on Database Research', '2016', 2),
  (13, 'Disambiguating Databases','2015', 6),
  (14, 'A Library in the Palm of Your Hand: Mobile Services in Top 100 University Libraries','2015', 8),
  (15, 'THE EXCITEMENT OF DATABASE SYSTEMS','2015',1);
INSERT INTO CHIEF_EDITOR (EDITOR_ID, ENAME) VALUES
  (1, 'ABADI, DANIEL'),
  (2, 'RICHARDSON, RICK'),
  (4, 'Yan Quan Liu'),
  (5, 'DiDio, Laura');
INSERT INTO JOURNAL_VOLUME (DOCID, JVOLUME, EDITOR_ID) VALUES
  (12, 1, 1),
  (13, 2, 2),
  (14, 1, 4),
  (15, 3, 5);
INSERT INTO JOURNAL_ISSUE (DOCID, ISSUE_NO, SCOPE) VALUES
  (12, 1, 'CLOUD computing'),
  (12, 2, 'DATABASES'),
  (12, 3, 'RESEARCH'),
  (12, 4, 'SCALABILITY (Systems engineering)'),
  (12, 5, 'CONGRESSES'),
  (12, 6, 'BIG data -- Congresses'),
  (13, 1, 'CACHE memory'),
  (13, 2, 'HARD disks (Computer science)'),
  (13, 3, 'RELATIONAL databases'),
  (14, 1, 'ACADEMIC libraries'),
  (14, 2, 'SURVEYS'),
  (15, 1, 'DATABASES'),
  (15, 2, 'RESEARCH'),
  (15, 3, 'SOFTWARE analytics');
INSERT INTO INV_EDITOR (DOCID, ISSUE_NO, IENAME) VALUES
  (12, 1, 'AGRAWAL, RAKESH'),
  (12, 2, 'AILAMAKI, ANASTASIA'),
  (12, 3, 'BALAZINSKA, MAGDALENA'),
  (12, 3, 'BERNSTEIN, PHILIP A.'),
  (12, 4, 'CAREY, MICHAEL J.'),
  (12, 5, 'CHAUDHURI, SURAJIT'),
  (12, 6, 'DEAN, JEFFREY'),
  (12, 6, 'DOAN, ANHAI'),
  (12, 4, 'FRANKLIN, MICHAEL J.'),
  (12, 1, 'GEHRKE, JOHANNES'),
  (12, 2, 'JAGADISH, H. V.'),
  (13, 1, 'RICHARDSON, RICK'),
  (13, 2, 'JAGADISH, H. V.'),
  (13, 3, 'FRANKLIN, MICHAEL J.'),
  (14, 1, 'Yan Quan Liu'),
  (14, 2, 'Yan Quan Liu'),
  (14, 2, 'Briggs, Sarah'),
  (15, 1, 'DiDio, Laura'),
  (15, 2, 'DiDio, Laura'),
  (15, 3, 'DiDio, Laura');
-- Insert Proceedings Datas
INSERT INTO DOCUMENT (DOCID, TITLE, PDATE, PUBLISHERID) VALUES
  (16, 'Proceedings : actions, making of place', '2013', 3),
  (17, 'Modern architecture in East Africa around independence', '2005', 1),
  (18, 'Proceedings / IEEE/ACM SIGGRAPH Symposium on Volume Visualization and Graphics', '2002', 4),
  (19, 'Graphics Interface 2001', '2001', 8),
  (20, 'American Society of Civil Engineers', '1955', 9);
INSERT INTO PROCEEDINGS (DOCID, CDATE, CLOCATION, CEDITOR) VALUES
  (16, '2013-04-01', 'Philadelphia, Pa. : Tyler School of Art, Temple University', 'Oskey, Eric'),
  (17, '2005-07-27', 'Utrecht : ArchiAfrika', 'Mike'),
  (18, '2002-10-28', 'Piscataway, N.J.', 'SIGGRAPH'),
  (19, '2001-06-07', 'Toronto, Ont. : Canadian Human-Computer Communications Society', 'Watson, Benjamin'),
  (20, '2955-12-02', 'New York', 'American Society of Civil Engineers');
-- Insert Branch Datas
INSERT INTO BRANCH (LIBID, LNAME, LLOCATION) VALUES
  (1, 'Mike Library', 'Newark'),
  (2, 'Jone Library', 'Harrison'),
  (3, 'Jack Library', 'New York');
-- Insert Copy Datas
INSERT INTO COPY (DOCID, COPYNO, LIBID, POSITION) VALUES
  (1, 1, 1, '001B03'),
  (1, 2, 1, '002B03'),
  (1, 3, 1, '003B03'),
  (1, 4, 1, '004B03'),
  (1, 5, 1, '005B03'),
  (1, 6, 1, '006B03'),
  (2, 1, 2, '001A03'),
  (2, 2, 2, '002A03'),
  (2, 3, 2, '003A03'),
  (2, 4, 2, '004A03'),
  (3, 5, 3, '001A02'),
  (3, 6, 3, '002A02'),
  (3, 7, 3, '003A02'),
  (4, 1, 3, '001C03'),
  (5, 1, 1, '001F03'),
  (5, 2, 1, '002F03'),
  (5, 3, 1, '003F03'),
  (5, 4, 1, '004F03'),
  (5, 5, 1, '005F03'),
  (6, 1, 2, '001A09'),
  (6, 2, 2, '002A09'),
  (6, 3, 2, '003A09'),
  (6, 4, 2, '004A09'),
  (6, 5, 2, '005A09'),
  (7, 1, 2, '010A03'),
  (8, 1, 1, '012A03'),
  (8, 2, 1, '013A03'),
  (8, 3, 1, '014A03'),
  (8, 4, 1, '011A03'),
  (8, 5, 1, '002A13'),
  (9, 1, 1, '003A13'),
  (9, 2, 1, '104C03'),
  (9, 3, 1, '201A03'),
  (9, 4, 1, '012A83'),
  (10, 1, 3, '013F09'),
  (10, 2, 3, '004E03'),
  (10, 3, 3, '001E03'),
  (10, 4, 3, '002E03'),
  (11, 1, 3, '003E03'),
  (11, 2, 3, '004E03'),
  (12, 1, 2, '001G01'),
  (12, 2, 2, '002G01'),
  (12, 3, 2, '003G01'),
  (12, 4, 2, '004G01'),
  (13, 1, 2, '203B03'),
  (13, 2, 2, '204B03'),
  (14, 1, 3, '091B03'),
  (14, 2, 3, '092B03'),
  (14, 3, 3, '093B03'),
  (14, 4, 3, '094B03'),
  (15, 1, 1, '021A03'),
  (15, 2, 1, '022A03'),
  (15, 3, 1, '023A03'),
  (16, 1, 1, '024A03'),
  (16, 2, 1, '423A03'),
  (16, 3, 1, '024A03'),
  (16, 4, 1, '021B03'),
  (16, 5, 1, '122A03'),
  (16, 6, 1, '123A03'),
  (17, 1, 3, '004A18'),
  (17, 2, 3, '001A17'),
  (17, 3, 3, '002A14'),
  (17, 4, 3, '003A15'),
  (18, 1, 2, '064A16'),
  (19, 1, 3, '003A24'),
  (19, 2, 3, '004A23'),
  (19, 3, 3, '011A20'),
  (19, 4, 3, '042A43'),
  (19, 5, 3, '103A34'),
  (19, 6, 3, '084A67'),
  (20, 1, 3, '001A03'),
  (20, 2, 3, '002A03'),
  (20, 3, 3, '003A03'),
  (20, 4, 3, '004A03');
-- Insert Reader Datas
INSERT INTO READER (READERID, RTYPE, RNAME, ADDRESS) VALUES
  ('jz01', 'Student', 'Jack Qin', 'Harrison Ave., NJ');