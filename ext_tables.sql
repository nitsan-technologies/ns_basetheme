#
# Table structure for table 'tx_nsbasetheme_domain_model_apidata'
#
CREATE TABLE tx_nsbasetheme_domain_model_apidata (
   id int(11) NOT NULL auto_increment,
   extension_key varchar(255) DEFAULT '',
   right_sidebar_html text,
   premuim_extension_html text,
   support_html text,
   footer_html text,
   last_update date,

   PRIMARY KEY (id)
);