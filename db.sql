-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 28 fév. 2023 à 19:49
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog_p5`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `validation` int(11) NOT NULL,
  `content` mediumtext CHARACTER SET utf8mb4 NOT NULL,
  `author` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `validation`, `content`, `author`, `created_at`, `update_at`) VALUES
(1, 2, 1, 'Le lorem ipsum c&#039;est vraiment pas terrible....', 3, '2023-01-20 10:11:44', '2023-02-13 15:33:40'),
(6, 1, 1, 'Salut !', NULL, '2023-02-03 10:14:49', '2023-02-13 15:33:38'),
(7, 8, 1, 'Hello !', 4, '2023-02-13 10:41:07', '2023-02-13 15:36:40'),
(10, 8, 1, 'Bonjour !!!', 4, '2023-02-13 12:22:58', '2023-02-22 18:54:45'),
(13, 6, 1, 'Super article !', 4, '2023-02-22 19:10:30', '2023-02-22 19:10:30'),
(14, 2, 1, 'Top ! C&#039;est cool !', 4, '2023-02-22 19:11:10', '2023-02-28 19:38:20');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `introtext` text NOT NULL,
  `content` longtext NOT NULL,
  `author` int(11) NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `state`, `title`, `slug`, `introtext`, `content`, `author`, `update_at`, `created_at`) VALUES
(1, 1, 'Je suis le premier post de toto', 'je-suis-le-premier-post-de-toto', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum. Nam nec neque mattis, ultricies risus at, pellentesque sem. Nunc non lorem vel lacus cursus tincidunt. In rhoncus, est ut dignissim tincidunt, magna metus mattis sem, et mollis velit odio eu leo. Maecenas eu tortor id lorem hendrerit tempor. Donec justo risus, bibendum vel ligula ullamcorper, pharetra tempus massa. Ut lectus dui, blandit et ex quis, iaculis bibendum orci. Ut mauris enim, tempus eu finibus quis, mollis placerat orci. Nulla et tincidunt turpis, et facilisis felis. Cras felis ipsum, fermentum sit amet lacus eget, scelerisque placerat felis. In at arcu turpis. Aenean ipsum odio, congue id blandit vel, bibendum sed erat. Vestibulum tincidunt non libero sit amet iaculis. Fusce at tortor vitae arcu aliquet fermentum.\n\nPellentesque augue purus, bibendum volutpat ornare non, lacinia sit amet nibh. Duis aliquet libero ut mauris pretium dictum. Proin efficitur augue quis augue rhoncus auctor. Aenean sed turpis at turpis scelerisque pharetra nec non nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi suscipit sapien a libero maximus molestie. In ipsum nulla, egestas sit amet suscipit in, egestas quis felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In ante diam, congue non consequat id, placerat sit amet odio.\n\nPellentesque in lacinia mauris, at pretium nisl. Aenean rhoncus congue pretium. Nunc placerat ex a elit iaculis, id scelerisque arcu laoreet. Sed porttitor elit id velit accumsan volutpat. Pellentesque eget finibus lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed sodales ligula dolor, a consequat urna suscipit in. Morbi ac nunc neque. Sed faucibus sed risus quis condimentum. Donec lacus eros, feugiat pretium dolor consectetur, volutpat tristique nibh. Donec est lorem, dictum eu felis eget, cursus accumsan nulla. Nulla mollis nunc vitae mollis mollis. Suspendisse vitae est quis neque posuere fringilla vitae gravida tortor. Maecenas rutrum arcu magna, id vulputate ipsum fringilla non. Integer blandit sagittis laoreet. Phasellus erat nibh, blandit at velit nec, rhoncus cursus ipsum.\n\nMaecenas placerat mauris arcu, sed aliquam lacus bibendum a. Nulla eleifend tellus eu lorem laoreet pretium. Nullam vehicula scelerisque pharetra. Sed non felis sollicitudin, sagittis lacus eget, blandit justo. Nam sed nulla fermentum diam mollis dapibus nec et elit. Cras aliquet lorem eleifend diam malesuada blandit. Nam est urna, placerat in quam sed, scelerisque ultrices nisl. Cras commodo scelerisque nibh, vel vestibulum erat tempus quis. In non scelerisque risus. Cras viverra nunc ac quam egestas, nec varius urna malesuada. Maecenas elementum sodales nisi, vel porta tortor ullamcorper a. Nunc luctus eros mauris, ac scelerisque sapien euismod sed. Phasellus commodo vitae lacus vitae tempus. Sed lorem nisl, faucibus sit amet libero quis, aliquam sodales lectus. Nullam molestie dolor at sem porttitor, ac rhoncus arcu condimentum. Duis porttitor urna mollis nisl tincidunt, ac egestas justo semper.\n\nInteger vel aliquet turpis, ut consequat nunc. Maecenas mi sem, pharetra ac nisl quis, mattis pretium tellus. Donec turpis felis, tincidunt eget gravida nec, mollis quis dolor. Vivamus dolor erat, tristique quis arcu efficitur, gravida ultrices diam. In hac habitasse platea dictumst. Nam ac orci luctus, ornare turpis sit amet, dapibus quam. Integer ultricies justo in sollicitudin dapibus. Nunc euismod scelerisque ante non dapibus. Ut varius placerat ex a pretium.', 3, '2023-02-13 15:23:46', '2023-01-20 10:00:42'),
(2, 1, 'Je suis le premier post de tata', 'je-suis-le-premier-post-de-tata', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum. Nam nec neque mattis, ultricies risus at, pellentesque sem. Nunc non lorem vel lacus cursus tincidunt. In rhoncus, est ut dignissim tincidunt, magna metus mattis sem, et mollis velit odio eu leo. Maecenas eu tortor id lorem hendrerit tempor. Donec justo risus, bibendum vel ligula ullamcorper, pharetra tempus massa. Ut lectus dui, blandit et ex quis, iaculis bibendum orci. Ut mauris enim, tempus eu finibus quis, mollis placerat orci. Nulla et tincidunt turpis, et facilisis felis. Cras felis ipsum, fermentum sit amet lacus eget, scelerisque placerat felis. In at arcu turpis. Aenean ipsum odio, congue id blandit vel, bibendum sed erat. Vestibulum tincidunt non libero sit amet iaculis. Fusce at tortor vitae arcu aliquet fermentum.\r\n\r\nPellentesque augue purus, bibendum volutpat ornare non, lacinia sit amet nibh. Duis aliquet libero ut mauris pretium dictum. Proin efficitur augue quis augue rhoncus auctor. Aenean sed turpis at turpis scelerisque pharetra nec non nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi suscipit sapien a libero maximus molestie. In ipsum nulla, egestas sit amet suscipit in, egestas quis felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In ante diam, congue non consequat id, placerat sit amet odio.\r\n\r\nPellentesque in lacinia mauris, at pretium nisl. Aenean rhoncus congue pretium. Nunc placerat ex a elit iaculis, id scelerisque arcu laoreet. Sed porttitor elit id velit accumsan volutpat. Pellentesque eget finibus lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed sodales ligula dolor, a consequat urna suscipit in. Morbi ac nunc neque. Sed faucibus sed risus quis condimentum. Donec lacus eros, feugiat pretium dolor consectetur, volutpat tristique nibh. Donec est lorem, dictum eu felis eget, cursus accumsan nulla. Nulla mollis nunc vitae mollis mollis. Suspendisse vitae est quis neque posuere fringilla vitae gravida tortor. Maecenas rutrum arcu magna, id vulputate ipsum fringilla non. Integer blandit sagittis laoreet. Phasellus erat nibh, blandit at velit nec, rhoncus cursus ipsum.\r\n\r\nMaecenas placerat mauris arcu, sed aliquam lacus bibendum a. Nulla eleifend tellus eu lorem laoreet pretium. Nullam vehicula scelerisque pharetra. Sed non felis sollicitudin, sagittis lacus eget, blandit justo. Nam sed nulla fermentum diam mollis dapibus nec et elit. Cras aliquet lorem eleifend diam malesuada blandit. Nam est urna, placerat in quam sed, scelerisque ultrices nisl. Cras commodo scelerisque nibh, vel vestibulum erat tempus quis. In non scelerisque risus. Cras viverra nunc ac quam egestas, nec varius urna malesuada. Maecenas elementum sodales nisi, vel porta tortor ullamcorper a. Nunc luctus eros mauris, ac scelerisque sapien euismod sed. Phasellus commodo vitae lacus vitae tempus. Sed lorem nisl, faucibus sit amet libero quis, aliquam sodales lectus. Nullam molestie dolor at sem porttitor, ac rhoncus arcu condimentum. Duis porttitor urna mollis nisl tincidunt, ac egestas justo semper.\r\n\r\nInteger vel aliquet turpis, ut consequat nunc. Maecenas mi sem, pharetra ac nisl quis, mattis pretium tellus. Donec turpis felis, tincidunt eget gravida nec, mollis quis dolor. Vivamus dolor erat, tristique quis arcu efficitur, gravida ultrices diam. In hac habitasse platea dictumst. Nam ac orci luctus, ornare turpis sit amet, dapibus quam. Integer ultricies justo in sollicitudin dapibus. Nunc euismod scelerisque ante non dapibus. Ut varius placerat ex a pretium.', 3, '2023-01-29 12:39:12', '2023-01-20 10:00:42'),
(6, 1, 'Je suis le deuxième post de tata', 'je-suis-le-deuxieme-post-de-tata', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum. Nam nec neque mattis, ultricies risus at, pellentesque sem. Nunc non lorem vel lacus cursus tincidunt. In rhoncus, est ut dignissim tincidunt, magna metus mattis sem, et mollis velit odio eu leo. Maecenas eu tortor id lorem hendrerit tempor. Donec justo risus, bibendum vel ligula ullamcorper, pharetra tempus massa. Ut lectus dui, blandit et ex quis, iaculis bibendum orci. Ut mauris enim, tempus eu finibus quis, mollis placerat orci. Nulla et tincidunt turpis, et facilisis felis. Cras felis ipsum, fermentum sit amet lacus eget, scelerisque placerat felis. In at arcu turpis. Aenean ipsum odio, congue id blandit vel, bibendum sed erat. Vestibulum tincidunt non libero sit amet iaculis. Fusce at tortor vitae arcu aliquet fermentum.\r\n\r\nPellentesque augue purus, bibendum volutpat ornare non, lacinia sit amet nibh. Duis aliquet libero ut mauris pretium dictum. Proin efficitur augue quis augue rhoncus auctor. Aenean sed turpis at turpis scelerisque pharetra nec non nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi suscipit sapien a libero maximus molestie. In ipsum nulla, egestas sit amet suscipit in, egestas quis felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In ante diam, congue non consequat id, placerat sit amet odio.\r\n\r\nPellentesque in lacinia mauris, at pretium nisl. Aenean rhoncus congue pretium. Nunc placerat ex a elit iaculis, id scelerisque arcu laoreet. Sed porttitor elit id velit accumsan volutpat. Pellentesque eget finibus lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed sodales ligula dolor, a consequat urna suscipit in. Morbi ac nunc neque. Sed faucibus sed risus quis condimentum. Donec lacus eros, feugiat pretium dolor consectetur, volutpat tristique nibh. Donec est lorem, dictum eu felis eget, cursus accumsan nulla. Nulla mollis nunc vitae mollis mollis. Suspendisse vitae est quis neque posuere fringilla vitae gravida tortor. Maecenas rutrum arcu magna, id vulputate ipsum fringilla non. Integer blandit sagittis laoreet. Phasellus erat nibh, blandit at velit nec, rhoncus cursus ipsum.\r\n\r\nMaecenas placerat mauris arcu, sed aliquam lacus bibendum a. Nulla eleifend tellus eu lorem laoreet pretium. Nullam vehicula scelerisque pharetra. Sed non felis sollicitudin, sagittis lacus eget, blandit justo. Nam sed nulla fermentum diam mollis dapibus nec et elit. Cras aliquet lorem eleifend diam malesuada blandit. Nam est urna, placerat in quam sed, scelerisque ultrices nisl. Cras commodo scelerisque nibh, vel vestibulum erat tempus quis. In non scelerisque risus. Cras viverra nunc ac quam egestas, nec varius urna malesuada. Maecenas elementum sodales nisi, vel porta tortor ullamcorper a. Nunc luctus eros mauris, ac scelerisque sapien euismod sed. Phasellus commodo vitae lacus vitae tempus. Sed lorem nisl, faucibus sit amet libero quis, aliquam sodales lectus. Nullam molestie dolor at sem porttitor, ac rhoncus arcu condimentum. Duis porttitor urna mollis nisl tincidunt, ac egestas justo semper.\r\n\r\nInteger vel aliquet turpis, ut consequat nunc. Maecenas mi sem, pharetra ac nisl quis, mattis pretium tellus. Donec turpis felis, tincidunt eget gravida nec, mollis quis dolor. Vivamus dolor erat, tristique quis arcu efficitur, gravida ultrices diam. In hac habitasse platea dictumst. Nam ac orci luctus, ornare turpis sit amet, dapibus quam. Integer ultricies justo in sollicitudin dapibus. Nunc euismod scelerisque ante non dapibus. Ut varius placerat ex a pretium.', 3, '2023-01-29 12:39:16', '2023-01-20 10:00:42'),
(7, 1, 'Je suis le deuxième post de toto', 'je-suis-le-deuxieme-post-de-toto', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in tellus finibus, consequat enim nec, commodo ipsum. Nam nec neque mattis, ultricies risus at, pellentesque sem. Nunc non lorem vel lacus cursus tincidunt. In rhoncus, est ut dignissim tincidunt, magna metus mattis sem, et mollis velit odio eu leo. Maecenas eu tortor id lorem hendrerit tempor. Donec justo risus, bibendum vel ligula ullamcorper, pharetra tempus massa. Ut lectus dui, blandit et ex quis, iaculis bibendum orci. Ut mauris enim, tempus eu finibus quis, mollis placerat orci. Nulla et tincidunt turpis, et facilisis felis. Cras felis ipsum, fermentum sit amet lacus eget, scelerisque placerat felis. In at arcu turpis. Aenean ipsum odio, congue id blandit vel, bibendum sed erat. Vestibulum tincidunt non libero sit amet iaculis. Fusce at tortor vitae arcu aliquet fermentum.\r\n\r\nPellentesque augue purus, bibendum volutpat ornare non, lacinia sit amet nibh. Duis aliquet libero ut mauris pretium dictum. Proin efficitur augue quis augue rhoncus auctor. Aenean sed turpis at turpis scelerisque pharetra nec non nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi suscipit sapien a libero maximus molestie. In ipsum nulla, egestas sit amet suscipit in, egestas quis felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In ante diam, congue non consequat id, placerat sit amet odio.\r\n\r\nPellentesque in lacinia mauris, at pretium nisl. Aenean rhoncus congue pretium. Nunc placerat ex a elit iaculis, id scelerisque arcu laoreet. Sed porttitor elit id velit accumsan volutpat. Pellentesque eget finibus lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed sodales ligula dolor, a consequat urna suscipit in. Morbi ac nunc neque. Sed faucibus sed risus quis condimentum. Donec lacus eros, feugiat pretium dolor consectetur, volutpat tristique nibh. Donec est lorem, dictum eu felis eget, cursus accumsan nulla. Nulla mollis nunc vitae mollis mollis. Suspendisse vitae est quis neque posuere fringilla vitae gravida tortor. Maecenas rutrum arcu magna, id vulputate ipsum fringilla non. Integer blandit sagittis laoreet. Phasellus erat nibh, blandit at velit nec, rhoncus cursus ipsum.\r\n\r\nMaecenas placerat mauris arcu, sed aliquam lacus bibendum a. Nulla eleifend tellus eu lorem laoreet pretium. Nullam vehicula scelerisque pharetra. Sed non felis sollicitudin, sagittis lacus eget, blandit justo. Nam sed nulla fermentum diam mollis dapibus nec et elit. Cras aliquet lorem eleifend diam malesuada blandit. Nam est urna, placerat in quam sed, scelerisque ultrices nisl. Cras commodo scelerisque nibh, vel vestibulum erat tempus quis. In non scelerisque risus. Cras viverra nunc ac quam egestas, nec varius urna malesuada. Maecenas elementum sodales nisi, vel porta tortor ullamcorper a. Nunc luctus eros mauris, ac scelerisque sapien euismod sed. Phasellus commodo vitae lacus vitae tempus. Sed lorem nisl, faucibus sit amet libero quis, aliquam sodales lectus. Nullam molestie dolor at sem porttitor, ac rhoncus arcu condimentum. Duis porttitor urna mollis nisl tincidunt, ac egestas justo semper.\r\n\r\nInteger vel aliquet turpis, ut consequat nunc. Maecenas mi sem, pharetra ac nisl quis, mattis pretium tellus. Donec turpis felis, tincidunt eget gravida nec, mollis quis dolor. Vivamus dolor erat, tristique quis arcu efficitur, gravida ultrices diam. In hac habitasse platea dictumst. Nam ac orci luctus, ornare turpis sit amet, dapibus quam. Integer ultricies justo in sollicitudin dapibus. Nunc euismod scelerisque ante non dapibus. Ut varius placerat ex a pretium.', 3, '2023-02-13 15:23:48', '2023-01-30 10:00:42'),
(8, 1, 'Je suis un article avec des accents', 'je-suis-un-article-avec-des-accents-1', 'Une texte intro', 'Desc', 4, '2023-02-13 15:36:24', '2023-02-13 09:33:35');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `validate` int(11) NOT NULL DEFAULT '0',
  `token_validation` text,
  `token_expiration` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `is_admin`, `email`, `password`, `created_at`, `validate`, `token_validation`, `token_expiration`) VALUES
(3, 'Steven Oyer', 0, 'contact@stevenoyer.fr', '$2y$10$MFcQmAmTj4m0t4Utccn2auU6reQAk0G.SsxuvqFgSJCxV4AzfKoCm', '2023-02-24 10:52:22', 0, NULL, NULL),
(4, 'Admin', 1, 'admin@admin.fr', '$2y$10$aFJsaclwK9Cyv3jsUIZKmOwuaVy4lR0qhhwxDUxeX2pLS5NOB0M5W', '2023-02-13 15:52:03', 1, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`,`author`),
  ADD KEY `comments_ibfk_1` (`author`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
