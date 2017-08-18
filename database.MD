don't forget to put your info in the DatabaseProviding config class

create DB 

CREATE TABLE `event_stream` (
  `event_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `event_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` char(26) COLLATE utf8_unicode_ci NOT NULL,
  `aggregate_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `aggregate_type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `causation_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `causation_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `event_stream_m_v_uix` (`aggregate_id`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;