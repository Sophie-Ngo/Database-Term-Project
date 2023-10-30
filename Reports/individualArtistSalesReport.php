<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Individual Artist Sales Report</title>
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
        <a href="http://cs.furman.edu/~sngo/ChenKingNgo_term_project/homepage.html">Index</a>
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

$artistFN = $_GET["artistFN"];
$artistLN = $_GET["artistLN"];
$beginningDate = $_GET['beginningDate'];

echo "<h2>Individual Artist Sales Report</h2>";

$dateOfReport = mysqli_query($linkID, "SELECT DATE_FORMAT(CURRENT_DATE, '%m/%d/%Y') AS 'Date of Report'; ");
echo "Date of Report: " . mysqli_fetch_row($dateOfReport)[0];

echo nl2br("\nReport for works beginning with the date listed of " . $beginningDate); // INPUT DATE TO GO HERE

$findArtistID = mysqli_prepare($linkID, 
"SELECT artistid
FROM   Artist a
WHERE  a.firstname LIKE Concat(?, '%')
       AND a.lastname LIKE Concat(?, '%'); 
");
mysqli_stmt_bind_param($findArtistID, 'ss', $artistFN, $artistLN);

// to use with user input
$indivArtist = mysqli_prepare($linkID, 
"SELECT lastname          AS 'Artist Last Name',
        firstname         AS 'First Name',
        street            AS 'Street',
        city              AS 'City',
        state             AS 'State',
        a.zip             AS 'Zip',
        a.areacode        AS 'Area Code',
        a.telephonenumber AS 'Number'
FROM   Artist a, Zips z
WHERE  a.zip = z.zip
AND artistid = ?;"); 
mysqli_stmt_bind_param($indivArtist, "s", $artistID);

$worksSold = mysqli_prepare($linkID, 
"SELECT worktitle         AS 'Title',
        datelisted        AS 'Date Listed',
        worktype          AS 'Type',
        workmedium        AS 'Medium',
        workstyle         AS 'Style',
        workyearcompleted AS 'Year',
        askingprice       AS 'Asking Price',
        saleprice         AS 'Sale Price',
        saledate          AS 'Date Sold'
FROM   Sale s, Artwork w
WHERE  w.artworkid = s.artworkid
AND w.artistid = ?
AND datelisted >= ?; ");
mysqli_stmt_bind_param($worksSold, "ss", $artistID, $beginningDate);

$totalOfSales = mysqli_prepare($linkID, 
"SELECT Sum(saleprice) AS 'Total of Sales'
FROM   Sale s, Artwork w
WHERE  w.artworkid = s.artworkid
       AND w.artistid = ?
       AND datelisted >= ?; "); 
mysqli_stmt_bind_param($totalOfSales, "ss", $artistID, $beginningDate);

$worksReturned = mysqli_prepare($linkID, 
"SELECT worktitle         AS 'Title',
        datelisted        AS 'Date Listed',
        worktype          AS 'Type',
        workmedium        AS 'Medium',
        workstyle         AS 'Style',
        workyearcompleted AS 'Year',
        askingprice       AS 'Asking Price',
        dateReturned      AS 'Date Returned'
FROM   Artwork
WHERE  artistid = ?
AND datelisted >= ?
AND datereturned IS NOT NULL; "); 
mysqli_stmt_bind_param($worksReturned, "ss", $artistID, $beginningDate);

$worksForSale = mysqli_prepare($linkID, 
"SELECT worktitle         AS 'Title',
        datelisted        AS 'Date Listed',
        worktype          AS 'Type',
        workmedium        AS 'Medium',
        workstyle         AS 'Style',
        workyearcompleted AS 'Year',
        askingprice       AS 'Asking price'
FROM   Artwork
WHERE  artistid = ?
AND datelisted >= ?
AND status = 'for sale'; "); 
mysqli_stmt_bind_param($worksForSale, "ss", $artistID, $beginningDate);

$totalOfAskingPrices = mysqli_prepare($linkID, 
"SELECT Sum(askingprice) AS 'Total Asking Prices'
FROM   Artwork
WHERE  artistid = ?
       AND datelisted >= ?
       AND status = 'for sale'; "); 
mysqli_stmt_bind_param($totalOfAskingPrices, "ss", $artistID, $beginningDate);


// get artist ID from user given params
mysqli_stmt_execute($findArtistID);
mysqli_stmt_store_result($findArtistID);
if (mysqli_stmt_num_rows($findArtistID) == 0) {
       echo '<br>';
       echo nl2br("\nNo artist exists with that name.\n");
       echo "<a href='https://cs.furman.edu/~sngo/ChenKingNgo_term_project/Forms/individualArtistSalesReportForm.html'>Try again </a>";
       exit;
}
mysqli_stmt_bind_result($findArtistID, $artistID);
mysqli_stmt_fetch($findArtistID);
mysqli_stmt_close($findArtistID);

echo "<h5>Individual Artist Info</h5>";
mysqli_stmt_execute($indivArtist);
display_mysqli_stmt_result($indivArtist);

echo "<h5>Works Sold</h5>";
mysqli_stmt_execute($worksSold);
$result = display_mysqli_stmt_result($worksSold);
if ($result != null) {
       echo "<h3>Total of Sales</h3>";
       mysqli_stmt_execute($totalOfSales);
       display_mysqli_stmt_result($totalOfSales);
}

echo "<h5>Works Returned</h5>";
mysqli_stmt_execute($worksReturned);
display_mysqli_stmt_result($worksReturned);

echo "<h5>Works For Sale</h5>";
mysqli_stmt_execute($worksForSale);
$result = display_mysqli_stmt_result($worksForSale);
if ($result != null) {     
       echo "<h3>Total of Asking Prices</h3>";
       mysqli_stmt_execute($totalOfAskingPrices);
       display_mysqli_stmt_result($totalOfAskingPrices);
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