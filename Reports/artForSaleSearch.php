<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Art For Sale Search</title>
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
<div style="margin-left:5%;"><body>
<?php
require('functions.php');
require('../credentials.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$linkID = mysqli_connect($host ,$user, $pass, $db);  

$artistFN = $_GET["artistFN"];
$artistLN = $_GET['artistLN'];
$type = $_GET['type'];
$medium = $_GET['medium'];
$style = $_GET['style'];

// $findArtworkID = mysqli_prepare($linkID, 
// "SELECT artworkid
// FROM   Artist a,
//        Artwork w
// WHERE  w.artistid = a.artistid
//        AND a.firstname LIKE Concat('%', ?, '%')
//        AND a.lastname LIKE Concat('%', ?, '%')
//        AND w.worktype LIKE Concat('%', ?, '%')
//        AND w.workmedium LIKE Concat('%', ?, '%')
//        AND w.workstyle LIKE Concat('%', ?, '%')
// ");
// mysqli_stmt_bind_param($findArtworkID, 'sssss', $artistFN, $artistLN, $type, $medium, $style);

$artworkSearch = mysqli_prepare($linkID, 
"SELECT worktitle   AS 'Work Title',
        worktype    AS 'Type',
        workmedium  AS 'Medium',
        workstyle   AS 'Style',
        askingprice AS 'Asking Price'
FROM   Artwork
WHERE  artworkid in (SELECT artworkid
                    FROM    Artist a,
                            Artwork w
                    WHERE  w.artistid = a.artistid
                        AND a.firstname LIKE Concat('%', ?, '%')
                        AND a.lastname LIKE Concat('%', ?, '%')
                        AND w.worktype LIKE Concat('%', ?, '%')
                        AND w.workmedium LIKE Concat('%', ?, '%')
                        AND w.workstyle LIKE Concat('%', ?, '%'))
    AND Artwork.status = 'for sale'
");
mysqli_stmt_bind_param($artworkSearch, 'sssss', $artistFN, $artistLN, $type, $medium, $style);

// mysqli_stmt_execute($findArtworkID);
// mysqli_stmt_bind_result($findArtworkID, $artworkID);
// mysqli_stmt_fetch($findArtworkID);
// mysqli_stmt_close($findArtworkID);

echo "<h2>Art For Sale Search Results</h2>";

mysqli_stmt_execute($artworkSearch);
display_mysqli_stmt_result($artworkSearch);

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