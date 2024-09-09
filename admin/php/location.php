<?php


function load_location($link){

    $sql="SELECT * FROM locations";

    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
           
            // <th>Name</th>
            // <th>Street</th>
            // <th>City</th>
            // <th>Province</th>
            // <th>Email</th>
            // <th>Contno</th>
            echo '<tr>
                <td>'.$row['name'].'</a></td> 
                <td>'.$row['street'].'</a></td> 
                <td>'.$row['city'].'</a></td> 
                <td>'.$row['province'].'</a></td> 
                <td>'.$row['email'].'</a></td> 
                <td>'.$row['contno'].'</a></td> 
                <td><i class="fa fa-edit" href="location.html?l_id='.$row['location_id'].'" style="text-decoration:none;"></i></td>
            </tr>';
    
    
        }

    }




}
