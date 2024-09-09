<?php

function get_categories($link){

    function get_cetg_count($link){
        $sql="SELECT count(DISTINCT category) as category FROM inventory";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            return $row['category'];
        }else{
            return 0;
        }
    }
    $count=0;
        $str2='';
            $str1='';
            $sql="SELECT DISTINCT category FROM inventory";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                while($row = mysqli_fetch_array($result)){

                    if($row['category']!=""){

                    
                    $count+=1;
                    if($count==get_cetg_count($link)){
                        $str2.= $row['category'] ;
                    }else{
                        $str2.= $row['category'] .',';
                    }
                    }
                };
               
            }
        
            // round($tax, 2)
            $str3='';
            $str=$str1.$str2.$str3;
            return $str;
    
    
    }

    