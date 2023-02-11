USE mariadb;
CREATE TABLE `Posts` ( 
    `ID` INT NOT NULL AUTO_INCREMENT , 
    `Name` TEXT NOT NULL , 
    `CreationDate` DATE NOT NULL DEFAULT '1000-01-01' , 
    PRIMARY KEY (`ID`)
);
CREATE TABLE `Tags` ( 
    `ID` INT NOT NULL AUTO_INCREMENT , 
    `Name` TEXT NOT NULL , 
    `IsCategory` BOOLEAN NOT NULL , 
    PRIMARY KEY (`ID`)
);
CREATE TABLE `PostTags` ( 
    `PostID` INT NOT NULL , 
    `TagID` INT NOT NULL , 
    PRIMARY KEY (`PostID`, `TagID`), 
    FOREIGN KEY (`PostID`) REFERENCES Posts (ID), 
    FOREIGN KEY (`TagID`) REFERENCES Tags (ID)
);
