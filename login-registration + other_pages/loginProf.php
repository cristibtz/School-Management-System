<?php
session_start();
if(isset($_SESSION["user_prof"]))
{
    header("Location: indexProf.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Form</title>
    </head>

    <body>
        <?php
        if(isset($_POST["login"]))
        {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM Professor_users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if($user) 
            {
                if(password_verify($password, $user["password"]))
                {
                    session_start();
                    $_SESSION["user_prof"]= "yes";
                    $_SESSION["firstname_prof"] = $user["firstname"];
                    $_SESSION["surname_prof"] = $user["surname"];
                    $_SESSION["groupID_prof"] = $user["GroupID"];
                    $_SESSION["subject"] = $user["subject"];
                    $_SESSION["id_prof"] = $user["ID"];

                    header("Location: indexProf.php");
                    die();
                } else
                {
                    echo "<div> Password doesn't match! </div>";

                }
            } else 
            {
                echo "<div> Email doesn't exist! </div>";
            }
        }
        ?>
        <center><h1>Professor Login</h1></center>

        <center>
            <form action="loginProf.php" method="post"> 
                <table>

                    <tr>   
                        <td><input type="email" name="email" placeholder="Email address"></td>
                    </tr>
                    
                    <tr>
                        <td><input type="password" name="password" placeholder="Password"></td>
                    </tr>
                        
                    <tr>
                        <td><center><input type="submit" value="Login" name="login"></center></td>
                    </tr>   

                </table>
            </form>
        </center>
    
        <center>
            <p> Not registered yet? <a href = "signupProf.php">Register here</a></p> 
        </center>
    
    </body>

</html>
