INSERT INTO users (username, email, password, user_group) VALUES
('milenpetrov', 'milenpetrov@example.com', 'meow', 'teacher'),
('teacher2', 'teacher2@example.com', 'meow', 'teacher'),
('student1', 'student1@example.com', 'meow', '5'),
('student2', 'student2@example.com', 'meow', '6'),
('student3', 'student3@example.com', 'meow', '7');


INSERT INTO requirements (title, description, hashtags, priority, layer, isNonFunctional) VALUES
('User Registration', 'Users can sign up with email or social accounts', '#user', 'high', 'client', FALSE),
('Payment Integration', 'Payments Integration', '#payment', 'medium', 'client', FALSE),
('Notification', 'The apps sends reminders for upcoming deadlines', '#notifications', 'low', 'business', FALSE),
('System Scalability', 'Ensure the system can handle increased load efficiently', '#scalability #performance', 'high', 'business', TRUE),
('User Authentication', 'Implement secure login system', '#security #auth', 'high', 'business', FALSE),
('Database Optimization', 'Improve query performance', '#performance #database', 'medium', 'db', TRUE),
('Client-side Validation', 'Validate forms before submission', '#ui #validation', 'low', 'client', FALSE);

INSERT INTO indicators (requirement_id, indicator_name, unit, value, indicator_description) VALUES
(4, 'CPU Utilization at Peak Load', '%', 75.00, 'Percentage of CPU usage when system is at max capacity'),
(6, 'Query Execution Time', 'ms', 120.00, 'Time taken to execute an average database query'),
(6, 'Index Efficiency', '%', 85.00, 'Percentage of queries using indexes effectively');

INSERT INTO tasks (title, user_group, user_id) VALUES
('Design Login System', '5', 1), 
('Database Performance Optimization', '6', 2), 
('Implement Multi-Language Support', '7', 1); 

