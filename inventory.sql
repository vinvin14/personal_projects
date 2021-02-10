-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2021 at 10:46 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `responsible_person` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `logAttemptsMax` int(11) NOT NULL,
  `logCurAttempt` int(11) NOT NULL,
  `lastLog` datetime NOT NULL,
  `token` varchar(45) DEFAULT NULL,
  `account_permission` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `responsible_person`, `role`, `logAttemptsMax`, `logCurAttempt`, `lastLog`, `token`, `account_permission`) VALUES
(1, 'admin', '$2y$10$B.5AU.wS4DRK7WzQP2sbFOyNPP3wxR04Wx/ua222whIeu4TC8F/ia', '', 'superadmin', 5, 0, '0000-00-00 00:00:00', 'el!zW2-#@1kq9xa', 1),
(2, 'vinvin14', '$2y$10$B.5AU.wS4DRK7WzQP2sbFOyNPP3wxR04Wx/ua222whIeu4TC8F/ia', 'Valdez, Kelvin M.', 'encoder', 0, 0, '0000-00-00 00:00:00', '9le@a2z-1!xk#qW', 1),
(4, 'vin142', '$2y$10$06wclbVD.kAiebnxBZ15nuHYuzo1/S1Z2m7Pl4rCVvoGThVWbmNmm', 'Valdez, Kelvin M.', 'admin', 0, 0, '0000-00-00 00:00:00', '!1kWez@l#xq-a92', 1);

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `actions` longtext NOT NULL,
  `date_of_action` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `module` varchar(50) NOT NULL,
  `ip_address` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user`, `actions`, `date_of_action`, `module`, `ip_address`) VALUES
(1, 'admin', 'Updated test', '2021-02-09 08:56:48', 'BOARD_UPDATE', '127.0.0.1'),
(2, 'admin', 'Updated to Description: test | RMA: RMA-101', '2021-02-09 09:08:58', 'BOARD_UPDATE', '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` int(11) NOT NULL,
  `rma` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `serialNumber` varchar(50) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `storedIn` int(11) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `faultCode` int(11) DEFAULT NULL,
  `faultDetails` varchar(100) DEFAULT NULL,
  `dateReceived` date DEFAULT NULL,
  `startOfRepair` date DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `fe` int(11) DEFAULT NULL,
  `receivedBy` varchar(50) DEFAULT NULL,
  `entryBy` varchar(50) DEFAULT NULL,
  `repPartNum` varchar(100) DEFAULT NULL,
  `motherRecord` int(11) DEFAULT NULL,
  `slot` varchar(50) DEFAULT NULL,
  `typeOfService` varchar(50) DEFAULT NULL,
  `systemType` varchar(50) DEFAULT NULL,
  `EWSFindings` longtext DEFAULT NULL,
  `findings` longtext DEFAULT NULL,
  `causeOfFC` varchar(100) DEFAULT NULL,
  `causeOfFCDetails` mediumtext DEFAULT NULL,
  `partUsage` varchar(50) DEFAULT NULL,
  `turnAroundTime` varchar(50) DEFAULT NULL,
  `benchTestFindings` longtext DEFAULT NULL,
  `workPerformed` longtext DEFAULT NULL,
  `endOfRepair` date DEFAULT NULL,
  `upgradeTime` varchar(50) DEFAULT NULL,
  `testTime` varchar(50) DEFAULT NULL,
  `repairTime` varchar(50) DEFAULT NULL,
  `replacementPart` longtext DEFAULT NULL,
  `replacementPartDesc` longtext DEFAULT NULL,
  `reasonForReturn` varchar(100) DEFAULT NULL,
  `operatingSystem` varchar(100) DEFAULT NULL,
  `jobStatus` varchar(50) DEFAULT NULL,
  `softDeleted` enum('yes','no','','') DEFAULT 'no',
  `incomingTrackingNumber` varchar(50) DEFAULT NULL,
  `outgoingTrackingNumber` varchar(50) DEFAULT NULL,
  `shipToCustomerName` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `dateShipped` date DEFAULT NULL,
  `contactPerson` varchar(50) DEFAULT NULL,
  `contactNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `rma`, `description`, `serialNumber`, `partNumber`, `storedIn`, `location`, `faultCode`, `faultDetails`, `dateReceived`, `startOfRepair`, `remarks`, `status`, `fe`, `receivedBy`, `entryBy`, `repPartNum`, `motherRecord`, `slot`, `typeOfService`, `systemType`, `EWSFindings`, `findings`, `causeOfFC`, `causeOfFCDetails`, `partUsage`, `turnAroundTime`, `benchTestFindings`, `workPerformed`, `endOfRepair`, `upgradeTime`, `testTime`, `repairTime`, `replacementPart`, `replacementPartDesc`, `reasonForReturn`, `operatingSystem`, `jobStatus`, `softDeleted`, `incomingTrackingNumber`, `outgoingTrackingNumber`, `shipToCustomerName`, `address`, `dateShipped`, `contactPerson`, `contactNumber`) VALUES
(30, 'RMA-101', 'test', 'SN032232232', 'PN-0344', NULL, NULL, NULL, NULL, '2021-02-04', NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'test1', 'test1', NULL, NULL, NULL, NULL, 'trial1', NULL, '2021-02-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'no', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `boardtypes`
--

CREATE TABLE `boardtypes` (
  `id` int(11) NOT NULL,
  `boardType` varchar(100) NOT NULL,
  `description` longtext DEFAULT NULL,
  `dateAdded` date DEFAULT NULL,
  `entryBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE `components` (
  `id` int(11) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `referenceDesignator` varchar(50) DEFAULT NULL,
  `systemType` int(11) DEFAULT NULL,
  `boardType` int(11) DEFAULT NULL,
  `vendor` varchar(50) DEFAULT NULL,
  `storedIn` int(11) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `actualQuantity` int(11) NOT NULL DEFAULT 0,
  `dateReceived` date NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `entryBy` varchar(50) NOT NULL,
  `maximumQuantity` int(11) NOT NULL DEFAULT 0,
  `criticalTagging` varchar(50) DEFAULT NULL,
  `minimumQuantity` int(11) NOT NULL DEFAULT 0,
  `unitPrice` double NOT NULL,
  `totalPrice` double NOT NULL,
  `rma` varchar(100) DEFAULT NULL,
  `has_item_movement` enum('yes','no','','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `components`
--

INSERT INTO `components` (`id`, `partNumber`, `referenceDesignator`, `systemType`, `boardType`, `vendor`, `storedIn`, `location`, `unit`, `description`, `actualQuantity`, `dateReceived`, `remarks`, `dateAdded`, `entryBy`, `maximumQuantity`, `criticalTagging`, `minimumQuantity`, `unitPrice`, `totalPrice`, `rma`, `has_item_movement`) VALUES
(12, 'PN-03443', NULL, NULL, NULL, 'Laser Marketing', 11, 'drawer#1', 9, 'Transistor 34412', 10, '2021-02-04', NULL, '2021-02-04 12:22:12', 'admin', 58, NULL, 5, 1, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `consignedspares`
--

CREATE TABLE `consignedspares` (
  `id` int(11) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `serialNumber` varchar(50) DEFAULT NULL,
  `description` longtext NOT NULL,
  `systemType` int(11) DEFAULT NULL,
  `boardType` int(11) DEFAULT NULL,
  `storedIn` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `dateAdded` date NOT NULL,
  `actualQuantity` int(11) NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `unit` int(11) NOT NULL,
  `dateReceived` date NOT NULL,
  `entryBy` varchar(50) DEFAULT NULL,
  `maximumQuantity` int(11) NOT NULL,
  `criticalTagging` int(11) DEFAULT NULL,
  `minimumQuantity` int(11) NOT NULL,
  `unitPrice` double NOT NULL,
  `depreciationValue` double DEFAULT NULL,
  `usefulLife` int(11) DEFAULT NULL,
  `totalPrice` double NOT NULL,
  `vendor` varchar(50) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `is_out` int(11) NOT NULL DEFAULT 0,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consignedspares`
--

INSERT INTO `consignedspares` (`id`, `partNumber`, `serialNumber`, `description`, `systemType`, `boardType`, `storedIn`, `location`, `dateAdded`, `actualQuantity`, `remarks`, `unit`, `dateReceived`, `entryBy`, `maximumQuantity`, `criticalTagging`, `minimumQuantity`, `unitPrice`, `depreciationValue`, `usefulLife`, `totalPrice`, `vendor`, `invoice_number`, `is_out`, `deleted_at`) VALUES
(21, 'PN-0344', 'SN032232232', 'Transistor 344', 1, NULL, 11, 'drawer#1', '2021-02-04', 1, NULL, 9, '2021-02-04', 'admin', 0, NULL, 0, 502, 5, 5, 502, 'Laser Marketing', '', 0, NULL),
(22, 'PN-03442', 'SN032232232', 'Transistor 3444', 1, NULL, 11, 'drawer#1', '2021-02-04', 1, NULL, 9, '2021-02-04', 'admin', 0, NULL, 0, 1, NULL, 10, 1, 'Laser Marketing', '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `consumables`
--

CREATE TABLE `consumables` (
  `id` int(11) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `actualQuantity` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `storedIn` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `description` longtext DEFAULT NULL,
  `criticalLevel` varchar(45) DEFAULT NULL,
  `entryBy` varchar(50) NOT NULL,
  `dateAdded` date NOT NULL,
  `maximumQuantity` int(11) DEFAULT NULL,
  `criticalTagging` varchar(50) DEFAULT NULL,
  `minimumQuantity` int(11) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `unitPrice` double NOT NULL,
  `totalPrice` double NOT NULL,
  `hasItemMovement` enum('yes','no','','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consumables`
--

INSERT INTO `consumables` (`id`, `partNumber`, `actualQuantity`, `unit`, `storedIn`, `location`, `description`, `criticalLevel`, `entryBy`, `dateAdded`, `maximumQuantity`, `criticalTagging`, `minimumQuantity`, `remarks`, `unitPrice`, `totalPrice`, `hasItemMovement`) VALUES
(38, 'TEST', 2, 9, 11, 'drawer#1', 'Test', NULL, 'admin', '2021-02-03', 10, 'critical', 2, NULL, 12, 24, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `criticaltagging`
--

CREATE TABLE `criticaltagging` (
  `id` int(11) NOT NULL,
  `tagging` varchar(50) NOT NULL,
  `indicator` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `criticaltagging`
--

INSERT INTO `criticaltagging` (`id`, `tagging`, `indicator`) VALUES
(1, 'Excellent State', 0),
(2, 'Very Good State', 0),
(3, 'Good State', 0),
(4, 'Average State', 0),
(5, 'Below Average State', 0),
(6, 'Critical State', 0),
(7, 'Near Depletion State', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customerID` varchar(50) NOT NULL,
  `name` varchar(45) NOT NULL,
  `totalTransactions` int(11) DEFAULT NULL,
  `address` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customerID`, `name`, `totalTransactions`, `address`) VALUES
(1, 'JB', 'Jollibee', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emailtonotify`
--

CREATE TABLE `emailtonotify` (
  `id` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `owner` varchar(45) NOT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emailtonotify`
--

INSERT INTO `emailtonotify` (`id`, `email`, `owner`, `added`) VALUES
(1, 'mr.kelvinvaldez14@gmail.com', 'admin', '2021-02-03 14:08:38');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `serialNumber` varchar(100) DEFAULT NULL,
  `modelNumber` varchar(50) DEFAULT NULL,
  `vendor` varchar(50) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `description` mediumtext NOT NULL,
  `actualQuantity` int(11) NOT NULL,
  `dateReceived` date NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `storedIn` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `unit` int(11) NOT NULL,
  `dateAdded` date NOT NULL,
  `entryBy` varchar(50) DEFAULT NULL,
  `criticalTagging` int(11) DEFAULT NULL,
  `depreciationValue` int(11) DEFAULT NULL,
  `totalPrice` int(11) NOT NULL,
  `unitPrice` int(11) NOT NULL,
  `usefulLife` int(11) DEFAULT NULL,
  `is_out` int(11) DEFAULT 0,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `partNumber`, `serialNumber`, `modelNumber`, `vendor`, `invoice_number`, `brand`, `description`, `actualQuantity`, `dateReceived`, `remarks`, `storedIn`, `location`, `unit`, `dateAdded`, `entryBy`, `criticalTagging`, `depreciationValue`, `totalPrice`, `unitPrice`, `usefulLife`, `is_out`, `deleted_at`) VALUES
(15, 'PN-0344', 'SN032232232', 'model-02', 'Samsung', NULL, 'SAMSUNG', 'Washing machine', 1, '2021-02-04', NULL, 11, 'drawer#1', 9, '2021-02-04', 'admin', NULL, 1200, 5000, 5000, 10, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `errors`
--

CREATE TABLE `errors` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `details` longtext NOT NULL,
  `errorOccured` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `errors`
--

INSERT INTO `errors` (`id`, `user`, `module`, `details`, `errorOccured`) VALUES
(24, 'New User', 'REGISTRATION', 'Route [signup] not defined.', '2021-02-08 11:01:23'),
(25, 'New User', 'REGISTRATION', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'\' for key \'token_UNIQUE\' (SQL: insert into `accounts` (`responsible_person`, `username`, `password`) values (Valdez, Kelvin M., vinvin142, $2y$10$h/UgqHQ.dSX8aklux4JwcO.Gw2H1K3rDufoyVkCcFjKPUS.N3XYwS))', '2021-02-08 13:14:05'),
(26, 'admin', 'BOARDUPDATE', 'Call to undefined method App\\Models\\Board::changes()', '2021-02-09 09:25:30'),
(27, 'admin', 'BOARDUPDATE', 'array_push() expects parameter 1 to be array, string given', '2021-02-09 09:29:32'),
(28, 'admin', 'BOARDUPDATE', 'array_push() expects parameter 1 to be array, string given', '2021-02-09 09:30:58'),
(29, 'admin', 'BOARDUPDATE', 'array_push() expects parameter 1 to be array, string given', '2021-02-09 09:33:48'),
(30, 'admin', 'BOARDUPDATE', 'array_push() expects parameter 1 to be array, string given', '2021-02-09 09:34:52'),
(31, 'admin', 'BOARDUPDATE', 'array_push() expects parameter 1 to be array, string given', '2021-02-09 09:35:06'),
(32, 'admin', 'BOARDUPDATE', 'Undefined variable: changes', '2021-02-09 09:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `faultcodes`
--

CREATE TABLE `faultcodes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `dateAdded` datetime DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location` varchar(45) NOT NULL,
  `description` longtext DEFAULT NULL,
  `dateAdded` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `description`, `dateAdded`) VALUES
(11, 'test', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movement_components`
--

CREATE TABLE `movement_components` (
  `id` int(11) NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `purpose` int(11) DEFAULT NULL,
  `rma` int(11) DEFAULT NULL,
  `reference_designator` varchar(50) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `vendor` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `reference` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_received_released` date NOT NULL,
  `received_released_by` varchar(50) NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `movement_components`
--

INSERT INTO `movement_components` (`id`, `type`, `purpose`, `rma`, `reference_designator`, `invoice_number`, `vendor`, `brand`, `reference`, `quantity`, `date_received_released`, `received_released_by`, `remarks`, `deleted_at`) VALUES
(13, 'outgoing', 1, NULL, NULL, NULL, NULL, NULL, 12, 5, '2021-02-04', 'kelvin valdez', NULL, '2021-02-04');

-- --------------------------------------------------------

--
-- Table structure for table `movement_component_tracker`
--

CREATE TABLE `movement_component_tracker` (
  `id` int(11) NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `item_movement` int(11) NOT NULL,
  `component` int(11) NOT NULL,
  `previous_quantity` int(11) DEFAULT NULL,
  `new_quantity` int(11) DEFAULT NULL,
  `previous_unit_price` double DEFAULT NULL,
  `new_unit_price` double DEFAULT NULL,
  `date_of_transaction` date NOT NULL,
  `entry_by` varchar(50) NOT NULL,
  `status` enum('active','inactive','updated','reverted') DEFAULT NULL,
  `rma` int(11) DEFAULT NULL,
  `reference_designator` varchar(50) DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `movement_component_tracker`
--

INSERT INTO `movement_component_tracker` (`id`, `type`, `item_movement`, `component`, `previous_quantity`, `new_quantity`, `previous_unit_price`, `new_unit_price`, `date_of_transaction`, `entry_by`, `status`, `rma`, `reference_designator`, `deleted_at`) VALUES
(1, 'outgoing', 13, 12, 10, 5, 1, 1, '2021-02-04', 'admin', 'reverted', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movement_consigned`
--

CREATE TABLE `movement_consigned` (
  `id` int(11) NOT NULL,
  `purpose` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `reference` int(11) NOT NULL,
  `date_received_released` date NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `received_released_by` varchar(50) NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movement_consumables`
--

CREATE TABLE `movement_consumables` (
  `id` int(11) NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `purpose` int(11) DEFAULT NULL,
  `rma` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `vendor` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `reference` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_received_released` date NOT NULL,
  `year` varchar(50) NOT NULL,
  `month` varchar(50) NOT NULL,
  `received_released_by` varchar(50) NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movement_consumable_tracker`
--

CREATE TABLE `movement_consumable_tracker` (
  `id` int(11) NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `item_movement` int(11) NOT NULL,
  `consumable` int(11) NOT NULL,
  `previous_quantity` int(11) NOT NULL,
  `new_quantity` int(11) NOT NULL,
  `previous_unit_price` double NOT NULL,
  `new_unit_price` double NOT NULL,
  `date_of_transaction` date NOT NULL,
  `entry_by` varchar(50) NOT NULL,
  `status` enum('reverted','active','inactive','updated') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movement_equipment`
--

CREATE TABLE `movement_equipment` (
  `id` int(11) NOT NULL,
  `purpose` int(11) DEFAULT NULL,
  `reference` int(11) NOT NULL,
  `date_received` date NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `received_by` varchar(50) NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movement_tool`
--

CREATE TABLE `movement_tool` (
  `id` int(11) NOT NULL,
  `purpose` int(11) DEFAULT NULL,
  `reference` int(11) NOT NULL,
  `date_received_released` date NOT NULL,
  `type` enum('incoming','outgoing','','') NOT NULL,
  `received_released_by` varchar(50) NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `details` longtext NOT NULL,
  `seen` enum('yes','no','','') NOT NULL DEFAULT 'no',
  `status` int(11) NOT NULL DEFAULT 0,
  `type` enum('notification','alert','message','') NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `origin` varchar(50) NOT NULL,
  `user` varchar(50) NOT NULL,
  `link` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `item`, `details`, `seen`, `status`, `type`, `created`, `origin`, `user`, `link`) VALUES
(1, 38, 'TEST | Test has reached critical stage', 'no', 1, 'notification', '2021-02-03 14:09:02', 'consumables', 'admin', '/resources/consumable/38'),
(2, 38, 'TEST | Test has reached critical stage', 'no', 0, 'notification', '2021-02-03 16:00:00', 'consumables', 'admin', '/resources/consumable/38');

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `id` int(11) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `middlename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `whereAbouts` varchar(45) DEFAULT NULL,
  `officerStatus` int(11) DEFAULT 1,
  `startOfService` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `officerstatus`
--

CREATE TABLE `officerstatus` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `officerstatus`
--

INSERT INTO `officerstatus` (`id`, `status`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'On Leave'),
(4, 'Suspended'),
(5, 'Resigned'),
(6, 'Retired'),
(7, 'Terminated');

-- --------------------------------------------------------

--
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `id` int(11) NOT NULL,
  `purpose` varchar(45) NOT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purposes`
--

INSERT INTO `purposes` (`id`, `purpose`, `description`) VALUES
(1, 'To Return', NULL),
(2, 'Defective', ''),
(4, 'For Replacement', ''),
(5, 'RMA', ''),
(6, 'Expired', '');

-- --------------------------------------------------------

--
-- Table structure for table `rack`
--

CREATE TABLE `rack` (
  `id` int(11) NOT NULL,
  `rack` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rack`
--

INSERT INTO `rack` (`id`, `rack`) VALUES
(1, 'rack 1'),
(2, 'rack 2'),
(3, 'rack 3'),
(4, 'rack 4'),
(5, 'rack 5');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `tagging` varchar(50) NOT NULL,
  `origin` varchar(50) NOT NULL,
  `details` longtext NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `repairrecords`
--

CREATE TABLE `repairrecords` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `batch` varchar(100) DEFAULT NULL,
  `customer` int(11) NOT NULL,
  `totalJob` int(11) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `totalRepaired` int(11) DEFAULT NULL,
  `boardsForRepair` int(11) DEFAULT NULL,
  `transactionDate` date NOT NULL,
  `receivedBy` varchar(45) NOT NULL,
  `totalDefective` int(11) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `typeOfService` int(11) DEFAULT NULL,
  `status` enum('Active','Pending','Inactive','Declined','Completed') NOT NULL,
  `record` enum('Active','Inactive','','') DEFAULT 'Active',
  `shipToCustomerName` varchar(100) DEFAULT NULL,
  `shipDate` date DEFAULT NULL,
  `incomingTracking` varchar(50) DEFAULT NULL,
  `outgoingTracking` varchar(50) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `contactPerson` varchar(100) DEFAULT NULL,
  `contactNumber` varchar(50) DEFAULT NULL,
  `softDeleted` enum('yes','no','','') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `repairrecords`
--

INSERT INTO `repairrecords` (`id`, `description`, `batch`, `customer`, `totalJob`, `unit`, `totalRepaired`, `boardsForRepair`, `transactionDate`, `receivedBy`, `totalDefective`, `remarks`, `typeOfService`, `status`, `record`, `shipToCustomerName`, `shipDate`, `incomingTracking`, `outgoingTracking`, `address`, `contactPerson`, `contactNumber`, `softDeleted`) VALUES
(1, 'Transistor #344', NULL, 1, 1, NULL, 0, 1, '0000-00-00', '', 0, NULL, NULL, 'Active', 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `replacementparts`
--

CREATE TABLE `replacementparts` (
  `id` int(11) NOT NULL,
  `rma` int(11) NOT NULL,
  `parts` int(11) NOT NULL,
  `partsDetails` longtext DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `entryDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `prevQuantity` double DEFAULT NULL,
  `reference_designator` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `request_type` varchar(45) NOT NULL,
  `officer` int(11) NOT NULL,
  `dateOfRequest` date NOT NULL,
  `repair` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `remarks` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `resourcestracker`
--

CREATE TABLE `resourcestracker` (
  `id` int(11) NOT NULL,
  `item` varchar(100) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `lastQuantity` int(11) NOT NULL,
  `quantityChanges` int(11) NOT NULL,
  `transaction` enum('incoming','outgoing','','') NOT NULL,
  `origin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`, `description`) VALUES
(1, 'For Repair', ''),
(2, 'Repaired', ''),
(4, 'Unrepairable', '');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `systemtypes`
--

CREATE TABLE `systemtypes` (
  `id` int(11) NOT NULL,
  `systemType` varchar(100) NOT NULL,
  `description` longtext DEFAULT NULL,
  `dateAdded` date NOT NULL DEFAULT current_timestamp(),
  `createdBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `systemtypes`
--

INSERT INTO `systemtypes` (`id`, `systemType`, `description`, `dateAdded`, `createdBy`) VALUES
(1, 'Max', NULL, '2021-02-04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` int(11) NOT NULL,
  `partNumber` varchar(50) NOT NULL,
  `modelNumber` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `actualQuantity` int(11) NOT NULL,
  `dateReceived` date NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `vendor` varchar(50) NOT NULL,
  `storedIn` int(11) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `unit` int(11) NOT NULL,
  `dateAdded` date NOT NULL,
  `entryBy` varchar(50) DEFAULT NULL,
  `minimumQuantity` int(11) DEFAULT NULL,
  `maximumQuantity` int(11) DEFAULT NULL,
  `criticalTagging` int(11) DEFAULT NULL,
  `unitPrice` double NOT NULL,
  `depreciationValue` double DEFAULT NULL,
  `totalPrice` double DEFAULT NULL,
  `usefulLife` varchar(50) DEFAULT NULL,
  `calibrationReq` longtext DEFAULT NULL,
  `calibrationDate` date DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `is_out` int(11) DEFAULT 0,
  `deleted_at` date DEFAULT NULL,
  `origin` varchar(50) DEFAULT NULL,
  `receivedBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `partNumber`, `modelNumber`, `description`, `brand`, `actualQuantity`, `dateReceived`, `remarks`, `vendor`, `storedIn`, `location`, `unit`, `dateAdded`, `entryBy`, `minimumQuantity`, `maximumQuantity`, `criticalTagging`, `unitPrice`, `depreciationValue`, `totalPrice`, `usefulLife`, `calibrationReq`, `calibrationDate`, `invoice_number`, `is_out`, `deleted_at`, `origin`, `receivedBy`) VALUES
(15, 'PN-2013', 'model-02', 'Allen Wrench', 'Allen Super', 1, '2021-02-04', NULL, 'Techni Tool', 11, 'drawer#13', 9, '2021-02-04', 'admin', NULL, NULL, NULL, 50, NULL, 50, '1', NULL, NULL, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `type`) VALUES
(8, 'Boards'),
(9, 'Tools'),
(10, 'Components');

-- --------------------------------------------------------

--
-- Table structure for table `typeofservices`
--

CREATE TABLE `typeofservices` (
  `id` int(11) NOT NULL,
  `typeOfService` varchar(100) NOT NULL,
  `description` longtext DEFAULT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `addedBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `unit` varchar(45) NOT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit`, `description`) VALUES
(9, 'piece', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `token_UNIQUE` (`token`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `boardtypes`
--
ALTER TABLE `boardtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_components_unit` (`unit`);

--
-- Indexes for table `consignedspares`
--
ALTER TABLE `consignedspares`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumables`
--
ALTER TABLE `consumables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `storagePartNum_UNIQUE` (`partNumber`),
  ADD KEY `fk_storage_loc_idx` (`storedIn`),
  ADD KEY `fk_storage_unit_idx` (`unit`),
  ADD KEY `fk_storage_entryBy` (`entryBy`);

--
-- Indexes for table `criticaltagging`
--
ALTER TABLE `criticaltagging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `emailtonotify`
--
ALTER TABLE `emailtonotify`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_equipment_unit` (`unit`);

--
-- Indexes for table `errors`
--
ALTER TABLE `errors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faultcodes`
--
ALTER TABLE `faultcodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `location` (`location`);

--
-- Indexes for table `movement_components`
--
ALTER TABLE `movement_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movement_component_tracker`
--
ALTER TABLE `movement_component_tracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movement_consigned`
--
ALTER TABLE `movement_consigned`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movement_consumables`
--
ALTER TABLE `movement_consumables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_number` (`invoice_number`),
  ADD KEY `reference` (`reference`);

--
-- Indexes for table `movement_consumable_tracker`
--
ALTER TABLE `movement_consumable_tracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movement_equipment`
--
ALTER TABLE `movement_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movement_tool`
--
ALTER TABLE `movement_tool`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `officerstatus`
--
ALTER TABLE `officerstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `purpose_UNIQUE` (`purpose`);

--
-- Indexes for table `rack`
--
ALTER TABLE `rack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repairrecords`
--
ALTER TABLE `repairrecords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_repairStorage_unit_idx` (`unit`),
  ADD KEY `fk_repairStorage_client_idx` (`customer`);

--
-- Indexes for table `replacementparts`
--
ALTER TABLE `replacementparts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_req_repair_idx` (`repair`),
  ADD KEY `fk_req_officer_idx` (`officer`);

--
-- Indexes for table `resourcestracker`
--
ALTER TABLE `resourcestracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `status_UNIQUE` (`status`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `supplier_UNIQUE` (`supplier`);

--
-- Indexes for table `systemtypes`
--
ALTER TABLE `systemtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tool_unit` (`unit`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `typeofservices`
--
ALTER TABLE `typeofservices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`unit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `boardtypes`
--
ALTER TABLE `boardtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `consignedspares`
--
ALTER TABLE `consignedspares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `consumables`
--
ALTER TABLE `consumables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `criticaltagging`
--
ALTER TABLE `criticaltagging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emailtonotify`
--
ALTER TABLE `emailtonotify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `errors`
--
ALTER TABLE `errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `faultcodes`
--
ALTER TABLE `faultcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `movement_components`
--
ALTER TABLE `movement_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `movement_component_tracker`
--
ALTER TABLE `movement_component_tracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `movement_consigned`
--
ALTER TABLE `movement_consigned`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `movement_consumables`
--
ALTER TABLE `movement_consumables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movement_consumable_tracker`
--
ALTER TABLE `movement_consumable_tracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movement_equipment`
--
ALTER TABLE `movement_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `movement_tool`
--
ALTER TABLE `movement_tool`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `officerstatus`
--
ALTER TABLE `officerstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rack`
--
ALTER TABLE `rack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repairrecords`
--
ALTER TABLE `repairrecords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `replacementparts`
--
ALTER TABLE `replacementparts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resourcestracker`
--
ALTER TABLE `resourcestracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemtypes`
--
ALTER TABLE `systemtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `typeofservices`
--
ALTER TABLE `typeofservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `components`
--
ALTER TABLE `components`
  ADD CONSTRAINT `fk_components_unit` FOREIGN KEY (`unit`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `consumables`
--
ALTER TABLE `consumables`
  ADD CONSTRAINT `fk_consumable_unit` FOREIGN KEY (`unit`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `fk_equipment_unit` FOREIGN KEY (`unit`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `fk_req_officer` FOREIGN KEY (`officer`) REFERENCES `officer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_req_repair` FOREIGN KEY (`repair`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `fk_tool_unit` FOREIGN KEY (`unit`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
