CREATE TABLE Course (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    ImageUrl VARCHAR(255)
);
INSERT INTO Course (Title, Description, ImageUrl)
VALUES 
('Laravel Programming', 'Learn Laravel from scratch.', 'images/laravel.png'),
('.NET Programming', 'Master .NET framework and C#.', 'images/dot-net.png'),
('Spring Boot Programming', 'Spring Boot essentials for backend developers.', 'images/spring-boot.png'),
('Angular Programming', 'Angular for frontend developers.', 'images/angular.png');
('Python for Data Science', 'Explore data analysis and visualization using Python.', 'images/ảnh 1.png'),
('JavaScript Development', 'Learn modern JavaScript for web development.', 'images/ảnh 2.png'),
('Ruby on Rails Development', 'Build web applications using Ruby on Rails.', 'images/ảnh 3.png'),
('Machine Learning Basics', 'Introduction to machine learning concepts and techniques.', 'images/ảnh 4.png');
