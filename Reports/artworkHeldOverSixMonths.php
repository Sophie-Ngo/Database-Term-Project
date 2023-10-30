<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Artwork Held Over 6 Months</title>
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

echo "<h2>Artwork Held Over Six Months</h2>";

$dateOfReport = mysql_query("SELECT DATE_FORMAT(CURRENT_DATE, '%m/%d/%Y') AS 'Date of Report'; ");
echo "Date of Report: " . mysql_fetch_row($dateOfReport)[0];

$SQL = 
"SELECT DISTINCT 

IF (Artwork.collectorsocialsecuritynumber IS NULL, 
(SELECT CONCAT(Artist.firstname, ' ', Artist.lastname) FROM Artist WHERE Artwork.artistid = Artist.artistid), 
(SELECT CONCAT(Collector.firstname, ' ', Collector.lastname) FROM Collector WHERE Collector.socialsecuritynumber = Artwork.collectorsocialsecuritynumber))
AS Owner,

IF (Artwork.collectorsocialsecuritynumber IS NULL, 
(SELECT CONCAT(Artist.areacode, ' ', Artist.telephonenumber) FROM Artist WHERE Artwork.artistid = Artist.artistid), 
(SELECT CONCAT(Collector.areacode, ' ', Collector.telephonenumber) FROM Collector WHERE Collector.socialsecuritynumber = Artwork.collectorsocialsecuritynumber)) 
AS 'Owner Telephone',

CONCAT(Artist.firstname, ' ', Artist.lastname) AS 'Artist Name',
worktitle AS 'Title',
datelisted AS 'Date Listed',
askingprice AS 'Asking Price'
FROM Artwork,
Collector,
Artist
WHERE  Artwork.artistid = Artist.artistid
AND datelisted <= Date_add(CURRENT_DATE, interval - 6 MONTH)
AND datereturned IS NULL
AND status = 'for sale' ";

$result = mysql_query($SQL, $linkID);
if (!$result) {
    echo " Could not successfully run query ($SQL) from DB: " . mysql_error();
    exit;
}

display_query_result($result);

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