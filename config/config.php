<?php 

    define("secret_key", "xxxxxxx");
    define("issuer_claim","THE_ISSUERMYSELF");
    define("audience_claim", "THE_AUDIENCE");
    define("issuedat_claim", time());
    define("notbefore_claim", issuedat_claim + 10);
    define("expire_claim", issuedat_claim + 25000);

?>