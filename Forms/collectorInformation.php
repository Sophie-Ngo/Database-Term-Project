<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Collector Inserted</title>
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

	$interviewDate      = $_POST["interviewDate"];
	$interviewerName    = $_POST["interviewerName"];
	$lastName           = $_POST["lastName"];
	$firstName          = $_POST["firstName"];
    $street             = $_POST['street'];
    $city               = $_POST['city'];
    $state              = $_POST['state'];
    $zip                = $_POST['zip'];
    $areaCode           = $_POST['areaCode'];
    $telenum            = $_POST['telephoneNumber'];
    $ssn                = $_POST['socialSecurityNumber'];

    $artistLastName     = $_POST["artistLName"];
    $artistFirstName    = $_POST["artistFName"];
    $collectionType     = $_POST["collectionType"];
    $collectionMedium   = $_POST["collectionMedium"];
    $collectionStyle    = $_POST["collectionStyle"];

    
	$linkID = mysqli_connect("localhost","tchen","csc353", "ChenKingNgo")
			or die ("Could not connect: " . mysql_error()); 


    check_zip($linkID, $city, $state, $zip); 
    

    # Insert new collector without preferred artist 
    if ($artistLastName == '' & $artistFirstName == '') {
        
        $insertCollectorSQL = mysqli_prepare($linkID, 
            "INSERT INTO Collector (socialSecurityNumber, firstName, lastName, interviewDate, interviewerName,
                        areaCode, telephoneNumber, street, zip, collectionMedium, collectionStyle, collectionType) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param($insertCollectorSQL, "ssssssssssss", $ssn, $firstName, $lastName, $interviewDate, $interviewerName, 
                            $areaCode, $telenum, $street, $zip, $collectionMedium, $collectionStyle, $collectionType); 
        try {
            mysqli_stmt_execute($insertCollectorSQL); 
            echo "\n Insert new collector successful! \n"; 
        } 
        catch (Exception $e) {
            echo "Insert Fail: Duplicate Entry";  
        } 
    }

    # New collector with preferred artist information 
    else {
        $artistId = get_artistId($linkID, $artistFirstName, $artistLastName); 

        if ($artistId == 0) {
            echo "the artist does not exist in db!"; 
            echo "<form method='POST' action='artistForm.html'>
            <input type='submit' value = 'Click here to insert a new artist' />
            </form>";
        } 
        # Insert new collector with artist and collection information 
        else {
            $insertCollectorSQL = mysqli_prepare($linkID, 
                "INSERT INTO Collector (socialSecurityNumber, firstName, lastName, interviewDate, interviewerName,
                            areaCode, telephoneNumber, street, zip, collectionArtistId, collectionMedium, collectionStyle, collectionType) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, $artistId, ?, ?, ?)");

            mysqli_stmt_bind_param($insertCollectorSQL, "ssssssssssss", $ssn, $firstName, $lastName, $interviewDate, $interviewerName, 
                                $areaCode, $telenum, $street, $zip, $collectionMedium, $collectionStyle, $collectionType); 

            try {
                mysqli_stmt_execute($insertCollectorSQL); 
                echo "\n Insert new collector successful! \n"; 
            } 
            catch (Exception $e) {
                echo "Insert Fail: Duplicate Entry";  
            } 
        }
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