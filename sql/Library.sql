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
-- Create Function
-- Compute Fine Function
DROP FUNCTION IF EXISTS COMPUTE_FINE;
CREATE FUNCTION COMPUTE_FINE(borrowID INT) RETURNS DECIMAL(5,2)
  BEGIN
    SELECT BDTIME, RDTIME INTO @btime, @rtime
    FROM BORROWS
    WHERE BORNUMBER = borrowID;
    IF @rtime IS NULL THEN
      SET @rtime = NOW();
    END IF;

    SET @days = datediff(@rtime, @btime);
    IF @days > 20 THEN
      SET @fine = (@days-20) * 0.2;
    ELSE
      SET @fine = 0;
    END IF;

    RETURN (@fine);
  END;
-- Compute Remain Days Function
DROP FUNCTION IF EXISTS COMPUTE_REMAIN_DAYS;
CREATE FUNCTION COMPUTE_REMAIN_DAYS(borrowID INT) RETURNS INT
  BEGIN
    SELECT BDTIME, RDTIME INTO @btime, @rtime
    FROM BORROWS
    WHERE BORNUMBER = borrowID;
    IF @rtime IS NOT NULL THEN
      SET @day = 0;
    ELSE
      SET @day = datediff(NOW(), @btime);
      IF @day > 20 THEN
        SET @day = 0;
      ELSE
        SET @day = 20 - @day;
      END IF;
    END IF;
    RETURN (@day);
  END;
-- Create Event
-- Open Event
SET GLOBAL event_scheduler = 1;
-- Delete Reserve at 6:00 p.m. Event
DROP EVENT IF EXISTS CANCEL_RESERVE_EVERYDAY;
CREATE EVENT CANCEL_RESERVE_EVERYDAY
  ON SCHEDULE
    EVERY 1 DAY
    STARTS '2016-05-01 18:00:00' ON COMPLETION PRESERVE ENABLE
DO
  DELETE FROM RESERVES;
-- Create Constrain
-- Check Number of Reservations for Each Reader
DROP TRIGGER IF EXISTS RESERVES_LIMIT;
CREATE TRIGGER RESERVES_LIMIT
BEFORE INSERT ON RESERVES
FOR EACH ROW
  BEGIN
    IF (10 = (SELECT COUNT(RESUMBER)
              FROM RESERVES
              WHERE READERID = new.READERID
              GROUP BY READERID)) THEN
      SET NEW.READERID = NULL;
    END IF ;
  END;
-- Check Number of Borrows for Each Reader
DROP TRIGGER IF EXISTS BORROWS_LIMIT;
CREATE TRIGGER BORROWS_LIMIT
BEFORE INSERT ON BORROWS
FOR EACH ROW
  BEGIN
    IF (10 = (SELECT COUNT(BORNUMBER)
              FROM BORROWS
              WHERE READERID = new.READERID AND RDTIME IS NULL
              GROUP BY READERID)) THEN
      SET NEW.READERID = NULL;
    END IF ;
  END;
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
  -- LIBARY 1
  ('B1', 1, 1, '001B03'),
  ('B1', 2, 1, '002B03'),
  ('B1', 3, 1, '004B03'),
  ('B1', 4, 1, '005B03'),
  ('B1', 5, 1, '006B03'),
  ('B1', 6, 1, '804B03'),
  ('B1', 7, 1, '905B03'),
  ('B1', 8, 1, '976B03'),
  ('B5', 1, 1, '001F03'),
  ('B5', 2, 1, '002F03'),
  ('B5', 3, 1, '003F03'),
  ('B5', 4, 1, '004F03'),
  ('B5', 5, 1, '005F03'),
  ('B8', 1, 1, '012A03'),
  ('B8', 2, 1, '013A03'),
  ('B8', 3, 1, '014A03'),
  ('B8', 4, 1, '011A03'),
  ('B8', 5, 1, '002A13'),
  ('B9', 1, 1, '003A13'),
  ('B9', 2, 1, '104C03'),
  ('B9', 3, 1, '201A03'),
  ('B9', 4, 1, '012A83'),
  ('J1', 1, 1, '002G01'),
  ('J4', 1, 1, '021A03'),
  ('J4', 2, 1, '022A03'),
  ('J4', 3, 1, '023A03'),
  ('P1', 1, 1, '024A03'),
  ('P1', 2, 1, '423A03'),
  ('P1', 3, 1, '024A83'),
  ('P1', 4, 1, '122A03'),
  ('P1', 5, 1, '123A03'),
  ('P5', 1, 1, '001A93'),
  -- LIBARY 2
  ('B1', 1, 2, '003B03'),
  ('B1', 2, 2, '803B03'),
  ('B2', 1, 2, '001A03'),
  ('B2', 2, 2, '002A03'),
  ('B2', 3, 2, '003A03'),
  ('B2', 4, 2, '004A03'),
  ('B6', 1, 2, '001A09'),
  ('B6', 2, 2, '003A09'),
  ('B6', 3, 2, '004A09'),
  ('B6', 4, 2, '005A09'),
  ('B7', 1, 2, '010A03'),
  ('B10',1, 2, '001E03'),
  ('J1', 1, 2, '001G01'),
  ('J1', 2, 2, '003G01'),
  ('J1', 3, 2, '004G01'),
  ('J2', 1, 2, '203B03'),
  ('J2', 2, 2, '204B03'),
  ('P3', 1, 2, '064A16'),
  ('P4', 2, 2, '011A20'),
  -- LIBARY 3
  ('B1', 1, 3, '101B03'),
  ('B1', 2, 3, '502F03'),
  ('B3', 1, 3, '001A02'),
  ('B3', 2, 3, '002A02'),
  ('B3', 3, 3, '003A02'),
  ('B4', 1, 3, '001C03'),
  ('B6', 1, 3, '002A09'),
  ('B10',1, 3, '013F09'),
  ('B10',2, 3, '004E03'),
  ('B10',3, 3, '002E03'),
  ('B11',1, 3, '003E03'),
  ('B11',2, 3, '004E83'),
  ('J3', 1, 3, '091B03'),
  ('J3', 2, 3, '092B03'),
  ('J3', 3, 3, '093B03'),
  ('J3', 4, 3, '094B03'),
  ('P1', 1, 3, '021B03'),
  ('P2', 1, 3, '004A18'),
  ('P2', 2, 3, '001A17'),
  ('P2', 3, 3, '002A14'),
  ('P2', 4, 3, '003A15'),
  ('P4', 1, 3, '003A24'),
  ('P4', 2, 3, '004A23'),
  ('P4', 3, 3, '042A43'),
  ('P4', 4, 3, '103A34'),
  ('P4', 5, 3, '084A67'),
  ('P5', 1, 3, '002A93'),
  ('P5', 2, 3, '003A93'),
  ('P5', 3, 3, '004A93');
-- Insert Reader Datas
INSERT INTO READER (READERID, RTYPE, RNAME, ADDRESS) VALUES
  ('jz01', 'student', 'Jack Qin', 'Harrison Ave., NJ'),
  ('mk01', 'staff', 'Mike', 'Frank E Ave., NJ'),
  ('jn01', 'student', 'Jone', 'Newark, NJ'),
  ('jh01', 'senior citizen', 'John', '37 Harrison Ave., NJ'),
  ('qi01', 'student', 'Qin', '38 Harrison Ave., NJ'),
  ('lm01', 'student', 'Lemon', 'Kearny, NJ'),
  ('mi01', 'student', 'Mint', 'Clifton ave, NJ'),
  ('ni01', 'staff', 'Nicle', 'Mariland, NJ'),
  ('ml01', 'staff', 'Milan', '289 Harrison Ave., NJ'),
  ('mk02', 'student', 'Mike A', '90 Harrison Ave., NJ'),
  ('jz02', 'senior citizen', 'John Zoo', '89 Frank E, NJ'),
  ('zo01', 'student', 'Zone', 'Newark, NJ'),
  ('ii01', 'student', 'Illin', '23 Harrison Ave., NJ');
-- Insert Borrows Datas
INSERT INTO BORROWS (BORNUMBER, READERID, DOCID, COPYNO, LIBID, BDTIME, RDTIME) VALUES
  (1, 'jz01', 'B5', 1, 1, '2016-05-01 14:35:03', null),
  (2, 'jz01', 'B5', 2, 1, '2014-04-06 14:35:06', '2014-05-01 14:35:22'),
  (3, 'jz01', 'B6', 1, 2, '2016-05-01 14:35:09', '2016-05-01 14:35:25'),
  (4, 'jz01', 'B6', 2, 2, '2016-03-17 14:35:11', '2016-05-01 14:35:28'),
  (5, 'jz01', 'B2', 1, 2, '2016-05-01 14:35:13', '2016-05-01 14:35:33'),
  (6, 'jz01', 'B6', 1, 3, '2013-05-01 14:35:16', '2013-05-01 14:35:39'),
  (7, 'jz01', 'B5', 1, 1, '2016-04-19 14:36:14', '2016-05-01 14:37:05'),
  (8, 'jz01', 'B5', 3, 1, '2016-05-01 14:36:17', null),
  (9, 'jz01', 'B9', 1, 1, '2015-04-25 14:36:20', '2015-05-01 14:37:03'),
  (10, 'jz01', 'B5', 2, 1, '2016-03-01 14:36:58', null),

  (11, 'mk01', 'J1', 1, 1, '2016-04-28 14:35:03', '2016-05-01 14:35:36'),
  (12, 'mk01', 'P1', 2, 1, '2016-04-06 14:35:06', '2016-04-27 14:35:22'),
  (13, 'mk01', 'B10', 1, 2, '2015-01-01 14:35:09', '2015-03-01 14:35:25'),
  (14, 'mk01', 'B11', 2, 3, '2014-03-17 14:35:11', '2014-05-01 14:35:28'),
  (15, 'mk01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (16, 'mk01', 'B6', 1, 2, '2014-03-10 14:35:16', '2014-10-01 14:35:39'),
  (17, 'mk01', 'J2', 2, 2, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (18, 'mk01', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (19, 'mk01', 'B9', 1, 1, '2010-10-25 14:36:20', '2010-10-30 14:37:03'),
  (20, 'mk01', 'B5', 2, 1, '2016-03-29 14:36:58', null),

  (21, 'jn01', 'B5', 1, 1, '2016-05-01 14:35:03', null),
  (22, 'jn01', 'B5', 2, 1, '2014-04-06 14:35:06', '2014-05-01 14:35:22'),
  (23, 'jn01', 'B6', 1, 2, '2016-05-01 14:35:09', '2016-05-01 14:35:25'),
  (24, 'jn01', 'B6', 2, 2, '2016-03-20 14:35:11', '2016-05-01 14:35:28'),
  (25, 'jn01', 'J1', 1, 1, '2016-05-01 14:35:13', '2016-05-01 14:35:33'),
  (26, 'jn01', 'B6', 1, 3, '2013-05-01 14:35:16', '2013-05-01 14:35:39'),
  (27, 'jn01', 'B9', 1, 1, '2016-04-19 14:36:14', '2016-05-01 14:37:05'),
  (28, 'jn01', 'B5', 3, 1, '2016-05-01 14:36:17', null),
  (29, 'jn01', 'B5', 1, 1, '2015-04-25 14:36:20', '2015-05-01 14:37:03'),
  (30, 'jn01', 'B5', 2, 1, '2016-03-01 14:36:58', null),

  (31, 'jh01', 'P5', 3, 3, '2016-05-01 14:35:03', null),
  (32, 'jh01', 'B5', 2, 1, '2014-04-06 14:35:06', '2014-05-01 14:35:22'),
  (33, 'jh01', 'B6', 1, 2, '2016-05-01 14:35:09', '2016-05-01 14:35:25'),
  (34, 'jh01', 'P4', 4, 3, '2016-03-17 14:35:11', '2016-05-01 14:35:28'),
  (35, 'jh01', 'B2', 1, 2, '2016-05-01 14:35:13', '2016-05-01 14:35:33'),
  (36, 'jh01', 'B6', 1, 3, '2013-05-01 14:35:16', '2013-05-01 14:35:39'),
  (37, 'jh01', 'B5', 1, 1, '2016-04-19 14:36:14', '2016-05-01 14:37:05'),
  (38, 'jh01', 'B5', 3, 1, '2016-05-01 14:36:17', null),
  (39, 'jh01', 'B9', 1, 1, '2015-03-25 14:36:20', '2015-05-01 14:37:03'),
  (40, 'jh01', 'J3', 2, 3, '2016-03-01 14:36:58', null),

  (41, 'qi01', 'J1', 1, 1, '2016-04-28 14:35:03', null),
  (42, 'qi01', 'P1', 2, 1, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (43, 'qi01', 'B10', 1, 2, '2015-01-01 14:35:09', '2015-03-01 14:35:25'),
  (44, 'qi01', 'B1', 4, 1, '2014-03-17 14:35:11', '2014-05-01 14:35:28'),
  (45, 'qi01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (46, 'qi01', 'B6', 1, 2, '2014-03-10 14:35:16', '2014-10-01 14:35:39'),
  (47, 'qi01', 'J2', 2, 2, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (48, 'qi01', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (49, 'qi01', 'B9', 4, 1, '2010-10-25 14:36:20', '2010-10-30 14:37:03'),
  (50, 'qi01', 'B8', 1, 1, '2016-03-01 14:36:58', null),

  (51, 'lm01', 'J2', 2, 2, '2016-03-28 14:35:03', null),
  (52, 'lm01', 'P1', 2, 1, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (53, 'lm01', 'B10', 1, 2, '2015-01-01 14:35:09', '2015-03-01 14:35:25'),
  (54, 'lm01', 'B1', 4, 1, '2014-03-17 14:35:11', '2014-05-01 14:35:28'),
  (55, 'lm01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (56, 'lm01', 'B6', 1, 2, '2014-03-10 14:35:16', '2014-10-01 14:35:39'),
  (57, 'lm01', 'J3', 4, 3, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (58, 'lm01', 'J4', 3, 1, '2016-03-29 14:36:17', null),
  (59, 'lm01', 'B9', 4, 1, '2010-10-25 14:36:20', '2010-10-30 14:37:03'),
  (60, 'lm01', 'B8', 1, 1, '2016-03-01 14:36:58', null),

  (61, 'mi01', 'B7', 1, 2, '2016-04-28 14:35:03', null),
  (62, 'mi01', 'P1', 2, 1, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (63, 'mi01', 'B11', 2, 3, '2015-01-01 14:35:09', '2015-03-01 14:35:25'),
  (64, 'mi01', 'B1', 4, 1, '2015-03-17 14:35:11', '2015-05-01 14:35:28'),
  (65, 'mi01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (66, 'mi01', 'B6', 1, 2, '2014-03-20 14:35:16', '2014-10-01 14:35:39'),
  (67, 'mi01', 'J2', 2, 2, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (68, 'mi01', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (69, 'mi01', 'B9', 4, 1, '2011-10-25 14:36:20', '2011-10-30 14:37:03'),
  (70, 'mi01', 'B8', 1, 1, '2016-03-01 14:36:58', null),

  (71, 'ni01', 'B5', 1, 1, '2016-05-01 14:35:03', null),
  (72, 'ni01', 'B5', 2, 1, '2014-04-06 14:35:06', '2014-05-01 14:35:22'),
  (73, 'ni01', 'B6', 1, 2, '2009-05-01 14:35:09', '2009-05-01 14:35:25'),
  (74, 'ni01', 'B6', 2, 2, '2016-03-20 14:35:11', '2016-05-01 14:35:28'),
  (75, 'ni01', 'J1', 1, 1, '2016-04-29 14:35:13', '2016-05-01 14:35:33'),
  (76, 'ni01', 'B6', 1, 3, '2013-05-01 14:35:16', '2013-05-01 14:35:39'),
  (77, 'ni01', 'B9', 1, 1, '2016-04-19 14:36:14', '2016-05-01 14:37:05'),
  (78, 'ni01', 'J3', 4, 3, '2016-05-01 14:36:17', null),
  (79, 'ni01', 'B5', 1, 1, '2015-04-25 14:36:20', '2015-05-01 14:37:03'),
  (80, 'ni01', 'B5', 2, 1, '2016-03-01 14:36:58', null),

  (81, 'ml01', 'J1', 1, 1, '2016-04-28 14:35:03', null),
  (82, 'ml01', 'P1', 2, 1, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (83, 'ml01', 'B11',1, 3, '2015-01-017 14:35:09', '2015-03-01 14:35:25'),
  (84, 'ml01', 'B11',2, 3, '2014-03-29 14:35:11', '2014-05-01 14:35:28'),
  (85, 'ml01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (86, 'ml01', 'B6', 1, 2, '2014-03-10 14:35:16', '2014-10-01 14:35:39'),
  (87, 'ml01', 'J2', 2, 2, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (88, 'ml01', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (89, 'ml01', 'P1', 5, 1, '2010-10-28 14:36:20', '2010-10-30 14:37:03'),
  (90, 'ml01', 'B8', 1, 1, '2016-03-01 14:36:58', null),

  (91, 'mk02', 'J1', 1, 1, '2016-04-28 14:35:03', null),
  (92, 'mk02', 'P1', 2, 1, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (93, 'mk02', 'B11',1, 3, '2015-01-017 14:35:09', '2015-03-01 14:35:25'),
  (94, 'mk02', 'B11',2, 3, '2014-03-29 14:35:11', '2014-05-01 14:35:28'),
  (95, 'mk02', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (96, 'mk02', 'B6', 1, 2, '2014-03-10 14:35:16', '2014-10-01 14:35:39'),
  (97, 'mk02', 'J2', 2, 2, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (98, 'mk02', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (99, 'mk02', 'P1', 5, 1, '2010-10-28 14:36:20', '2010-10-30 14:37:03'),
  (100,'mk02', 'B8', 1, 1, '2016-03-01 14:36:58', null),

  (101, 'jz02', 'B5', 1, 1, '2016-05-01 14:35:03', null),
  (102, 'jz02', 'B5', 2, 1, '2014-04-06 14:35:06', '2014-05-01 14:35:22'),
  (103, 'jz02', 'B6', 1, 2, '2016-05-01 14:35:09', '2016-05-01 14:35:25'),
  (104, 'jz02', 'B6', 2, 2, '2016-03-17 14:35:11', '2016-05-01 14:35:28'),
  (105, 'jz02', 'B2', 1, 2, '2016-05-01 14:35:13', '2016-05-01 14:35:33'),
  (106, 'jz02', 'B6', 1, 3, '2013-05-01 14:35:16', '2013-05-01 14:35:39'),
  (107, 'jz02', 'P2', 2, 3, '2016-03-19 14:36:14', '2016-05-01 14:37:05'),
  (108, 'jz02', 'B5', 3, 1, '2016-05-01 14:36:17', null),
  (109, 'jz02', 'B9', 1, 1, '2015-04-25 14:36:20', '2015-05-01 14:37:03'),
  (110, 'jz02', 'B5', 2, 1, '2016-03-01 14:36:58', null),

  (111, 'zo01', 'B7', 1, 2, '2016-04-28 14:35:03', null),
  (112, 'zo01', 'P1', 2, 1, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (113, 'zo01', 'B11', 2, 3, '2015-01-01 14:35:09', '2015-03-01 14:35:25'),
  (114, 'zo01', 'B1', 4, 1, '2015-03-17 14:35:11', '2015-05-01 14:35:28'),
  (115, 'zo01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (116, 'zo01', 'B6', 1, 2, '2014-02-20 14:35:16', '2014-10-01 14:35:39'),
  (117, 'zo01', 'J2', 2, 2, '2013-04-28 14:36:14', '2013-05-01 14:37:05'),
  (118, 'zo01', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (119, 'zo01', 'B9', 4, 1, '2008-10-25 14:36:20', '2008-10-30 14:37:03'),
  (120, 'zo01', 'B8', 1, 1, '2016-03-01 14:36:58', null),

  (121, 'ii01', 'B7', 1, 2, '2016-04-28 14:35:03', null),
  (122, 'ii01', 'P2', 1, 3, '2016-01-06 14:35:06', '2016-04-27 14:35:22'),
  (123, 'ii01', 'B11', 2, 3, '2015-01-01 14:35:09', '2015-03-01 14:35:25'),
  (124, 'ii01', 'B1', 4, 1, '2015-03-17 14:35:11', '2015-05-01 14:35:28'),
  (125, 'ii01', 'B2', 2, 2, '2015-05-01 14:35:13', '2015-05-01 14:35:33'),
  (126, 'ii01', 'B6', 1, 2, '2014-03-20 14:35:16', '2014-10-01 14:35:39'),
  (127, 'ii01', 'J2', 2, 2, '2013-04-19 14:36:14', '2013-05-01 14:37:05'),
  (128, 'ii01', 'J3', 3, 3, '2016-03-29 14:36:17', null),
  (129, 'ii01', 'B9', 4, 1, '2011-10-25 14:36:20', '2011-10-30 14:37:03'),
  (130, 'ii01', 'B8', 1, 1, '2016-02-01 14:36:58', null);