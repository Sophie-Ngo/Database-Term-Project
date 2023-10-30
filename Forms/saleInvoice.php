<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>New Sale Invoice</title>
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

    $title           = $_POST["title"];
	$artistLastName  = $_POST["artistLastName"];
	$artistFirstName = $_POST["artistFirstName"];

    $ownerLastName  = $_POST["ownerLastName"];
	$ownerFirstName = $_POST["ownerFirstName"];
    $o_street       = $_POST['o_street'];
    $o_city         = $_POST['o_city'];
    $o_state        = $_POST['o_state'];
    $o_zip          = $_POST['o_zip'];
    $o_areaCode     = $_POST['o_areaCode'];
    $o_telenum      = $_POST['o_telephoneNumber'];

    $buyerLastName  = $_POST["buyerLastName"];
	$buyerFirstName = $_POST["buyerFirstName"];
    $b_street       = $_POST['b_street'];
    $b_city         = $_POST['b_city'];
    $b_state        = $_POST['b_state'];
    $b_zip          = $_POST['b_zip'];
    $b_areaCode     = $_POST['b_areaCode'];
    $b_telenum      = $_POST['b_telephoneNumber'];

    $price          = $_POST['price'];
    $tax            = $_POST['tax'];
    $totalPaid      = $_POST['totalPaid'];
    $saleDate       = $_POST['saleDate'];

    $linkID = mysqli_connect("localhost","tchen","csc353", "ChenKingNgo"); 

    # Find Buyer Information -> Insert new buyer if not already exist 
    $buyerId = get_buyerId($linkID, $buyerFirstName, $buyerLastName); 

    # Insert new buyer if buyerId = 0 
    if ($buyerId == 0) {

        check_zip($linkID, $b_city, $b_state, $b_zip); 

        $insertBuyerSQL = mysqli_prepare($linkID, "INSERT INTO Buyer (firstName, lastName, street, zip,
            areaCode, telephoneNumber) VALUES (?, ?, ?, ?, ?, ?) "); 
        
        mysqli_stmt_bind_param($insertBuyerSQL, "ssssss", $buyerFirstName, $buyerLastName, $b_street, $b_zip,
            $b_areaCode, $b_telenum); 

        mysqli_stmt_execute($insertBuyerSQL); 

        echo "New Buyer: $buyerFirstName $buyerLastName inserted!"; 

        # get new inserted buyer ID 
        $buyerId = get_buyerId($linkID, $buyerFirstName, $buyerLastName); 
    }

    # Find artistId 
    $artistId = get_artistId($linkID, $artistFirstName, $artistLastName); 

    # Find artworkId (artistId + workTitle)
    $findArtworkId = mysqli_prepare($linkID, "SELECT artworkId FROM Artwork WHERE artistId = ? AND workTitle = ?"); 

    mysqli_stmt_bind_param($findArtworkId, "ss", $artistId, $title); 
    mysqli_stmt_execute($findArtworkId); 
    
    mysqli_stmt_bind_result($findArtworkId, $artworkId); 
    mysqli_stmt_fetch($findArtworkId); 
   
    mysqli_stmt_close($findArtworkId);
    
    
    # Increment invoice number 
    $incrementInvoiceNumSQL = mysqli_prepare($linkID, "SELECT max(invoiceNumber) FROM Sale"); 
    mysqli_stmt_execute($incrementInvoiceNumSQL); 
    mysqli_stmt_bind_result($incrementInvoiceNumSQL, $invoiceNumber); 
    mysqli_stmt_fetch($incrementInvoiceNumSQL);
    $invoiceNumber += 1; 
    
    mysqli_stmt_close($incrementInvoiceNumSQL);

    # Insert Sale Information 
    $insertSaleSQL = mysqli_prepare($linkID, "INSERT INTO Sale (invoiceNumber, artworkId, saleDate, salePrice, saleTax, buyerId) VALUES ('$invoiceNumber', '$artworkId', ?, ?, ?, '$buyerId') ");
    #echo "$saleDate, $price, $tax";
    
    mysqli_stmt_bind_param($insertSaleSQL, "sss", $saleDate, $price, $tax);

    try {
        mysqli_stmt_execute($insertSaleSQL); 
        echo "New Sale Invoice $invoiceNumber inserted!"; 
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