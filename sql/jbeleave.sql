SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `jbe_leave`
--

-- --------------------------------------------------------

--
-- Table structure for table `jbe_employees` 
-- 
--

CREATE TABLE `jbe_employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email_phone` varchar(50) NOT NULL UNIQUE,
  `department` varchar(50) NOT NULL,
  `job_description` varchar(255) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `region` varchar(50) NOT NULL,
  `employeetype` varchar(50) DEFAULT 'user',
  `linemanagername` varchar(255) NOT NULL,
  `linemanageremail` varchar(255) NOT NULL,
  `totalleave` int(20) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jbe_employees_leave` 
-- 
--
CREATE TABLE `jbe_employees_leave` (
  `employee_leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `totalleave` int(11) NOT NULL,
  `daystaken` int(11) NOT NULL,
  `daysleft` int(11) NOT NULL,
  `start_date` varchar(100) NOT NULL,
  `end_date` varchar(100) NOT NULL,
  `noofdays` int(10) NOT NULL,
  `resumption_date` varchar(100) NOT NULL,
  `year` varchar(20) NOT NULL,
  `replacedby` varchar(255) NOT NULL,
  `leavetype` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `hr_attend` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`employee_leave_id`),
  CONSTRAINT `jbe_employees_employee_id_fk1` FOREIGN KEY (`employee_id`)
  REFERENCES `jbe_employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leave_years` 
-- 
--
CREATE TABLE `leave_years` (
  `leave_year_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `totalleave` int(11) NOT NULL,
  `daystaken` int(11) NOT NULL,
  `daysleft` int(11) NOT NULL,
  PRIMARY KEY (`leave_year_id`),
  CONSTRAINT `jbe_employees_employee_id_fk2` FOREIGN KEY (`employee_id`)
  REFERENCES `jbe_employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
    `department_id` int(11) NOT NULL,
    `department` varchar(100) NOT NULL,
    `department_slug` varchar(100) NOT NULL
);


ALTER TABLE `departments` 
    ADD PRIMARY KEY (`department_id`);

ALTER TABLE `departments`
    MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT;



-- --------------------------------------------------------
--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
    `region_id` int(11) NOT NULL,
    `region` varchar(100) NOT NULL,
    `region_slug` varchar(100) NOT NULL
);

ALTER TABLE `regions` 
    ADD PRIMARY KEY (`region_id`);

ALTER TABLE `regions`
    MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;


-- --------------------------------------------------------
--
-- Table structure for table `branches`
--
CREATE TABLE `branches` (
    `branch_id` int(11) NOT NULL,
    `branch` varchar(100) NOT NULL,
    `region_id` int(11) NOT NULL,
    `branch_slug` varchar(100) NOT NULL
);

ALTER TABLE `branches`
    ADD PRIMARY KEY (`branch_id`);

ALTER TABLE `branches`
    MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `branches`
    ADD CONSTRAINT `regions_region_id_fk` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`);


-- --------------------------------------------------------
--
-- Table structure for table INSERT
--

INSERT INTO `departments` (`department`, `department_slug`) VALUES 
('Accounts', 'accounts'),
('Aftersales', 'aftersales'),
('Fabrication', 'fabrication'),
('Finance', 'finance'),
('Genset', 'genset'),
('Health Safety Environment', 'health_safety_environment'),
('Human Resources', 'human_resources'),
('Information Technology', 'information_technology'),
('Logistics', 'logistics'),
('Management', 'management'),
('Maintenace', 'maintenace'),
('Marketing', 'marketing'),
('Production', 'production'),
('Project Account', 'project_account'),
('Public Relation', 'public_relation'),
('Procurement', 'procurement'),
('Sales', 'sales'),
('Stores', 'stores'),
('Technical', 'technical'),
('Telecom', 'telecom');

INSERT INTO `regions` (`region`, `region_slug`) VALUES 
('Nigeria', 'nigeria'),
('Lebanon', 'lebanon'),
('UAE', 'uae'),
('Afghanistan', 'afghanistan'),
('Kuwait', 'kuwait'),
('Ghana', 'ghana'),
('Qatar', 'qatar'),
('Uganda', 'uganda'),
('South African', 'south_africa'),
('Pakistan', 'pakistan');

INSERT INTO `branches` (`branch`, `branch_slug`, `region_id`) VALUES 
('Abuja', 'abuja', 1),
('Ajah', 'ajah', 1),
('Factory', 'factory', 1),
('Ikeja', 'ikeja', 1),
('Kano', 'kano', 1),
('Port Harcourt', 'port_harcourt', 1),
('Victoria Island', 'vi', 1);

GRANT ALL PRIVILEGES ON jbeleave.* TO mike@localhost identified BY 'mike';

INSERT INTO `jbe_employees` (`employee_id`, `firstname`, `lastname`, `password`, `email_phone`, `department`, `job_description`, `branch`, `region`, `employeetype`, `linemanagername`, `linemanageremail`, `totalleave`, `created_at`) 
VALUES ('1', 'Michael', 'Aroworade', 'Fahrenheit1', 'Ayoaro85@gmail.com', 'Information Technology', 'IT Engineer', 'Victoria Island', 'Nigeria', 'user', 'Abdelmaleeq Adebayo', 'Abdelmaleeq.Adebayo.com', '18', current_timestamp());
INSERT INTO `jbe_employees` (`employee_id`, `firstname`, `lastname`, `password`, `email_phone`, `department`, `job_description`, `branch`, `region`, `employeetype`, `linemanagername`, `linemanageremail`, `totalleave`, `created_at`) 
VALUES ('2', 'Abdelmaleeq', 'Adebayo', 'Jb@ITam1n', 'it.vi@jubailibros.com', 'Information Technology', 'IT Manager', 'Ikeja', 'Nigeria', 'supervisor', 'Nabil Suleiman', 'Ayoaro85@gmail.com', '24', current_timestamp());
INSERT INTO `jbe_employees` (`employee_id`, `firstname`, `lastname`, `password`, `email_phone`, `department`, `job_description`, `branch`, `region`, `employeetype`, `linemanagername`, `linemanageremail`, `totalleave`, `created_at`) 
VALUES ('3', 'Ugoh', 'ukongwubel', 'Jb@VI123hr', 'Ayoaro85@gmail.com', 'Human Resources', 'HR Officer', 'Victoria Island', 'Nigeria', 'hr', 'Esther Abhulimen', 'Ay_michael96@yhoo.com', '22', current_timestamp());


INSERT INTO `leave_years` (`leave_year_id`, `employee_id`, `year`, `totalleave`, `daystaken`, `daysleft`) VALUES ('1', '1', '2022', '18', '18', '0');
INSERT INTO `leave_years` (`leave_year_id`, `employee_id`, `year`, `totalleave`, `daystaken`, `daysleft`) VALUES ('2', '1', '2023', '18', '0', '18');
INSERT INTO `leave_years` (`leave_year_id`, `employee_id`, `year`, `totalleave`, `daystaken`, `daysleft`) VALUES ('3', '2', '2022', '24', '18', '6');
INSERT INTO `leave_years` (`leave_year_id`, `employee_id`, `year`, `totalleave`, `daystaken`, `daysleft`) VALUES ('4', '2', '2023', '24', '0', '24');
INSERT INTO `leave_years` (`leave_year_id`, `employee_id`, `year`, `totalleave`, `daystaken`, `daysleft`) VALUES ('5', '3', '2022', '22', '22', '0');
INSERT INTO `leave_years` (`leave_year_id`, `employee_id`, `year`, `totalleave`, `daystaken`, `daysleft`) VALUES ('6', '3', '2023', '22', '4', '18');


COMMIT;


-- yearsRecord = SELECT jbe_employees.employee_id, leave_years.* FROM jbe_employees INNER JOIN  
-- leave_years ON jbe_employees.employee_id = leave_years.employee_id;