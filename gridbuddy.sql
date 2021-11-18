
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gridbuddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `circuit` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `pin` int(11) NOT NULL,
  `pole_lr` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `circuit`, `name`, `start_date`, `end_date`, `pin`, `pole_lr`) VALUES
(1, 'Phillip Island', 'Island Magic', '2021-11-26', '2021-11-28', 9876, 'L');

-- --------------------------------------------------------

--
-- Table structure for table `grid`
--

CREATE TABLE `grid` (
  `id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  `car` varchar(5) NOT NULL,
  `driver` varchar(100) NOT NULL,
  `grid_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=present, 1=scratch, 2=present, but from lane'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grid`
--

INSERT INTO `grid` (`id`, `race_id`, `pos`, `car`, `driver`, `grid_status`) VALUES
(1, 1, 1, '51', '', 1),
(3, 1, 3, '14', '', 0),
(4, 1, 4, '11', '', 1),
(5, 1, 5, '14', '', 1),
(6, 1, 6, '88', '', 1),
(7, 1, 7, '42', '', 1),
(8, 1, 8, '21', '', 1),
(9, 1, 9, '53', '', 0),
(10, 1, 10, '74', '', 1),
(11, 1, 11, '72', '', 2),
(12, 1, 12, '95', '', 0),
(13, 1, 13, '23', '', 0),
(14, 1, 14, '65', '', 0),
(15, 1, 15, '8', '', 0),
(108, 1, 18, '101', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `initials` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`id`, `event_id`, `initials`) VALUES
(1, 1, 'JC'),
(2, 1, 'RC'),
(3, 1, 'JKS'),
(4, 1, 'Bob');

-- --------------------------------------------------------

--
-- Table structure for table `race`
--

CREATE TABLE `race` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ref` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_time` time NOT NULL,
  `completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `race`
--

INSERT INTO `race` (`id`, `event_id`, `ref`, `name`, `start_time`, `completed`) VALUES
(1, 1, 'E34', 'GT3 Test', '16:55:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rows`
--

CREATE TABLE `rows` (
  `race_id` int(11) NOT NULL,
  `row` int(11) NOT NULL,
  `official_id` int(11) NOT NULL,
  `green_flag` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rows`
--

INSERT INTO `rows` (`race_id`, `row`, `official_id`, `green_flag`) VALUES
(1, 1, 3, 0),
(1, 2, 3, 0),
(1, 3, 3, 0),
(1, 4, 2, 0),
(1, 5, 2, 0),
(1, 6, 2, 0),
(1, 7, 2, 0),
(1, 8, 2, 0),
(1, 9, 1, 0),
(1, 99, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grid`
--
ALTER TABLE `grid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `race`
--
ALTER TABLE `race`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rows`
--
ALTER TABLE `rows`
  ADD UNIQUE KEY `race_row` (`race_id`,`row`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `grid`
--
ALTER TABLE `grid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `race`
--
ALTER TABLE `race`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
