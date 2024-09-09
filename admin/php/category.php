<?php


function count_category($link){

        $sql="SELECT count(c_id) as 'count' FROM categories";
        if($result = mysqli_query($link, $sql)){ 
            $row = mysqli_fetch_array($result);
            return $row['count'];
        }
    

}

function load_category($link){

    $sql="SELECT * FROM categories";

    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
           
            echo '<tr>
                <td>'.$row['c_id'].'</td> 
                <td id="'.$row['c_id'].'name">'.$row['name'].'</a></td> 
                <td id="'.$row['c_id'].'desc">'.$row['description'].'</a></td>
                <td><i class="fa fa-edit" style="cursor: pointer;" onclick="upd_catg_onclick('.$row['c_id'].');"  data-toggle="modal" data-target="#updmodal"></i></td>
                <td><i class="fa fa-trash" style="cursor: pointer;" onclick="del_catg_onclick('.$row['c_id'].');"   data-toggle="modal" data-target="#delmodal"></i></td>
            </tr>';
    
    
        }

    }




}

function add_category($link,$name,$desc){
    $sql="SELECT name FROM categories WHERE name='$name'";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) < 1){
            $dte=date('Y-m-d').' '.date('H:i:s');
            $dtedisp=date('m/d/Y').' at '.date('g:iA');
            $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');
          
            $c_id=mt_rand(1000000,9999999);   
            $sql="SELECT c_id FROM categories where c_id=$c_id"; 
            $result=mysqli_query($link, $sql);     
            if($result && mysqli_num_rows($result) > 0){      
                $user_data=mysqli_fetch_assoc($result);      
                while($c_id ==  $user_data['item_id']){    
                    $c_id=mt_rand(1000000,9999999);     
                }
          
            }
          
                        $sql="INSERT INTO categories values ($c_id,'$name','$desc',
                        '$dte','$dtedisp',$dtenum)"; 
                        if ($link->query($sql) === TRUE) {
          
                            echo 1;
          
                        }else{
                
                            echo "Error : " . $link->error;
                        }
         }else{
            echo 4;
         }
    

    
    



}

function upd_category($link,$id,$name,$desc){
    $sql="SELECT name FROM categories WHERE c_id=$id";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        $oldname= $row['name'];
    }

                $sql="UPDATE categories set name ='$name', description='$desc' WHERE c_id=$id"; 
                if ($link->query($sql) === TRUE) {
  
                    $sql="UPDATE inventory set category ='$name' where category = '$oldname'"; 
                    if ($link->query($sql) === TRUE) {
                        $sql="UPDATE items set category ='$name' where category = '$oldname'"; 
                        if ($link->query($sql) === TRUE) {
                            echo 1;
                        }else{
                            echo "Error : " . $link->error;
                        }
                    }else{
                
                        echo "Error : " . $link->error;
                    }

  
                }else{
        
                    echo "Error : " . $link->error;
                }



}

function del_category($link,$id,$type){

    $sql="SELECT name FROM categories WHERE c_id=$id";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        $name= $row['name'];
    }


    $sql="DELETE FROM categories WHERE c_id=$id"; 
    if ($link->query($sql) === TRUE) {
        if($type==2){
            $sql="UPDATE inventory set category ='' where category = '$name'"; 
            if ($link->query($sql) === TRUE) {
                $sql="UPDATE items set category ='' where category = '$name'"; 
                if ($link->query($sql) === TRUE) {
                    return 2;
                }else{
                    return "Error : " . $link->error;
                }
            }else{
        
                return "Error : " . $link->error;
            }
            
        }
        return 1;
    }else{

        return "Error : " . $link->error;
    }






}


function get_categories($link){

function get_cetg_count($link){
    $sql="SELECT count(c_id) as 'count' FROM categories";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['count'];
    }else{
        return 0;
    }
}
$count=0;
    $str2='';
        $str1='';
        $sql="SELECT * FROM categories";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){
                $count+=1;
                if($count==get_cetg_count($link)){
                    $str2.= $row['name'] ;
                }else{
                    $str2.= $row['name'] .',';
                }

            };
           
        }
    
        // round($tax, 2)
        $str3='';
        $str=$str1.$str2.$str3;
        return $str;


}