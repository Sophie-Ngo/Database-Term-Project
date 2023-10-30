<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sales For Week</title>
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
    h3 {
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
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$linkID = mysqli_connect($host ,$user, $pass, $db); 

$weekEnding = $_GET["weekEnding"];

echo "<h2>Sales For Week</h2>";

$dateOfReport = mysqli_query($linkID, "SELECT DATE_FORMAT(CURRENT_DATE, '%m/%d/%Y') AS 'Date of Report'; ");
echo "Date of Report: " . mysqli_fetch_row($dateOfReport)[0];

echo nl2br("\nWeek ending " . $weekEnding); 

$Sales = mysqli_prepare($linkID, 
"SELECT Concat(sp.firstname, ' ', sp.lastname)  AS Salesperson,
        Concat(a.firstname, ' ', a.lastname)    AS Artist,
        w.worktitle AS Title,
        IF(
            collectorsocialsecuritynumber IS NULL, 
            Concat(a.firstname, ' ', a.lastname
        ),
        (SELECT Concat(firstname, ' ', lastname) FROM Collector
        WHERE  Collector.socialsecuritynumber = w.collectorsocialsecuritynumber)) AS Owner,
        b.buyerid                               AS Buyer,
        saledate                                AS 'Sale Date',
        saleprice                               AS 'Sale Price',
        s.saleprice * 0.1 * 0.5                 AS 'Commission'
FROM   Sale s, Salesperson sp, Artist a, Artwork w, Buyer b
WHERE  a.artistid = w.artistid
AND w.artworkid = s.artworkid
AND s.buyerid = b.buyerid
AND s.salespersonssn = sp.socialsecuritynumber
AND s.saledate BETWEEN Date_add(?, INTERVAL -1 week) AND
                       ?
GROUP  BY sp.firstname,
   sp.lastname,
   a.firstname,
   a.lastname,
   w.worktitle,
   b.buyerid,
   saledate,
   saleprice; ");
mysqli_stmt_bind_param($Sales, "ss", $weekEnding, $weekEnding);

$totalPriceComm = mysqli_prepare($linkID,
"SELECT Concat(Salesperson.firstname, ' ', Salesperson.lastname) AS Salesperson,
        Sum(saleprice)                                           AS 'Total Sale Price',
        Sum(Sale.saleprice * 0.1 * 0.5)                          AS 'Total Commission'
FROM   Sale, Salesperson
WHERE  salespersonssn = socialsecuritynumber
AND Sale.saledate BETWEEN Date_add(?, INTERVAL -1 week) AND
                          ?
GROUP  BY salespersonssn; ");
mysqli_stmt_bind_param($totalPriceComm, "ss", $weekEnding, $weekEnding);

$sumSales = mysqli_prepare($linkID,
"SELECT Sum(Sale.saleprice) AS 'Total of all Sales for Week'
FROM   Sale
WHERE  Sale.saledate BETWEEN Date_add(?, INTERVAL -1 week) AND
                             ?; ");
mysqli_stmt_bind_param($sumSales, "ss", $weekEnding, $weekEnding);

echo "<h3> Sales </h3>";
mysqli_stmt_execute($Sales);
$result = display_mysqli_stmt_result($Sales);
if ($result != null){
   echo "<h3> Total Selling Price/Commission per Salesperson </h3>";
   mysqli_stmt_execute($totalPriceComm);
   display_mysqli_stmt_result($totalPriceComm);
   
   echo "<h3> Total of All Sales for Week </h3>";
   mysqli_stmt_execute($sumSales);
   display_mysqli_stmt_result($sumSales);
}

mysqli_close($linkID);
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