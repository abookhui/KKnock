CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    nickname VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (username)
);

CREATE TABLE comments (
    id INT NOT NULL AUTO_INCREMENT,
    post_id INT NULL,
    user VARCHAR(50) NULL,
    content TEXT NULL,
    created_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    parent_id INT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE posts (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    user VARCHAR(50) NOT NULL,
    created_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    file_path VARCHAR(255) NULL,
    list_type VARCHAR(10) NOT NULL DEFAULT 'list1',
    PRIMARY KEY (id)
);
