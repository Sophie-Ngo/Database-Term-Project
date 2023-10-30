<?php
// specially for mysqli statements (prepared statements)
// important: CLOSES the stmt after displaying is done
function display_mysqli_stmt_result($stmt){
    mysqli_stmt_store_result($stmt);
    $numRows = mysqli_stmt_num_rows($stmt);
    if ($numRows == 0) {
        echo "No results.";
        return null;
    }
    echo "<TABLE BORDER=1 CELLPADDING=8>";
    echo "<thead><tr>";   
    $i=0;
    $meta = $stmt->result_metadata();
    $query_data=array(); 

    // header 
    while ($field = $meta->fetch_field()) { 
      echo "<th>" . $field->name . "</th>";
      $var = $i;
      $$var = null; 
      $query_data[$var] = &$$var; 
      $i++;    
    }
    echo "</tr></thead>";

    // show all records
    call_user_func_array(array($stmt,'bind_result'), $query_data); 
    while ($stmt->fetch()) {                   
      echo "<tr>";
      for ($i = 0; $i < count($query_data); $i++) { 
        echo "<td>" .  $query_data[$i] . "</td>"; 
      }
      echo "</tr>";        
    }
    echo "</table>";
    $stmt->close();
    // echo "Num rows: " . $numRows;
    return 1;
}

// displays the resulting table from a SQL query (not mysqli)
function display_query_result($result) {
    if (mysql_num_rows($result) == 0) {
        echo nl2br("\nNo results.");
        return;
    }
    // echo result
    echo "<TABLE BORDER=1 CELLPADDING=8>";

    // header
    echo "<tr>";
    while ($field = mysql_fetch_field($result)) {
        echo "<td><b>" . $field->name . "</b></td>";
    }
    echo "</tr>";

    // show all records
    while ($record = mysql_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($record as $value) {
            echo "<td>". $value . "</td>";
        }
        echo " </tr>";
    }
    echo "</table>";
}


// made this function to handle errors easier
// not mysqli
function run_query($query, $linkID) {
    $result = mysql_query($query, $linkID);
    if (!$result) {
        echo nl2br("\nCould not successfully run query ($SQL) from DB: ") . mysql_error();
        exit;
    }
    return $result;
}

?>