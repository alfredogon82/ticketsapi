<?php 
    /* jwt configuration */
    
    define("SECRET_KEY", "xxxxxxx");
    define("ISSUER_CLAIM","THE_ISSUERMYSELF");
    define("AUDIENCE_CLAIM", "THE_AUDIENCE");
    define("ISSUEDAT_CLAIM", time());
    define("NOTBEFORE_CLAIM", issuedat_claim + 10);
    define("EXPIRE_CLAIM", issuedat_claim + 25000);

    /* database configuration */

    define("DB_HOST", "localhost");
    define("DB_PORT", "3306");
    define("DB_USER", "alfredo");
    define("DB_PASS", "1q23e4r5T");
    define("DB_NAME", "ticketsapi");
    define("DB_CHARSEt", "UTF-8");


?>