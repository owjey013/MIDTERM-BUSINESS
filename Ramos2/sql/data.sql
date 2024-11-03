CREATE TABLE Users (
    User_ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL, 
    date_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Chef (
    Chef_ID INT AUTO_INCREMENT PRIMARY KEY,
    Chef_Name VARCHAR(50),
    Chef_Specialty VARCHAR(50),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    added_by INT,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT, 
    FOREIGN KEY (added_by) REFERENCES Users(User_ID),
    FOREIGN KEY (updated_by) REFERENCES Users(User_ID)
);

CREATE TABLE Dishes (
    Dishes_ID INT AUTO_INCREMENT PRIMARY KEY,
    Dishes_Menu VARCHAR(50),
    Chef_ID INT,
    Dishes_Cost INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    added_by INT,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT,
    FOREIGN KEY (Chef_ID) REFERENCES Chef(Chef_ID),
    FOREIGN KEY (added_by) REFERENCES Users(User_ID),
    FOREIGN KEY (updated_by) REFERENCES Users(User_ID)
);