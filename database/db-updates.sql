replace INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('tpl_teacher_request_received', '1', 'New Teacher Request - Admin', 'New Teacher Request on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">    \r\n    <tbody>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:30px 0;\"></td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:0 0 0;\">                \r\n                <!--\r\n                header start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td> \r\n <td style=\"text-align:right;padding: 40px;\">{social_media_icons}\r\n </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                header end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page body start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\"><img src=\"icon-account.png\" alt=\"\" />         \r\n         <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">         </h5>         \r\n         <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\"> Teacher Request</h2>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\">         \r\n         <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\"> Dear Admin</h3>\r\n         <p>{name} wants to become a teacher on <a href=\"{website_url}\">{website_name}</a>.</p>         \r\n         <table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">             \r\n <tbody> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Refernce Number</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{refnum}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Name</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Phone<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Subjects</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{subjects}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Requested On</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{request_date}</td> \r\n     </tr>             \r\n </tbody>         \r\n         </table>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page body end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page footer start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"height:30px;\"></td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more\r\n         help?<br />\r\n      <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">Be sure to add\r\n         <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a>to your\r\n         address book or safe sender list so our emails get to your inbox.<br />\r\n      <br />\r\n      &copy; 2018, {website_name}. All Rights Reserved.\r\n     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0; height:50px;\"></td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page footer end here\r\n                -->\r\n </td>        \r\n        </tr>    \r\n    </tbody>\r\n</table>', '\'{refnum}\' => Request Reference Number\r\n\'{name}\' => Applicant name,\r\n\'{phone}\' => Phone Number,\r\n\'{request_date}\' => Requested On - Datetime,\r\n\'{subjects}\' => Subjects that the application can teach', '1');

replace INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('tpl_teacher_request_received', '2', 'New Teacher Request - Admin', 'New Teacher Request on {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#f5f5f5\" style=\"font-family:Arial; color:#333; line-height:26px;\">    \r\n    <tbody>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:30px 0;\"></td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td style=\"background:#e84c3d;padding:0 0 0;\">                \r\n                <!--\r\n                header start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff;border-bottom: 1px solid #eee;\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"padding:20px 40px;\"><a href=\"#\" style=\"display: block;\">{Company_Logo}</a></td> \r\n <td style=\"text-align:right;padding: 40px;\">{social_media_icons}\r\n </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                header end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page body start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\"><img src=\"icon-account.png\" alt=\"\" />         \r\n         <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\">         </h5>         \r\n         <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: #e84c3d;\"> Teacher Request</h2>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \"> \r\n <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 20px;\">         \r\n         <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;\"> Dear Admin</h3>\r\n         <p>{name} wants to become a teacher on <a href=\"{website_url}\">{website_name}</a>.</p>         \r\n         <table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">             \r\n <tbody> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Refernce Number</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{refnum}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Name</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Phone<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Subjects</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{subjects}</td> \r\n     </tr> \r\n     <tr>     \r\n         <td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">                         Requested On</td>     \r\n         <td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{request_date}</td> \r\n     </tr>             \r\n </tbody>         \r\n         </table>     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page body end here\r\n                -->\r\n </td>        \r\n        </tr>        \r\n        <tr>            \r\n            <td>                \r\n                <!--\r\n                page footer start here\r\n                -->\r\n \r\n                <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                    \r\n                    <tbody>                        \r\n                        <tr> \r\n <td style=\"height:30px;\"></td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more\r\n         help?<br />\r\n      <a href=\"{contact_us_url}\" style=\"color:#e84c3d;\">We‘re here, ready to talk</a></td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0 40px; color:#999;vertical-align:top; line-height:20px; text-align: center;\"> \r\n <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">     \r\n     <tbody> \r\n <tr>     \r\n     <td style=\"padding:20px 0 30px; font-size:13px; color:#999;\">Be sure to add\r\n         <a href=\"#\" style=\"color: #e84c3d\">{notifcation_email}</a>to your\r\n         address book or safe sender list so our emails get to your inbox.<br />\r\n      <br />\r\n      &copy; 2018, {website_name}. All Rights Reserved.\r\n     </td> \r\n </tr>     \r\n     </tbody> \r\n </table> </td>                        \r\n                        </tr>                        \r\n                        <tr> \r\n <td style=\"padding:0; height:50px;\"></td>                        \r\n                        </tr>                    \r\n                    </tbody>                \r\n                </table>                \r\n                <!--\r\n                page footer end here\r\n                -->\r\n </td>        \r\n        </tr>    \r\n    </tbody>\r\n</table>', '\'{refnum}\' => Request Reference Number\r\n\'{name}\' => Applicant name,\r\n\'{phone}\' => Phone Number,\r\n\'{request_date}\' => Requested On - Datetime,\r\n\'{subjects}\' => Subjects that the application can teach', '1');