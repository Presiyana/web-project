
INSERT INTO requirements (title, description, hashtags, priority, layer, isNonFunctional) VALUES
('User Authentication', 'Implement secure login system', '#security #auth', 'high', 'business', FALSE),
('Database Optimization', 'Improve query performance', '#performance #database', 'medium', 'db', TRUE),
('Client-side Validation', 'Validate forms before submission', '#ui #validation', 'low', 'client', FALSE);


INSERT INTO indicators (requirement_id, indicator_name, unit, value, indicator_description) VALUES
(2, 'Query Execution Time', 'ms', 120.00, 'Time taken to execute an average database query'),
(2, 'Index Efficiency', '%', 85.00, 'Percentage of queries using indexes effectively'),
(2, 'Cache Hit Ratio', '%', 90.00, 'Percentage of queries served from cache instead of direct DB access'),
(2, 'Concurrent User Load', 'users', 500, 'Number of users the database can handle simultaneously');