<?php
session_start();
if(isset($_SESSION["user"]))
{
    header("Location: indexStudent.php");
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

            $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
         
            $errors = array();

            if(empty($firstname) OR empty($surname) OR
            empty($email) OR empty($pass) 
            OR empty($passwordRepeat) OR empty($groupID))
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
                array_push($errors, "Select your assigned class!");
            }

            require_once "database.php";

            $sql = "SELECT * FROM Student_users WHERE email = '$email'";
        
            $result = mysqli_query($conn, $sql);

            $rowCount = mysqli_num_rows($result);

            if($rowCount > 0) {
                array_push($errors, "Email already exists!");
            }

            if(count($errors) > 0) {
                foreach($errors as $error)
                    echo $error;
            } else 
            {

                require_once "database.php";
                
                
                $sql = "INSERT INTO Student_users (firstname, surname, email, password, GroupID) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

                
                $sql1 = "INSERT INTO Subjects (studentID, Programming, Math, Physics, GroupID) VALUES (?, ?, ?, ?, ?)";
                $stmt1 = mysqli_stmt_init($conn);
                $prepareStmt1 = mysqli_stmt_prepare($stmt1, $sql1);


                if($prepareStmt)
                {
                    //INSERT DATA INTO Student_user   
                    mysqli_stmt_bind_param($stmt, "sssss",$firstname, $surname, $email, $passwordHash, $groupID);
                    mysqli_stmt_execute($stmt);
                    
                } else {
                    die("Something went wrong");
                    echo ini_set('display_errors','1');
                }
            
                
                //INSERT DATA INTO Subjects based on student's ID(1 row for each student 
                //and each row corresponds to a student entry from Student_users)
                $grabID = "SELECT * FROM Student_users WHERE email = '$email'";
                $result1 = mysqli_query($conn, $grabID);
                $final = mysqli_fetch_array($result1, MYSQLI_ASSOC);
                $studentID = $final["id"];

                if(!empty($studentID))
                {
                    $dummy_text= "To be graded";
                    mysqli_stmt_bind_param($stmt1, "sssss", $studentID, $dummy_text, $dummy_text, $dummy_text, $groupID);
                    mysqli_stmt_execute($stmt1);
                    echo "<div> You registered successfully! </div>";                
                } 
            }

        }
        ?>

        <center><h1>Student registration</h1></center>
        
        <center>
            <form action="signupStudent.php" method="post"> 
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
                            <center><input type="submit" value="Register" name="submit"></center>
                        </td>
                    </tr>   
                </table>
            </form>
        </center>
        
        <center>
            <p>Already have an account? <a href = "loginStudent.php">Login here</a></p> 
        </center>
    </body>
    
    <script>
    if(window.history.replaceState) 
    {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>

</html>