DROP DATABASE IF EXISTS art_gallery;
CREATE DATABASE art_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE art_gallery;

-- Table: art_style (normalized art styles)
CREATE TABLE art_style (
  style_id INT AUTO_INCREMENT PRIMARY KEY,
  style_name VARCHAR(50) NOT NULL UNIQUE
);

-- Insert dummy art styles
INSERT INTO art_style (style_name) VALUES 
('Pop Art'), ('Fauvism'), ('Oil on Canvas'), ('Analytic Cubism'), ('Oil Painting'), ('High Renaissance');

-- Table: artist
CREATE TABLE artist (
  artistid VARCHAR(20) PRIMARY KEY,
  fname VARCHAR(50) NOT NULL,
  lname VARCHAR(50) NOT NULL,
  birthplace VARCHAR(50),
  style_id INT,
  FOREIGN KEY (style_id) REFERENCES art_style(style_id)
);

-- Insert dummy artists
INSERT INTO artist (artistid, fname, lname, birthplace, style_id) VALUES
('ART1', 'Georgia', 'O Keeffe', 'USA', 3), -- Oil on Canvas
('ART2', 'Pablo', 'Picasso', 'Spain', 4),  -- Analytic Cubism
('ART3', 'Rembrandt', 'van Rijn', 'Netherlands', 5), -- Oil Painting
('ART4', 'Theodore', 'Chasseriau', 'France', 5),
('ART5', 'Leonardo', 'da Vinci', 'Italy', 6), -- High Renaissance
('ART7', 'Mind', 'Hunter', 'Kathmandu', 5);

-- Table: gallery
CREATE TABLE gallery (
  gid VARCHAR(20) PRIMARY KEY,
  gname VARCHAR(100) NOT NULL,
  location VARCHAR(100)
);

-- Trigger to uppercase gallery names before insert
DELIMITER $$
CREATE TRIGGER UPPERCASE_gname BEFORE INSERT ON gallery
FOR EACH ROW
BEGIN
  SET NEW.gname = UPPER(NEW.gname);
END$$
DELIMITER ;

-- Insert dummy galleries
INSERT INTO gallery (gid, gname, location) VALUES
('MM126', 'MY GALLERY', 'Patna'),
('NG123', 'NATIONAL GALLERY', 'Washington'),
('BM123', 'BRITISH MUSEUM', 'London'),
('JG123', 'JAHANGIR GALLERY', 'Mumbai'),
('TLM123', 'THE LOUVRE MUSEUM', 'Paris'),
('MM123', 'METROPOLITAN MUSEUM', 'New York');

-- Table: exhibition
CREATE TABLE exhibition (
  eid VARCHAR(20) PRIMARY KEY,
  gid VARCHAR(20),
  startdate DATE,
  enddate DATE,
  FOREIGN KEY (gid) REFERENCES gallery(gid)
);

-- Insert dummy exhibitions
INSERT INTO exhibition (eid, gid, startdate, enddate) VALUES
('H123', 'BM123', '2018-12-21', '2019-01-05'),
('I123', 'MM123', '2019-01-25', '2019-02-05'),
('G123', 'NG123', '2018-12-01', '2018-12-15'),
('J123', 'TLM123', '2018-12-15', '2019-01-15'),
('K123', 'JG123', '2019-03-09', '2019-03-27');

-- Table: artwork
CREATE TABLE artwork (
  artid VARCHAR(20) PRIMARY KEY,
  title VARCHAR(100),
  year YEAR,
  type_of_art VARCHAR(50),
  price DECIMAL(15, 2),
  eid VARCHAR(20),
  gid VARCHAR(20),
  artistid VARCHAR(20),
  FOREIGN KEY (eid) REFERENCES exhibition(eid),
  FOREIGN KEY (gid) REFERENCES gallery(gid),
  FOREIGN KEY (artistid) REFERENCES artist(artistid)
);

-- Insert dummy artworks
INSERT INTO artwork (artid, title, year, type_of_art, price, eid, gid, artistid) VALUES
('AW12', 'Mona Lisa', 1888, 'Painting', 1000000000.00, 'G123', 'NG123', 'ART5'),
('AW34', 'Poppies', 1873, 'Painting', 150000000.00, 'H123', 'MM123', 'ART1'),
('AW56', 'Guernica', 1937, 'Painting', 250000000.00, 'I123', 'TLM123', 'ART2'),
('AW78', 'The Night Watch', 1642, 'Painting', 90000000.00, 'J123', 'BM123', 'ART3'),
('AW90', 'Two Sisters', 2010, 'Sculpture', 200000.00, 'K123', 'JG123', 'ART4'),
('A111', 'Untitled', 2018, 'Abstract', 2000000000.00, NULL, NULL, 'ART7');

-- Table: customer
CREATE TABLE customer (
  custid VARCHAR(20) PRIMARY KEY,
  fname VARCHAR(50) NOT NULL,
  lname VARCHAR(50) NOT NULL,
  dob DATE,
  address VARCHAR(100),
  gid VARCHAR(20),
  FOREIGN KEY (gid) REFERENCES gallery(gid)
);

-- Insert dummy customers
INSERT INTO customer (custid, fname, lname, dob, address, gid) VALUES
('AT2000', 'Akshay', 'Thakur', '2000-04-16', 'New York', 'MM123'),
('AR1998', 'Ashutosh', 'Ranjan', '1998-02-04', 'Paris', 'TLM123'),
('AD1998', 'Ayush', 'Dhar', '1998-09-28', 'London', 'BM123'),
('AM1994', 'Avanish', 'Mehta', '1994-10-05', 'Mumbai', 'JG123'),
('PM1996', 'Prashant', 'Mehta', '1996-06-18', 'Washington', 'NG123'),
('AR2022', 'Aashu', 'Demo', '2022-05-10', 'Delhi', 'MM126'),
('AR2025', 'Aashut', 'Demo0', '2022-05-10', 'Delhi', 'MM126');

-- Table: contacts (customer phone numbers)
CREATE TABLE contacts (
  custid VARCHAR(20),
  phone VARCHAR(15),
  PRIMARY KEY (custid, phone),
  FOREIGN KEY (custid) REFERENCES customer(custid)
);

-- Insert dummy contacts
INSERT INTO contacts (custid, phone) VALUES
('AT2000', '9456805776'),
('AR1998', '8073271337'),
('AD1998', '9980904736'),
('AM1994', '7737564076'),
('PM1996', '8002391707'),
('AR2022', '800239170'),
('AR2025', '8002391707');

-- Table: preferences (Customer preferences about artists and galleries)
CREATE TABLE preferences (
  pref_id INT AUTO_INCREMENT PRIMARY KEY,
  custid VARCHAR(20),
  artistid VARCHAR(20),
  gid VARCHAR(20),
  FOREIGN KEY (custid) REFERENCES customer(custid),
  FOREIGN KEY (artistid) REFERENCES artist(artistid),
  FOREIGN KEY (gid) REFERENCES gallery(gid)
);

-- Insert dummy preferences
INSERT INTO preferences (custid, artistid, gid) VALUES
('AT2000', 'ART1', 'MM123'),
('AR1998', 'ART2', 'TLM123'),
('AD1998', 'ART3', 'BM123'),
('AM1994', 'ART4', 'JG123'),
('PM1996', 'ART5', 'NG123');

-- Table: orders (Track customer orders and sales)
CREATE TABLE orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  custid VARCHAR(20),
  artid VARCHAR(20),
  order_date DATE,
  quantity INT DEFAULT 1,
  price DECIMAL(15, 2),
  gid VARCHAR(20),
  FOREIGN KEY (custid) REFERENCES customer(custid),
  FOREIGN KEY (artid) REFERENCES artwork(artid),
  FOREIGN KEY (gid) REFERENCES gallery(gid)
);
INSERT INTO orders (custid, artid, order_date, quantity, price, gid)
VALUES ('AR1998', 'AW34', '2025-05-24', 1, 150000000.00, 'MM123');

-- Insert dummy orders
INSERT INTO orders (custid, artid, order_date, quantity, price, gid) VALUES
('AT2000', 'AW12', '2022-05-10', 1, 1000000000.00, 'NG123'),
('AR1998', 'AW34', '2022-06-15', 1, 150000000.00, 'MM123'),
('AD1998', 'AW56', '2022-07-20', 1, 250000000.00, 'TLM123'),
('AM1994', 'AW78', '2022-08-05', 1, 90000000.00, 'BM123'),
('PM1996', 'AW90', '2022-09-10', 1, 200000.00, 'JG123');
CREATE TABLE artist_sales (
  artistid VARCHAR(20),
  month_year VARCHAR(7), -- Format: YYYY-MM
  total_sales DECIMAL(15,2) DEFAULT 0,
  PRIMARY KEY (artistid, month_year),
  FOREIGN KEY (artistid) REFERENCES artist(artistid)
);
-- Stored procedure to display customer info with contacts
DELIMITER $$
DROP PROCEDURE IF EXISTS display$$
CREATE PROCEDURE display()
BEGIN
  SELECT c.custid, c.fname, c.lname, c.dob, c.address, ct.phone
  FROM customer c
  LEFT JOIN contacts ct ON c.custid = ct.custid;
END$$
DELIMITER ;

-- Stored procedure to get age of customers
DELIMITER $$
DROP PROCEDURE IF EXISTS GetAge$$
CREATE PROCEDURE GetAge()
BEGIN
  SELECT custid, fname, lname, dob, YEAR(CURDATE()) - YEAR(dob) AS age
  FROM customer;
END$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER update_artist_sales_monthly AFTER INSERT ON orders
FOR EACH ROW
BEGIN
  DECLARE art_artistid VARCHAR(20);
  DECLARE order_month VARCHAR(7);

  -- Get artistid for the sold artwork
  SELECT artistid INTO art_artistid FROM artwork WHERE artid = NEW.artid;

  -- Extract month and year in 'YYYY-MM' format from order_date
  SET order_month = DATE_FORMAT(NEW.order_date, '%Y-%m');

  -- If entry exists, update total_sales, else insert new record
  IF EXISTS (
    SELECT 1 FROM artist_sales WHERE artistid = art_artistid AND month_year = order_month
  ) THEN
    UPDATE artist_sales 
    SET total_sales = total_sales + NEW.price * NEW.quantity
    WHERE artistid = art_artistid AND month_year = order_month;
  ELSE
    INSERT INTO artist_sales (artistid, month_year, total_sales)
    VALUES (art_artistid, order_month, NEW.price * NEW.quantity);
  END IF;
END$$
DELIMITER ;

-- View: Best Selling Artwork of the Month (based on total sales amount)
CREATE OR REPLACE VIEW best_selling_artwork_month AS
SELECT 
  DATE_FORMAT(o.order_date, '%Y-%m') AS month_year,
  a.artid,
  a.title,
  a.artistid,
  SUM(o.price * o.quantity) AS total_sales
FROM orders o
JOIN artwork a ON o.artid = a.artid
GROUP BY month_year, a.artid, a.title, a.artistid
ORDER BY month_year DESC, total_sales DESC;
COMMIT;
