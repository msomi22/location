
-- Schema Name: locationdb
-- Username: location
-- Password: location12345

\c postgres

-- Then execute the following:
DROP DATABASE IF EXISTS locationdb; -- To drop a database you can't be logged into it. Drops if it exists.
CREATE DATABASE locationdb;

-- Connect with the database on the username
\c locationdb location
p


-- =========================
-- 1.  Geodata 
-- =========================

-- -------------------
-- Table Geodata
-- -------------------


CREATE TABLE  Geodata (
    id SERIAL PRIMARY KEY,
    uuid text UNIQUE NOT NULL, 
    personid text,
    agent text,
    accessdate timestamp with time zone DEFAULT now(),
    latitude text,
    longitude text
    
);
\COPY Geodata(uuid,personid,agent,accessdate,latitude,longitude) FROM '/tmp/Geodata.csv' WITH DELIMITER AS '|' CSV HEADER
ALTER TABLE Geodata OWNER TO location;

