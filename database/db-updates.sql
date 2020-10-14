INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('MSG_Money_added_to_wallet', 1, '<p>Your order has been successfully processed!</p><p>Please direct any questions you have to the <a href=\"{contact-us-page-url}\">web portal owner</a>.</p><p>Thanks for choosing us online!');


UPDATE `tbl_url_rewrites` SET `urlrewrite_custom` = 'teachers/profile/urlparameter' WHERE `tbl_url_rewrites`.`urlrewrite_custom` = 'teachers/urlparameter';