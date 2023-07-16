<?php 

//This file shows all the things from the Documentation

    //Basics

        //Variables --------------------------------------------------------------------------------------
            echo "<h3>Variables: </h3>";

            //How to print them
            $a = 1;                           
            $b = "Apple";

            echo $a . "<br>"; // '. "<br>"' is just here for a linebreak to make the resut look better
            echo $b . "<br>";   
            
            echo "<br>";

            //How to connect them
            $a = 2;
            $b = "Lemon";

            $c = $a . $b;

            echo $c . "<br>";
            echo $a . $b . "<br>";
        //------------------------------------------------------------------------------------------------


        //Arrays -----------------------------------------------------------------------------------------
            echo "<h3>Arrays: </h3>";

            //Array:
            $cars = array("Volvo", "BMW", "Toyota");

            print_r($cars);

            echo "<br>";

            //Array inside of Array:
            $cars_audi = array("A4", "A5", "A7");
            $cars_bmw = array("M5", "M8", "X5");
            $cars_vw = array("Golf", "Polo", "tiguan");

            $brands = array($cars_audi, $cars_bmw, $cars_vw);

            print_r($brands);

            echo "<br>";

            //Array with spasific names
            $row = array("ID" => "1", "FirstName" => "John", "LastName" => "Wick");

            print_r($row);

            echo "<br>";

            //address specific locations of an Array
            echo $cars[2] ."<br>";
            echo $brands[2][1] ."<br>";

            //Pretty way to output an Array
            echo "<pre>";print_r($cars);echo "</pre>";
        //------------------------------------------------------------------------------------------------



    //Database_PDO

        //Connection ------------------------------------------------------------------------------------
            $server = "localhost";
            $user = "root";
            $pw = "raspberry";
            $db = "CarDB";
            
            try {
            
                $con = new PDO(
                    "mysql:host=" . $server . ";" . 
                    "dbname=" . $db . ";" . 
                    "charset=utf8", $user, $pw);
            
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            } catch(Exception $e) {
            
                echo "Error - Connect to DB: " . $e->getCode() . ":" . $e->getMessage();
            
            }
        // ----------------------------------------------------------------------------------------------


        //Select ----------------------------------------------------------------------------------------        
            echo "<h3>Select: </h3>";

            //Bind parameters and execute select
            function createStatement($query, $arrayValues = NULL) {
                global $con; 
                $stmt = $con->prepare($query);
                $stmt->execute($arrayValues);
                return $stmt;
            }

            //Call the select and get result (without parameters)
            function select_all_brands() {
                $select_all_brands = "
                    SELECT * FROM cardb.brand
                ;";
        
                $select_all_brands = createStatement($select_all_brands);
        
                return $select_all_brands->fetchAll(PDO::FETCH_ASSOC);
            }

            //This is how to call it
            $rows = select_all_brands();

            echo "<pre>";
            print_r($rows);
            echo "</pre>";

            echo "<br>";

            //Output specific data record
            echo $rows[2]["Brand_Name"];

            //Call the select and get result (with parameters)
            function select_brands_where($brand_name) {
                $select_brands_where = "
                    SELECT * FROM cardb.brand
                    WHERE Brand_Name = ?
                ;";
        
                $values = array($brand_name);
                $select_brands_where = createStatement($select_brands_where, $values);
        
                return $select_brands_where->fetchAll(PDO::FETCH_ASSOC);
            }  

            $rows_where = select_brands_where("Honda");

            echo "<pre>";
            print_r($rows_where);
            echo "</pre>";

            echo "<br>";

            //Call a select with LIKE
            function select_brands_where_like($search_for) {
                $select_brands_where_like = "
                    SELECT * FROM cardb.owner
                    WHERE Owner_LastName LIKE ?
                ;";

                $values = array($search_for);
                $select_brands_where_like = createStatement($select_brands_where_like, $values);
        
                return $select_brands_where_like->fetchAll(PDO::FETCH_ASSOC);
            }  

            $rows_where_like = select_brands_where_like("M%");

            echo "<pre>";
            print_r($rows_where_like);
            echo "</pre>";

            echo "<br>";

            //Call a select with more then one parameter
            function select_car_by_color_and_hp($color, $min_horsepower) {
                $select_car_by_color_and_hp = "
                    SELECT * FROM cardb.car c
                    LEFT JOIN cardb.color co ON c.Color_ID = co.Color_ID
                    WHERE co.Color_Name = ?
                    AND c.Car_Horsepower > ?
                ;";

                $values = array($color, $min_horsepower);
                $select_car_by_color_and_hp = createStatement($select_car_by_color_and_hp, $values);
        
                return $select_car_by_color_and_hp->fetchAll(PDO::FETCH_ASSOC);
            }  

            $rows_color_hp = select_car_by_color_and_hp("Silver", 400);

            echo "<pre>";
            print_r($rows_color_hp);
            echo "</pre>";

            echo "<br>";
        // ----------------------------------------------------------------------------------------------


        //Insert ----------------------------------------------------------------------------------------   

            echo "<h3>Insert: </h3>";

            //Call the insert and execute it
            function insert_brand($new_brand_name) {
                $existing_brands = select_all_brands();
  
                foreach ($existing_brands as $brand) {
                    if ($brand["Brand_Name"] == $new_brand_name) {
                        return 'Brand is already existing!';
                    }
                }
            
                $insert_brand = "
                    INSERT INTO cardb.brand (Brand_Name) 
                    VALUES (?)
                ;";

                $values = array($new_brand_name);
            
                if (createStatement($insert_brand, $values)) {
                    return 'Brand inserted!';
                }
            
                return 'Something went wrong while inserting new brand!';
            }

            ?>

                <!-- Just a simple form for you to input a brand -->
                <form action="./TestMe.php" method="post">
                    <label for="brand_name">New brand name:</label>
                    <input type="text" name="brand_name" id="brand_name">
                    <button name="insert_brand" type="submit">Insert</button>
                </form>

            <?php

            if(isset($_POST["insert_brand"])) {

                $msg = insert_brand($_POST["brand_name"]);

                echo "<br>" . $msg . "<br>";

            }

            $all_brands = Select_all_brands();

            //here i use a function which is explained in "Useful Functions"
            printTable($all_brands);

            echo "<br>";
            
        // ----------------------------------------------------------------------------------------------


        // Update ---------------------------------------------------------------------------------------

            echo "<h3>Update: </h3>";
            
            //Call the update and execute it
            function update_brand_by_id($new_brand_name, $brand_id) {
                $update_brand_by_id = "
                    UPDATE cardb.brand
                    SET Brand_Name = ? 
                    WHERE Brand_ID = ?
                ;";

                $values = array($new_brand_name, $brand_id);
            
                if (createStatement($update_brand_by_id, $values)) {
                    return 'Brand updated!';
                }
            
                return 'Something went wrong while updating a brand!';
            }

            ?>
            <!-- Just a simple form for you to update a brand -->
            <form action="./TestMe.php" method="post">
                <label for="new_brand_name">New brand name:</label>
                <input type="text" name="new_brand_name" id="new_brand_name">
                <br>
                <label for="brand_id">Brand id you want to change:</label>
                <input type="text" name="brand_id" id="brand_id">
                <button name="update_brand" type="submit">Update</button>
            </form>

            <?php

            if(isset($_POST["update_brand"])) {

                $msg = update_brand_by_id($_POST["new_brand_name"],$_POST["brand_id"]);

                echo "<br>" . $msg . "<br>";

            }

            $all_brands = Select_all_brands();

            //here i use a function which is explained in "Useful Functions"
            printTable($all_brands);

            echo "<br>";

        // ----------------------------------------------------------------------------------------------


        // Delete ---------------------------------------------------------------------------------------

            echo "<h3>Delete: </h3>";

            //Call the delete and execute it
            function delete_brand_by_id($brand_id) {
                $delete_brand_by_id = "
                    DELETE FROM cardb.brand
                    WHERE Brand_ID = ?
                ;";

                $values = array($brand_id);
            
                if (createStatement($delete_brand_by_id, $values)) {
                    return 'Brand deleted!';
                }
            
                return 'Something went wrong while deleting a brand!';
            }

            ?>
            <!-- Just a simple form for you to update a brand -->
            <form action="./TestMe.php" method="post">
                <label for="brand_id">Brand id you want to delete:</label>
                <input type="text" name="brand_id" id="brand_id">
                <button name="delete_brand" type="submit">Delete</button>
            </form>

            <?php

            if(isset($_POST["delete_brand"])) {

                $msg = delete_brand_by_id($_POST["brand_id"]);

                echo "<br>" . $msg . "<br>";

            }

            $all_brands = Select_all_brands();

            //here i use a function which is explained in "Useful Functions"
            printTable($all_brands);

            echo "<br>";
        // ----------------------------------------------------------------------------------------------


        // Useful Functions -----------------------------------------------------------------------------
            echo "<h3>Useful Functions: </h3>";
            echo "printTable():";

            //Print a table
            function printTable($select_result) {
                echo '<table>';
                echo '<thead><tr>';
                
                // Table headers
                foreach ($select_result[0] as $key => $value) {
                    echo '<th>' . $key . '</th>';
                }
                
                echo '</tr></thead>';
                echo '<tbody>';
                
                // Table rows
                foreach ($select_result as $row) {
                    echo '<tr>';
                    
                    foreach ($row as $value) {
                        echo '<td>' . $value . '</td>';
                    }
                    
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            }

            $all_brands = Select_all_brands();

            printTable($all_brands);

            echo "<br>";
            echo "With marker:";

            function printTablewithMarker($select_result, $marker = NULL) {
                echo '<table class="table">';
                echo '<thead><tr>';
            
                // Table headers
                foreach ($select_result[0] as $key => $value) {
                    echo '<th>' . $key . '</th>';
                }
            
                echo '</tr></thead>';
                echo '<tbody>';
            
                // Table rows
                foreach ($select_result as $row) {
            
                    if (strtolower($row["Brand_Name"]) == strtolower($marker)) {
                        echo '<tr style="color:red">';
                    } else {
                        echo '<tr>';
                    }
                    
                    foreach ($row as $value) {
                        echo '<td>' . $value . '</td>';
                    }
                    
                    echo '</tr>';
                }
                    
                echo '</tbody>';
                echo '</table>';
            }

            $all_brands = Select_all_brands();

            printTablewithMarker($all_brands, "VW");

            echo "<br>";

            
        // ----------------------------------------------------------------------------------------------

?>