CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE centers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE courses (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE employees (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  center_id INT NOT NULL,
  course_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (center_id) REFERENCES centers(id),
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE training_sessions (
  id INT AUTO_INCREMENT,
  course_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO centers (name, address) VALUES
  ('مركز التدريب الأول', 'شارع التدريب الأول'),
  ('مركز التدريب الثاني', 'شارع التدريب الثاني');

INSERT INTO courses (name, description) VALUES
  ('دورة التدريب الأول', 'وصف الدورة التدريب الأول'),
  ('دورة التدريب الثاني', 'وصف الدورة التدريب الثاني');

INSERT INTO employees (name, email, phone) VALUES
  ('موظف التدريب الأول', 'موظف التدريب الأول@example.com', '0123456789'),
  ('موظف التدريب الثاني', 'موظف التدريب الثاني@example.com', '0987654321');