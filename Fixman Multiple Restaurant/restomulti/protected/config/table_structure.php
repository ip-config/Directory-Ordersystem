<?php
$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."admin_user (
  `admin_id` int(14) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,  
  `user_lang` int(14) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `lost_password_code` varchar(255) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `last_login` datetime NOT NULL,
  `user_access` text NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."category (
  `cat_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `sequence` int(14) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `date_modified` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `spicydish` int(2) NOT NULL DEFAULT '1',
  `spicydish_notes` text NOT NULL,
  `dish` text NOT NULL,
  `category_name_trans` text NOT NULL,
  `category_description_trans` text NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."client (
  `client_id` int(14) NOT NULL AUTO_INCREMENT,
  `social_strategy` varchar(100) NOT NULL DEFAULT 'web',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(100) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `location_name` text NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `lost_password_token` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `token` varchar(255) NOT NULL,
  `mobile_verification_code` int(14) NOT NULL,
  `mobile_verification_date` datetime NOT NULL,
  `custom_field1` varchar(255) NOT NULL,
  `custom_field2` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `email_verification_code` varchar(14) NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."client_cc (
  `cc_id` int(14) NOT NULL AUTO_INCREMENT,
  `client_id` int(14) NOT NULL,
  `card_name` varchar(255) NOT NULL,
  `credit_card_number` varchar(20) NOT NULL,
  `expiration_month` varchar(5) NOT NULL,
  `expiration_yr` varchar(5) NOT NULL,
  `cvv` varchar(20) NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`cc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."cooking_ref (
  `cook_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `cooking_name` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'published',
  `ip_address` varchar(50) NOT NULL,
  `cooking_name_trans` text NOT NULL,
  PRIMARY KEY (`cook_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."cuisine (
  `cuisine_id` int(14) NOT NULL AUTO_INCREMENT,
  `cuisine_name` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `cuisine_name_trans` text NOT NULL,
  PRIMARY KEY (`cuisine_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."currency (
  `currency_code` varchar(3) NOT NULL,
  `currency_symbol` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."item (
  `item_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `category` text NOT NULL,
  `price` text NOT NULL,
  `addon_item` text NOT NULL,
  `cooking_ref` text NOT NULL,
  `discount` varchar(14) NOT NULL,
  `multi_option` text NOT NULL,
  `multi_option_value` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `is_featured` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `ingredients` text NOT NULL,
  `spicydish` int(2) NOT NULL DEFAULT '1',
  `two_flavors` int(2) NOT NULL,
  `two_flavors_position` text NOT NULL,
  `require_addon` text NOT NULL,
   `dish` text NOT NULL,
  `item_name_trans` text NOT NULL,
  `item_description_trans` text NOT NULL,
  `non_taxable` int(1) NOT NULL DEFAULT '1',
  `not_available` int(1) NOT NULL DEFAULT '1',
  `gallery_photo` text NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."merchant (
  `merchant_id` int(14) NOT NULL AUTO_INCREMENT,
  `restaurant_slug` varchar(255) NOT NULL,
  `restaurant_name` varchar(255)  NOT NULL,
  `restaurant_phone` varchar(100)  NOT NULL,
  `contact_name` varchar(255)  NOT NULL,
  `contact_phone` varchar(100)  NOT NULL,
  `contact_email` varchar(255)  NOT NULL,
  `country_code` varchar(3)  NOT NULL,
  `street` text  NOT NULL,
  `city` varchar(255)  NOT NULL,
  `state` varchar(255) NOT NULL,
  `post_code` varchar(100)  NOT NULL,
  `cuisine` text  NOT NULL,
  `service` varchar(255)  NOT NULL,
  `free_delivery` int(1) NOT NULL DEFAULT '2',
  `delivery_estimation` varchar(100) NOT NULL,
  `username` varchar(100)  NOT NULL,
  `password` varchar(100)  NOT NULL,
  `activation_key` varchar(50) NOT NULL,
  `activation_token` varchar(255) NOT NULL,
  `status` varchar(100)  NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_activated` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(50)  NOT NULL,
  `package_id` int(14) NOT NULL,
  `package_price` float(14,5) NOT NULL,
  `membership_expired` date NOT NULL,
  `payment_steps` int(1) NOT NULL DEFAULT '1',
  `is_featured` int(1) NOT NULL DEFAULT '1',
  `is_ready` int(1) NOT NULL DEFAULT '1',
  `is_sponsored` int(2) NOT NULL DEFAULT '1',
  `sponsored_expiration` date NOT NULL,
  `lost_password_code` varchar(50) NOT NULL,
  `user_lang` int(14) NOT NULL,
  `membership_purchase_date` datetime NOT NULL,
  `sort_featured` int(14) NOT NULL,
  `is_commission` int(1) NOT NULL DEFAULT '1',
  `percent_commision` float(14,5) NOT NULL,
  `abn` varchar(255) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `commision_type` varchar(50) NOT NULL DEFAULT 'percentage',
  PRIMARY KEY (`merchant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."merchant_cc (
  `mt_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `card_name` varchar(255) NOT NULL,
  `credit_card_number` varchar(20) NOT NULL,
  `expiration_month` varchar(5) NOT NULL,
  `expiration_yr` varchar(5) NOT NULL,
  `cvv` varchar(20) NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`mt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."option (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `merchant_id` (`merchant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order (
  `order_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `json_details` text NOT NULL,
  `trans_type` varchar(100) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `sub_total` float(14,4) NOT NULL,
  `tax` float(14,4) NOT NULL,
  `taxable_total` decimal(14,4) NOT NULL,
  `total_w_tax` float(14,4) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `stats_id` int(14) NOT NULL,
  `viewed` int(1) NOT NULL DEFAULT '1',
  `delivery_charge` float(14,4) NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` varchar(100) NOT NULL,
  `delivery_asap` varchar(14) NOT NULL,
  `delivery_instruction` varchar(255) NOT NULL,
  `voucher_code` varchar(100) NOT NULL,
  `voucher_amount` float(14,4) NOT NULL,
  `voucher_type` varchar(100) NOT NULL,
  `cc_id` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `order_change` float(14,4) NOT NULL,
  `payment_provider_name` varchar(255) NOT NULL,
  `discounted_amount` float(14,5) NOT NULL,
  `discount_percentage` float(14,5) NOT NULL,
  `percent_commision` float(14,4) NOT NULL,
  `total_commission` float(14,4) NOT NULL,
  `commision_ontop` int(2) NOT NULL DEFAULT '2',
  `merchant_earnings` float(14,4) NOT NULL,
  `packaging` float(14,4) NOT NULL,
  `cart_tip_percentage` float(14,4) NOT NULL,
  `cart_tip_value` float(14,4) NOT NULL,
  `card_fee` float(14,4) NOT NULL,  
  `donot_apply_tax_delivery` int(1) NOT NULL DEFAULT '1',
  `order_locked` int(1) NOT NULL DEFAULT '1',
  `request_from` varchar(10) NOT NULL DEFAULT 'web',
  `mobile_cart_details` text NOT NULL,
   PRIMARY KEY (`order_id`),
   KEY `merchant_id` (`merchant_id`),
   KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";


$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_details (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `item_id` int(14) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `order_notes` text NOT NULL,
  `normal_price` float(14,4) NOT NULL,
  `discounted_price` float(14,4) NOT NULL,
  `size` varchar(255) NOT NULL,
  `qty` int(14) NOT NULL,
  `cooking_ref` varchar(255) NOT NULL,
  `addon` text NOT NULL,
  `ingredients` text NOT NULL,
  `non_taxable` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_status (
  `stats_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `description` varchar(200) NOT NULL,
  `date_created` date NOT NULL,
  `date_modified` date NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`stats_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."packages (
  `package_id` int(14) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float(14,4) NOT NULL,
  `promo_price` float(14,4) NOT NULL,
  `expiration` int(14) NOT NULL,
  `expiration_type` varchar(50) NOT NULL DEFAULT 'days',
  `unlimited_post` int(1) NOT NULL DEFAULT '2',
  `post_limit` int(14) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `sell_limit` int(14) NOT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."package_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `package_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL,
  `price` float(14,4) NOT NULL,
  `payment_type` varchar(100)  NOT NULL,
  `mt_id` int(14) NOT NULL,
  `TOKEN` varchar(255) NOT NULL,
  `membership_expired` date NOT NULL,
  `TRANSACTIONID` varchar(255) NOT NULL,
  `TRANSACTIONTYPE` varchar(100) NOT NULL,
  `PAYMENTSTATUS` varchar(100) NOT NULL,
  `PAYPALFULLRESPONSE` text NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50)  NOT NULL,
  `fee` float(14,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."paypal_checkout (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `token` varchar(255) NOT NULL,
  `paypal_request` text NOT NULL,
  `paypal_response` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'checkout',
  `date_created` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."paypal_payment (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `TOKEN` varchar(255) NOT NULL,
  `TRANSACTIONID` varchar(100) NOT NULL,
  `TRANSACTIONTYPE` varchar(100) NOT NULL,
  `PAYMENTTYPE` varchar(100) NOT NULL,
  `ORDERTIME` varchar(100) NOT NULL,
  `AMT` varchar(14) NOT NULL,
  `FEEAMT` varchar(14) NOT NULL,
  `TAXAMT` varchar(14) NOT NULL,
  `CURRENCYCODE` varchar(3) NOT NULL,
  `PAYMENTSTATUS` varchar(100) NOT NULL,
  `CORRELATIONID` varchar(100) NOT NULL,
  `TIMESTAMP` varchar(100) NOT NULL,
  `json_details` text NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."rating (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `ratings` float(14,1) NOT NULL,
  `client_id` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50)  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."rating_meaning (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `rating_start` float(14,1) NOT NULL,
  `rating_end` float(14,1) NOT NULL,
  `meaning` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."review (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `review` text NOT NULL,
  `rating` float(14,1) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'publish',
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `order_id` varchar(14) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."size (
  `size_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `size_name` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'published',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `size_name_trans` text NOT NULL,
  PRIMARY KEY (`size_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";



$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."subcategory (
  `subcat_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `subcategory_description` text NOT NULL,
  `discount` varchar(20) NOT NULL,
  `sequence` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'publish',
  `subcategory_name_trans` text NOT NULL,
  `subcategory_description_trans` text NOT NULL,
  PRIMARY KEY (`subcat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."subcategory_item (
  `sub_item_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `sub_item_name` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` varchar(15) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `sub_item_name_trans` text NOT NULL,
  `item_description_trans` text NOT NULL,
  PRIMARY KEY (`sub_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."custom_page (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `slug_name` varchar(255) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `icons` varchar(255) NOT NULL,
  `assign_to` varchar(50) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `open_new_tab` int(11) NOT NULL DEFAULT '1',
  `is_custom_link` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."languages (
  `lang_id` int(14) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(14) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `source_text` text NOT NULL,
  `is_assign` int(1) NOT NULL DEFAULT '2',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."stripe_logs (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `json_result` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";


$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."payment_order (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(10) CHARACTER SET utf8 NOT NULL,
  `payment_reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `order_id` int(14) NOT NULL,
  `raw_response` text CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."sms_broadcast (
  `broadcast_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `send_to` int(14) NOT NULL,
  `list_mobile_number` text CHARACTER SET utf8 NOT NULL,
  `sms_alert_message` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 AUTO_INCREMENT=1;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."sms_broadcast_details (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `broadcast_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_phone` varchar(50) NOT NULL,
  `sms_message` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `gateway_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_executed` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `gateway` varchar(255) NOT NULL DEFAULT 'twilio',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."sms_package (
  `sms_package_id` int(14) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float(14,4) NOT NULL,
  `promo_price` float(14,4) NOT NULL,
  `sms_limit` int(14) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`sms_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."sms_package_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `sms_package_id` int(14) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `package_price` float(14,4) NOT NULL,
  `sms_limit` int(14) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) NOT NULL,
  `payment_gateway_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."merchant_user (
  `merchant_user_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_access` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `last_login` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  PRIMARY KEY (`merchant_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."voucher (
  `voucher_id` int(14) NOT NULL AUTO_INCREMENT,
  `voucher_name` varchar(255) NOT NULL,
  `merchant_id` int(14) NOT NULL,
  `number_of_voucher` int(14) NOT NULL,
  `amount` float NOT NULL,
  `voucher_type` varchar(100) NOT NULL DEFAULT 'fixed amount',
  `status` varchar(100) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`voucher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."voucher_list (
  `voucher_id` int(14) NOT NULL,
  `voucher_code` varchar(50) NOT NULL,
  `status` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'unused',
  `client_id` int(14) NOT NULL,
  `date_used` varchar(50) NOT NULL,
  `order_id` int(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."bookingtable (
  `booking_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `number_guest` int(14) NOT NULL,
  `date_booking` date NOT NULL,
  `booking_time` varchar(50) NOT NULL,
  `booking_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `booking_notes` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `viewed` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."bank_deposit (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `branch_code` varchar(100) NOT NULL,
  `date_of_deposit` date NOT NULL,
  `time_of_deposit` varchar(50) NOT NULL,
  `amount` float(14,4) NOT NULL,
  `scanphoto` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` date NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `transaction_type` varchar(255) NOT NULL DEFAULT 'merchant_signup',
  `client_id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."payment_provider (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `payment_name` varchar(255) NOT NULL,
  `payment_logo` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'publish',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."offers (
  `offers_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `offer_percentage` float(14,4) NOT NULL,
  `offer_price` float(14,4) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`offers_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."newsletter (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."barclay_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `transaction_type` varchar(255) NOT NULL DEFAULT 'signup',
  `date_created` date NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `param1` varchar(255) NOT NULL,
  `param2` text NOT NULL,
  `param3` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."ingredients (
  `ingredients_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `ingredients_name` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'published',
  `ip_address` varchar(50) NOT NULL,
  `ingredients_name_trans` text NOT NULL,
  PRIMARY KEY (`ingredients_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."withdrawal (
  `withdrawal_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `amount` float(14,4) NOT NULL,
  `current_balance` float(14,4) NOT NULL,
  `balance` float(14,4) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `account` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `swift_code` varchar(100) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `bank_country` varchar(50) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `viewed` int(2) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL,
  `date_to_process` date NOT NULL,
  `date_process` datetime NOT NULL,
  `api_raw_response` text NOT NULL,
  `withdrawal_token` text NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `bank_type` varchar(255) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`withdrawal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."fax_package (
  `fax_package_id` int(14) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float(14,4) NOT NULL,
  `promo_price` float(14,4) NOT NULL,
  `fax_limit` int(14) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`fax_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."fax_package_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `fax_package_id` int(14) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `package_price` float(14,4) NOT NULL,
  `fax_limit` int(14) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) NOT NULL,
  `payment_gateway_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."fax_broadcast (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `faxno` varchar(50) NOT NULL,
  `recipname` varchar(32) NOT NULL,
  `faxurl` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `jobid` varchar(255) NOT NULL,
  `api_raw_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_process` datetime NOT NULL,
  `date_postback` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."shipping_rate (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `distance_from` int(14) NOT NULL,
  `distance_to` int(14) NOT NULL,
  `shipping_units` varchar(5) NOT NULL,
  `distance_price` float(14,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_delivery_address (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `contact_phone` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."dishes (
  `dish_id` int(14) NOT NULL AUTO_INCREMENT,
  `dish_name` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`dish_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."voucher_new (
  `voucher_id` int(14) NOT NULL AUTO_INCREMENT,
  `voucher_owner` varchar(255) NOT NULL DEFAULT 'merchant',
  `merchant_id` int(14) NOT NULL,
  `joining_merchant` text NOT NULL,
  `voucher_name` varchar(255) NOT NULL,
  `voucher_type` varchar(255) NOT NULL,
  `amount` float(14,4) NOT NULL,
  `expiration` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `used_once` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`voucher_id`),
  KEY `voucher_name` (`voucher_name`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."address_book (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `client_id` int(14) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `as_default` int(1) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_history (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_sms (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(50) NOT NULL,
  `code` int(4) NOT NULL,
  `session` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";

$tbl[]="
CREATE TABLE IF NOT EXISTS ".$table_prefix."zipcode (
  `zipcode_id` int(14) NOT NULL AUTO_INCREMENT,
  `zipcode` varchar(255) NOT NULL,
  `country_code` varchar(5) NOT NULL,
  `city` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `stree_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`zipcode_id`),
  KEY `country_code` (`country_code`),
  KEY `area` (`area`),
  KEY `stree_name` (`stree_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

/*end table*/

$tbl[]="
create OR REPLACE VIEW ".$table_prefix."view_order_details as
select a.* ,

(
select merchant_id 	
from
".$table_prefix."order
where
order_id=a.order_id
limit 0,1
) as merchant_id,

(
select status 	
from
".$table_prefix."order
where
order_id=a.order_id
limit 0,1
) as status,

(
select date_created 	
from
".$table_prefix."order
where
order_id=a.order_id
limit 0,1
) as date_created


from
".$table_prefix."order_details a
";


/*$tbl[]="
create OR REPLACE VIEW ".$table_prefix."view_merchant as
SELECT a.*,
(
select option_value
from
".$table_prefix."option
where
merchant_id=a.merchant_id
and
option_name='merchant_delivery_charges'
limit 0,1
) as delivery_charges,

(
select option_value
from
".$table_prefix."option
where
merchant_id=a.merchant_id
and
option_name='merchant_minimum_order'
limit 0,1
) as minimum_order,

(
select SUM(ratings)/COUNT(*) AS count
from
".$table_prefix."rating
where
merchant_id=a.merchant_id
) as ratings,

(
select option_value
from
".$table_prefix."option
where
merchant_id=a.merchant_id
and option_name='merchant_latitude'
limit 0,1
) as latitude,

(
select option_value
from
".$table_prefix."option
where
merchant_id=a.merchant_id
and option_name='merchant_longtitude'
limit 0,1
) as lontitude
	   	   

FROM
".$table_prefix."merchant a

";*/

$tbl[]="
create OR REPLACE VIEW ".$table_prefix."view_ratings as
select 
merchant_id,
SUM(ratings)/COUNT(*) AS ratings
from
".$table_prefix."rating
group by merchant_id
";

$tbl[]="
create OR REPLACE VIEW ".$table_prefix."view_merchant as

SELECT a.*,
b.option_value as latitude,
c.option_value as lontitude,
d.option_value as delivery_charges,
e.option_value as minimum_order,
f.ratings

FROM
".$table_prefix."merchant a
left join ".$table_prefix."option b
ON 
a.merchant_id =b.merchant_id 	
and b.option_name='merchant_latitude'

left join ".$table_prefix."option c
ON 
a.merchant_id =c.merchant_id 	
and c.option_name='merchant_longtitude'

left join ".$table_prefix."option d
ON 
a.merchant_id =d.merchant_id 	
and d.option_name='merchant_delivery_charges'

left join ".$table_prefix."option e
ON 
a.merchant_id =e.merchant_id 	
and e.option_name='merchant_minimum_order'

left join ".$table_prefix."view_ratings f
ON 
a.merchant_id =f.merchant_id 	
";

/*CURRENCY*/

$curr_data[]=array(
 'currency_code'=>"AUD",
 'currency_symbol'=>"&#36;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"CAD",
 'currency_symbol'=>"&#36;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"CNY",
 'currency_symbol'=>"&yen;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"EUR",
 'currency_symbol'=>"&euro;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"HKD",
 'currency_symbol'=>"&#36;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"JPY",
 'currency_symbol'=>"&yen;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"MXN",
 'currency_symbol'=>"&#36;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"MYR",
 'currency_symbol'=>"&#82;&#77;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"NZD",
 'currency_symbol'=>"&#36;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$curr_data[]=array(
 'currency_code'=>"USD",
 'currency_symbol'=>"&#36;",
 'date_created'=>date('c'),
 'ip_address'=>$_SERVER['REMOTE_ADDR']
);

/*CUISINE*/
$cuisine[]=array(
  'cuisine_name'=>'American',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Deli',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Indian',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Mediterranean',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Sandwiches',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Barbeque',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Diner',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Italian',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Mexican',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Sushi',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Burgers',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Greek',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Japanese',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Middle Eastern',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Thai',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Chinese',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Healthy',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Korean',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Pizza',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$cuisine[]=array(
  'cuisine_name'=>'Vegetarian',
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);

/*RATING*/
$rating[]=array(
  'rating_start'=>"1.0",
  'rating_end'=>"1.9",
  'meaning'=>"poor",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$rating[]=array(
  'rating_start'=>"2.0",
  'rating_end'=>"2.9",
  'meaning'=>"good",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$rating[]=array(
  'rating_start'=>"3.0",
  'rating_end'=>"4.0",
  'meaning'=>"very good",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$rating[]=array(
  'rating_start'=>"4.1",
  'rating_end'=>"5.0",
  'meaning'=>"excellent",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);

/*ORDER STATUS*/
$order_stats[]=array(
  'description'=>"pending",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$order_stats[]=array(
  'description'=>"cancelled",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$order_stats[]=array(
  'description'=>"delivered",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$order_stats[]=array(
  'description'=>"paid",
  'date_created'=>date('c'),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);