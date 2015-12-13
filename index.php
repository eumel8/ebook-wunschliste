<?php

setlocale (LC_ALL, 'de_DE');

require 'author.php';


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

<!DOCTYPE html>
<!--
Template Name: Opportunity
Author: <a href="http://www.os-templates.com/">OS Templates</a>
Author URI: http://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: http://www.os-templates.com/template-terms
-->
<html lang="de">
<head>
<title>#ebook wunschliste</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="layout/styles/layout.css" type="text/css" />
<link rel="stylesheet" href="ajax/jquery.ajax-combobox.css">
</head>
<body id="top">
<div class="wrapper col1">
  <div id="topbar">
  </div>
</div>
<div class="wrapper col2">
  <div id="header">
    <div id="logo">
      <h1><a href="index.php">ebook wunschliste</a></h1>
      <p>ein freier Webservice f&uuml;r similare Suche von Ebooks</p>
    </div>
    <div id="topnav">
    <div id="search">
      <form action='<?php htmlspecialchars($_SERVER["SCRIPT_NAME"]) ?>' method='get' accept-charset='UTF-8'>
        <fieldset>
          <legend for="keywords">ebook-search</legend>
          <input type="submit" name="go" id="go" value="GO" onclick="history.go(0)">
          <input type="text" name="keywords" value="<?php echo $Keywords ?>"  size="56" autofocus="autofocus" id="keywords">
        </fieldset>
      </form>

                <!-- JavaScript -->
                <script src="//code.jquery.com/jquery.min.js"></script>
                <script src="ajax/jquery.ajax-combobox.js"></script>
                <script>
                        $('#keywords').ajaxComboBox(
                                'ajax/jquery.ajax-combobox.php',
                                {
                                        button_img: 'ajax/btn.png',
                                        db_table: 'authors',
					field: 'author',
                                        lang: 'de'
                                }
                        );
                </script>

    </div>
    </div>

    <br class="clear" />
  </div>
</div>
<?php

  if ($_GET) {
    if (isset($_GET['keywords']))  {$Keywords = filter_var($_GET['keywords'],FILTER_SANITIZE_STRING) ;}
    if (isset($_GET['ItemPage']))  {$ItemPage = filter_var($_GET['ItemPage'],FILTER_SANITIZE_NUMBER_INT);}
    if (isset($_GET['ItemId']))    {$ItemId = filter_var($_GET['ItemId'],FILTER_SANITIZE_STRING);}
    if (isset($_GET['Operation'])) {$Operation = filter_var($_GET['Operation'],FILTER_SANITIZE_STRING);}
    if (isset($_GET['ResponseGroup'])) {$ResponseGroup = filter_var($_GET['ResponseGroup'],FILTER_SANITIZE_STRING);}

    if (isset($_COOKIE["Session"])) {
      $session = $_COOKIE["Session"];
    } else {
      $skey  = "S".md5(microtime().$_SERVER['REMOTE_ADDR']);
      setcookie('Session',$skey,time()+ (10 * 365 * 24 * 60 * 60));
      $session = $skey;
    }

    $Keyword = explode(',',$Keywords);

    foreach($Keyword as $key) {    
      $key = preg_replace("/^ /", "", $key);
      $key = preg_replace("/$ /", "", $key);
    AuthorKeys::author_save($key,$session);

    }

// Rueckschau

    if (preg_match("/^[[:print:]]{3,}+$/", $Keywords)) {
      setcookie("Keywords",$Keywords,time()+ (10 * 365 * 24 * 60 * 60));
    }

    if (preg_match('/,/',$Keywords)) {
      $Keywords = preg_replace("/^/", "\"", $Keywords);
      $Keywords = preg_replace("/$/", "\"", $Keywords);
      $Keywords = preg_replace("/,/", "\"|\"", $Keywords);
    }

    $srequest = "GET\necs.amazonaws.de\n/onca/xml\n";
    $url      = "http://ecs.amazonaws.de/onca/xml?";

    if (isset($ItemId)) {
      $request = "AWSAccessKeyId=".$AWSAccessKeyId."&AssociateTag=".$AssociateTag."&IdType=ASIN&ItemId=".$ItemId."&Operation=ItemLookup&ResponseGroup=".rawurlencode($ResponseGroup)."&Service=AWSECommerceService&Timestamp=".$Timestamp."&Version=".$Version;
    } else {
      $request = "AWSAccessKeyId=".$AWSAccessKeyId."&AssociateTag=".$AssociateTag."&ItemPage=".$ItemPage."&Keywords=".rawurlencode($Keywords)."&Operation=".$Operation."&ResponseGroup=".rawurlencode($ResponseGroup)."&SearchIndex=".$SearchIndex."&Service=AWSECommerceService&Timestamp=".$Timestamp."&Version=".$Version;
    }

    $url      .= $request;
    $srequest .= $request;
    $signature = rawurlencode(base64_encode(hash_hmac('sha256', $srequest, $AWSSecretKey, true)));
    $url      .= "&Signature=".$signature;

// echo "<a href=\"".$url."\">url</a>\n";

    $response = file_get_contents($url);
    $result   = simplexml_load_string($response); 

    ?>
      <div class="wrapper col4">
      <div id="container">
      <div id="content">
    <?php foreach ($result->Items->Item as $item) { ?>
	<div id="latestnews">
	<ul>
	    <li>
	    <?php
   	      if($item->MediumImage->URL){
		echo ("<div class=\"imgholder\"><a href=\"".$item->DetailPageURL."\" target=\"_blank\"><img src=\"".$item->MediumImage->URL."\" border=\"0\" alt=\"Cover\"></a></div>\n");
	      }

	      echo "<div class=\"latestnews\">";
	      echo ("<h3><a href=\"".$item->DetailPageURL."\"target=\"_blank\" >".htmlspecialchars((strlen($item->ItemAttributes->Title) > 75) ? substr($item->ItemAttributes->Title,0,72).'...' : $item->ItemAttributes->Title)."</a></h3><br>\n");

	      if($item->ItemAttributes->Author){
	        echo ("Autor: <a href=\"".$_SERVER["SCRIPT_NAME"]."?keywords=".$item->ItemAttributes->Author." \">".htmlspecialchars($item->ItemAttributes->Author)."</a>\n");

	      }

	      if($item->ItemAttributes->Publisher){
	        echo ("<br>Verlag: ".htmlspecialchars($item->ItemAttributes->Publisher)."\n");
	      }

	      if($item->ItemAttributes->PublicationDate){
	        echo ("<br>erschienen: ".htmlspecialchars($item->ItemAttributes->PublicationDate)."\n");
	      } 

	      // if($item->OfferSummary->LowestNewPrice->FormattedPrice){
	      if($item->OfferSummary->LowestNewPrice){
	        echo ("<br>Preis: ".htmlspecialchars($item->OfferSummary->LowestNewPrice->FormattedPrice)."\n");
	      }

	      // if($item->OfferSummary->LowestUsedPrice->FormattedPrice){
	      if($item->OfferSummary->LowestUsedPrice){
	        echo (" / erh&auml;ltlich ab: ".htmlspecialchars($item->OfferSummary->LowestUsedPrice->FormattedPrice)."\n");
	      }

	      if($item->DetailPageURL) {
	        echo ("<p class=\"readmore\"><a href=\"".$item->DetailPageURL."\" target=\"_blank\" >mehr Details</a></p>\n");
	      }

	      if($item->ASIN){
	        echo   "<p class=\"readmore\"><a href=\"".htmlspecialchars("?".
				          "SearchIndex"."=". $SearchIndex ."&".
					     "ItemPage"   ."=".$ItemPage  ."&".
					          "Operation=ItemLookup&" .
					    "ResponseGroup=Similarities&" .
					        "ItemId"."=".$item->ASIN).
				    "\">Similare Suche: $item->ASIN</a></p>\n";
	      }

	      if($item->SimilarProducts){
	        echo "<p>";
		echo "Similar: <ul>";
		foreach ($item->SimilarProducts->SimilarProduct as $similarproduct) {
		    echo "<li><a href=\"".$_SERVER["SCRIPT_NAME"]."?".
				    "ItemId"  ."=".$similarproduct->ASIN."&".
					    "Operation=ItemLookup&" .
					    "ResponseGroup=Images,ItemAttributes" .
                       			    "\">".$similarproduct->Title."</a></li>";

     		}
     		  echo "</ul>";
     		  echo "</p>";
   	      }

?>
          </li>
       </ul>
     </div>
<?php
}
?>
     </div>
   </div>
</div>
<div id="container">
  <div id="content">
<?php

foreach ($result->Items->Request as $found) {
  echo ("<h3>Page ".$found->ItemSearchRequest->ItemPage."</h3>\n");
  $SearchIndex = ($found->ItemSearchRequest->SearchIndex);
  $Keywords    = ($found->ItemSearchRequest->Keywords);
}


foreach ($result->Items as $results) {
  echo ("alle Ergebnisse: ".$results->TotalResults);
  echo (" auf ".$results->TotalPages. " Seiten");
}

$TotalPages  =  ($results->TotalPages);
$SearchIndex =  ($found->ItemSearchRequest->SearchIndex);
$Keywords    =  ($found->ItemSearchRequest->Keywords);
$ItemPage    =  ($found->ItemSearchRequest->ItemPage);
$Keywords    =  preg_replace("/[ ]/", "+", $Keywords);

if ($TotalPages > 1) {
  if ($ItemPage*1 <= $TotalPages) {
     echo   "<p class=\"readmore\">";
     echo   "<a class=\"fl_right\" href=\"".htmlspecialchars("?".
            "SearchIndex"."=". $SearchIndex ."&".
            "Keywords"   ."=". $Keywords    ."&".
            "ItemPage"    ."=".($ItemPage +1)).
            "\">n&auml;chste Seite</a></p>\n";
  }
}
?>
  <div id="coloumn">
    <a href="<?php echo $_SERVER["SCRIPT_NAME"];?>">Neue Suche</a>
    <br class="clear" />
  </div>
</div>
</div>
<?php

}
?>

<div class="wrapper col5">
  <div id="footer">
    <div class="footbox ">
      <h2>B&uuml;cher Shops</h2>
      <ul>
        <li><a href="http://www.amazon.de">http://www.amazon.de</a></li>
        <li><a href="http://www.thalia.de">http://www.thalia.de</a></li>
        <li><a href="http://www.hugendubel.de">http://www.hugendubel.de</a></li>
        <li class="last"><a href="http://www.buch.de">http://www.buch.de</a></li>
      </ul>
    </div>
    <div class="footbox ">
      <h2>B&uuml;cher Shops</h2>
      <ul>
        <li><a href="http://www.ebooks.de">http://www.ebooks.de</a></li>
        <li><a href="http://www.neobooks.de">http://www.neobooks.de</a></li>
        <li><a href="http://www.buecher.de">http://www.buecher.de</a></li>
        <li class="last"><a href="http://www.xtme.de/tolino-gratis-ebooks-und-schnappchen/">http://www.xtme.de/tolino-gratis-ebooks-und-schnappchen/</a></li>
      </ul>
    </div>
    <div class="footbox ">
      <h2>Weitere Dienste</h2>
      <ul>
        <li><a href="/todo/index.html">Mein B&uuml;cherbord</a></li>
      </ul>
    &nbsp;
    </div>
    <div class="footbox last">
    <div class="scroll">
    <strong>R&uuml;ckschau:</strong><br />
    <?php

    $authors = AuthorKeys::author_get($session);
    $authors = array_reverse($authors);
    foreach($authors as $author) {    
      echo "<a href=\"".$_SERVER["SCRIPT_NAME"]."?keywords=".$author."\">".$author."</a><br />";
    }
    ?>
    </div>
    </div>
    <br class="clear" />
  </div>
</div>
<div class="wrapper col6">
  <div id="copyright">
    <p class="fl_left">Copyright &copy; 2015 - All Rights Reserved - <a href="#">ebook.eumel.de</a></p>
    <p class="fl_right">Template by <a target="_blank" href="http://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
    <br class="clear" />
  </div>
</div>
</body>
</html
