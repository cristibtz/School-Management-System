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
        <title>Registration Form</title>
    </head>

    <body>
        <?php
        ini_set("display_errors", '1');
        if(isset($_POST["submit"]))
        {
            $firstname = $_POST["firstname"];
            $surname = $_POST["surname"];
            $email = $_POST["email"];
            $pass = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            $groupID = $_POST["groupID"];
            $subject = $_POST["subject"];

            $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
            
            $errors = array();
            if(empty($firstname) OR empty($surname) OR
            empty($email) OR empty($pass) 
            OR empty($passwordRepeat) OR empty($groupID) OR empty($subject))
            {
                array_push($errors,"All fields are required! ");
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                array_push($errors, "Email is not valid! ");
            }

            if(strlen($pass) < 8)
            {
                array_push($errors, "Password must be at least 8 characters long! ");
            }

            if($pass !== $passwordRepeat)
            {
                array_push($errors, "Passwords don't match! ");
            }
         
            if(empty($groupID))
            {
                array_push($errors, "Select your assigned class! ");
            }

            if(empty($subject))
            {
                array_push($errors, "Select your taught subject! ");
            }

            require_once "database.php";

            $sql = "SELECT * FROM Professor_users WHERE email = '$email'";
        
            $result = mysqli_query($conn, $sql);

            $rowCount = mysqli_num_rows($result);

            if($rowCount > 0) 
            {
                array_push($errors, "Email already exists!");
            }

            $sql = "SELECT * FROM Professor_users WHERE GroupID = '$groupID'";
            $sql1 = "SELECT * FROM Professor_users WHERE subject = '$subject' AND GroupID = '$groupID'";

            $result = mysqli_query($conn, $sql);
            $result1 = mysqli_query($conn, $sql1);

            $rowCount = mysqli_num_rows($result);
            $rowCount1 = mysqli_num_rows($result1);
        
            if($rowCount1 > 0 )
            {
                array_push($errors, "There already is a $subject professor in that group. ");

            }

            if($rowCount == 3)
            {
                array_push($errors, "All the teachers for $groupID have already signedup. Check your class again!. ");
            }

            if(count($errors) > 0) {
                foreach($errors as $error)
                    echo $error;
            } else 
            {

                require_once "database.php";
                
                //INSERT DATA IN Professor_users
                $sql = "INSERT INTO Professor_users (firstname, surname, email, password, subject, GroupID) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

                if($prepareStmt)
                {
                    
                    mysqli_stmt_bind_param($stmt, "ssssss",$firstname, $surname, $email, $passwordHash, $subject, $groupID);
                    mysqli_stmt_execute($stmt);
                    echo "<div> You registered successfully! </div>";                

                    
                } else 
                {
                    die("Something went wrong");
                    echo ini_set('display_errors','1');
                }        
            }

        }
        ?>

        <center><h1>Professor registration</h1></center>

    
        <center>
            <form action="signupProf.php" method="post"> 
                <table>
                
                    <tr>
                        <td><input type="text" name="firstname" placeholder="First Name"></td>
                    </tr>

                    <tr>
                        <td><input type="text" name="surname" placeholder="Surname"></td>
                    </tr>

                    <tr>
                        <td><input type="email" name="email" placeholder="Email address"></td>
                    </tr>
                    
                    <tr>
                        <td><input type="password" name="password" placeholder="Password"></td>
                    </tr>
                        
                    <tr>
                        <td><input type="password" name="repeat_password" placeholder="Confirm password"></td>
                    </tr>

                    <tr>
                        <td>
                            <label for="groupID"> Select assigned class: 
                                <select name="groupID">
                                    <option name="" value=""></option>
                                    <option name ="1111" value="1111"> 1111 </option>
                                    <option name ="2222" value="2222"> 2222 </option>
                                    <option name ="3333" value="3333"> 3333 </option>
                                    <option name ="4444" value="4444"> 4444 </option>
                                    <option name ="5555" value="5555"> 5555 </option>
                                </select>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="subject"> Select taught subject: 
                                <select name = "subject">
                                    <option name="" value=""></option>
                                    <option name ="Programming" value="Programming"> Programming </option>
                                    <option name ="math" value="Math"> Math </option>
                                    <option name ="physics" value="Physics"> Physics </option>
                                </select>
                            </label> 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <center><input type="submit" value="Register" name="submit"></center>
                        </td>
                    </tr>   
                </table>
            </form>
        </center>

        <center>
            <p> Already have an account? <a href = "loginProf.php">Login here</a></p>
        </center>

    </body>

    <script>
    if(window.history.replaceState) 
    {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</html>