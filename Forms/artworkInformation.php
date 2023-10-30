<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Artwork Inserted</title>
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
<div style="margin-left:5%;"><?php
    require('function.php');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    # Artwork Information 
    $artistLastName  = $_POST["artistLastName"];
    $artistFirstName = $_POST["artistFirstName"];
    $title           = $_POST["title"];

    $yearCompleted  = $_POST["yearCompleted"];
    $type           = $_POST["type"];
    $medium         = $_POST["medium"];
    $style          = $_POST["style"];
    $size           = $_POST["size"];

    # Owner Information 
    $ownerLasName   = $_POST["ownerLastName"];
    $ownerFirsName  = $_POST["ownerFirstName"];
    $street         = $_POST["street"];
    $city           = $_POST['city'];
    $state          = $_POST['state'];
    $zip            = $_POST['zip'];
    $areaCode       = $_POST['areaCode'];
    $telenum        = $_POST['telephoneNumber'];
    $ownerSSN       = $_POST['ownerSSN'];
    
    # Price Information 
    $dateListed     = $_POST["dateListed"];
    $askingPrice    = $_POST["askingPrice"];


	$linkID = mysqli_connect("localhost","tchen","csc353", "ChenKingNgo"); 

    # Insert Owner Information - if owned by someone other than the artist 
    if ($ownerSSN != null ) {
        
        # check if collector exist with provided ssn 
        $findCollectorSQL = mysqli_prepare($linkID, "SELECT socialSecurityNumber FROM Collector WHERE 
                                                socialSecurityNumber = ? "); 
        mysqli_stmt_bind_param($findCollectorSQL, "s", $ownerSSN); 
        $collectorSSN = mysqli_stmt_execute($findCollectorSQL); 
        
        # collector does not exist -> added to Collector table
        if (mysqli_stmt_affected_rows($findCollectorSQL) == 0) {
            check_zip($linkID, $city, $state, $zip);

            $insertCollectorSQL = mysqli_prepare($linkID, 
                        "INSERT INTO Collector (socialSecurityNumber, firstName, lastName, 
                            areaCode, telephoneNumber, street, zip) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param($insertCollectorSQL, "ssssssssssss", $ssn, $firstName, $lastName, $interviewDate, $interviewerName, 
                                $areaCode, $telenum, $street, $zip); 
            
            try {
                mysqli_stmt_execute($insertCollectorSQL); 
                echo "Insert new collector successfully!"; 
            } 
            catch (Exception $e) {
                echo "Insert Fail: Duplicate Entry";  
            }    
            
        }
    }

    # Insert Artwork Information 
    $artistId = get_artistId($linkID, $artistFirstName, $artistLastName); 
    
    $insertArtworkSQL = mysqli_prepare($linkID, 
                        "INSERT INTO Artwork (artistId, workTitle, askingPrice, dateListed, workMedium, workSize, workStyle, workType, 
                            workYearCompleted, collectorSocialSecurityNumber) 
                            VALUES ($artistId, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($insertArtworkSQL, "sssssssss", $title, $askingPrice, $dateListed, $medium, $size, 
                                $style, $type, $yearCompleted, $ownerSSN); 

    try {
        mysqli_stmt_execute($insertArtworkSQL);
        echo "Insert new artwork successfully!"; 
    } 
    catch (Exception $e) {
        echo "Insert Fail: Duplicate Entry";  
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