-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2025 at 06:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms_sdlc`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `course_id`, `title`, `description`, `deadline`, `file_path`) VALUES
(2, 25, 'Analyzing and Developing an SDLC Process for a Sample Software System', 'This assignment requires students to apply their knowledge of the Software Development Life Cycle (SDLC) to analyze, design, and plan the development of a hypothetical software system. Students will:\r\n\r\n1. Select a specific project or software idea.\r\n\r\n2. Apply the stages of SDLC (Planning, Analysis, Design, Implementation, Testing, Deployment, Maintenance).\r\n\r\n3. Describe in detail the activities, outputs, and tools used in each stage.\r\n\r\n4. Present an SDLC process diagram and explain the choice of model (Waterfall, Agile, Spiral, etc.).\r\n\r\nThe goal of the assignment is to help students gain a solid understanding of how to manage the software development life cycle, improve planning and requirements analysis skills, and evaluate technical solutions.', '2025-08-15 08:56:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`id`, `course_id`, `title`, `order`) VALUES
(20, 25, 'Introduction to SDLC', 1),
(21, 25, 'Requirement Analysis', 1),
(22, 25, 'System Design', 1),
(23, 25, 'Implementation', 1),
(24, 25, 'Testing', 1),
(25, 25, 'Deployment and Maintenance', 1),
(26, 26, 'Introduction to Networking', 1),
(27, 26, 'Network Models', 1),
(28, 26, 'Network Devices', 1),
(29, 26, 'Network Media', 1),
(30, 26, 'IP Addressing', 1),
(31, 26, 'Network Protocols', 1),
(32, 26, 'Network Security', 1),
(33, 27, 'Introduction to Databases', 1),
(34, 27, 'Data Modeling', 1),
(35, 27, 'Relational Database Concepts', 1),
(36, 27, 'SQL Basics', 1),
(37, 27, 'Advanced SQL', 1),
(38, 27, 'Database Development', 1),
(39, 27, 'Database Management', 1),
(40, 28, 'Introduction to Security', 1),
(41, 28, 'Security Threats and Vulnerabilities', 1),
(42, 28, 'Network Security', 1),
(43, 28, 'Data Security', 1),
(44, 28, 'User and Access Management', 1),
(45, 28, 'Cybersecurity Best Practices', 1),
(46, 28, 'Legal and Ethical Issues', 1),
(47, 29, 'Introduction to Data Structures and Algorithms', 1),
(48, 29, 'Arrays and Strings', 1),
(49, 29, 'Linked Lists', 1),
(50, 29, 'Stacks and Queues', 1),
(51, 29, 'Trees', 1),
(52, 29, 'Graphs', 1),
(53, 29, 'Searching and Sorting Algorithms', 1),
(54, 29, 'Algorithm Analysis', 1),
(55, 30, 'Introduction to Computers', 1),
(56, 30, 'Computer Hardware', 1),
(57, 30, 'Computer Software', 1),
(58, 30, 'Operating Systems', 1),
(59, 30, 'Computer Networks', 1),
(60, 30, 'Computer Security', 1),
(61, 31, 'Introduction to Project Planning', 1),
(62, 31, 'Defining Project Objectives and Scope', 1),
(63, 31, 'Requirements Analysis', 1),
(64, 31, 'Resource Planning', 1),
(65, 31, 'Scheduling and Timeline', 1),
(66, 31, 'Risk Management', 1),
(67, 31, 'Monitoring and Evaluation', 1),
(68, 32, 'Introduction to Web Development', 1),
(69, 32, 'HTML Basics', 1),
(70, 32, 'CSS Styling', 1),
(71, 32, 'JavaScript Fundamentals', 1),
(72, 32, 'Web Design Principles', 1),
(73, 32, 'Frameworks and Tools', 1),
(74, 32, 'Backend Basics', 1),
(75, 32, 'Website Deployment', 1),
(76, 33, 'Introduction to Programming', 1),
(77, 33, 'Programming Environment', 1),
(78, 33, 'Variables and Data Types', 1),
(79, 33, 'Operators and Expressions', 1),
(80, 33, 'Control Structures', 1),
(81, 33, 'Functions and Methods', 1),
(82, 33, 'Arrays and Collections', 1),
(83, 33, 'Basic Debugging and Testing', 1),
(84, 34, 'Introduction to IoT', 1),
(85, 34, 'IoT Architecture', 1),
(86, 34, 'Hardware for IoT', 1),
(87, 34, 'IoT Communication Technologies', 1),
(88, 34, 'IoT Software and Platforms', 1),
(89, 34, 'IoT Applications', 1),
(90, 34, 'IoT Security and Privacy', 1),
(91, 34, 'Future Trends in IoT', 1),
(92, 35, 'Introduction to Professional Practice', 1),
(93, 35, 'Professional Ethics', 1),
(94, 35, 'Communication Skills', 1),
(95, 35, 'Teamwork and Collaboration', 1),
(96, 35, 'Time and Task Management', 1),
(97, 35, 'Problem-Solving and Critical Thinking', 1),
(98, 35, 'Legal and Regulatory Compliance', 1),
(99, 35, 'Career Development', 1),
(100, 36, 'Introduction to Business Processes', 1),
(101, 36, 'Process Analysis', 1),
(102, 36, 'Process Improvement Methods', 1),
(103, 36, 'Technology in Business Processes', 1),
(104, 36, 'Documentation and Standardization', 1),
(105, 36, 'Performance Measurement', 1),
(106, 36, 'Quality Assurance in Processes', 1),
(107, 36, 'Change Management in Business Processes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `created_by`, `thumbnail_url`, `thumbnail`) VALUES
(25, 'Software Development Life Cycle (SDLC)', 'The Software Development Life Cycle (SDLC) course provides knowledge and skills about the stages of software development, from requirements analysis, design, coding, and testing to deployment and maintenance. Students will understand processes, methods, and tools to build effective software that meets user needs and ensures quality.', 4, NULL, 'uploads/course_thumbnails/6899862f44cf1_thumbnail.png'),
(26, 'Networking', 'The Networking course provides fundamental knowledge about computer networks, including concepts, models, devices, and network protocols. Students will learn how to design, implement, and manage network systems, as well as ensure connection security and performance in information technology environments.', 4, NULL, 'uploads/course_thumbnails/689cbcc92d9a1_thumbnail_networking.png'),
(27, 'Database Design & Development', 'The Database Design & Development course provides knowledge and skills for designing, building, and managing databases. Students will learn fundamental concepts, data modeling, SQL query language, and optimization techniques to develop database systems that meet application and user requirements.', 4, NULL, 'uploads/course_thumbnails/689cbd62ef95c_thumbnail_ddd.png'),
(28, 'Security', 'The Security course provides fundamental knowledge about information security and network safety, including concepts, threats, vulnerabilities, and methods to protect systems. Students will learn how to identify risks, apply attack prevention techniques, encrypt data, and maintain the security of both personal information and enterprise systems.', 4, NULL, 'uploads/course_thumbnails/689cbcb83259a_thumbnail_sercurity.png'),
(29, 'Data Structures & Algorithms', 'The Data Structures & Algorithms course provides knowledge and skills for efficiently organizing, storing, and processing data. Students will learn common data structures (arrays, linked lists, stacks, queues, trees, graphs) and fundamental algorithms (searching, sorting, traversing data) to optimize program performance and solve problems systematically.', 4, NULL, 'uploads/course_thumbnails/689cbd0c45b57_thumbnail_dsa.png'),
(30, 'Computing Fundamental', 'The Computing Fundamentals course provides basic knowledge about computers and information technology, including hardware, software, operating systems, computer networks, and security. Students will gain an understanding of key concepts, terminology, and skills to use computers effectively for learning, work, and problem-solving.', 6, NULL, 'uploads/course_thumbnails/689cbc881460b_thumbnail_cf.png'),
(31, 'Planning a Computing Project', 'The Planning a Computing Project course provides knowledge and skills to plan, organize, and manage an information technology project. Students will learn how to define objectives, analyze requirements, allocate resources, set timelines, and assess risks to ensure the project is executed effectively and achieves the desired outcomes.', 6, NULL, 'uploads/course_thumbnails/689cbb9c1a28e_thumbnail_planning_project.png'),
(32, 'Website Design & Development', 'The Website Design & Development course provides knowledge and skills to design and build complete websites. Students will learn how to create intuitive user interfaces, apply web design principles, program with HTML, CSS, and JavaScript, and use tools or frameworks to develop modern, user-friendly, and cross-platform websites.', 6, NULL, 'uploads/course_thumbnails/689cbad14687e_thumbnail_wwd.png'),
(33, 'Programming', 'Programming provides the fundamental knowledge and skills to write, understand and maintain software source code. Students will learn programming concepts, simple data structures, language syntax, logic techniques and problem-solving methods to develop efficient and organized applications.', 7, NULL, 'uploads/course_thumbnails/689cb9f31e974_thumbnail_programming.png'),
(34, 'Internet of Things', 'The Internet of Things (IoT) course provides fundamental knowledge and skills about connecting and enabling communication between smart devices through the internet. Students will learn about the principles of IoT operation, hardware and software components, communication protocols, real-world applications, and issues related to security and data management in IoT systems.', 7, NULL, 'uploads/course_thumbnails/689cb9e3a7bdf_thumbnail_iot.png'),
(35, 'Professional Practice', 'The Professional Practice course provides the knowledge and skills needed to work professionally in the field of information and computer technology. Students will learn about professional ethics, communication skills, teamwork, time management, problem-solving, and compliance with legal regulations to develop a sustainable and effective career.', 8, NULL, 'uploads/course_thumbnails/689cb9087772e_thumbnail_pp.png'),
(36, 'Business Process Support', 'The Business Process Support course provides knowledge and skills to support, optimize, and manage business processes within an organization. Students will learn how to analyze processes, use technology and software tools, improve work efficiency, and ensure that operations align with business objectives and quality standards.', 8, NULL, 'uploads/course_thumbnails/689cb7e06f974_thumbnail_bps.png');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`) VALUES
(6, 2, 25, '2025-08-11 13:07:29'),
(7, 2, 36, '2025-08-15 08:33:46'),
(8, 2, 32, '2025-08-15 08:34:06'),
(9, 2, 34, '2025-08-15 08:36:48'),
(10, 2, 33, '2025-08-15 08:49:53'),
(11, 2, 31, '2025-08-15 08:49:57'),
(12, 2, 30, '2025-08-15 08:50:01'),
(13, 2, 28, '2025-08-15 08:50:03'),
(14, 2, 27, '2025-08-15 08:50:06'),
(15, 2, 26, '2025-08-15 08:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `chapter_id`, `title`, `file_path`, `order`) VALUES
(34, 20, 'What is SDLC?', NULL, 1),
(35, 20, 'Why SDLC is important', NULL, 1),
(36, 20, 'Common SDLC models', NULL, 1),
(37, 21, 'Understanding user needs', NULL, 1),
(38, 21, 'Gathering requirements', NULL, 1),
(39, 21, 'Documenting requirements', NULL, 1),
(40, 22, 'High-level design (architecture)', NULL, 1),
(41, 22, 'Detailed design (components)', NULL, 1),
(42, 22, 'Design tools and diagrams', NULL, 1),
(43, 23, 'Writing clean code', NULL, 1),
(44, 23, 'Using version control', NULL, 1),
(45, 23, 'Following coding standards', NULL, 1),
(46, 24, 'Why testing is important', NULL, 1),
(47, 24, 'Types of testing', NULL, 1),
(48, 24, 'Fixing bugs', NULL, 1),
(49, 25, 'Deploying software', NULL, 1),
(50, 25, 'Monitoring software performance', NULL, 1),
(51, 25, 'Updating and improving software', NULL, 1),
(52, 26, 'What is a Computer Network?', NULL, 1),
(53, 26, 'Benefits of Networking', NULL, 1),
(54, 26, 'Types of Networks (LAN, WAN, MAN)', NULL, 1),
(55, 27, 'OSI Model Overview', NULL, 1),
(56, 27, 'TCP/IP Model Overview', NULL, 1),
(57, 27, 'Comparing OSI and TCP/IP', NULL, 1),
(58, 28, 'Routers and Switches', NULL, 1),
(59, 28, 'Hubs, Access Points, and Modems', NULL, 1),
(60, 28, 'Network Interface Cards (NICs)', NULL, 1),
(61, 29, 'Wired Connections (Ethernet, Fiber)', NULL, 1),
(62, 29, 'Wireless Connections (Wi-Fi, Bluetooth)', NULL, 1),
(63, 29, 'Choosing the Right Media', NULL, 1),
(64, 30, 'IPv4 Basics', NULL, 1),
(65, 30, 'IPv6 Basics', NULL, 1),
(66, 30, 'Subnetting Concepts', NULL, 1),
(67, 31, 'HTTP, HTTPS, FTP', NULL, 1),
(68, 31, 'TCP, UDP', NULL, 1),
(69, 31, 'DNS and DHCP', NULL, 1),
(70, 32, 'Common Threats (Viruses, Attacks)', NULL, 1),
(71, 32, 'Firewalls and Encryption', NULL, 1),
(72, 32, 'Safe Networking Practices', NULL, 1),
(73, 33, 'What is a Database?', NULL, 1),
(74, 33, 'Importance of Databases in Applications', NULL, 1),
(75, 33, 'Types of Databases', NULL, 1),
(76, 34, 'Understanding Entities and Attributes', NULL, 1),
(77, 34, 'Relationships Between Entities', NULL, 1),
(78, 34, 'Entity-Relationship (ER) Diagrams', NULL, 1),
(79, 35, 'Tables, Rows, and Columns', NULL, 1),
(80, 35, 'Primary Keys and Foreign Keys', NULL, 1),
(81, 35, 'Normalization Basics', NULL, 1),
(82, 36, 'Introduction to SQL', NULL, 1),
(83, 36, 'SELECT, INSERT, UPDATE, DELETE', NULL, 1),
(84, 36, 'Filtering and Sorting Data', NULL, 1),
(85, 37, 'Joins (INNER, LEFT, RIGHT)', NULL, 1),
(86, 37, 'Aggregation and Grouping', NULL, 1),
(87, 37, 'Subqueries and Views', NULL, 1),
(88, 38, 'Creating and Modifying Tables', NULL, 1),
(89, 38, 'Constraints and Indexes', NULL, 1),
(90, 38, 'Stored Procedures and Functions', NULL, 1),
(91, 39, 'Backup and Restore', NULL, 1),
(92, 39, 'Security and User Permissions', NULL, 1),
(93, 39, 'Performance Optimization', NULL, 1),
(94, 40, 'What is Security?', NULL, 1),
(95, 40, 'Importance of Information Security', NULL, 1),
(96, 40, 'Key Security Principles (CIA Triad)', NULL, 1),
(97, 41, 'Common Threats (Malware, Phishing, Social Engineering)', NULL, 1),
(98, 41, 'Vulnerabilities in Systems and Applications', NULL, 1),
(99, 41, 'Real-world Security Incidents', NULL, 1),
(100, 42, 'Basics of Network Protection', NULL, 1),
(101, 42, 'Firewalls and Intrusion Detection Systems', NULL, 1),
(102, 42, 'Secure Network Design', NULL, 1),
(103, 43, 'Data Classification and Protection', NULL, 1),
(104, 43, 'Encryption Methods (Symmetric & Asymmetric)', NULL, 1),
(105, 43, 'Data Backup and Recovery', NULL, 1),
(106, 44, 'Authentication and Authorization', NULL, 1),
(107, 44, 'Strong Password Policies', NULL, 1),
(108, 44, 'Multi-factor Authentication', NULL, 1),
(109, 45, 'Safe Internet and Email Use', NULL, 1),
(110, 45, 'Security Awareness Training', NULL, 1),
(111, 45, 'Incident Response Basics', NULL, 1),
(112, 46, 'Data Privacy Laws (GDPR, etc.)', NULL, 1),
(113, 46, 'Ethical Hacking', NULL, 1),
(114, 46, 'Professional Responsibility in Security', NULL, 1),
(115, 47, 'What are Data Structures?', NULL, 1),
(116, 47, 'What are Algorithms?', NULL, 1),
(117, 47, 'Importance of Efficiency', NULL, 1),
(118, 48, 'Introduction to Arrays', NULL, 1),
(119, 48, 'Basic Array Operations', NULL, 1),
(120, 48, 'Working with Strings', NULL, 1),
(121, 49, 'Singly Linked Lists', NULL, 1),
(122, 49, 'Doubly Linked Lists', NULL, 1),
(123, 49, 'Circular Linked Lists', NULL, 1),
(124, 50, 'Understanding Stacks (LIFO)', NULL, 1),
(125, 50, 'Understanding Queues (FIFO)', NULL, 1),
(126, 50, 'Priority Queues and Deques', NULL, 1),
(127, 51, 'Binary Trees Basics', NULL, 1),
(128, 51, 'Binary Search Trees (BST)', NULL, 1),
(129, 51, 'Tree Traversals (Inorder, Preorder, Postorder)', NULL, 1),
(130, 52, 'Graph Terminology and Representation', NULL, 1),
(131, 52, 'Graph Traversals (BFS, DFS)', NULL, 1),
(132, 52, 'Shortest Path Algorithms Basics', NULL, 1),
(133, 53, 'Linear Search and Binary Search', NULL, 1),
(134, 53, 'Bubble Sort and Selection Sort', NULL, 1),
(135, 53, 'Insertion Sort and Merge Sort', NULL, 1),
(136, 54, 'Big O Notation Basics', NULL, 1),
(137, 54, 'Time Complexity Examples', NULL, 1),
(138, 54, 'Space Complexity Basics', NULL, 1),
(139, 55, 'What is a Computer?', NULL, 1),
(140, 55, 'History and Generations of Computers', NULL, 1),
(141, 55, 'Types of Computers', NULL, 1),
(142, 56, 'Input and Output Devices', NULL, 1),
(143, 56, 'Storage Devices', NULL, 1),
(144, 56, 'Central Processing Unit (CPU)', NULL, 1),
(145, 57, 'System Software and Application Software', NULL, 1),
(146, 57, 'Examples of Popular Software', NULL, 1),
(147, 57, 'Installing and Updating Software', NULL, 1),
(148, 58, 'Functions of an Operating System', NULL, 1),
(149, 58, 'Types of Operating Systems', NULL, 1),
(150, 58, 'Basic OS Navigation (Windows, macOS, Linux)', NULL, 1),
(151, 59, 'What is a Network?', NULL, 1),
(152, 59, 'Internet Basics', NULL, 1),
(153, 59, 'Network Devices and Connections', NULL, 1),
(154, 60, 'Common Computer Threats', NULL, 1),
(155, 60, 'Safe Internet Practices', NULL, 1),
(156, 60, 'Data Backup and Recovery', NULL, 1),
(157, 61, 'What is a Computing Project?', NULL, 1),
(158, 61, 'Importance of Project Planning', NULL, 1),
(159, 61, 'Project Life Cycle Overview', NULL, 1),
(160, 62, 'Setting Clear Project Goals', NULL, 1),
(161, 62, 'Determining Project Scope', NULL, 1),
(162, 62, 'Identifying Success Criteria', NULL, 1),
(163, 63, 'Gathering User Requirements', NULL, 1),
(164, 63, 'Documenting Requirements', NULL, 1),
(165, 63, 'Validating and Prioritizing Requirements', NULL, 1),
(166, 64, 'Human Resources and Roles', NULL, 1),
(167, 64, 'Hardware and Software Needs', NULL, 1),
(168, 64, 'Budget Planning', NULL, 1),
(169, 65, 'Creating a Project Schedule', NULL, 1),
(170, 65, 'Milestones and Deadlines', NULL, 1),
(171, 65, 'Using Project Management Tools', NULL, 1),
(172, 66, 'Identifying Potential Risks', NULL, 1),
(173, 66, 'Risk Assessment Techniques', NULL, 1),
(174, 66, 'Contingency Planning', NULL, 1),
(175, 67, 'Tracking Project Progress', NULL, 1),
(176, 67, 'Quality Assurance', NULL, 1),
(177, 67, 'Post-Project Review and Lessons Learned', NULL, 1),
(178, 68, 'What is a Website?', NULL, 1),
(179, 68, 'How the Web Works', NULL, 1),
(180, 68, 'Overview of Frontend and Backend', NULL, 1),
(181, 69, 'HTML Structure and Elements', NULL, 1),
(182, 69, 'Text, Links, and Images', NULL, 1),
(183, 69, 'Forms and Input Elements', NULL, 1),
(184, 70, 'Introduction to CSS', NULL, 1),
(185, 70, 'Colors, Fonts, and Layouts', NULL, 1),
(186, 70, 'Responsive Design Basics', NULL, 1),
(187, 71, 'Introduction to JavaScript', NULL, 1),
(188, 71, 'Variables, Functions, and Events', NULL, 1),
(189, 71, 'DOM Manipulation', NULL, 1),
(190, 72, 'UI/UX Basics', NULL, 1),
(191, 72, 'Navigation and Accessibility', NULL, 1),
(192, 72, 'Mobile-Friendly Design', NULL, 1),
(193, 73, 'Introduction to Frontend Frameworks (e.g., Bootstrap)', NULL, 1),
(194, 73, 'Using Code Editors and Version Control (Git)', NULL, 1),
(195, 73, 'Working with Templates and Libraries', NULL, 1),
(196, 74, 'Introduction to Server-Side Programming', NULL, 1),
(197, 74, 'Connecting to a Database', NULL, 1),
(198, 74, 'Basic CRUD Operations', NULL, 1),
(199, 75, 'Preparing a Website for Launch', NULL, 1),
(200, 75, 'Hosting and Domain Names', NULL, 1),
(201, 75, 'Website Maintenance and Updates', NULL, 1),
(202, 76, 'What is Programming?', NULL, 1),
(203, 76, 'Programming Languages Overview', NULL, 1),
(204, 76, 'How Programs Work', NULL, 1),
(205, 77, 'Installing and Using an IDE', NULL, 1),
(206, 77, 'Writing and Running Your First Program', NULL, 1),
(207, 77, 'Understanding Syntax and Errors', NULL, 1),
(208, 78, 'Declaring Variables', NULL, 1),
(209, 78, 'Common Data Types (Numbers, Strings, Booleans)', NULL, 1),
(210, 78, 'Type Conversion and Casting', NULL, 1),
(211, 79, 'Arithmetic Operators', NULL, 1),
(212, 79, 'Comparison and Logical Operators', NULL, 1),
(213, 79, 'Assignment and Increment/Decrement', NULL, 1),
(214, 80, 'Conditional Statements (if, else, switch)', NULL, 1),
(215, 80, 'Loops (for, while, do-while)', NULL, 1),
(216, 80, 'Nested Control Structures', NULL, 1),
(217, 81, 'Defining and Calling Functions', NULL, 1),
(218, 81, 'Parameters and Return Values', NULL, 1),
(219, 81, 'Scope and Lifetime of Variables', NULL, 1),
(220, 82, 'Introduction to Arrays', NULL, 1),
(221, 82, 'Accessing and Modifying Array Elements', NULL, 1),
(222, 82, 'Using Lists and Other Collections', NULL, 1),
(223, 83, 'Understanding Debugging', NULL, 1),
(224, 83, 'Using Debugging Tools', NULL, 1),
(225, 83, 'Writing Simple Test Cases', NULL, 1),
(226, 84, 'What is IoT?', NULL, 1),
(227, 84, 'History and Evolution of IoT', NULL, 1),
(228, 84, 'Benefits and Challenges of IoT', NULL, 1),
(229, 85, 'IoT System Layers (Perception, Network, Application)', NULL, 1),
(230, 85, 'IoT Devices and Sensors', NULL, 1),
(231, 85, 'IoT Gateways and Data Processing', NULL, 1),
(232, 86, 'Microcontrollers and Microprocessors (e.g., Arduino, Raspberry Pi)', NULL, 1),
(233, 86, 'Common IoT Sensors and Actuators', NULL, 1),
(234, 86, 'Power Sources and Energy Management', NULL, 1),
(235, 87, 'Wired Communication (Ethernet, Serial)', NULL, 1),
(236, 87, 'Wireless Communication (Wi-Fi, Bluetooth, Zigbee)', NULL, 1),
(237, 87, 'IoT-Specific Protocols (MQTT, CoAP, LoRaWAN)', NULL, 1),
(238, 88, 'IoT Operating Systems and Firmware', NULL, 1),
(239, 88, 'IoT Cloud Platforms (AWS IoT, Google Cloud IoT, Azure IoT)', NULL, 1),
(240, 88, 'Data Collection and Visualization Tools', NULL, 1),
(241, 89, 'Smart Home and Building Automation', NULL, 1),
(242, 89, 'Industrial IoT (IIoT) and Smart Manufacturing', NULL, 1),
(243, 89, 'Healthcare, Agriculture, and Transportation Applications', NULL, 1),
(244, 90, 'Common IoT Security Risks', NULL, 1),
(245, 90, 'Encryption and Secure Communication', NULL, 1),
(246, 90, 'Data Privacy and Legal Considerations', NULL, 1),
(247, 91, 'AI and Machine Learning in IoT', NULL, 1),
(248, 91, 'Edge and Fog Computing', NULL, 1),
(249, 91, 'Emerging Technologies and Opportunities', NULL, 1),
(250, 92, 'Understanding Professionalism', NULL, 1),
(251, 92, 'Importance in the IT Industry', NULL, 1),
(252, 92, 'Key Professional Qualities', NULL, 1),
(253, 93, 'Principles of Ethical Conduct', NULL, 1),
(254, 93, 'Common Ethical Issues in IT', NULL, 1),
(255, 93, 'Case Studies in Ethics', NULL, 1),
(256, 94, 'Verbal and Non-verbal Communication', NULL, 1),
(257, 94, 'Writing Professional Emails and Reports', NULL, 1),
(258, 94, 'Presentation and Public Speaking Skills', NULL, 1),
(259, 95, 'Roles in a Team', NULL, 1),
(260, 95, 'Building Trust and Cooperation', NULL, 1),
(261, 95, 'Resolving Conflicts', NULL, 1),
(262, 96, 'Setting Goals and Priorities', NULL, 1),
(263, 96, 'Time Management Tools and Techniques', NULL, 1),
(264, 96, 'Meeting Deadlines', NULL, 1),
(265, 97, 'Problem Identification and Analysis', NULL, 1),
(266, 97, 'Creative Solutions and Decision-Making', NULL, 1),
(267, 97, 'Evaluating Results', NULL, 1),
(268, 98, 'Understanding IT Laws and Regulations', NULL, 1),
(269, 98, 'Intellectual Property Rights', NULL, 1),
(270, 98, 'Data Privacy and Protection Laws', NULL, 1),
(271, 99, 'Building a Professional Portfolio', NULL, 1),
(272, 99, 'Preparing for Job Interviews', NULL, 1),
(273, 99, 'Lifelong Learning and Skill Development', NULL, 1),
(274, 100, 'What is a Business Process?', NULL, 1),
(275, 100, 'Importance of Process Management', NULL, 1),
(276, 100, 'Types of Business Processes', NULL, 1),
(277, 101, 'Identifying Process Steps', NULL, 1),
(278, 101, 'Mapping Business Processes', NULL, 1),
(279, 101, 'Analyzing Efficiency and Bottlenecks', NULL, 1),
(280, 102, 'Continuous Improvement Concepts', NULL, 1),
(281, 102, 'Lean Principles', NULL, 1),
(282, 102, 'Six Sigma Basics', NULL, 1),
(283, 103, 'Business Process Management Systems (BPMS)', NULL, 1),
(284, 103, 'Automation Tools (RPA)', NULL, 1),
(285, 103, 'Integration of IT Solutions', NULL, 1),
(286, 104, 'Creating Standard Operating Procedures (SOPs)', NULL, 1),
(287, 104, 'Using Templates and Forms', NULL, 1),
(288, 104, 'Version Control and Updates', NULL, 1),
(289, 105, 'Key Performance Indicators (KPIs)', NULL, 1),
(290, 105, 'Data Collection and Reporting', NULL, 1),
(291, 105, 'Benchmarking and Best Practices', NULL, 1),
(292, 106, 'Understanding Quality Standards', NULL, 1),
(293, 106, 'Auditing Business Processes', NULL, 1),
(294, 106, 'Corrective and Preventive Actions', NULL, 1),
(295, 107, 'Understanding Change in Organizations', NULL, 1),
(296, 107, 'Communicating Process Changes', NULL, 1),
(297, 107, 'Managing Resistance and Adoption', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(3, '1', '2', 4, '2025-08-03 20:50:42', '2025-08-03 20:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL CHECK (`grade` between 0 and 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `assignment_id`, `student_id`, `file_path`, `grade`) VALUES
(5, 2, 2, 'uploads/submissions/689e947b06c29_BC00361_TienNV_AssignmentFinal.docx', 90);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','instructor','student') NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `reset_token`, `reset_token_expires`) VALUES
(2, 'Nguyễn Việt Tiến', 'student@gmail.com', '$2y$10$zKZOrOu7m42tjByV3AH1Ye8wJ.pqRh9D2dUEs5j6OvU5r08TjiudC', 'student', NULL, NULL),
(4, 'Tran Van Nhuom', 'nhuomtv@gmail.com', '$2y$10$vaanCBj9/ZySy2UIohc/9.Z8QmKZoal3T5vbNHhCnpnscYO2TX3oW', 'instructor', NULL, NULL),
(5, 'Nguyễn Việt Tiến', 'admin@gmail.com', '$2y$10$qBk2Q8oZbpoSx0/yQmJguOBuE3hN3Uvg4fdZf78vFEMyKCdd7yUhe', 'admin', 'baf0cb95b4468b0aca0b32734335be742814d7fd5363b34ae9eab9b5c6380173', '2025-08-03 11:19:22'),
(6, 'Le Nhat Quang', 'quangln@gmail.com', '$2y$10$ULNDL0HkXRa4h.1QzjdADeEmurcXmNi5J6vYzBOQAessSDpLwhelC', 'instructor', NULL, NULL),
(7, 'Le Duc Trong', 'trongld@gmail.com', '$2y$10$sq2j9CPFzLQA79kco4dkEuibQHY.iIRf6cGLq9gY.zdxEjUrgfys2', 'instructor', NULL, NULL),
(8, 'Nguyen Thanh Phuoc', 'phuocnt@gmail.com', '$2y$10$C76pFtj84hC9woMafTslXeFnMsLOWsG5cqJDTyfsuluMNK8h6MAcu', 'instructor', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forums`
--
ALTER TABLE `forums`
  ADD CONSTRAINT `forums_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forums_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD CONSTRAINT `quiz_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
