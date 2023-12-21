-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 08, 2023 at 02:54 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `globalhealthdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `APE`
--

CREATE TABLE `APE` (
  `id` int(11) NOT NULL,
  `headCount` int(11) DEFAULT NULL,
  `controlNumber` int(11) DEFAULT NULL,
  `controlDate` date DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` varchar(6) DEFAULT NULL,
  `organizationId` int(11) DEFAULT NULL,
  `employeeNumber` varchar(255) DEFAULT NULL,
  `membership` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `dateRegistered` date DEFAULT NULL,
  `dateCompleted` date DEFAULT NULL,
  `examination` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `APE`
--

INSERT INTO `APE` (`id`, `headCount`, `controlNumber`, `controlDate`, `firstName`, `middleName`, `lastName`, `birthDate`, `age`, `sex`, `organizationId`, `employeeNumber`, `membership`, `department`, `level`, `dateRegistered`, `dateCompleted`, `examination`, `remarks`, `timestamp`, `userId`) VALUES
(1, 1, 1, '2023-11-29', 'GRACE', 'REYES', 'ADALLA', NULL, 35, 'Female', 2, '', 'Member', 'Employee', 'Philcare', '2023-08-30', '2023-10-31', 'Basic 5', '', '2023-10-26 14:33:51', 57),
(2, 2, 2, '2023-11-29', 'GIAN CARLO', 'G', 'ALCANTARA', NULL, 34, 'Male', 2, '', 'Member', 'Employee', 'Philcare', '2023-08-29', '2023-10-25', 'Basic 5 + ECG', '', '2023-10-26 14:38:27', 57),
(46, 3, NULL, NULL, 'John', 'William', 'Doe', NULL, 25, 'Male', 2, '001', 'Gold', 'IT', 'Junior', '2023-10-01', NULL, 'Physical', 'Completed', '2023-11-12 02:37:03', 57),
(47, 4, NULL, NULL, 'Jane', 'Alice', 'Smith', NULL, 32, 'Female', 2, '002', 'Silver', 'Sales', 'Senior', '2023-11-15', NULL, 'Medical', NULL, '2023-11-12 02:37:03', 57),
(48, 5, NULL, NULL, 'Michael', 'Robert', 'Johnson', NULL, 28, 'Male', 2, '003', 'Platinum', 'Engineering', 'Manager', '2023-12-01', NULL, 'Dental', '', '2023-11-12 02:37:03', 57),
(49, 6, NULL, NULL, 'Amanda', 'Mary', 'Clark', NULL, 35, 'Female', 2, '004', 'Silver', 'Finance', 'Director', '2023-12-20', NULL, 'Medical', '', '2023-11-12 02:37:03', 57),
(50, 7, NULL, NULL, 'Chris', 'David', 'Brown', NULL, 28, 'Male', 2, '005', 'Gold', 'IT', 'Junior', '2023-01-10', NULL, 'Physical', NULL, '2023-11-12 02:37:03', 57),
(51, 8, NULL, NULL, 'Elmma', 'Elizabeth', 'Watson', NULL, 38, 'Female', 2, '006', 'Silver', 'Sales', 'Senior', '2023-02-05', NULL, 'Medical', '', '2023-11-12 02:37:03', 57),
(52, 9, NULL, NULL, 'David', 'Matthew', 'Adams', NULL, 33, 'Male', 2, '007', 'Platinum', 'Engineering', 'Director', '2023-03-15', NULL, 'Physical', '', '2023-11-12 02:37:03', 57),
(53, 10, NULL, NULL, 'Olivia', 'Rose', 'Smith', NULL, 28, 'Female', 2, '008', 'Silver', 'Finance', 'Junior', '2023-04-20', NULL, 'Dental', NULL, '2023-11-12 02:37:03', 57),
(54, 11, NULL, NULL, 'Daniel', 'Joseph', 'Anderson', NULL, 22, 'Male', 2, '009', 'Gold', 'IT', 'Manager', '2023-05-01', NULL, 'Physical', NULL, '2023-11-12 02:37:03', 57),
(55, 1, NULL, NULL, 'Sophia', 'Grace', 'White', NULL, 29, 'Female', 2, '010', 'Platinum', 'Engineering', 'Senior', '2023-00-15', NULL, 'Medical', NULL, '2023-11-12 02:37:03', 57),
(56, 12, NULL, NULL, 'John', 'Anderson', 'Smith', NULL, 35, 'Male', 2, '001', 'Gold', 'IT', 'Senior', '2023-01-05', NULL, 'Blood Test; X-ray; EKG', NULL, '2023-11-12 02:37:03', 57),
(57, 13, NULL, NULL, 'Jane', 'Hamilton', 'Doe', NULL, 28, 'Female', 2, '002', 'Silver', 'HR', 'Junior', '2023-02-10', NULL, 'Physical Examination; Blood Pressure Check; Cholesterol Test', NULL, '2023-11-12 02:37:03', 57),
(58, 14, NULL, NULL, 'Michael', 'Bryant', 'Johnson', NULL, 40, 'Male', 2, '003', 'Bronze', 'Finance', 'Manager', '2023-03-15', NULL, 'MRI Scan; Colonoscopy; Electroencephalogram (EEG)', NULL, '2023-11-12 02:37:03', 57),
(59, 15, NULL, NULL, 'Emily', 'Ferguson', 'Williams', NULL, 32, 'Female', 2, '004', 'Gold', 'Marketing', 'Senior', '2023-04-20', NULL, 'Ultrasound; Stress Test; Bone Density Test', NULL, '2023-11-12 02:37:03', 57),
(60, 16, NULL, NULL, 'Christopher', 'Harrison', 'Brown', NULL, 25, 'Male', 2, '005', 'Silver', 'IT', 'Junior', '2023-05-25', NULL, 'CT Scan; Allergy Test; Spirometry', NULL, '2023-11-12 02:37:03', 57),
(61, 17, NULL, NULL, 'Amanda', 'Cooper', 'Miller', NULL, 38, 'Female', 2, '006', 'Bronze', 'Finance', 'Manager', '2023-06-30', NULL, 'Endoscopy; DEXA Scan; Genetic Testing', NULL, '2023-11-12 02:37:03', 57),
(62, 18, NULL, NULL, 'David', 'Martin', 'Davis', NULL, 45, 'Male', 2, '007', 'Gold', 'HR', 'Senior', '2023-07-05', NULL, 'Echocardiogram; Blood Test; X-ray', NULL, '2023-11-12 02:37:03', 57),
(63, 19, NULL, NULL, 'Sophia', 'Spencer', 'Anderson', NULL, 29, 'Female', 2, '008', 'Silver', 'Marketing', 'Junior', '2023-08-10', NULL, 'Bone Density Test; Cholesterol Test; Allergy Test', NULL, '2023-11-12 02:37:03', 57),
(64, 20, NULL, NULL, 'Matthew', 'Hunter', 'Jackson', NULL, 33, 'Male', 2, '009', 'Bronze', 'IT', 'Manager', '2023-09-15', NULL, 'Physical Examination; Electroencephalogram (EEG); CT Scan', NULL, '2023-11-12 02:37:03', 57),
(65, 21, NULL, NULL, 'Olivia', 'Roberts', 'White', NULL, 26, 'Female', 2, '010', 'Gold', 'Finance', 'Senior', '2023-10-20', NULL, 'EKG; Stress Test; Ultrasound', NULL, '2023-11-12 02:37:03', 57),
(66, 22, NULL, NULL, 'Andrew', 'Fisher', 'Turner', NULL, 31, 'Male', 2, '011', 'Silver', 'IT', 'Junior', '2023-11-25', NULL, 'Genetic Testing; MRI Scan; Blood Pressure Check', NULL, '2023-11-12 02:37:03', 57),
(67, 23, NULL, NULL, 'Isabella', 'Conrad', 'Hall', NULL, 37, 'Female', 2, '012', 'Bronze', 'HR', 'Manager', '2023-12-30', NULL, 'DEXA Scan; Endoscopy; Spirometry', NULL, '2023-11-12 02:37:03', 57),
(68, 24, NULL, NULL, 'William', 'Fleming', 'Wilson', NULL, 42, 'Male', 2, '013', 'Gold', 'Marketing', 'Senior', '2023-01-04', NULL, 'Echocardiogram; Physical Examination; Cholesterol Test', NULL, '2023-11-12 02:37:03', 57),
(69, 25, NULL, NULL, 'Ella', 'Fitzgerald', 'Martin', NULL, 27, 'Female', 2, '014', 'Silver', 'IT', 'Junior', '2023-02-09', NULL, 'Blood Test; X-ray; Genetic Testing', NULL, '2023-11-12 02:37:03', 57),
(70, 26, NULL, NULL, 'Daniel', 'Morrison', 'Moore', NULL, 39, 'Male', 2, '015', 'Bronze', 'Finance', 'Manager', '2023-03-15', NULL, 'Colonoscopy; MRI Scan; Ultrasound', NULL, '2023-11-12 02:37:03', 57),
(71, 27, NULL, NULL, 'Sophie', 'Presley', 'Adams', NULL, 34, 'Female', 2, '016', 'Gold', 'HR', 'Senior', '2023-04-20', NULL, 'Stress Test; Electroencephalogram (EEG); Endoscopy', NULL, '2023-11-12 02:37:03', 57),
(72, 28, NULL, NULL, 'Benjamin', 'Jefferson', 'Evans', NULL, 29, 'Male', 2, '017', 'Silver', 'Marketing', 'Junior', '2023-05-25', NULL, 'DEXA Scan; Blood Pressure Check; CT Scan', NULL, '2023-11-12 02:37:03', 57),
(73, 29, NULL, NULL, 'Chloe', 'Hawkins', 'Clark', NULL, 43, 'Female', 2, '018', 'Bronze', 'IT', 'Manager', '2023-06-30', NULL, 'Bone Density Test; Allergy Test; EKG', NULL, '2023-11-12 02:37:03', 57),
(74, 30, NULL, NULL, 'James', 'Hudson', 'Hill', NULL, 24, 'Male', 2, '019', 'Gold', 'Finance', 'Senior', '2023-07-05', NULL, 'MRI Scan; Cholesterol Test; Genetic Testing', NULL, '2023-11-12 02:37:03', 57),
(75, 31, NULL, NULL, 'George', 'Smith', 'Washington', NULL, 55, 'Male', 2, '020', 'Silver', 'IT', 'Junior', '2023-08-10', NULL, 'EKG; Endoscopy; Ultrasound', NULL, '2023-11-12 02:37:03', 57),
(76, 32, NULL, NULL, 'Abigail', 'Adams', 'Johnson', NULL, 40, 'Female', 2, '021', 'Bronze', 'HR', 'Manager', '2023-09-15', NULL, 'CT Scan; Stress Test; Bone Density Test', NULL, '2023-11-12 02:37:03', 57),
(77, 33, NULL, NULL, 'Alexander', 'Hamilton', 'Miller', NULL, 32, 'Male', 2, '022', 'Gold', 'Finance', 'Senior', '2023-10-20', NULL, 'MRI Scan; Colonoscopy; Electroencephalogram (EEG)', NULL, '2023-11-12 02:37:03', 57),
(78, 34, NULL, NULL, 'Alice', 'Turner', 'Fisher', NULL, 28, 'Female', 2, '033', 'Gold', 'IT', 'Junior', '2023-11-25', NULL, 'Physical Examination; Blood Test; Echocardiogram', NULL, '2023-11-12 02:37:03', 57),
(79, 35, NULL, NULL, 'Grace', 'Harrison', 'King', NULL, 37, 'Male', 2, '034', 'Bronze', 'Marketing', 'Manager', '2023-12-30', NULL, 'Stress Test; Ultrasound; Genetic Testing', NULL, '2023-11-12 02:37:03', 57),
(80, 36, NULL, NULL, 'Oliver', 'Cooper', 'Evans', NULL, 36, 'Male', 2, '035', 'Silver', 'HR', 'Senior', '2023-01-04', NULL, 'CT Scan; Bone Density Test; DEXA Scan', NULL, '2023-11-12 02:37:03', 57),
(81, 37, NULL, NULL, 'Liam', 'Carter', 'Johnson', NULL, 30, 'Male', 2, '036', 'Bronze', 'IT', 'Junior', '2023-02-09', NULL, 'Endoscopy; Colonoscopy; Blood Pressure Check', NULL, '2023-11-12 02:37:03', 57),
(82, 38, NULL, NULL, 'Ava', 'Caroline', 'Turner', NULL, 28, 'Female', 2, '037', 'Silver', 'Finance', 'Manager', '2023-03-15', NULL, 'EKG; Genetic Testing; MRI Scan', NULL, '2023-11-12 02:37:03', 57),
(83, 39, NULL, NULL, 'Logan', 'Spencer', 'Fisher', NULL, 41, 'Male', 2, '038', 'Bronze', 'Marketing', 'Senior', '2023-04-20', NULL, 'DEXA Scan; Electroencephalogram (EEG); Stress Test', NULL, '2023-11-12 02:37:03', 57),
(84, 40, NULL, NULL, 'Mia', 'Harper', 'Jackson', NULL, 31, 'Female', 2, '039', 'Gold', 'IT', 'Junior', '2023-05-25', NULL, 'Physical Examination; Cholesterol Test; Ultrasound', NULL, '2023-11-12 02:37:03', 57),
(85, 41, NULL, NULL, 'Oliver', 'Cooper', 'Evans', NULL, 36, 'Male', 2, '040', 'Silver', 'HR', 'Senior', '2023-06-30', NULL, 'X-ray; Spirometry; Bone Density Test', NULL, '2023-11-12 02:37:03', 57),
(86, 42, NULL, NULL, 'Sophia', 'Claire', 'Martin', NULL, 27, 'Female', 2, '041', 'Gold', 'Marketing', 'Senior', '2023-07-05', NULL, 'CT Scan; MRI Scan; Blood Test', NULL, '2023-11-12 02:37:03', 57),
(87, 43, NULL, NULL, 'Liam', 'Carter', 'Johnson', NULL, 30, 'Male', 2, '042', 'Silver', 'IT', 'Junior', '2023-08-10', NULL, 'Colonoscopy; Echocardiogram; Endoscopy', NULL, '2023-11-12 02:37:03', 57),
(100, 44, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Male', 2, '1', NULL, NULL, NULL, '2023-11-28', NULL, NULL, 'test remarks', '2023-11-28 00:31:19', 57),
(101, 45, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Male', 2, '1', NULL, NULL, NULL, '2023-11-28', NULL, NULL, '1', '2023-11-28 04:24:18', 57),
(102, 46, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Male', 2, '', NULL, NULL, NULL, '2023-11-29', NULL, NULL, '', '2023-11-29 02:55:11', 57),
(103, 47, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Male', 2, '', NULL, NULL, NULL, '2023-11-29', NULL, NULL, '', '2023-11-29 02:55:30', 57),
(104, 48, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Male', 2, '', NULL, NULL, NULL, '2023-11-29', NULL, NULL, '', '2023-11-29 06:34:26', 57),
(105, 49, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Male', 2, '', NULL, NULL, NULL, '2023-11-29', NULL, NULL, '', '2023-11-29 06:34:44', 57),
(108, 50, NULL, NULL, 'Dhan Marlan', 'Amene', 'Ortiz', NULL, 1, 'Female', 2, '', NULL, NULL, NULL, '2023-11-29', NULL, NULL, '', '2023-11-29 08:58:07', 57);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor`
--

CREATE TABLE `Doctor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Doctor`
--

INSERT INTO `Doctor` (`id`, `name`, `type`) VALUES
(1, 'Ernie Caliboso, M.D. F.P.C.R.F.U.S.P', 'Radiologist');

-- --------------------------------------------------------

--
-- Table structure for table `LaboratoryResult`
--

CREATE TABLE `LaboratoryResult` (
  `labRes_ID` int(11) NOT NULL,
  `labRes_APE_FK` int(11) NOT NULL,
  `labRes_user_FK` int(11) NOT NULL,
  `labRes_date` date NOT NULL,
  `labRes_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `labRes_hepa_b` varchar(100) DEFAULT NULL,
  `labRes_drug_shabu` varchar(100) DEFAULT NULL,
  `labRes_drug_marijuana` varchar(100) DEFAULT NULL,
  `labRes_hema_hemoglobin` varchar(100) DEFAULT NULL,
  `labRes_hema_hematocrit` varchar(100) DEFAULT NULL,
  `labRes_hema_whiteblood` varchar(100) DEFAULT NULL,
  `labRes_hema_segmenters` varchar(100) DEFAULT NULL,
  `labRes_hema_lymphocytes` varchar(100) DEFAULT NULL,
  `labRes_hema_monocytes` varchar(100) DEFAULT NULL,
  `labRes_hema_eosinophils` varchar(100) DEFAULT NULL,
  `labRes_hema_basophils` varchar(100) DEFAULT NULL,
  `labRes_hema_stab` varchar(100) DEFAULT NULL,
  `labRes_urin_color` varchar(100) DEFAULT NULL,
  `labRes_urin_transparency` varchar(100) DEFAULT NULL,
  `labRes_urin_reaction` varchar(100) DEFAULT NULL,
  `labRes_urin_gravity` varchar(100) DEFAULT NULL,
  `labRes_urin_protein` varchar(100) DEFAULT NULL,
  `labRes_urin_glucose` varchar(100) DEFAULT NULL,
  `labRes_urin_wbc` varchar(100) DEFAULT NULL,
  `labRes_urin_rbc` varchar(100) DEFAULT NULL,
  `labRes_urin_mucous` varchar(100) DEFAULT NULL,
  `labRes_urin_epithelial` varchar(100) DEFAULT NULL,
  `labRes_urin_amorphous` varchar(100) DEFAULT NULL,
  `labRes_urin_bacteria` varchar(100) DEFAULT NULL,
  `labRes_urin_cast` varchar(100) DEFAULT NULL,
  `labRes_urin_crystals` varchar(100) DEFAULT NULL,
  `labRes_para_color` varchar(100) DEFAULT NULL,
  `labRes_para_consistency` varchar(100) DEFAULT NULL,
  `labRes_para_result` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `LaboratoryResult`
--

INSERT INTO `LaboratoryResult` (`labRes_ID`, `labRes_APE_FK`, `labRes_user_FK`, `labRes_date`, `labRes_timestamp`, `labRes_hepa_b`, `labRes_drug_shabu`, `labRes_drug_marijuana`, `labRes_hema_hemoglobin`, `labRes_hema_hematocrit`, `labRes_hema_whiteblood`, `labRes_hema_segmenters`, `labRes_hema_lymphocytes`, `labRes_hema_monocytes`, `labRes_hema_eosinophils`, `labRes_hema_basophils`, `labRes_hema_stab`, `labRes_urin_color`, `labRes_urin_transparency`, `labRes_urin_reaction`, `labRes_urin_gravity`, `labRes_urin_protein`, `labRes_urin_glucose`, `labRes_urin_wbc`, `labRes_urin_rbc`, `labRes_urin_mucous`, `labRes_urin_epithelial`, `labRes_urin_amorphous`, `labRes_urin_bacteria`, `labRes_urin_cast`, `labRes_urin_crystals`, `labRes_para_color`, `labRes_para_consistency`, `labRes_para_result`) VALUES
(6, 1, 57, '2023-12-08', '2023-12-07 17:24:36', '&quot;NON-REACTIVE&quot;', 'NEGATIVE1', 'NEGATIVE2', '15.1', '42%', '6,500', '53%', '40%', '7%', '0', '00', '000', 'PALE YELLOW', 'CLEAR', '6.0', '1.025', 'NEGATIVE1', 'NEGATIVE2', '0-2/hpf', '1-2/hpf', 'OCCASIONAL', '0', '00', '000', 'NO CAST SEEN.', '0000', 'YELLOWISH', 'SOFT', 'NO INTESTINAL PARASITE SEEN');

-- --------------------------------------------------------

--
-- Table structure for table `LaboratoryTest`
--

CREATE TABLE `LaboratoryTest` (
  `labTest_ID` int(11) NOT NULL,
  `labTestGroup` varchar(100) NOT NULL,
  `labTestCode` varchar(100) NOT NULL,
  `labTestName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `LaboratoryTest`
--

INSERT INTO `LaboratoryTest` (`labTest_ID`, `labTestGroup`, `labTestCode`, `labTestName`) VALUES
(1, 'Hepatitis', 'hepa-b', 'Hepatitis B Screening'),
(2, 'Drug Test', 'drug-shabu', 'Methamphetamine (Shabu)'),
(3, 'Drug Test', 'drug-marijuana', 'Tetrahydrocannabinol (Marijuana)'),
(4, 'Hematology', 'hema-hemoglobin', 'Hemoglobin'),
(5, 'Hematology', 'hema-hematocrit', 'Hematocrit'),
(6, 'Hematology', 'hema-whiteblood', 'White Blood Cell'),
(7, 'Hematology', 'hema-segmenters', 'Segmenters'),
(8, 'Hematology', 'hema-lymphocytes', 'Lymphocytes'),
(9, 'Hematology', 'hema-monocytes', 'Monocytes'),
(10, 'Hematology', 'hema-eosinophils', 'Eosinophils'),
(11, 'Hematology', 'hema-basophils', 'Basophils'),
(12, 'Hematology', 'hema-stab', 'Stab'),
(13, 'Urinalysis', 'urin-color', 'Color'),
(14, 'Urinalysis', 'urin-transparency', 'Transparency'),
(15, 'Urinalysis', 'urin-reaction', 'Reaction'),
(16, 'Urinalysis', 'urin-gravity', 'Specific Gravity'),
(17, 'Urinalysis', 'urin-protein', 'Protein'),
(18, 'Urinalysis', 'urin-glucose', 'Glucose'),
(19, 'Urinalysis', 'urin-wbc', 'Wbc'),
(20, 'Urinalysis', 'urin-rbc', 'Rbc'),
(21, 'Urinalysis', 'urin-mucous', 'Mucous Threads'),
(22, 'Urinalysis', 'urin-epithelial', 'Epithelial Cells'),
(23, 'Urinalysis', 'urin-amorphous', 'Amorphous Urates'),
(24, 'Urinalysis', 'urin-bacteria', 'Bacteria'),
(25, 'Urinalysis', 'urin-cast', 'Cast'),
(26, 'Urinalysis', 'urin-crystals', 'Crystals'),
(27, 'Parasitology', 'para-color', 'Color'),
(28, 'Parasitology', 'para-consistency', 'Consistency'),
(29, 'Parasitology', 'para-result', 'Result');

-- --------------------------------------------------------

--
-- Table structure for table `MedicalExamination`
--

CREATE TABLE `MedicalExamination` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `MedicalExamination`
--

INSERT INTO `MedicalExamination` (`id`, `name`, `description`) VALUES
(1, 'Blood Pressure Measurement', 'Checks for hypertension or hypotension.'),
(2, 'Complete Blood Count (CBC)', 'Measures different blood components, including red and white blood cells.'),
(3, 'Blood Glucose Test', 'Evaluates blood sugar levels, important for diabetes diagnosis and management.'),
(4, 'Lipid Profile', 'Assesses cholesterol levels and other lipids in the blood.'),
(5, 'Electrocardiogram (ECG or EKG)', 'Records the electrical activity of the heart.'),
(6, 'Chest X-ray', 'Provides images of the chest, including the heart and lungs.'),
(7, 'MRI (Magnetic Resonance Imaging)', 'Uses magnetic fields and radio waves to create detailed images of internal structures.'),
(8, 'CT Scan (Computed Tomography)', 'Combines X-rays to produce cross-sectional images of the body.'),
(9, 'Ultrasound', 'Uses sound waves to create images of organs and structures within the body.'),
(10, 'Colonoscopy', 'Examines the colon and rectum for abnormalities, often used for colorectal cancer screening.'),
(11, 'Mammogram', 'X-ray of the breast to detect and diagnose breast diseases, including cancer.'),
(12, 'Pap Smear', 'Screens for cervical cancer by collecting cells from the cervix.'),
(13, 'Prostate-Specific Antigen (PSA) Test', 'Measures PSA levels in the blood, often used for prostate cancer screening.'),
(14, 'Bone Density Test (DEXA or DXA)', 'Measures bone mineral density to assess the risk of osteoporosis.'),
(15, 'Thyroid Function Tests', 'Evaluate the thyroid gland\'s function by measuring hormone levels.'),
(16, 'Spirometry', 'Assesses lung function by measuring the amount and speed of air that can be inhaled and exhaled.'),
(17, 'Liver Function Tests', 'Measure various enzymes and proteins in the blood to assess liver health.'),
(18, 'Kidney Function Tests', 'Evaluate kidney function by measuring levels of substances in the blood and urine.'),
(19, 'Hemoglobin A1c Test', 'Monitors long-term glucose control in individuals with diabetes.'),
(20, 'Skin Biopsy', 'Involves removing a sample of skin tissue for examination under a microscope.'),
(21, 'Stool Tests', 'Detects abnormalities, parasites, or blood in the stool.'),
(22, 'Allergy Testing', 'Identifies allergens that may trigger allergic reactions.'),
(23, 'Genetic Testing', 'Examines an individual\'s DNA for variations associated with genetic disorders.'),
(24, 'Echocardiogram', 'Uses ultrasound to create images of the heart and assess its function.'),
(25, 'Throat Culture', 'Collects a sample from the throat to identify bacterial infections.');

-- --------------------------------------------------------

--
-- Table structure for table `Organization`
--

CREATE TABLE `Organization` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Organization`
--

INSERT INTO `Organization` (`id`, `name`, `email`, `phone`, `address`) VALUES
(1, 'Global Health Diagnostics', 'info@globalhealth-diagnostics.com', '09989928677', '132 Kalayaan Ave, Central Diliman, Quezon City'),
(2, 'iAcademy', 'inquire@iacademy.edu.ph', '0288895555', 'Filinvest Cebu Cyberzone Tower 2, Lahug, Cebu City'),
(91, 'Jollibee Foods Corporation', 'email@jfc.com', '0000000', 'Jollibee Plaza, Ortigas Center, Pasig, Philippines'),
(170, 'Anoc88', 'anoc88@gmail.com', '09985519034', 'Cavite'),
(171, 'STAFF SEARCH ASIA SERVICE COOP', 'globalhealth28@yahoo.com', '09989928677', 'muntinlupa');

-- --------------------------------------------------------

--
-- Table structure for table `RadiologyReport`
--

CREATE TABLE `RadiologyReport` (
  `id` int(11) NOT NULL,
  `caseNumber` int(11) NOT NULL,
  `dateCreated` date NOT NULL,
  `APEFK` int(11) NOT NULL,
  `organizationFK` int(11) NOT NULL,
  `MedicalExamination_FK` int(11) DEFAULT NULL,
  `chestPA` varchar(255) NOT NULL,
  `impression` varchar(255) NOT NULL,
  `doctorFK` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `userFK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `RadiologyReport`
--

INSERT INTO `RadiologyReport` (`id`, `caseNumber`, `dateCreated`, `APEFK`, `organizationFK`, `MedicalExamination_FK`, `chestPA`, `impression`, `doctorFK`, `timestamp`, `userFK`) VALUES
(15, 123, '2023-12-04', 1, 2, 6, 'Both lung fields are clear.\r\nHeart is not enlarged.\r\nThe rest of visualized structures are unremarkable.', 'ESSENTIALLY NORMAL CHEST', 1, '2023-12-04 11:20:49', 57),
(16, 1, '2023-12-06', 60, 2, 6, 'Both lung fields are clear.\r\nHeart is not enlarged.\r\nThe rest of visualized structures are unremarkable.', 'ESSENTIALLY NORMAL CHEST test 60', 1, '2023-12-06 06:49:47', 57);

-- --------------------------------------------------------

--
-- Table structure for table `ResultsAPE`
--

CREATE TABLE `ResultsAPE` (
  `id` int(11) NOT NULL,
  `medicalExaminationFK` int(11) NOT NULL,
  `APEFK` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `userFK` int(11) NOT NULL,
  `fileName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ResultsAPE`
--

INSERT INTO `ResultsAPE` (`id`, `medicalExaminationFK`, `APEFK`, `timestamp`, `userFK`, `fileName`) VALUES
(48, 1, 1, '2023-12-04 07:46:25', 57, '3983289293633968094708014445513710240284765n_1701675985.png');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `isActive` int(11) NOT NULL DEFAULT 1,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashedPassword` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizationId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `email`, `username`, `role`, `isActive`, `password`, `hashedPassword`, `organizationId`) VALUES
(7, 'Sincere@april.biz', 'sincere4pril', 2, 1, '12345', '$2b$10$nGjmho1nX26DjHB7zqNt3uMTVQprly9VlwH2uaPL7g6nHWMFj621C', 2),
(8, 'Shanna@melissa.tv', 'Shanna', 2, 1, '12345', '$2b$10$coiChLMtv5tm9yQi8766JebqgB9Fhjc9YlEmmCrf0d6tEMyL1OuNm', 2),
(11, 'Nathan@yesenia.net', 'Samantha', 2, 1, '12345', '$2b$10$KoJrHafLXVG6UsW9b6prz.0qOTNIBXsHYohLTJgyvXep6wn3EExoS', 2),
(12, 'Julianne.OConner@kory.org', 'Karianne', 1, 1, '12345', '$2b$10$Ua663Nax1YQfw3ViBzEB1OH5YmGkLpNGVRs3JjQIPiiQvpkrLp4dy', 91),
(15, 'Lucio_Hettinger@annie.ca', 'Kamren', 1, 1, '12345', '$2b$10$XBR4p0GPH.CuvaTQz96gvexWrfZZ6CguRY.DuFOsUd.xR1calDOFy', 2),
(16, 'Karley_Dach@jasper.info', 'Leopoldo_Corkery', 1, 1, '12345', '$2b$10$4/9ONoascBR1u6KpYhgDk.eX4O8oo5hMlrXZ5SZxnxMwZljN40qVm', 1),
(17, 'Telly.Hoeger@billy.biz', 'Elwyn.Skiles', 1, 1, '12345', '$2b$10$5QZqXK71BePDbFd5ugCUjehgi2ihtToCX0y7TeU92aN9HRPiO7iZ.', 1),
(18, 'Sherwood@rosamond.me', 'Maxime_Nienow', 1, 1, '12345', '$2b$10$0LcwwARtuXRQWSHxDMJij.L0oAUVkdazCFs18te2aD.W1R/aBzi96', 1),
(19, 'Chaim_McDermott@dana.io', 'GlennaReichert12', 2, 1, '12345', '$2b$10$AdCrHYCAsSjIszuxWBanVOA//WAC6CAI6SWHrt0sQB/Ah9TAyjwU.', 2),
(20, 'Rey.Padberg@karina.biz', 'Moriah.Stanton', 1, 1, '12345', '$2b$10$hnGY7/09gul8rjNlgmKJy.ZjZ7LIyzDVDqDzgR5FJozrCPQlPSyBe', 1),
(57, 'admin@test.com', 'admin', 1, 1, 'admin', NULL, 1),
(58, 'client@test.com', 'client', 2, 1, 'client', NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `APE`
--
ALTER TABLE `APE`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `organizationId` (`organizationId`);

--
-- Indexes for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaboratoryResult`
--
ALTER TABLE `LaboratoryResult`
  ADD PRIMARY KEY (`labRes_ID`),
  ADD UNIQUE KEY `labRes_APE_FK` (`labRes_APE_FK`),
  ADD KEY `APEFK` (`labRes_APE_FK`),
  ADD KEY `userFK` (`labRes_user_FK`);

--
-- Indexes for table `LaboratoryTest`
--
ALTER TABLE `LaboratoryTest`
  ADD PRIMARY KEY (`labTest_ID`),
  ADD UNIQUE KEY `testCode` (`labTestCode`);

--
-- Indexes for table `MedicalExamination`
--
ALTER TABLE `MedicalExamination`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `Organization`
--
ALTER TABLE `Organization`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `RadiologyReport`
--
ALTER TABLE `RadiologyReport`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `APEFK_2` (`APEFK`),
  ADD KEY `userFK` (`userFK`),
  ADD KEY `doctorFK` (`doctorFK`),
  ADD KEY `MedicalExamination_FK` (`MedicalExamination_FK`),
  ADD KEY `organizationFK` (`organizationFK`),
  ADD KEY `APEFK` (`APEFK`);

--
-- Indexes for table `ResultsAPE`
--
ALTER TABLE `ResultsAPE`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MedicalExaminationFK` (`medicalExaminationFK`),
  ADD KEY `APEFK` (`APEFK`),
  ADD KEY `UserFK` (`userFK`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `organizationId` (`organizationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `APE`
--
ALTER TABLE `APE`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `Doctor`
--
ALTER TABLE `Doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `LaboratoryResult`
--
ALTER TABLE `LaboratoryResult`
  MODIFY `labRes_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `LaboratoryTest`
--
ALTER TABLE `LaboratoryTest`
  MODIFY `labTest_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `MedicalExamination`
--
ALTER TABLE `MedicalExamination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `Organization`
--
ALTER TABLE `Organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `RadiologyReport`
--
ALTER TABLE `RadiologyReport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ResultsAPE`
--
ALTER TABLE `ResultsAPE`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `APE`
--
ALTER TABLE `APE`
  ADD CONSTRAINT `ape_ibfk_1` FOREIGN KEY (`organizationId`) REFERENCES `Organization` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ape_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `LaboratoryResult`
--
ALTER TABLE `LaboratoryResult`
  ADD CONSTRAINT `laboratoryresult_ibfk_1` FOREIGN KEY (`labRes_APE_FK`) REFERENCES `APE` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `laboratoryresult_ibfk_3` FOREIGN KEY (`labRes_user_FK`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `RadiologyReport`
--
ALTER TABLE `RadiologyReport`
  ADD CONSTRAINT `radiologyreport_ibfk_1` FOREIGN KEY (`APEFK`) REFERENCES `APE` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `radiologyreport_ibfk_2` FOREIGN KEY (`doctorFK`) REFERENCES `Doctor` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `radiologyreport_ibfk_3` FOREIGN KEY (`MedicalExamination_FK`) REFERENCES `MedicalExamination` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `radiologyreport_ibfk_4` FOREIGN KEY (`organizationFK`) REFERENCES `Organization` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `radiologyreport_ibfk_5` FOREIGN KEY (`userFK`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ResultsAPE`
--
ALTER TABLE `ResultsAPE`
  ADD CONSTRAINT `resultsape_ibfk_1` FOREIGN KEY (`APEFK`) REFERENCES `APE` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `resultsape_ibfk_2` FOREIGN KEY (`medicalExaminationFK`) REFERENCES `MedicalExamination` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `resultsape_ibfk_3` FOREIGN KEY (`userFK`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`organizationId`) REFERENCES `Organization` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
