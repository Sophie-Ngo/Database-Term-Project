<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Works For Sale</title>
<link rel = "icon" href = "http://cs.furman.edu/~sngo/ChenKingNgo_term_project/imgsources/Bell-Tower-RGB.png" type = "image/x-icon">
  <meta name="generator" content="Google Web Designer 15.2.1.0306">
  <style id="gwd-text-style">
    p {
      font-family:'Trebuchet MS', sans-serif;
      margin-left:5%
    }
    label {
      font-family:'Trebuchet MS', sans-serif;
    }
    h1 {
      text-align: center;
      margin-bottom: 1em;
      margin-top: 2em;
      font-family: 'Trebuchet MS', sans-serif;"
    }
    h2 {
      font-family: 'Trebuchet MS', sans-serif;"
    } 
  </style>
  <style>
    html, body {
      width: 100%;
      height: 100%;
      margin: 0px;
    }
    body {
      background-color: transparent;
      transform: perspective(1400px) matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
      transform-style: preserve-3d;
    }
    body * {
      transform-style: preserve-3d;
    }
    footer {
      text-align: center;
      height:2em;
      margin-top:2.5%;
      bottom:0;
      width:100%;   
    }
    footer p {
      line-height:2em;
      color:black;
    }
    #header img {
      float: left;
      width: 10%;
      height: 10%;
      margin-top:-2em;
      margin-bottom:1em;
      margin-left:1em;
      margin-right:0em;
    }
    #header {
      margin-bottom:2.5%;
    }
    #nav {
      margin-bottom:1em;
    }
    #nav a {
      padding: 24px 26px;
    }
    a {
      font-family:'Trebuchet MS', sans-serif;
    }
    a:link {
      color: #201547;
      background-color: transparent;
      text-decoration: none;
    }
    a:hover {
      color: #582C83;
      background-color: transparent;
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div id="header">
    <img src="http://cs.furman.edu/~sngo/ChenKingNgo_term_project/imgsources/Diamond%20F-RGB.png" id="logo"/>
    <h1>Furman Art Gallery</h1>
    <div id="nav">
      <p style="text-align:right; margin-right:1em;">
        <a href="http://cs.furman.edu/~sngo/ChenKingNgo_term_project/homepage.html" rel="help">Index </a>
	<a href="http://cs.furman.edu/~sngo/ChenKingNgo_term_project/userdocs.html" rel="help" target="_blank">User Documentation</a>
      </p>
    </div>
    <hr style="color:#582C83; weight:2px" />  
  </div>
<br />
<div style="margin-left:5%;">
<?php
require('functions.php');
require('../credentials.php');

$linkID = mysql_connect($host ,$user, $pass);  

mysql_select_db($db, $linkID);		

echo "<h2>Works For Sale</h2>";

$dateOfReport = mysql_query("SELECT DATE_FORMAT(CURRENT_DATE, '%m/%d/%Y') AS 'Date of Report'; ");
echo "Date of Report: " . mysql_fetch_row($dateOfReport)[0];

$SQL = 
"SELECT worktitle                                            AS Title,
        Concat(Artist.firstname, ' ', Artist.lastname)       AS Artist,
        worktype                                             AS Type,
        workmedium                                           AS Medium,
        workstyle                                            AS Style,
        Concat(Collector.firstname, ' ', Collector.lastname) AS 'Owner\'s Name',
        askingprice                                          AS 'Asking Price',
        dateshown                                            AS 'Date Shown',
        datelisted                                           AS 'Date Listed'
FROM   Artwork, Collector, Artist
WHERE  Artist.artistid = Artwork.artistid
AND Artwork.collectorsocialsecuritynumber = Collector.socialsecuritynumber;";

$sumAskingPrices = "SELECT Sum(askingprice) AS 'Total Asking Prices' FROM Artwork; ";

$result = run_query($SQL, $linkID);
$sum = run_query($sumAskingPrices, $linkID);

display_query_result($result);
display_query_result($sum);

mysql_close($linkID);
?>
  </div>
  <footer>
    <hr style="color:#201547; weight:5px;">
    <p>
      Sophie Ngo, Ting Chen, Hanna King
    </p>
  </footer>  
</body>
</html>