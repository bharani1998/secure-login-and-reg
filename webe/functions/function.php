<?php


function clean($string)
{
    return htmlentities($string);
}

function redirect($location)
{
    return header("Location:$location");
}
function set_message($message)
{
    if(!empty($message)){
        $_SESSION['message']=$message;
        
    }
    else
    {
        $message="";
    }
}

function display()
{
    if(isset($_SESSION['message']))
    {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}


function emailu($mail)
{
  
    $sql = "SELECT id FROM users WHERE email='$mail'";
    $result = query($sql);
    if(row_count($result)>0)
    {
       
        return true;
    }
    else
    {
        
        return false;
    }
    
}


function sendmail($email,$subject,$msg)
{
return mail($email,$subject,$msg);
    
}

function generator()
{
    $token=$_SESSION['token']=md5(uniqid(mt_rand(),true));
                     return $token;
}


function validate()
{

    $errors=[];
        $min =3;
        $max=20;
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
  
        $name=clean($_POST['name']);
         $mail=clean($_POST['mail']);
         $city=clean($_POST['city']);
         $phone=clean($_POST['phone']);
         $password=clean($_POST['password']);
         $add=clean($_POST['address']);
        $cpass=clean($_POST['cpassword']);
        
        
        if($password!==$cpass)
        {
             $errors[] = " your password fields do not match ";
        }
        
        if(strlen($name)<$min)
        {
           $errors[] = " your  name cannot be less than {$min} chars";
        }  
        
        if(strlen($name)>$max)
        {
           $errors[] = " your  name cannot be more than {$max} chars";
        }
        if(emailu($mail))
        {
             $errors[] = "sorry that email already exists";
        }
        
        if(!empty($errors))
        {
            
            foreach($errors as $error)
            {
                echo '
                
                
                <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Warning!</strong>'   .$error  .'</div>
  

                
                
                
                ';
            }
        
        }
        else
        {
         registeruse($name,$mail,$city,$phone,$password,$add);
         
        }
    }
}

function registeruse($name,$mail,$city,$phone,$password,$add)
{

    $name = escape($name);
    $mail = escape($mail);
    $city = escape($city); 
    $phone = escape($phone);
    $password = escape($password);
    $add = escape($add);

     
        $code=md5(microtime());
        $password=md5($password);
        $sql="INSERT INTO users(name,email,city,contact,address,password,code,active)VALUES('$name','$mail','$city','$phone','$add','$password','$code',0)";
        $result=query($sql);
        confirm($result);
   echo '
   
   <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Success! Please check your email for activation link</strong></div>';
    
    $subject="Activate account";
    $msg=" Please click the link below to activate your account
        http://localhost/webe/activate.php?mail=$mail&code=$code";
       
    sendmail($mail,$subject,$msg);
        return true;
        
    }

function activateuser()
{
    
    if($_SERVER['REQUEST_METHOD']=="GET")
        
    {
        global $con;
        if(isset($_GET['mail']))
            
        {
        $mail=clean($_GET['mail']);
        $code=clean($_GET['code']);
            $sql="SELECT id FROM users WHERE email='".escape($_GET['mail'])."' AND code='".escape($_GET['code'])."' ";
            $result=query($sql);
            confirm($result);
            $row_count=row_count($result);
            if($row_count==1)
            {
            $sql2 = "UPDATE users SET active=1,code=0 WHERE email='".escape($mail)."'AND code='".escape($code)."' ";
            $result2=query($sql2);
            confirm($result2);
           set_message("<p class='bg-success'> Your account has been activated please login </p>");
                redirect("login.php");
            }
            else
            {
                set_message("<p class='bg-success'> Your account has been not activated please try again </p>");
                redirect("login.php");
            }
    }
    
}
}

function validatelogin()
{

    $errors=[];
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
$mail=clean($_POST['mail']);
$password=clean($_POST['password']); 
$remember=isset($_POST['remember']);
        
        
        if(empty($mail))
        {
           $errors[]="Email field cannot be empty"; 
        }
        if(empty($password))
        {
           $errors[]="Password field cannot be empty"; 
        }      
        
        
        
        if(!empty($errors))
        {
            
            foreach($errors as $error)
            {
                echo '  <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Warning!</strong>'   .$error  .'</div>
    
                                
                ';
            }


    }
        else
        {
         if(logo($mail,$password,$remember))
             {
             redirect("admin.php");
         }
            else
            {
             echo '
   
   <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Please check your credentials</strong></div>';   
                
            }
                
                
        }

}
}



function logo($mail,$password,$remember)
{
    $sql="SELECT password,id FROM users WHERE email='".escape($mail)."' AND active=1";
    $result=query($sql);
    if(row_count($result)==1)
    {
        
        
        $row = fetch_array($result);
        $db_pass=$row['password'];
        if(md5($password)==$db_pass)
            
        {
            if($remember=="on")
            {
                setcookie('mail',$mail,time()+86400);
            }
            
           $_SESSION['mail'] = $mail;
            
            return true;
        }     
        
    else
    {
        return false;
    }
        return true;
    }
    else
    {
        return false;
    }
}

function logged_in()
{
    if(isset($_SESSION['mail']) || isset($_COOKIE['mail']))
    {
        
        return true;
    }
    else
    {
        return false;
    }
}


function recover_password()
{
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        if(isset($_SESSION['token']) && $_POST['token']=== $_SESSION['token'])
        {
           $mail=clean($_POST['mail']);
             if(emailu($mail))
             {
                 $vali=md5(microtime());
                 setcookie('temp_access',$vali,time()+900);
                 
                 $sql="UPDATE users SET code='".escape($vali)."'WHERE email='".escape($mail)."'";
                 $result=query($sql);
                 confirm($result);
                 $subject="Please reset your password";
                 $message="Here is your password reset code {vali}
                 Click here to reset your password http://localhost/webe/code.php?mail=$mail&code=$vali";
                
                 if(!sendmail($mail,$subject,$message))
                 {
                                    echo '
   
   <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Failure!</strong></div>';      
                 }
                 else
                 {
echo '<div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Please check spam folder for a password reset code</strong></div>';  
                     
                     
                  
redirect("index.php");           
                     
                     
                     
                 }
                 
             }
            else
            {
              echo '
   
   <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Not found on database!</strong></div>';   
            }
        } 
        else
        {
         redirect("index.php");   
        }
        
        
        
        
        if(isset($_POST['cancel-submit']))
        {
            redirect("login.php");
        }
      
    }
}

function kuya(){

    if(isset($_COOKIE['temp_access'])){
        
             
             
              if(!isset($_GET['mail']) && !isset($_GET['code'])){
        
        
         redirect("index.php");  
        
                     
                  
                  
                  
        
        
              } else if((empty($_GET['mail'])) ||(empty($_GET['code']))){
                  
                  
                   redirect("index.php"); 
                  
                  
                  
                  
                  
              } else{
                  
                  
                  
                  
                  if(isset($_POST['code'])){
                                                
                      
                      
                  $mail= clean($_GET['mail']);
                      
         $validation_code= clean($_POST['code']);
                      
                      
                      $sql="SELECT id FROM users WHERE code= '".escape($validation_code)."'AND email='". escape($mail)."'";
                      
                      $result= query($sql);
                     
                     if(row_count($result)==1)
                     {
                         
                         
                     setcookie('temp_access',$vali,time()+3000);    
                         
                         redirect("reset.php?mail=$mail&code=$validation_code");
                     }
                      
                      else{  
                          
                         
          echo '
   <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Sorry your validation code is wrong</strong></div>';   
        
                          
                          
                          
                      }
      
                  
                  }
                  
                  
                  
                  
                  
              }
        
         
        
        
    }
 
        
       else{
    
          echo '
   
   <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Sorry your validation cookie is expired</strong></div>';   
        
        redirect("recover.php");
    }
                                               
                                               }



function mama()
{
    
    echo '  <div class="alert alert-warning alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Success!</strong></div>
    
                                
                ';
    
}





function password_reset(){
    $vali=md5(microtime());
    
    setcookie('temp_access',$vali,time()+900);
    
     if(isset($_COOKIE['temp_access'])){
         
         
         
          if(isset($_GET['mail'])&&isset($_GET['code']))
        
       {
         
         
     if(isset($_SESSION['token']) && isset($_POST['token']) && $_POST['token']=== $_SESSION['token']){    
    
        if(($_POST['password']) === ($_POST['confirm_password'])) 
        {
            
         $updated_password =md5($_POST['password']);         
    $sql="UPDATE users SET password='".escape($updated_password)."',code=0 WHERE email='".escape($_GET['mail'])."'";

       query($sql);
            
            redirect("login.php");
            
            
            
    
        
    
       
         
    }
    
   
          
        
        
        
        
        
        
       }
}else{
         echo '
   
   <div class="alert alert-danger alert-dismissible" role="alert">
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span> </button>
    <strong>Sorry your time has expired</strong></div>';    
         
         
      redirect("recover.php");   
         
         
         
     }
}


}









































                                               

                                              
                                              


?> 