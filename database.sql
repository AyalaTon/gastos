-- GASTOS --

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `user` varchar(50) NOT NULL UNIQUE,
  `pass` text DEFAULT NULL,
  `token` text DEFAULT NULL,
  `tokenFecha` varchar(50) DEFAULT NULL
)

INSERT INTO `usuario` (`id`, `user`, `pass`, `token`, `tokenFecha`) VALUES
(1, 'milton@gastos', NULL, NULL, NULL),
(2, 'paula@gastos', NULL, NULL, NULL);