/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE TABLE `connection` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada conexión',
  `id_system_start` INT(11) NOT NULL COMMENT 'Id del sistema del que se parte',
  `id_system_end` INT(11) NOT NULL COMMENT 'Id del sistema destino o null si todavía no se ha investigado',
  `order` INT(11) NOT NULL COMMENT 'Orden de la conexión entre las que tiene un sistema',
  `navigate_time` INT(11) NOT NULL COMMENT 'Tiempo que se tarda en navegar al sistema destino',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `ship_crew` (
  `id_ship` INT(11) NOT NULL COMMENT 'Id de la nave',
  `id_crew` INT(11) NOT NULL COMMENT 'Id del tripulante',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_ship`,`id_crew`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `npc_ship` (
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que hace la venta',
  `id_ship` INT(11) NOT NULL COMMENT 'Id de la nave que vende',
  `start_value` INT(11) NOT NULL COMMENT 'Cantidad inicial de naves que vende',
  `value` INT(11) NOT NULL COMMENT 'Cantidad de naves que le quedan disponibles',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_npc`,`id_ship`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `system` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada sistema',
  `id_player` INT(11) NOT NULL COMMENT 'Id del jugador que descubre el sistema',
  `original_name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre original del sistema',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre actual del sistema',
  `num_planets` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de planetas que tiene el sistema',
  `num_npc` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de NPCs que hay en el sistema',
  `type` VARCHAR(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipo de sol',
  `radius` INT(11) NOT NULL COMMENT 'Radio del sol',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `message` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada mensaje',
  `id_player_from` INT(11) NOT NULL COMMENT 'Id del jugador que envía un mensaje',
  `id_player_to` INT(11) NOT NULL COMMENT 'Id del jugador al que se le envía un mensaje',
  `message` VARCHAR(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contenido del mensaje',
  `req_id_resource` INT(11) NULL COMMENT 'Tipo de recurso que se solicita',
  `req_value` INT(11) NULL COMMENT 'Cantidad del recurso que se solicita',
  `req_credits` INT(11) NULL COMMENT 'Cantidad de créditos que se solicitan',
  `offer_id_resource` INT(11) NULL COMMENT 'Tipo de recurso que se ofrece',
  `offer_value` INT(11) NULL COMMENT 'Cantidad del recurso que se ofrece',
  `offer_credits` INT(11) NULL COMMENT 'Cantidad de créditos que se ofrecen',
  `req_status` INT(11) NULL COMMENT 'Estado de la solicitud 0 sin aceptar, 1 aceptada, null no hay solicitud',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `planet` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único del planeta',
  `id_system` INT(11) NOT NULL COMMENT 'Id del sistema al que pertenece el planeta',
  `original_name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre original del planeta',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre actual del planeta',
  `type` INT(11) NOT NULL COMMENT 'Tipo de planeta',
  `radius` INT(11) NOT NULL COMMENT 'Radio del planeta',
  `rotation` INT(11) NOT NULL COMMENT 'Velocidad de rotación del planeta alrededor del sol',
  `distance` INT(11) NOT NULL COMMENT 'Distancia del planeta a su sol',
  `num_moons` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de lunas que tiene el planeta',
  `explored` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si el planeta ha sido explorado 1 o no 0',
  `explore_time` INT(11) NOT NULL COMMENT 'Tiempo necesario para explorar el planeta',
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que habita el planeta o null si no tiene',
  `id_construction` INT(11) NOT NULL COMMENT 'Id de la construcción que hay en el planeta o null si no tiene',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `npc_module` (
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que hace la venta',
  `id_module` INT(11) NOT NULL COMMENT 'Id del módulo que vende',
  `start_value` INT(11) NOT NULL COMMENT 'Cantidad inicial de módulos que vende',
  `value` INT(11) NOT NULL COMMENT 'Cantidad de módulos que le quedan disponibles',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_npc`,`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `construction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada construcción',
  `id_player` INT(11) NOT NULL COMMENT 'Id del jugador que hace la construcción',
  `commerce` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si tiene puesto de comercio 1 o no 0',
  `repair` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si tiene taller de reparaciones 1 o no 0',
  `workshop` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si tiene taller de construcciones 1 o no 0',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `npc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada NPC',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del NPC',
  `id_race` INT(11) NOT NULL COMMENT 'Id de la raza del NPC',
  `last_reset` DATETIME NULL COMMENT 'Fecha del último reseteo del NPC',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `player` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único del jugador',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de usuario del jugador',
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email del jugador',
  `pass` VARCHAR(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contraseña cifrada del jugador',
  `credits` INT(11) NOT NULL COMMENT 'Cantidad de créditos que posee el jugador',
  `id_ship` INT(11) NOT NULL COMMENT 'Id de la nave que actualmente pilota el jugador',
  `id_system` INT(11) NOT NULL COMMENT 'Id del sistema en el que se encuentra el jugador',
  `id_job` INT(11) NOT NULL COMMENT 'Id de la tarea que está desempeñando el jugador',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `job` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única para cada tarea',
  `id_player` INT(11) NOT NULL COMMENT 'Id del jugador que hace la tarea',
  `type` INT(11) NOT NULL COMMENT 'Tipo de tarea',
  `value` TEXT COLLATE utf8mb4_unicode_ci NULL COMMENT 'Información extra de la tarea',
  `start` INT(11) NOT NULL COMMENT 'Timestamp de inicio de la tarea',
  `end` INT(11) NOT NULL COMMENT 'Timestamp de fin de la tarea',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `moon` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada luna',
  `id_planet` INT(11) NOT NULL COMMENT 'Id del planeta al que pertenece la luna',
  `original_name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre original de la luna',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre actual de la luna',
  `radius` INT(11) NOT NULL COMMENT 'Radio de la luna',
  `rotation` INT(11) NOT NULL COMMENT 'Velocidad de rotación de la luna alrededor del planeta',
  `distance` INT(11) NOT NULL COMMENT 'Distancia de la luna a su planeta',
  `explored` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si la luna ha sido explorada 1 o no 0',
  `explore_time` INT(11) NOT NULL COMMENT 'Tiempo necesario para explorar la luna',
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que habita el planeta o null si no tiene',
  `id_construction` INT(11) NOT NULL COMMENT 'Id de la construcción que hay en el planeta o null si no tiene',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `crew` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada tripulante',
  `id_player` INT(11) NOT NULL COMMENT 'Id del jugador que contrata al tripulante',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tripulante',
  `id_race` INT(11) NOT NULL COMMENT 'Id de la raza del tripulante',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `ship` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único para cada nave',
  `id_player` INT(11) NOT NULL COMMENT 'Id del jugador dueño de la nave o null si la vende un NPC',
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que vende la nave o null si es de un jugador',
  `original_name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre original de la nave',
  `name` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre actual de la nave',
  `id_type` INT(11) NOT NULL COMMENT 'Tipo de nave',
  `max_strength` INT(11) NOT NULL COMMENT 'Puntos máximos de integridad de la nave',
  `strength` INT(11) NOT NULL COMMENT 'Puntos de integridad actuales de la nave',
  `endurance` INT(11) NOT NULL COMMENT 'Puntos de resistencia',
  `shield` INT(11) NULL COMMENT 'Puntos de escudo',
  `id_engine` INT(11) NOT NULL COMMENT 'Tipo de motor que lleva la nave',
  `speed` INT(11) NOT NULL COMMENT 'Velocidad base de la nave',
  `max_cargo` INT(11) NOT NULL COMMENT 'Capacidad total de carga de la nave',
  `cargo` INT(11) NOT NULL COMMENT 'Capacidad inicial de carga de la nave',
  `damage` INT(11) NULL COMMENT 'Daño total que puede hacer la nave',
  `id_generator` INT(11) NOT NULL COMMENT 'Tipo de generador de energía',
  `max_energy` INT(11) NOT NULL COMMENT 'Puntos de energía totales de la nave',
  `energy` INT(11) NOT NULL COMMENT 'Puntos de energía iniciales de la nave',
  `slots` INT(11) NOT NULL COMMENT 'Número de huecos disponibles para módulos',
  `crew` INT(11) NULL COMMENT 'Número de tripulantes que se pueden llevar en la nave',
  `credits` INT(11) NULL COMMENT 'Cantidad de créditos que cuesta la nave',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `module` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada módulo',
  `id_player` INT(11) NOT NULL COMMENT 'Id del jugador dueño del módulo o null si lo vende un NPC',
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que vende el módulo o null si es de un jugador',
  `id_ship` INT(11) NOT NULL COMMENT 'Id de la nave en la que está equipado o null si no está equipado',
  `name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre descriptivo del módulo',
  `id_type` INT(11) NOT NULL COMMENT 'Tipo de módulo',
  `engine` FLOAT NULL COMMENT 'Multiplicador de velocidad en caso de ser un módulo de motor',
  `shield` INT(11) NULL COMMENT 'Puntos de escudo que aumenta el módulo en caso de ser un módulo de escudo',
  `cargo` INT(11) NULL COMMENT 'Capacidad de carga en caso de ser un módulo de carga',
  `damage` INT(11) NULL COMMENT 'Puntos de daño en caso de ser un módulo de arma',
  `crew` INT(11) NULL COMMENT 'Cantidad de tripulantes en caso de ser un módulo de cabinas',
  `energy` INT(11) NOT NULL COMMENT 'Puntos de energía que consume el módulo o produce en caso de ser un módulo de energía',
  `slots` INT(11) NOT NULL COMMENT 'Número de huecos que ocupa el módulo en la nave',
  `credits` INT(11) NULL COMMENT 'Cantidad de créditos que cuesta el módulo',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `resource` (
  `id_planet` INT(11) NOT NULL COMMENT 'Id del planeta que contiene el recurso',
  `id_moon` INT(11) NOT NULL COMMENT 'Id de la luna que contiene el recurso',
  `type` INT(11) NOT NULL COMMENT 'Tipo de recurso',
  `value` INT(11) NOT NULL COMMENT 'Cantidad del recurso',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_planet`,`id_moon`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `npc_resource` (
  `id_npc` INT(11) NOT NULL COMMENT 'Id del NPC que tiene un recurso a la venta',
  `type` INT(11) NOT NULL COMMENT 'Tipo de recurso',
  `start_value` INT(11) NOT NULL COMMENT 'Cantidad inicial del recurso que vende',
  `value` INT(11) NOT NULL COMMENT 'Cantidad del recurso que le queda disponible',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_npc`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `connection`
  ADD KEY `fk_connection_system_idx` (`id_system_start`),
  ADD KEY `fk_connection_system_idx` (`id_system_end`),
  ADD CONSTRAINT `fk_connection_system` FOREIGN KEY (`id_system_start`) REFERENCES `system` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_connection_system` FOREIGN KEY (`id_system_end`) REFERENCES `system` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `ship_crew`
  ADD KEY `fk_ship_crew_ship_idx` (`id_ship`),
  ADD KEY `fk_ship_crew_crew_idx` (`id_crew`),
  ADD CONSTRAINT `fk_ship_crew_ship` FOREIGN KEY (`id_ship`) REFERENCES `ship` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ship_crew_crew` FOREIGN KEY (`id_crew`) REFERENCES `crew` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `npc_ship`
  ADD KEY `fk_npc_ship_npc_idx` (`id_npc`),
  ADD KEY `fk_npc_ship_ship_idx` (`id_ship`),
  ADD CONSTRAINT `fk_npc_ship_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_npc_ship_ship` FOREIGN KEY (`id_ship`) REFERENCES `ship` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `system`
  ADD KEY `fk_system_player_idx` (`id_player`),
  ADD CONSTRAINT `fk_system_player` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `message`
  ADD KEY `fk_message_player_idx` (`id_player_from`),
  ADD KEY `fk_message_player_idx` (`id_player_to`),
  ADD CONSTRAINT `fk_message_player` FOREIGN KEY (`id_player_from`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_message_player` FOREIGN KEY (`id_player_to`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `planet`
  ADD KEY `fk_planet_system_idx` (`id_system`),
  ADD KEY `fk_planet_npc_idx` (`id_npc`),
  ADD KEY `fk_planet_construction_idx` (`id_construction`),
  ADD CONSTRAINT `fk_planet_system` FOREIGN KEY (`id_system`) REFERENCES `system` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_planet_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_planet_construction` FOREIGN KEY (`id_construction`) REFERENCES `construction` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `npc_module`
  ADD KEY `fk_npc_module_npc_idx` (`id_npc`),
  ADD KEY `fk_npc_module_module_idx` (`id_module`),
  ADD CONSTRAINT `fk_npc_module_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_npc_module_module` FOREIGN KEY (`id_module`) REFERENCES `module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `construction`
  ADD KEY `fk_construction_player_idx` (`id_player`),
  ADD CONSTRAINT `fk_construction_player` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `player`
  ADD KEY `fk_player_ship_idx` (`id_ship`),
  ADD KEY `fk_player_system_idx` (`id_system`),
  ADD KEY `fk_player_job_idx` (`id_job`),
  ADD CONSTRAINT `fk_player_ship` FOREIGN KEY (`id_ship`) REFERENCES `ship` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_system` FOREIGN KEY (`id_system`) REFERENCES `system` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_job` FOREIGN KEY (`id_job`) REFERENCES `job` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `job`
  ADD KEY `fk_job_player_idx` (`id_player`),
  ADD CONSTRAINT `fk_job_player` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `moon`
  ADD KEY `fk_moon_planet_idx` (`id_planet`),
  ADD KEY `fk_moon_npc_idx` (`id_npc`),
  ADD KEY `fk_moon_construction_idx` (`id_construction`),
  ADD CONSTRAINT `fk_moon_planet` FOREIGN KEY (`id_planet`) REFERENCES `planet` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_moon_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_moon_construction` FOREIGN KEY (`id_construction`) REFERENCES `construction` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `crew`
  ADD KEY `fk_crew_player_idx` (`id_player`),
  ADD CONSTRAINT `fk_crew_player` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `ship`
  ADD KEY `fk_ship_player_idx` (`id_player`),
  ADD KEY `fk_ship_npc_idx` (`id_npc`),
  ADD CONSTRAINT `fk_ship_player` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ship_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `module`
  ADD KEY `fk_module_player_idx` (`id_player`),
  ADD KEY `fk_module_npc_idx` (`id_npc`),
  ADD KEY `fk_module_ship_idx` (`id_ship`),
  ADD CONSTRAINT `fk_module_player` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_module_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_module_ship` FOREIGN KEY (`id_ship`) REFERENCES `ship` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `resource`
  ADD KEY `fk_resource_planet_idx` (`id_planet`),
  ADD KEY `fk_resource_moon_idx` (`id_moon`),
  ADD CONSTRAINT `fk_resource_planet` FOREIGN KEY (`id_planet`) REFERENCES `planet` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_resource_moon` FOREIGN KEY (`id_moon`) REFERENCES `moon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `npc_resource`
  ADD KEY `fk_npc_resource_npc_idx` (`id_npc`),
  ADD CONSTRAINT `fk_npc_resource_npc` FOREIGN KEY (`id_npc`) REFERENCES `npc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
