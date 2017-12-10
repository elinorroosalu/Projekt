# Veebipood


Veebirakenduse pilt....

Grete, Julika ja Elinori projekt. 
Projekti eesmärgiks on teha veebipood, kus inimesed saavad registreeruda kasutajaks ning panna müüki soovitud esemeid või avaldada ostusoovi. 

1. Kasutaja saab sisse logida, kui puudub kasutaja suunatakse registreerumise lehele
2. Sisseloginud kasutaja suunatakse kuulutuste lehele, kus tal on võimalik vaadata kõik müüki pandud tooteid
3. Kasutaja saab lisada uue kuulutuse või siis muuta olemasolevat
4. Ostusoovi korral on olemas müüjate kontakt iga kuulutuse juures


Tabelite loomise SQL-laused:
CREATE TABLE login(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
First_Name VARCHAR(30),
Last_Name VARCHAR(30),
Username VARCHAR(20),
Password VARCHAR(128),
Birthday DATE,
Gender INT(1),
Email VARCHAR(100),
Created DEFAULT CURRENT_TIMESTAMP,
Deleted DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP 
);

CREATE TABLE market(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
UserID INT,
Heading VARCHAR(30),
Descript VARCHAR(500),
Created DEFAULT CURRENT_TIMESTAMP,
Deleted DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP 
);

CREATE TABLE photos(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
userid INT,
filename VARCHAR(30),
thumbnail VARCHAR(30)
alt VARCHAR(140),
privacy INT,
Created DEFAULT CURRENT_TIMESTAMP,
Deleted DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP 
);


