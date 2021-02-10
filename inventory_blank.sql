-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2021 at 01:40 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

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
  `officer` int(11) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `logAttemptsMax` int(11) NOT NULL,
  `logCurAttempt` int(11) NOT NULL,
  `lastLog` datetime NOT NULL,
  `token` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `officer`, `role`, `logAttemptsMax`, `logCurAttempt`, `lastLog`, `token`) VALUES
(1, 'admin', '$2y$10$B.5AU.wS4DRK7WzQP2sbFOyNPP3wxR04Wx/ua222whIeu4TC8F/ia', NULL, 'admin', 5, 0, '0000-00-00 00:00:00', 'qa-2W9zle!k@#1x');

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
(1, 'aehr@test.com', 'admin', '2021-02-03 12:39:03');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `token_UNIQUE` (`token`),
  ADD KEY `fk_acc_officer_idx` (`officer`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `boardtypes`
--
ALTER TABLE `boardtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `consignedspares`
--
ALTER TABLE `consignedspares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `consumables`
--
ALTER TABLE `consumables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `criticaltagging`
--
ALTER TABLE `criticaltagging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailtonotify`
--
ALTER TABLE `emailtonotify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `errors`
--
ALTER TABLE `errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `faultcodes`
--
ALTER TABLE `faultcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movement_components`
--
ALTER TABLE `movement_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `movement_component_tracker`
--
ALTER TABLE `movement_component_tracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_acc_officer` FOREIGN KEY (`officer`) REFERENCES `officer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
