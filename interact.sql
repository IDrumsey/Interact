-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2020 at 08:33 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `interact`
--

-- --------------------------------------------------------

--
-- Table structure for table `bracket_type`
--

CREATE TABLE `bracket_type` (
  `bracket_ID` int(11) NOT NULL,
  `bracket_name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bracket_type`
--

INSERT INTO `bracket_type` (`bracket_ID`, `bracket_name`, `description`) VALUES
(6, 'Round Robin', 'Every team will play every other team once. At the end of the tournament, the placement of a team will be determined by the points that team earned throughout the tournament. For each win, a team will receive 2 points. For a draw, a team will receive 1 point. For a loss, a team will receive 0 points. In the case of tied points between two teams, those teams will face each other in a non-draw single round to determine their placements.'),
(7, 'Double Round Robin', 'Every team will play every other team twice. At the end of the tournament, the placement of a team will be determined by the points that team earned throughout the tournament. For each win, a team will receive 2 points. For a draw, a team will receive 1 point. For a loss, a team will receive 0 points. In the case of tied points between two teams, those teams will face each other in a non-draw single round to determine their placements.'),
(8, 'Single Elimination', 'A team is eliminated from the tournament after their first loss. This style of tournament requires the entire prize to be given to the winner of the tournament.'),
(9, 'Double Elimination', 'There are two brackets in this style tournament. The upper bracket and the lower bracket. To be eliminated from the tournament, a team must lose twice. Rounds will continue until there is only one team left who hasn\'t lost twice.');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `game_ID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `teams_per_game` int(11) NOT NULL,
  `players_per_team` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`game_ID`, `title`, `teams_per_game`, `players_per_team`) VALUES
(1, 'Overwatch', 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `gamepresets`
--

CREATE TABLE `gamepresets` (
  `presetName` varchar(50) NOT NULL,
  `presetID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gamepresets`
--

INSERT INTO `gamepresets` (`presetName`, `presetID`) VALUES
('Map', 0);

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `invitor` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `invited` int(11) NOT NULL,
  `invitation_ID` int(11) NOT NULL,
  `tournament_ID` int(11) DEFAULT NULL,
  `match_ID` int(11) DEFAULT NULL,
  `team_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`invitor`, `type`, `invited`, `invitation_ID`, `tournament_ID`, `match_ID`, `team_ID`) VALUES
(23, 'Tournament', 27, 13, 39, NULL, 24),
(23, 'Tournament', 24, 14, 39, NULL, 24);

-- --------------------------------------------------------

--
-- Table structure for table `presetvalues`
--

CREATE TABLE `presetvalues` (
  `gameID` int(11) NOT NULL,
  `presetID` int(11) NOT NULL,
  `presetValue` varchar(50) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `presetvalues`
--

INSERT INTO `presetvalues` (`gameID`, `presetID`, `presetValue`, `ID`) VALUES
(1, 0, 'King\'s Row', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prize_distribution_method`
--

CREATE TABLE `prize_distribution_method` (
  `method_id` int(11) NOT NULL,
  `method_name` varchar(50) NOT NULL,
  `method_description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prize_distribution_method`
--

INSERT INTO `prize_distribution_method` (`method_id`, `method_name`, `method_description`) VALUES
(1, 'everyoneAWinner', 'The prize pool amount is split between all participating teams relative to their placement.'),
(2, 'topThree', '1st, 2nd, and 3rd place teams receive 50, 30, and 20 percent of the prize pool amount.'),
(3, 'winnerOnly', 'The winner receives the entirety of the prize pool amount.');

-- --------------------------------------------------------

--
-- Table structure for table `round_stats`
--

CREATE TABLE `round_stats` (
  `player_id` int(11) DEFAULT NULL,
  `round_id` int(11) NOT NULL,
  `player_points` int(11) DEFAULT NULL,
  `player_losses` int(11) DEFAULT NULL,
  `stat_id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `teamassociations`
--

CREATE TABLE `teamassociations` (
  `userID` int(11) NOT NULL,
  `teamID` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `rank` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teamassociations`
--

INSERT INTO `teamassociations` (`userID`, `teamID`, `id`, `rank`) VALUES
(23, 24, 11, 'owner'),
(27, 24, 12, 'member'),
(24, 24, 13, 'member'),
(27, 25, 14, 'owner'),
(27, 26, 15, 'owner'),
(27, 27, 16, 'owner'),
(35, 28, 17, 'owner'),
(35, 31, 19, 'owner'),
(35, 32, 20, 'owner'),
(35, 33, 21, 'owner'),
(35, 34, 22, 'owner'),
(35, 35, 23, 'owner'),
(35, 36, 24, 'owner');

-- --------------------------------------------------------

--
-- Table structure for table `teamdetails`
--

CREATE TABLE `teamdetails` (
  `team_Name` varchar(50) NOT NULL,
  `numWins` int(11) NOT NULL,
  `numLosses` int(11) NOT NULL,
  `team_ID` int(11) NOT NULL,
  `logo_set` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teamdetails`
--

INSERT INTO `teamdetails` (`team_Name`, `numWins`, `numLosses`, `team_ID`, `logo_set`) VALUES
('The Pebbles', 0, 0, 24, 1),
('test Team Dos', 0, 0, 25, 1),
('Test Team Tres', 0, 0, 26, 1),
('Test Team Quatro', 0, 0, 27, 1),
('test team 1', 0, 0, 28, 1),
('Test Team 2', 0, 0, 31, 1),
('Test Team 3', 0, 0, 32, 1),
('Test Team 4', 0, 0, 33, 1),
('Test Team 5', 0, 0, 34, 1),
('Test Team 6', 0, 0, 35, 1),
('Test Team 7', 0, 0, 36, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournament_ID` int(11) NOT NULL,
  `totalPrize` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `bracket_type_ID` int(11) NOT NULL,
  `num_players_registered` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `prize_distribution_ID` int(11) NOT NULL,
  `tournament_Name` varchar(50) NOT NULL,
  `join_Prize_Type` varchar(20) NOT NULL,
  `num_rounds` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `grouping_style` varchar(30) NOT NULL,
  `owner` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournament_ID`, `totalPrize`, `gameID`, `bracket_type_ID`, `num_players_registered`, `start_date`, `end_date`, `prize_distribution_ID`, `tournament_Name`, `join_Prize_Type`, `num_rounds`, `status`, `grouping_style`, `owner`) VALUES
(36, 10, 1, 9, 0, '2020-12-11', '2020-12-13', 1, 'Test Tournament', 'entryFee', NULL, 'pending', 'team', 'usernameOfAwesomeness'),
(39, 100, 1, 7, 0, '2020-12-08', '2020-12-29', 3, 'Test Tournament 2', 'totalAmount', NULL, 'Started', 'team', 'testuser7');

-- --------------------------------------------------------

--
-- Table structure for table `tournamentmatch`
--

CREATE TABLE `tournamentmatch` (
  `id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `team1_ID` int(11) NOT NULL,
  `team2_ID` int(11) NOT NULL,
  `round_id` int(11) NOT NULL,
  `complete_status` varchar(30) NOT NULL,
  `match_winner` int(11) DEFAULT NULL,
  `match_loser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tournamentmatch`
--

INSERT INTO `tournamentmatch` (`id`, `start_date`, `start_time`, `end_time`, `team1_ID`, `team2_ID`, `round_id`, `complete_status`, `match_winner`, `match_loser`) VALUES
(33, NULL, NULL, NULL, 28, 31, 35, 'incomplete', NULL, NULL),
(34, NULL, NULL, NULL, 32, 33, 35, 'incomplete', NULL, NULL),
(35, NULL, NULL, NULL, 34, 35, 35, 'incomplete', NULL, NULL),
(36, NULL, NULL, NULL, 36, 24, 35, 'incomplete', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_player_association`
--

CREATE TABLE `tournament_player_association` (
  `user_id` int(11) DEFAULT NULL,
  `tournament_id` int(11) NOT NULL,
  `association_id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tournament_player_association`
--

INSERT INTO `tournament_player_association` (`user_id`, `tournament_id`, `association_id`, `team_id`) VALUES
(23, 36, 34, NULL),
(24, 36, 35, 24),
(27, 36, 36, 24),
(35, 39, 38, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_round`
--

CREATE TABLE `tournament_round` (
  `round_id` int(11) NOT NULL,
  `num_matches` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `round_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tournament_round`
--

INSERT INTO `tournament_round` (`round_id`, `num_matches`, `status`, `tournament_id`, `round_number`) VALUES
(35, 4, 'incomplete', 39, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_team_association`
--

CREATE TABLE `tournament_team_association` (
  `tournament_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `association_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tournament_team_association`
--

INSERT INTO `tournament_team_association` (`tournament_id`, `team_id`, `association_id`) VALUES
(36, 24, 12),
(36, 25, 13),
(36, 26, 14),
(36, 27, 15),
(39, 28, 16),
(39, 31, 17),
(39, 32, 18),
(39, 33, 19),
(39, 34, 20),
(39, 35, 21),
(39, 36, 22),
(39, 24, 23);

-- --------------------------------------------------------

--
-- Table structure for table `usergameassociations`
--

CREATE TABLE `usergameassociations` (
  `user_ID` int(11) NOT NULL,
  `game_ID` int(11) DEFAULT NULL,
  `association_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usergameassociations`
--

INSERT INTO `usergameassociations` (`user_ID`, `game_ID`, `association_ID`) VALUES
(23, 1, 8),
(24, 1, 9),
(27, 1, 12),
(28, 1, 13),
(29, 1, 14),
(30, 1, 15),
(33, 1, 17),
(34, 1, 18),
(35, 1, 19),
(36, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `profileImageSet` int(11) NOT NULL,
  `primary_color` varchar(10) NOT NULL,
  `secondary_color` varchar(10) NOT NULL,
  `tertiary_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pass`, `email`, `profileImageSet`, `primary_color`, `secondary_color`, `tertiary_color`) VALUES
(23, 'usernameOfAwesomeness', 'e6b700260e2b0c31722952ab1a2f5a8b50705c83f2365f2adac5095be0280f7b', 'ccasper3@kent.edu', 1, '#5896c6', '#ae00ff', '#000419'),
(24, 'TheBlob34', '57b9825272aa0e10500f7d37494405e1ced9570362c54c43d9dc363bdb1be97b', 'ccasper3@kent.edu', 1, '#ffee00', '#000000', '#0e0075'),
(27, 'Amperage', 'b5e0c5eb8bad0ddcef60a2012b281ca6a5f3db8a4de0e7839c66b5085fd60413', 'ccasper3@kent.edu', 1, '#a7e49a', '#000000', '#a33838'),
(28, 'testuser1', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#000000', '#ffffff', '#000000'),
(29, 'testuser2', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#000000', '#ffffff', '#000000'),
(30, 'testuser3', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#000000', '#ffffff', '#000000'),
(33, 'testuser4', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#000000', '#ffffff', '#000000'),
(34, 'testuser5', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#000000', '#ffffff', '#000000'),
(35, 'testuser6', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#9f6565', '#ffffff', '#000000'),
(36, 'testuser7', '64144c8754c39da10cb463e8ad9db9f62e3486099c69f2348b883fe67075114d', 'ccasper3@kent.edu', 1, '#000000', '#ffffff', '#000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bracket_type`
--
ALTER TABLE `bracket_type`
  ADD PRIMARY KEY (`bracket_ID`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`game_ID`);

--
-- Indexes for table `gamepresets`
--
ALTER TABLE `gamepresets`
  ADD PRIMARY KEY (`presetID`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`invitation_ID`),
  ADD KEY `invitor` (`invitor`),
  ADD KEY `invited` (`invited`),
  ADD KEY `match_ID` (`match_ID`),
  ADD KEY `tournament_ID` (`tournament_ID`),
  ADD KEY `team_ID` (`team_ID`);

--
-- Indexes for table `presetvalues`
--
ALTER TABLE `presetvalues`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `presetID` (`presetID`),
  ADD KEY `gameID` (`gameID`);

--
-- Indexes for table `prize_distribution_method`
--
ALTER TABLE `prize_distribution_method`
  ADD PRIMARY KEY (`method_id`);

--
-- Indexes for table `round_stats`
--
ALTER TABLE `round_stats`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `round_id` (`round_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `teamassociations`
--
ALTER TABLE `teamassociations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `teamID` (`teamID`);

--
-- Indexes for table `teamdetails`
--
ALTER TABLE `teamdetails`
  ADD PRIMARY KEY (`team_ID`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`tournament_ID`),
  ADD KEY `bracket_type_ID` (`bracket_type_ID`),
  ADD KEY `gameID` (`gameID`),
  ADD KEY `prize_distribution_ID` (`prize_distribution_ID`);

--
-- Indexes for table `tournamentmatch`
--
ALTER TABLE `tournamentmatch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team1_ID` (`team1_ID`),
  ADD KEY `team2_ID` (`team2_ID`),
  ADD KEY `round_id` (`round_id`),
  ADD KEY `match_winner` (`match_winner`),
  ADD KEY `match_loser` (`match_loser`);

--
-- Indexes for table `tournament_player_association`
--
ALTER TABLE `tournament_player_association`
  ADD PRIMARY KEY (`association_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `tournament_round`
--
ALTER TABLE `tournament_round`
  ADD PRIMARY KEY (`round_id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `tournament_team_association`
--
ALTER TABLE `tournament_team_association`
  ADD PRIMARY KEY (`association_id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `tournament_team_association_ibfk_2` (`team_id`);

--
-- Indexes for table `usergameassociations`
--
ALTER TABLE `usergameassociations`
  ADD PRIMARY KEY (`association_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bracket_type`
--
ALTER TABLE `bracket_type`
  MODIFY `bracket_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `game_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `invitation_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `presetvalues`
--
ALTER TABLE `presetvalues`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prize_distribution_method`
--
ALTER TABLE `prize_distribution_method`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `round_stats`
--
ALTER TABLE `round_stats`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teamassociations`
--
ALTER TABLE `teamassociations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `teamdetails`
--
ALTER TABLE `teamdetails`
  MODIFY `team_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournament_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tournamentmatch`
--
ALTER TABLE `tournamentmatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tournament_player_association`
--
ALTER TABLE `tournament_player_association`
  MODIFY `association_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tournament_round`
--
ALTER TABLE `tournament_round`
  MODIFY `round_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tournament_team_association`
--
ALTER TABLE `tournament_team_association`
  MODIFY `association_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `usergameassociations`
--
ALTER TABLE `usergameassociations`
  MODIFY `association_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`invitor`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invitations_ibfk_2` FOREIGN KEY (`invited`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invitations_ibfk_5` FOREIGN KEY (`tournament_ID`) REFERENCES `tournament` (`tournament_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invitations_ibfk_6` FOREIGN KEY (`team_ID`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presetvalues`
--
ALTER TABLE `presetvalues`
  ADD CONSTRAINT `presetvalues_ibfk_2` FOREIGN KEY (`presetID`) REFERENCES `gamepresets` (`presetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presetvalues_ibfk_3` FOREIGN KEY (`gameID`) REFERENCES `game` (`game_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `round_stats`
--
ALTER TABLE `round_stats`
  ADD CONSTRAINT `round_stats_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `round_stats_ibfk_2` FOREIGN KEY (`round_id`) REFERENCES `tournament_round` (`round_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `round_stats_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teamassociations`
--
ALTER TABLE `teamassociations`
  ADD CONSTRAINT `teamassociations_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teamassociations_ibfk_2` FOREIGN KEY (`teamID`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_ibfk_3` FOREIGN KEY (`bracket_type_ID`) REFERENCES `bracket_type` (`bracket_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournament_ibfk_4` FOREIGN KEY (`gameID`) REFERENCES `game` (`game_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournament_ibfk_6` FOREIGN KEY (`prize_distribution_ID`) REFERENCES `prize_distribution_method` (`method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournamentmatch`
--
ALTER TABLE `tournamentmatch`
  ADD CONSTRAINT `tournamentmatch_ibfk_5` FOREIGN KEY (`team1_ID`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournamentmatch_ibfk_6` FOREIGN KEY (`team2_ID`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournamentmatch_ibfk_7` FOREIGN KEY (`round_id`) REFERENCES `tournament_round` (`round_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournamentmatch_ibfk_8` FOREIGN KEY (`match_winner`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournamentmatch_ibfk_9` FOREIGN KEY (`match_loser`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournament_player_association`
--
ALTER TABLE `tournament_player_association`
  ADD CONSTRAINT `tournament_player_association_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournament_player_association_ibfk_2` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournament_player_association_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournament_round`
--
ALTER TABLE `tournament_round`
  ADD CONSTRAINT `tournament_round_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournament_team_association`
--
ALTER TABLE `tournament_team_association`
  ADD CONSTRAINT `tournament_team_association_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournament_team_association_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teamdetails` (`team_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usergameassociations`
--
ALTER TABLE `usergameassociations`
  ADD CONSTRAINT `usergameassociations_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
