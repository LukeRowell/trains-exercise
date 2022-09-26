<?php
    $data = array();
    $conn = mysqli_connect('localhost', 'root', '') or die("Connection failed: " .mysqli_connect_error());
    $createDBCommand = "CREATE DATABASE IF NOT EXISTS TRAINS_DB";

    if ($conn->query($createDBCommand) === TRUE) 
    {
        //echo "TRAINS_DB created successfully";
        $conn->select_db("TRAINS_DB");
    } 
    
    else 
    {
        echo "TRAINS_DB already exists: " . $conn->error;
    }

    $createTableCommand = "CREATE TABLE Trains (
        TRAIN_LINE VARCHAR(30),
        ROUTE_NAME VARCHAR(30),
        RUN_NUMBER VARCHAR(30),
        OPERATOR_ID VARCHAR(30))";

    if ($conn->query($createTableCommand) === TRUE) 
    {
        //echo "Table Trains created successfully";
    } 
    
    else 
    {
        //echo "Error creating table: " . $conn->error;
    }

    if (isset($_POST["search"]))
    {
        $columnOption = $_POST['order'];
        $wayOption = $_POST['way'];
        $selectCommand = "SELECT DISTINCT TRAIN_LINE, ROUTE_NAME, RUN_NUMBER, OPERATOR_ID FROM Trains ORDER BY $columnOption $wayOption";
        $result = $conn->query($selectCommand);

        if ($result->num_rows > 0)
        {              
            echo "<table>";
            echo "<tr>";
                echo "<th>TRAIN_LINE</th>";
                echo "<th>ROUTE_NAME</th>";
                echo "<th>RUN_NUMBER</th>";
                echo "<th>OPERATOR_ID</th>";
            echo "</tr>";  

            while ($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                    echo "<td>" . $row['TRAIN_LINE'] . "</td>";
                    echo "<td>" . $row['ROUTE_NAME'] . "</td>";
                    echo "<td>" . $row['RUN_NUMBER'] . "</td>";
                    echo "<td>" . $row['OPERATOR_ID'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }

        else
        {
            echo "No rows found". $conn->error;
        }
    }

    else if (isset($_FILES['data']))
    {
        $fileName = $_FILES['data']['name'];
        $tempFile =$_FILES['data']['tmp_name'];

        $fileInfo = pathinfo($fileName);

        if ($fileInfo["extension"] != "csv")
        {
            $error = "<h2>ERROR: Please limit file selection to .csv files only!</h2>";
            print_r($error);
        }

        else 
        {
            move_uploaded_file($tempFile, $fileName);

            $myfile = fopen($fileName, "r") or die("Unable to open file!");
            $rowCounter = 0;

            while (!feof($myfile)) 
            {
                if ($rowCounter > 0)
                {
                    $data[$rowCounter] = explode(',', fgets($myfile));
                }

                else
                {
                    $headerLine = explode(',', fgets($myfile));
                }

                $rowCounter = $rowCounter + 1;
            }

            fclose($myfile);

            if ($conn->ping()) 
            {
                foreach ($data as $row)
                {
                    $trainLine = $row[0];
                    $routeName = $row[1];
                    $runNumber = $row[2];
                    $operatorID = $row[3];
    
                    $insertCommand = "INSERT INTO Trains (`TRAIN_LINE`, `ROUTE_NAME`, `RUN_NUMBER`, `OPERATOR_ID`) VALUES ('$trainLine', '$routeName', '$runNumber', '$operatorID')";

                    if ($conn->query($insertCommand) === TRUE)
                    {
                        //echo "Row inserted successfully!";
                    }

                    else
                    {
                        echo "Error inserting row: " . $conn->error;
                    }
                }

                $selectCommand = "SELECT DISTINCT TRAIN_LINE, ROUTE_NAME, RUN_NUMBER, OPERATOR_ID FROM Trains ORDER BY RUN_NUMBER ASC";
                $result = $conn->query($selectCommand);

                if ($result->num_rows > 0)
                {              
                    echo "<table>";
                    echo "<tr>";
                        echo "<th>TRAIN_LINE</th>";
                        echo "<th>ROUTE_NAME</th>";
                        echo "<th>RUN_NUMBER</th>";
                        echo "<th>OPERATOR_ID</th>";
                    echo "</tr>";  

                    while ($row = mysqli_fetch_array($result))
                    {
                        echo "<tr>";
                            echo "<td>" . $row['TRAIN_LINE'] . "</td>";
                            echo "<td>" . $row['ROUTE_NAME'] . "</td>";
                            echo "<td>" . $row['RUN_NUMBER'] . "</td>";
                            echo "<td>" . $row['OPERATOR_ID'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }

                else
                {
                    echo "No rows found";
                }
            } 
            
            else 
            {
                echo "Cannot connect to MySQL";
            }
        }
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="style.php" media="screen">
    </head>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="data" />
            <input type="submit"/>
            <br/>
            <br/>
            <label for="order">Order by:</label>
            <select name="order" id="order">
                <option value="TRAIN_LINE">TRAIN_LINE</option>
                <option value="ROUTE_NAME">ROUTE_NAME</option>
                <option value="RUN_NUMBER">RUN_NUMBER</option>
                <option value="OPERATOR_ID">OPERATOR_ID</option>
            </select>
            <select name="way" id="way">
                <option value="ASC">Ascending</option>
                <option value="DESC">Descending</option>
            </select>
            <input type="submit" value="Search" name="search">
        </form>
    </body>
</html>