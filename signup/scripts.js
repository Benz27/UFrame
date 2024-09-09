    var y=0;
    var z;
    var x;
    var p1;
    var p2;

    var interc=false;   
    var einterc=false;  

function getuser() {   

        var str=document.getElementById("txtemail").value;
        var que="email";

    var xmlhttp=new XMLHttpRequest();     
    xmlhttp.onreadystatechange=function() {   
    if (this.readyState==4 && this.status==200) { 
    z=this.responseText;  
    y=Number(z);   

            if(y==1){
                einterc=true;   
            }else if(y==0){
                einterc=false; 
            }

            
            if (y==1){
                x=document.getElementById("txtemail").value;
                document.getElementById("txtemail").style.border="2px solid red";
                document.getElementById("alertp").innerHTML="Email was already taken!";
                document.getElementById("alertp").hidden=false;
            }else{
                x=document.getElementById("txtemail").value;
                document.getElementById("txtemail").style.border="1px solid #ced4da";
                document.getElementById("alertp").hidden=true;
                document.getElementById("alertp").innerHTML="";
            }


    }
  }
  
  xmlhttp.open("GET","../php/checkuser.php?q="+str+ "&qu=" + que,true);
  xmlhttp.send();
}

function fpass(){
 
p1=document.getElementById("txtpass").value;

}

function subm(){ 
    p1=document.getElementById("txtpass").value;
    p2=document.getElementById("txtconpass").value;
    var result = p1.localeCompare(p2);    
if(!einterc){  

    if(result==0){
        alert(result);
    // document.getElementById("form").submit();
    }else{

    document.getElementById("txtpass").style.border="2px solid red";
    document.getElementById("txtconpass").style.border="2px solid red";
    document.getElementById("alertp").innerHTML="Password did not match!";
    document.getElementById("alertp").hidden=false;
    document.getElementById("txtpass").value="";
    document.getElementById("txtconpass").value="";

    }

}

    
}

function passclick(){
    if(!einterc){
    document.getElementById("txtpass").style.border="none";
    document.getElementById("txtconpass").style.border="none";
    document.getElementById("alertp").innerHTML="";
    document.getElementById("alertp").hidden=true;
    }else{
        document.getElementById("txtpass").style.border="none";
    document.getElementById("txtconpass").style.border="none";
    }
 
}