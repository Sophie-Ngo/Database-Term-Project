<?php


function check_zip($linkID, $city, $state, $zip) {
    $checkZipSQL = mysqli_prepare($linkID, "SELECT * FROM Zips WHERE zip = ?");
    
    mysqli_stmt_bind_param($checkZipSQL, "s", $zip); 

    mysqli_stmt_execute($checkZipSQL); 

    mysqli_stmt_store_result($checkZipSQL); 
    
    # if zip is not in db, insert into zip table 
    if (mysqli_stmt_affected_rows($checkZipSQL) == 0) {
        $insertZipSQL = mysqli_prepare($linkID, "INSERT INTO Zips (city, state, zip) 
                                                 VALUES(?, ?, ?) ");
        mysqli_stmt_bind_param($insertZipSQL, "sss", $city, $state, $zip); 
        mysqli_stmt_execute($insertZipSQL); 

        echo "Insert new zip successful! \n";
        echo "You have added $zip as a new zip. \n"; 
    }
    // else {
    //     echo "$zip exist in db. \n"; 
    // }
}

function get_artistId($linkID, $artistFirstName, $artistLastName) {

    $findArtistIdSQL = mysqli_prepare($linkID, "SELECT artistId FROM Artist WHERE 
    firstName = ? AND lastName = ?"); 

    mysqli_stmt_bind_param($findArtistIdSQL, "ss", $artistFirstName, $artistLastName); 
    mysqli_stmt_execute($findArtistIdSQL); 

    mysqli_stmt_bind_result($findArtistIdSQL, $artistId);
    mysqli_stmt_fetch($findArtistIdSQL);
    # printf("Artist ID: $artistId");

    return $artistId; 
}

function get_buyerId($linkID, $buyerFirstName, $buyerLastName) {
    
    $findBuyerIdSQL = mysqli_prepare($linkID, "SELECT buyerId FROM Buyer WHERE 
    firstName = ? AND lastName = ?"); 

    mysqli_stmt_bind_param($findBuyerIdSQL, "ss", $buyerFirstName, $buyerLastName); 
    mysqli_stmt_execute($findBuyerIdSQL); 

    mysqli_stmt_bind_result($findBuyerIdSQL, $buyerId);
    mysqli_stmt_fetch($findBuyerIdSQL);
    # printf("Buyer ID: $buyerId");

    return $buyerId; 
}

?>