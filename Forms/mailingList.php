<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Mailing List</title>
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
        <a href="http://cs.furman.edu/~sngo/ChenKingNgo_term_project/homepage.html">Index</a>
	<a href="http://cs.furman.edu/~sngo/ChenKingNgo_term_project/userdocs.html" rel="help" target="_blank">User Documentation</a>
      </p>
    </div>
    <hr style="color:#582C83; weight:2px" />  
  </div>
<br />

<div style="margin-left:5%;">
<?php
    require('function.php');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	$date               = $_POST["date"];
	$lastName           = $_POST["lastName"];
	$firstName          = $_POST["firstName"];
    $street             = $_POST['street'];
    $city               = $_POST['city'];
    $state              = $_POST['state'];
    $zip                = $_POST['zip'];
    $areaCode           = $_POST['areaCode'];
    $telenum            = $_POST['telephoneNumber'];
    
    $preferredArtistFN  = $_POST["preferredArtistFN"];
    $preferredArtistLN  = $_POST["preferredArtistLN"];
    $preferredStyle     = $_POST["preferredStyle"];
    $preferredType      = $_POST["preferredType"];
    $preferredMedium    = $_POST["preferredMedium"];

    
	$linkID = mysqli_connect("localhost","tchen","csc353", "ChenKingNgo")
			or die ("Could not connect: " . mysql_error()); 


    check_zip($linkID, $city, $state, $zip); 

    # find preferred artist
    $artistId = get_artistId($linkID, $preferredArtistFN, $preferredArtistLN); 
    if ($artistId == 0) {
        $artistId = null; 
        
        $insertPotentialCustomerSQL = mysqli_prepare($linkID, 
                        "INSERT INTO PotentialCustomer (firstName, lastName, areaCode, telephoneNumber, street, zip,
                                    dateFilledIn, preferredMedium, preferredStyle, preferredType) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param($insertPotentialCustomerSQL, "ssssssssss", $firstName, $lastName, $areaCode, $telenum, $street, $zip, 
                                    $date, $preferredMedium, $preferredStyle, $preferredType); 

        mysqli_stmt_execute($insertPotentialCustomerSQL); 
        echo "Insert new potentional customer successful!";
    }
    
    else {
        $insertPotentialCustomerSQL = mysqli_prepare($linkID, 
                            "INSERT INTO PotentialCustomer (firstName, lastName, areaCode, telephoneNumber, street, zip,
                                        dateFilledIn, preferredArtistId, preferredMedium, preferredStyle, preferredType) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, '$artistId', ?, ?, ?)");

        mysqli_stmt_bind_param($insertPotentialCustomerSQL, "ssssssssss", $firstName, $lastName, $areaCode, $telenum, $street, $zip, 
                                        $date, $preferredMedium, $preferredStyle, $preferredType); 


        mysqli_stmt_execute($insertPotentialCustomerSQL); 
        echo "Insert new potentional customer successful!";
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