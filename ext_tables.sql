CREATE TABLE tx_sms77typo3_domain_model_message
(
    uid      INT(11) UNSIGNED DEFAULT '0' NOT NULL auto_increment,
    pid      INT(11)          DEFAULT '0' NOT NULL,

    created  INT(11) UNSIGNED DEFAULT '0' NOT NULL,
    config   TEXT                         NOT NULL,
    response TEXT             DEFAULT NULL,
    type     VARCHAR(255)                 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

CREATE TABLE tx_sms77typo3_domain_model_lookup
(
    uid      INT(11) UNSIGNED DEFAULT '0' NOT NULL auto_increment,
    pid      INT(11)          DEFAULT '0' NOT NULL,

    created  INT(11) UNSIGNED DEFAULT '0' NOT NULL,
    config   TEXT                         NOT NULL,
    response TEXT             DEFAULT NULL,
    type     VARCHAR(255)                 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);