<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Artwork History</title>
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

$workTitle = $_GET['workTitle'];
$artistFN = $_GET["artistFN"];
$artistLN = $_GET['artistLN'];

$findArtworkID = mysqli_prepare($linkID, 
"SELECT artworkid
FROM   Artist a,
       Artwork w
WHERE  w.worktitle LIKE Concat(?, '%') 
       AND w.artistid = a.artistid
       AND a.firstname LIKE Concat(?, '%')
       AND a.lastname LIKE Concat(?, '%'); 
");
mysqli_stmt_bind_param($findArtworkID, 'sss', $workTitle, $artistFN, $artistLN);

$fetchArtwork = mysqli_prepare($linkID,
"SELECT worktitle           AS 'Work Title',
        workyearcompleted   AS 'Year Completed',
        worktype            AS 'Type',
        workmedium          AS 'Medium',
        workstyle           AS 'Style',
        worksize            AS 'Size',
        askingprice         AS 'Asking Price',
        dateshown           AS 'Date Shown',
        datelisted          AS 'Date Listed',
        datereturned        AS 'Date Returned'
FROM   Artist a,
        Artwork w,
        Sale s
WHERE a.artistid = w.artistid AND s.artworkid = w.artworkid AND w.artworkid = ? ");
mysqli_stmt_bind_param($fetchArtwork, 's', $artworkID);

$fetchArtworkSalesInfo = mysqli_prepare($linkID, 
"SELECT invoicenumber           AS 'Invoice No.',
        amountremittedtoowner   AS 'Amount Remitted to Owner',
        saledate                AS 'Sale Date',
        saleprice               AS 'Sale Price',
        saletax                 AS 'Sale Tax'
FROM    Sale s,
        Artwork w
WHERE   s.artworkid = w.artworkid
        AND s.artworkid = ?; 
");
mysqli_stmt_bind_param($fetchArtworkSalesInfo, 's', $artworkID);

// get artwork ID from user given params
mysqli_stmt_execute($findArtworkID);
mysqli_stmt_bind_result($findArtworkID, $artworkID);
mysqli_stmt_fetch($findArtworkID);
mysqli_stmt_close($findArtworkID);

echo "<h2>Artwork History</h2>";

if ($artworkID == 0) {
    echo "Sorry, there was no artwork found with the given information. ";
    exit;
}
 
echo "<h3>Artwork Information</h3>";
mysqli_stmt_execute($fetchArtwork);
display_mysqli_stmt_result($fetchArtwork);

echo "<h3>Artwork Sale History</h3>";
mysqli_stmt_execute($fetchArtworkSalesInfo);
display_mysqli_stmt_result($fetchArtworkSalesInfo);
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