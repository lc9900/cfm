<?php
    require_once 'base_function.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <style type='text/css'>
            
            body {
              padding-top: 40px;
              padding-bottom: 40px;
              /*background-color: #f5f5f5;*/
              background-color: #bee5c6;
            }
      
            .form-signin {
              max-width: 300px;
              padding: 19px 29px 29px;
              margin: 0 auto 20px;
              background-color: #fff;
              border: 1px solid #e5e5e5;
              -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                      border-radius: 5px;
              -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                 -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                      box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
              margin-bottom: 10px;
            }
            .form-signin input[type="text"],
            .form-signin input[type="password"] {
              font-size: 16px;
              height: auto;
              margin-bottom: 15px;
              padding: 7px 9px;
            }
            
        </style>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        
        <script>
            $(document).ready(function(){
                $('#login').submit(function(){
                    var formData = $(this).serialize();
                    $.post('login.php',formData,processData);
                    
                    function processData(data){
                        if(data == 'fail'){
                            $('#loginerror').html("<strong>Oh Snap!</strong> Log in failed. Cap-lock?");
                            $('#loginerror').addClass('alert alert-danger');
                        }
                        else{
                            window.location.href = "home.php";
                        }
                    } //end of processData
                    return false;
                }); //End of submit
            }); //End of ready
        
        
        </script>
    </head>
    <body>
        <div class='container'>
            <form class='form-signin' method='post' action='login.php' id='login'>
                <h2 class='form-signin-heading'><a href='joinup.php'><em>Join up</em></a> and Dish</h2>
                <div id='loginerror'></div>
                <input type='text' class='input-large' placeholder='Username' name='user'/>
                <input type='password' class='input-large' placeholder='Password' name='password'/>
                <label class='checkbox'>
                    <input type='checkbox' value='1' name='keepme'>Keep me in longer
                </label>
                <button type='submit' class='btn btn-large btn-success'>Sign In</button>
            </form>
            
        </div>
    
    
    </body>
</html>