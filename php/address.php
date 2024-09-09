<?php

    $errmsg=array();

    
    $chk_address="";
    $i_email="";
    $i_phone="";
    $a_query="select * from info where user_id = $_SESSION[user_id]";    
    $a_result=mysqli_query($link, $a_query);
    if($a_result && mysqli_num_rows($a_result) > 0){  
        $a_row = mysqli_fetch_array($a_result);

        if($a_row['a_id'] == null){
            $a_id=array();
            $a_street=array();
            $a_city=array();
            $a_prov=array();
            $a_email=array();
            $a_phone=array();
            $a_sel=-1;
        }else{
            $a_id=explode("|", $a_row['a_id']);
            $a_street=explode("|", $a_row['street']);
            $a_city=explode("|", $a_row['city']);
            $a_prov=explode("|", $a_row['prov']);
            $a_email=explode("|", $a_row['cemail']);
            $a_phone=explode("|", $a_row['cphone']);
            $a_sel=$a_row['sel'];
        }
        $addr_count=count($a_street);
        if($addr_count>0){
            $chk_address=$a_street[$a_sel].", ".$a_city[$a_sel].", ".$a_prov[$a_sel];
            $i_phone=$a_phone[$a_sel];
        }
    }


//functions

function addr_add($s,$c,$p,$e,$ph,$sl,$link){

    $a_id_arr=$GLOBALS['a_id'];
    $a_street=$GLOBALS['a_street'];
    $a_city=$GLOBALS['a_city'];
    $a_prov=$GLOBALS['a_prov'];
    $a_phone=$GLOBALS['a_phone'];


    $s_stat=.1;
    $sql_q="";

    $a_id=mt_rand(1000000, 9999999); 

    while(array_keys($a_id_arr, $a_id)){
        $a_id=mt_rand(1000000, 9999999); 
    }

    array_push($a_id_arr,$a_id);
    array_push($a_street,$s);
    array_push($a_city,$c);
    array_push($a_prov,$p);
    array_push($a_phone,$ph);
    $a_sel=count($a_id_arr);

    $s_user_id = $_SESSION['user_id']; 
    $q_id=implode("|",$a_id_arr);
    $q_street=implode("|",$a_street);
    $q_city=implode("|",$a_city);
    $q_prov=implode("|",$a_prov);
    $q_phone=implode("|",$a_phone);

    $q_sel=$a_sel-1;
    if($sl==1){
        $sql_q=", sel = $q_sel";
        $s_stat=.2;
    }else if($a_sel==1){
        $sql_q=", sel = 0";
        $s_stat=.2;
    }
    $s_query="update info set a_id = '$q_id',street = '$q_street', city = '$q_city', prov = '$q_prov', cphone = '$q_phone' $sql_q where user_id = $s_user_id";    
    if ($link->query($s_query) === TRUE){
        echo $a_id+$s_stat;
    }else{
        array_push($errmsg, "Error on addr_add() function: ".$link->error);
    }

}

function addr_rem($id, $link){

    $a_id=$GLOBALS['a_id'];
    $a_street=$GLOBALS['a_street'];
    $a_city=$GLOBALS['a_city'];
    $a_prov=$GLOBALS['a_prov'];
    $a_sel=$GLOBALS['a_sel'];
    // $a_email=$GLOBALS['a_email'];
    $a_phone=$GLOBALS['a_phone'];

    foreach (array_keys($a_id, $id) as $key) {
        $targ=$key;
    }
    
    if(isset($targ)){
        $a_id=array_merge(array_slice($a_id,0,$targ), array_slice($a_id,$targ+1));
        $a_street=array_merge(array_slice($a_street,0,$targ), array_slice($a_street,$targ+1));
        $a_city=array_merge(array_slice($a_city,0,$targ), array_slice($a_city,$targ+1));
        $a_prov=array_merge(array_slice($a_prov,0,$targ), array_slice($a_prov,$targ+1));
        // $a_email=array_merge(array_slice($a_email,0,$targ), array_slice($a_email,$targ+1));
        $a_phone=array_merge(array_slice($a_phone,0,$targ), array_slice($a_phone,$targ+1));
        if($a_sel>count($a_id)-1){
            $a_sel=count($a_id)-1;
        }
        // if($a_sel==$targ){
        //     $a_sel=count($a_street)-2;
        // }else if($a_sel > $targ){
        //     $a_sel-=1;
        // }
        
        $s_user_id = $_SESSION['user_id']; 
        if(count($a_id)>0){
            $q_id=implode("|",$a_id);
            $q_street=implode("|",$a_street);
            $q_city=implode("|",$a_city);
            $q_prov=implode("|",$a_prov);
            // $q_email=implode("|",$a_email);
            $q_phone=implode("|",$a_phone);
        }else{
            $q_id=null;
            $q_street=null;
            $q_city=null;
            $q_prov=null;
            $q_email=null;
            $q_phone=null;
        }
        
        $q_sel=$a_sel;
        $s_query="update info set a_id = '$q_id', street = '$q_street', city = '$q_city', prov = '$q_prov', cphone = '$q_phone', sel = $q_sel where user_id = $s_user_id";    
        if ($link->query($s_query) === TRUE) {
            echo show_def($link);
        }else{
            array_push($errmsg, "Error on addr_rem() function: ".$link->error);
        }
    }
    
}

function addr_edt($id, $street, $city, $prov,$email,$phone, $link){
    $a_id=$GLOBALS['a_id'];
    $a_street=$GLOBALS['a_street'];
    $a_city=$GLOBALS['a_city'];
    $a_prov=$GLOBALS['a_prov'];
    // $a_email=$GLOBALS['a_email'];
    $a_phone=$GLOBALS['a_phone'];

    foreach (array_keys($a_id, $id) as $key) {
        $targ=$key;
    }

    if(isset($targ)){

    $a_street[$targ]=$street;
    $a_city[$targ]=$city;
    $a_prov[$targ]=$prov;
    // $a_email[$targ]=$email;
    $a_phone[$targ]=$phone;

    $s_user_id = $_SESSION['user_id']; 
    if(count($a_id)>0){

        $q_street=implode("|",$a_street);
        $q_city=implode("|",$a_city);
        $q_prov=implode("|",$a_prov);
        // $q_email=implode("|",$a_email);
        $q_phone=implode("|",$a_phone);
    }
    
    $s_query="update info set street = '$q_street', city = '$q_city', prov = '$q_prov', cphone = '$q_phone' where user_id = $s_user_id";    
    if ($link->query($s_query) === TRUE) {
        echo 1;
    }else{
        echo "Error on addr_edt() function: ".$link->error;
    }

    }

}

function addr_upd($link){

    $GLOBALS['chk_address']="";
    $a_query="select * from info where user_id = $_SESSION[user_id]";    
    $a_result=mysqli_query($link, $a_query);
    if($a_result && mysqli_num_rows($a_result) > 0){  
        $a_row = mysqli_fetch_array($a_result);

        if($a_row['street'] == null){
            $GLOBALS['a_id']=array();
            $GLOBALS['a_street']=array();
            $GLOBALS['a_city']=array();
            $GLOBALS['a_prov']=array();
            // $GLOBALS['a_email']=array();
            $GLOBALS['a_phone']=array();
            $GLOBALS['a_sel']=-1;
        }else{
            $GLOBALS['a_id']=explode("|", $a_row['a_id']);
            $GLOBALS['a_street']=explode("|", $a_row['street']);
            $GLOBALS['a_city']=explode("|", $a_row['city']);
            $GLOBALS['a_prov']=explode("|", $a_row['prov']);
            // $GLOBALS['a_email']=explode("|", $a_row['cemail']);
            $GLOBALS['a_phone']=explode("|", $a_row['cphone']);
            $GLOBALS['a_sel']=$a_row['sel'];
        }
 
        if(count($GLOBALS['a_street'])>0){
            $GLOBALS['chk_address']=$GLOBALS['a_street'][$GLOBALS['a_sel']].", ".$GLOBALS['a_city'][$GLOBALS['a_sel']].", ".$GLOBALS['a_prov'][$GLOBALS['a_sel']];
        }
    }

}
function addr_list_chk(){

    $a_id=$GLOBALS['a_id'];
    $a_street=$GLOBALS['a_street'];
    $a_city=$GLOBALS['a_city'];
    $a_prov=$GLOBALS['a_prov'];
    // $a_email=$GLOBALS['a_email'];
    $a_phone=$GLOBALS['a_phone'];
    $a_sel=$GLOBALS['a_sel'];
    $addr_count=$GLOBALS['addr_count'];

    echo ' 

    <div class="card card-header-actions mb-4">
        <div class="card-body px-0" id="tbody">';
    for($x=0;$x<$addr_count;$x++){

echo '<div class="d-flex align-items-center justify-content-between px-4" id="'.$a_id[$x].'">
        <div class="d-flex align-items-center">
            
            <div class="ms-4">
                <div class="strong" id="'.$a_id[$x].'addr">'.$a_street[$x].', '.$a_city[$x].', '.$a_prov[$x].'</div>
                <div class="small text-muted" id="'.$a_id[$x].'addr2" hidden></div>
                <div class="small text-muted" id="'.$a_id[$x].'addr3">'.$a_phone[$x].'</div>
            </div>
            <input type="text" id="'.$a_id[$x].'s" value="'.$a_street[$x].'" hidden>
            <input type="text" id="'.$a_id[$x].'c" value="'.$a_city[$x].'" hidden>
            <input type="text" id="'.$a_id[$x].'p" value="'.$a_prov[$x].'" hidden>
            <input type="text" id="'.$a_id[$x].'e" value="" hidden>
            <input type="text" id="'.$a_id[$x].'ph" value="'.$a_phone[$x].'" hidden>
        </div>
        <div class="ms-4 small" id="'.$a_id[$x].'def_div">';
        
        if($a_sel == $x){
            echo '<div class="badge bg-light text-dark me-3" id="'.$a_id[$x].'def">Default</div>';
        }
            
            echo'
            <a style="cursor:pointer;" data-toggle="modal" data-target="#modal" onclick="sel_btn('.$a_id[$x].')">Select</a>
        </div>
    </div>
    <hr id="'.$a_id[$x].'hr">';

    }
    echo '
        </div>
            </div>
            <hr class="mt-0 mb-4">';
}

function addr_list(){

    $a_id=$GLOBALS['a_id'];
    $a_street=$GLOBALS['a_street'];
    $a_city=$GLOBALS['a_city'];
    $a_prov=$GLOBALS['a_prov'];
    $a_sel=$GLOBALS['a_sel'];
    // $a_email=$GLOBALS['a_email'];
    $a_phone=$GLOBALS['a_phone'];
    $addr_count=$GLOBALS['addr_count'];

    for($x=0;$x<$addr_count;$x++){

echo '<div class="d-flex align-items-center justify-content-between px-4" id="'.$a_id[$x].'">
        <div class="d-flex align-items-center">
            
            <div class="ms-4">
                <div class="strong" id="'.$a_id[$x].'addr">'.$a_street[$x].', '.$a_city[$x].', '.$a_prov[$x].'</div>
                <div class="small text-muted" id="'.$a_id[$x].'addr2"></div>
                <div class="small text-muted" id="'.$a_id[$x].'addr3">'.$a_phone[$x].'</div>
            </div>
            <input type="text" id="'.$a_id[$x].'s" value="'.$a_street[$x].'" hidden>
            <input type="text" id="'.$a_id[$x].'c" value="'.$a_city[$x].'" hidden>
            <input type="text" id="'.$a_id[$x].'p" value="'.$a_prov[$x].'" hidden>
            <input type="text" id="'.$a_id[$x].'e" value="" hidden>
            <input type="text" id="'.$a_id[$x].'ph" value="'.$a_phone[$x].'" hidden>
        </div>
        <div class="ms-4 small" id="'.$a_id[$x].'def_div">';
        
        if($a_sel == $x){
            echo '<div class="badge bg-light text-dark me-3" id="'.$a_id[$x].'def">Default</div>';
        }else{
            echo '<a class="text-muted me-3" style="cursor:pointer;" onclick="swtch_def('.$a_id[$x].')" id="'.$a_id[$x].'def">Make Default</a>';
        }
            
            echo'
            <a style="cursor:pointer;" data-toggle="modal" data-target="#modal" onclick="edt_btn('.$a_id[$x].')">Edit</a>
            <i class="fa fa-trash ms-2" style="cursor: pointer;" onclick="del_btn('.$a_id[$x].')" data-toggle="modal" data-target="#del_modal"></i>
        </div>
    </div>
    <hr id="'.$a_id[$x].'hr">';

    }

}

function show_def($link){
    addr_upd($link);
    $a_id=$GLOBALS['a_id'];
    $a_sel=$GLOBALS['a_sel'];

    if(count($a_id)>0){
        return $a_id[$a_sel];
    }else{
        return 0;
    }


}

function swtch_def($id,$link){
    $old_a_id=$GLOBALS['a_id'][$GLOBALS['a_sel']];
    foreach (array_keys($GLOBALS['a_id'], $id) as $key) {
        $targ=$key;
    }

    if(isset($targ)){
        $query="update info set sel = '$targ' where user_id = $_SESSION[user_id]";    
        if ($link->query($query) === TRUE) {
            echo $old_a_id;
            addr_upd($link);


        }else{
            echo "Error on addr_edt() function: ".$link->error;
        }
    }


}
