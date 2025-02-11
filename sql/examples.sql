START TRANSACTION;

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
((SELECT id FROM requirements WHERE title = 'System Scalability'), 'CPU Utilization at Peak Load', '%', 75.00, 'Percentage of CPU usage when system is at max capacity'),
((SELECT id FROM requirements WHERE title = 'Database Optimization'), 'Query Execution Time', 'ms', 120.00, 'Time taken to execute an average database query'),
((SELECT id FROM requirements WHERE title = 'Database Optimization'), 'Index Efficiency', '%', 85.00, 'Percentage of queries using indexes effectively');

INSERT INTO tasks (title, user_group, user_id) VALUES
('Design Login System', '5', (SELECT id FROM users WHERE username = 'milenpetrov')), 
-- ('Database Performance Optimization', '6', (SELECT id FROM users WHERE username = 'teacher2')), 
('Implement Multi-Language Support', '7', (SELECT id FROM users WHERE username = 'milenpetrov')); 


INSERT INTO taskRequirements (task_id, requirement_id, status) VALUES
((SELECT id FROM tasks WHERE title = 'Design Login System'), (SELECT id FROM requirements WHERE title = 'User Registration'), 'in_progress'), 
-- ((SELECT id FROM tasks WHERE title = 'Database Performance Optimization'), (SELECT id FROM requirements WHERE title = 'Payment Integration'), 'complete'),  
((SELECT id FROM tasks WHERE title = 'Implement Multi-Language Support'), (SELECT id FROM requirements WHERE title = 'Notification'), 'in_progress'); 

COMMIT;; 