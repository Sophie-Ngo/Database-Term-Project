<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Art Show Report</title>
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

echo "<h2>Art Show Report</h2>";

$showTitle = $_GET["showTitle"];

$dateOfReport = mysqli_query($linkID, "SELECT DATE_FORMAT(CURRENT_DATE, '%m/%d/%Y') AS 'Date of Report'; ");
echo "Date of Report: " . mysqli_fetch_row($dateOfReport)[0];

$showInfo = mysqli_prepare($linkID, 
"SELECT ArtShow.showtitle                              AS 'Title of Show',
        ArtShow.showopeningdate                        AS 'Opening Date',
        ArtShow.showclosingdate                        AS 'Closing Date',
        Concat(Artist.firstname, ' ', Artist.lastname) AS 'Featured Artist',
        IF (ArtShow.showtheme IS NULL, (SELECT
        Concat(Artist.firstname, ' ', Artist.lastname)
                                        FROM   Artist
                                        WHERE
                                        ArtShow.showfeaturedartistid = Artist.artistid),
        (SELECT ArtShow.showtheme
        FROM   ArtShow
        WHERE
        lower(ArtShow.showtitle) LIKE Concat(lower(?),'%')))                AS 'Artist Name OR Show Theme'
FROM   ArtShow, Artist
WHERE  ArtShow.showfeaturedartistid = Artist.artistid
AND lower(ArtShow.showtitle) LIKE Concat(lower(?),'%'); "); 
// lower column AND parameter to make input case insensitive 

mysqli_stmt_bind_param($showInfo, "ss", $showTitle, $showTitle);

$worksIncluded = mysqli_prepare($linkID, 
"SELECT Concat(Artist.firstname, ' ', Artist.lastname) AS 'Artist', 
        Artwork.worktitle                              AS 'Title',
        Artwork.askingprice                            AS 'Asking Price',
        Artwork.status                                 AS 'Status'
FROM   ArtShow, Artwork, Artist, ShownIn
WHERE  ShownIn.artworkid = Artwork.artworkid
AND ShownIn.showtitle = ArtShow.showtitle
AND Artist.artistid = Artwork.artistid
AND lower(ArtShow.showtitle) LIKE Concat(lower(?),'%'); ");

mysqli_stmt_bind_param($worksIncluded, "s", $showTitle);

echo '<h3>Show Info</h3>';

$showInfo->execute();
display_mysqli_stmt_result($showInfo);

echo '<h3>Works Included</h3>';

$worksIncluded->execute();
display_mysqli_stmt_result($worksIncluded);


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