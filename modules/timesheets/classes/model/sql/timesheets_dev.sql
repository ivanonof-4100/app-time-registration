CREATE TABLE `timesheet` (
  `timesheet_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'UUID()',
  `employee_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contract_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `timesheet_work_date` date NOT NULL,
  `timesheet_hours_regular` float NOT NULL,
  `timesheet_hours_overtime` float NOT NULL,
  `timesheet_hours_break` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`timesheet_uuid`),
  KEY `fk_timesheet_employee_idx` (`employee_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

/* Triggers */
DELIMITER #
CREATE TRIGGER `trigger_timesheet_beforeinsert` BEFORE INSERT ON `timesheet`
FOR EACH ROW
BEGIN
	SET NEW.timesheet_uuid = UUID();
END
#DELIMITER ;



DELIMITER $$
$$
CREATE TRIGGER trigger_timesheet_beforeinsert
BEFORE INSERT ON timesheet FOR EACH ROW
BEGIN 
	SET NEW.timesheet_uuid = UUID(); 
END
$$
DELIMITER ;


/* SQL-statment: Number of registions for the employee */
SELECT count(t.timesheet_uuid) AS NUM_RECORDS_FOUND
FROM timesheet t
WHERE t.employee_uuid = '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
LIMIT 1

/* SQL-statment: Get all timesheets for the given period and for a given employee. */
SELECT t.timesheet_uuid, t.timesheet_work_date, t.timesheet_hours_regular, t.timesheet_hours_break
FROM timesheet t
WHERE t.employee_uuid = '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
 AND t.timesheet_work_date BETWEEN '2022-10-03' AND '2022-10-09'
ORDER BY t.timesheet_work_date ASC
;

/* SQL-statement that calculates the weekly overview that contains total regular, overtime & break hours for a given period */
SELECT sum(t.timesheet_hours_regular) AS total_hours_regular, sum(t.timesheet_hours_overtime) AS total_hours_overtime, sum(t.timesheet_hours_break) AS total_hours_break
FROM timesheet t
WHERE t.employee_uuid = '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
 AND t.timesheet_work_date BETWEEN '2022-10-03' AND '2022-10-09'
GROUP BY t.employee_uuid;

/* SQL-statement that retrives the annual overview has the accumulated hours for each week. */
SELECT week(t.timesheet_work_date) AS working_week, sum(t.timesheet_hours_regular) AS total_hours_regular, sum(t.timesheet_hours_overtime) AS total_hours_overtime, sum(t.timesheet_hours_break) AS total_hours_break
FROM timesheet t
WHERE t.employee_uuid = '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
 AND t.timesheet_work_date BETWEEN '2022-01-01' AND '2022-12-31'
GROUP BY t.employee_uuid, working_week
ORDER BY working_week DESC;

/* Data also with the quarter */
SELECT QUARTER(timesheet_work_date) AS working_quarter, week(t.timesheet_work_date) AS working_week, sum(t.timesheet_hours_regular) AS total_hours_regular, sum(t.timesheet_hours_overtime) AS total_hours_overtime, sum(t.timesheet_hours_break) AS total_hours_break
FROM timesheet t
WHERE t.employee_uuid = '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
 AND t.timesheet_work_date BETWEEN '2022-01-01' AND '2022-12-31'
GROUP BY t.employee_uuid, working_quarter, working_week
ORDER BY working_quarter DESC, working_week DESC;