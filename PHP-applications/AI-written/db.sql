CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  profile_picture VARCHAR(255) DEFAULT 'default_profile.png',
  reset_token VARCHAR(255) DEFAULT NULL,
  remember_token VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  admin ENUM('True', 'False') NOT NULL DEFAULT 'False'
);


INSERT INTO `users`
    (`id`, `username`, `password`, `admin`)
VALUES
    (0, 'admin', '$2y$10$TwpsTKtQfIqitFRe8vrqa.SQ.NJpfXu85u/oAkqa/pJKDo0CRPHt2', 'True');

-- Username: admin
-- Password: admin
