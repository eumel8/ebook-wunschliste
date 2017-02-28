<?php

$AWSAccessKeyId  = "XXXXXX";
$AWSSecretKey    = "XXXXXX";
$AssociateTag    = "XXXXXX";
$ItemPage        = 1;
$cookie          = isset($_COOKIE["Keywords"]) ? $_COOKIE["Keywords"] : "";
$Operation       = "ItemSearch";
$ResponseGroup   = "Medium";
$SearchIndex     = "Books";
$Service         = "AWSECommerceService";
$Timestamp       = rawurlencode(gmdate('Y-m-d\TH:i:s\Z'));
$Version         = "2013-08-01";
$Keywords        = isset($cookie) ? $cookie : rawurlencode("Frank Schaetzing");

?>
