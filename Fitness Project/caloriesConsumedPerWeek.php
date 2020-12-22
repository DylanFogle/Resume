<?php
  // A graph will be generated of how many calories a user consumed each day of the week.

  // Have the user enter two days that will define a week.
  // Then we simply calculate the amount of calories consumed each day.
  echo "<h4>Showing how many calories a user consumed in a week!</h4>";

  echo "<form method=POST>";
    echo "Please enter the first day of the week in the format YYYY-MM-DD!";
    echo "<input type=text name=CCFirstDate><br />";
    echo "Please enter the last day of the week in the format YYYY-MM-DD!";
    echo "<input type=text name=CCLastDate><br />";
    echo "<input type=submit value='Submit to see calories burned!'>";
  echo "</form>";

  if(!empty($_POST["CCFirstDate"]) && !empty($_POST["CCLastDate"])){
    $CCFirstDate = $_POST["CCFirstDate"];
    $CCLastDate = $_POST["CCLastDate"];
    $sql = "SELECT DISTINCT DATE FROM FOOD_AND_DRINK WHERE DATE >= :CCFD AND DATE <= :CCLD;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":CCFD" => "$CCFirstDate", ":CCLD" => "$CCLastDate"));
		if(!$success){
			echo "Error in query";
			die();
		}
    $rowsDoW = $prepared->fetchAll(PDO::FETCH_ASSOC);
    // We now have every distinct day of possible meal eaten by the user
	$CCDay0 = "N/A";
    $CCDay1 = "N/A";
    $CCDay2 = "N/A";
    $CCDay3 = "N/A";
    $CCDay4 = "N/A";
    $CCDay5 = "N/A";
    $CCDay6 = "N/A";
    $i = 0;
    foreach($rowsDoW as $row){
       if($i == 0){
                $CCDay0 = $row["DATE"];
        }
        if($i == 1){
                $CCDay1 = $row["DATE"];
        }
        if($i == 2){
                $CCDay2 = $row["DATE"];
        }
        if($i == 3){
                $CCDay3 = $row["DATE"];
        }
        if($i == 4){
                $CCDay4 = $row["DATE"];
        }
        if($i == 5){
                $CCDay5 = $row["DATE"];
        }
        if($i == 6){
                $CCDay6 = $row["DATE"];
        }
      $i++;
    }
    // But we need to redo the query again to get all meals eaten in that time.
    $sql1 = "SELECT NAME,CALORIES,DATE FROM FOOD_AND_DRINK WHERE DATE >= :CCFD AND DATE <= :CCLD;";
    $prepared1 = $pdo->prepare($sql1);
    $success1 = $prepared1->execute(array(":CCFD" => "$CCFirstDate", ":CCLD" => "$CCLastDate"));
		if(!$success1){
			echo "Error in query";
			die();
		}
    $rowsMoW = $prepared1->fetchAll(PDO::FETCH_ASSOC);
    // Because we already calculated the calories prior to insertion into FOOD_AND_DRINK,
    // we can simply tally them up day by day.
    $CCDay0Amount = 0;
    $CCDay1Amount = 0;
    $CCDay2Amount = 0;
    $CCDay3Amount = 0;
    $CCDay4Amount = 0;
    $CCDay5Amount = 0;
    $CCDay6Amount = 0;
    // For each meal eaten by the user.
    foreach($rowsMoW as $rowMOW){
      // Now check for day.
      if($CCDay0 == $rowMOW["DATE"]){
        $CCDay0Amount += $rowMOW["CALORIES"];
      }
      if($CCDay1 == $rowMOW["DATE"]){
        $CCDay1Amount += $rowMOW["CALORIES"];
      }
      if($CCDay2 == $rowMOW["DATE"]){
        $CCDay2Amount += $rowMOW["CALORIES"];
      }
      if($CCDay3 == $rowMOW["DATE"]){
        $CCDay3Amount += $rowMOW["CALORIES"];
      }
      if($CCDay4 == $rowMOW["DATE"]){
        $CCDay4Amount += $rowMOW["CALORIES"];
      }
      if($CCDay5 == $rowMOW["DATE"]){
        $CCDay5Amount += $rowMOW["CALORIES"];
      }
      if($CCDay6 == $rowMOW["DATE"]){
        $CCDay6Amount += $rowMOW["CALORIES"];
      }
    }
    echo "<table border=1>";
    echo "<tr><th>$CCDay0</th><th>$CCDay1</th><th>$CCDay2</th>";
    echo "<th>$CCDay3</th><th>$CCDay4</th><th>$CCDay5</th><th>$CCDay6</th></tr>";
    echo "<tr><td>$CCDay0Amount</td><td>$CCDay1Amount</td><td>$CCDay2Amount</td><td>$CCDay3Amount</td>";
    echo "<td>$CCDay4Amount</td><td>$CCDay5Amount</td><td>$CCDay6Amount</td></tr>";
	echo "</table>";
  }
?>
