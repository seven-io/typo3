CREATE TABLE tx_sms77typo3_domain_model_message
(
    uid      int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
    pid      int(11)          DEFAULT '0' NOT NULL,

    created  int(11) unsigned DEFAULT '0' NOT NULL,
    config   TEXT                         NOT NULL,
    response TEXT             DEFAULT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

