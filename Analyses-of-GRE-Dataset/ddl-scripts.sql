
/*DIM_PROGRAM*/

/** Creating dim_applicant table**/

DROP TABLE IF EXISTS dim_applicant ;

CREATE TABLE dim_applicant (
	applicant_key INT(12) NOT NULL AUTO_INCREMENT,
	application_id INT(12) NOT NULL,
	applicant_first_name VARCHAR(32) DEFAULT 'Unknown',
	applicant_last_name VARCHAR(32) DEFAULT 'Unknown',
	country_of_citizenship VARCHAR(32) DEFAULT 'Unknown',
	gender VARCHAR(32) DEFAULT 'Unknown',
	intended_entry_term VARCHAR(32) DEFAULT 'Unknown',
	PRIMARY KEY (`applicant_key`)
) ENGINE=MYISAM
 AUTO_INCREMENT=2728 DEFAULT CHARSET=utf8


/** Creating dim_date table**/

DROP TABLE IF EXISTS dim_date ;

CREATE TABLE dim_date (
  date_key INT(11) DEFAULT NULL,
  the_date DATE DEFAULT NULL,
  the_year SMALLINT(6) DEFAULT NULL,
  the_quarter TINYINT(4) DEFAULT NULL,
  the_month TINYINT(4) DEFAULT NULL,
  the_week TINYINT(4) DEFAULT NULL,
  day_of_year SMALLINT(6) DEFAULT NULL,
  day_of_month TINYINT(4) DEFAULT NULL,
  day_of_week TINYINT(4) DEFAULT NULL
) ENGINE=MYISAM DEFAULT CHARSET=utf8


/** Creating dim_ethnicity table**/

DROP TABLE IF EXISTS dim_ethnicity ;

CREATE TABLE dim_ethnicity (
  ethnicity_key INT(12) NOT NULL AUTO_INCREMENT,
  ethnicity_indian VARCHAR(50) DEFAULT 'NULL',
  ethnicity_asian VARCHAR(50) DEFAULT 'NULL',
  ethnicity_white VARCHAR(50) DEFAULT 'NULL',
  ethnicity_black VARCHAR(50) DEFAULT 'NULL',
  ethnicity_hawaiian VARCHAR(50) DEFAULT 'NULL',
  ethnicity_hispanic VARCHAR(4) DEFAULT 'NULL',
  PRIMARY KEY (`ethnicity_key`)
) ENGINE=MYISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8

/** Creating dim_program table**/

DROP TABLE IF EXISTS dim_program ;

CREATE TABLE dim_program (
  program_key INT(12) NOT NULL AUTO_INCREMENT,
  program_name VARCHAR(128) NOT NULL DEFAULT '',
  department_name VARCHAR(128) NOT NULL,
  PRIMARY KEY (program_key)
) ENGINE=MYISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8

/** Creating fact_application_1 table**/

DROP TABLE IF EXISTS fact_application_1 ;

CREATE TABLE fact_application_1 (
  applicant_key INT(12) DEFAULT '0',
  program_key INT(12) DEFAULT '0',
  application_key INT(12) DEFAULT '0',
  ethnicity_key INT(12) DEFAULT '0',
  last_updated_date DATE DEFAULT NULL,
  submitted_date DATE DEFAULT NULL,
  applied INT(7) NOT NULL DEFAULT '1',
  admitted INT(7) NOT NULL DEFAULT '0',
  accepted INT(7) NOT NULL DEFAULT '0',
  KEY idx_program_key (program_key),
  KEY idx_applicat_key (applicant_key),
  KEY idx_application_key (applicant_key),
  KEY idx_ethnicity_key (ethnicity_key)
) ENGINE=MYISAM DEFAULT CHARSET=utf8

/** Creating dim_application table**/

DROP TABLE IF EXISTS dim_application ;

CREATE TABLE dim_application (
  application_key INT(12) NOT NULL AUTO_INCREMENT,
  application_id INT(12) DEFAULT NULL,
  gre_quant INT(20) DEFAULT '0',
  gre_analytics INT(20) DEFAULT '0',
  gre_verbal INT(20) DEFAULT '0',
  submitted_date DATE DEFAULT NULL,
  last_updated_date DATE DEFAULT NULL,
  admission_decision VARCHAR(32) DEFAULT 'Unknown',
  PRIMARY KEY (application_key)
) ENGINE=MYISAM AUTO_INCREMENT=2728 DEFAULT CHARSET=utf8

/** Creating dim_college table**/

DROP TABLE IF EXISTS dim_college ;

CREATE TABLE dim_college (
  college_key INT(12) NOT NULL AUTO_INCREMENT,
  application_id INT(12) DEFAULT NULL,
  college_name_1 VARCHAR(50) DEFAULT 'NULL',
  college_name_2 VARCHAR(50) DEFAULT 'NULL',
  college_name_3 VARCHAR(50) DEFAULT 'NULL',
  college_country_1 VARCHAR(50) DEFAULT 'NULL',
  college_country_2 VARCHAR(50) DEFAULT 'NULL',
  college_country_3 VARCHAR(4) DEFAULT 'NULL',
  international_1 VARCHAR(4) DEFAULT 'NULL',
  international_2 VARCHAR(4) DEFAULT 'NULL',
  international_3 VARCHAR(4) DEFAULT 'NULL',
  PRIMARY KEY (college_key)
) ENGINE=MYISAM AUTO_INCREMENT=2752 DEFAULT CHARSET=utf8

/* This are the select statements used in the transformations*/

SELECT ethnicity_indian,ethnicity_asian,ethnicity_white,ethnicity_black,ethnicity_hawaiian,ethnicity_hispanic 
FROM admissions_info;

SELECT DISTINCT program, department_name
FROM admissions_info 
ORDER BY 1,2 ;

SELECT application_id, applicant_first_name, applicant_last_name, country_of_citizenship, gender,intended_entry_term 
FROM admissions_info;

SELECT application_id, gre_quantitative_perc, gre_analytical_perc,gre_verbal_perc,STR_TO_DATE(submitted_date,'%m/%d/%Y') 
AS submitted_date, STR_TO_DATE(last_updated_date,'%m/%d/%Y') AS last_updated_date ,
       CASE prog_actn WHEN 'DENY' THEN 'Admission Denied'
                      WHEN 'APPL' THEN 'Applicant Applied'
                      WHEN 'WAPP' THEN 'Applicant Withrew'
                      WHEN 'MATR' THEN 'Applicant Matriculated'
       END AS admission_decision       
  FROM admissions_info;
  
/* Bridge table */
  
INSERT INTO dim_college (application_id,college_name_1,college_name_2,college_name_3,college_country_1,college_country_2,college_country_3,international_1,international_2,international_3)
FROM admissions_info; 

SELECT application_id,college_name_1,college_name_2,college_name_3,college_country_1,college_country_2,college_country_3,international_1,international_2,international_3 
FROM admissions_info;


