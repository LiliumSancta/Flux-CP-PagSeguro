/*
ALTER TABLE `cp_donate`
  DROP COLUMN `payment_notification_code`,
  DROP COLUMN `payment_status`,
  MODIFY `account_id` int(11) NOT NULL,
  MODIFY `userid` varchar(23) NOT NULL,
  MODIFY `email` varchar(39) NOT NULL,
  MODIFY `payment_date` datetime NOT NULL,
  MODIFY `payment_id` varchar(50) NOT NULL,
  MODIFY `payment` float NOT NULL,
  MODIFY `payment_ip` varchar(35) NOT NULL,
  MODIFY `payment_type` varchar(23) NOT NULL DEFAULT 'PagSeguro',
  MODIFY `payment_code` varchar(50) NULL,
  CHANGE `payment_status_pagseguro` `payment_status` tinyint(3) NOT NULL DEFAULT '0';
*/

CREATE TABLE IF NOT EXISTS `cp_donate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `userid` varchar(23) NOT NULL,
  `email` varchar(39) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `payment` float NOT NULL,
  `payment_ip` varchar(35) NOT NULL,
  `payment_type` varchar(23) NOT NULL DEFAULT 'PagSeguro',
  `payment_code` varchar(50) NULL,
  `payment_status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;