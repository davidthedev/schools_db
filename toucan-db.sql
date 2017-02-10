SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `members` (`id`, `name`, `email`, `school_id`) VALUES
(1, 'John Smith', 'john.smith@mail.com', 1),
(2, 'Bob Green', 'bob.green@mail.com', 2),
(3, 'Alex Brown', 'alex.brown@yahoo.co.uk', 3),
(5, 'Stephen Misc', 'steph@hotmail.com', 1),
(6, 'Garry Johnson', 'garry.j@aol.com', 1),
(7, 'Sam Summer', 'sam.s@gmail.com', 1),
(8, 'Michael Davis', 'mic.dav@bt.com', 1),
(9, 'Larry Laros', 'megalar@tso.co.uk', 1),
(10, 'Tim Taylor', 'timmytim@sportsacademy.org', 1),
(11, 'Dan Williams', 'thereader@email.com', 1),
(12, 'David Davies', 'dave@friendsemail.com', 1),
(13, 'Loben Evans', 'lob@working.org', 1),
(14, 'Jim Bailey', 'jimo@limo.com', 1);

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `schools` (`id`, `name`) VALUES
(1, 'School A'),
(2, 'School B'),
(3, 'School C'),
(4, 'School D'),
(5, 'School E'),
(6, 'School F'),
(7, 'School G'),
(8, 'School H'),
(9, 'School I'),
(10, 'School J'),
(11, 'School K'),
(12, 'School L');

ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school` (`school_id`);

ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
