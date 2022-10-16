CREATE TABLE `timesheet` (
  `timesheet_uuid` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'UUID()',
  `employee_uuid` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_danish_ci NOT NULL,
  `contract_uuid` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_danish_ci DEFAULT NULL,
  `timesheet_work_date` date NOT NULL,
  `timesheet_hours_regular` float NOT NULL,
  `timesheet_hours_overtime` float NOT NULL,
  `timesheet_hours_break` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`timesheet_uuid`),
  KEY `fk_timesheet_employee_idx` (`employee_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_danish_ci

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


/* SQL-statement that calculates the total regular, overtime & break hours for a given period */
SELECT sum(t.timesheet_hours_regular) AS total_hours_regular, sum(t.timesheet_hours_overtime) AS total_hours_overtime, sum(t.timesheet_hours_break) AS total_hours_break
FROM timesheet t
WHERE t.employee_uuid = '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
 AND t.timesheet_work_date BETWEEN '2022-10-03' AND '2022-10-09'
GROUP BY t.employee_uuid;