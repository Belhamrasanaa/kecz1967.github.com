<?php
session_start();


$con = mysqli_connect("localhost", "root", "", "boissons3");
if (!$con) {
    echo "Vous n'êtes pas connecté à la base de données";
}


$error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    
    $sql = "SELECT * FROM admin WHERE nom_admin='$user' AND pass_admin='$pass'";

    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
    
        $_SESSION['loggedin'] = true; 
        $_SESSION['username'] = $user; 
       
 
        if(isset($_POST["remember"])) {
           
            setcookie("remember_username", $user, time() + (7 * 24 * 60 * 60));  
            setcookie("remember_password", $pass, time() + (7 * 24 * 60 * 60)); 
        }
       
     
        header("Location: navbar.php");
        exit;
    } else {
     
        $error_message = "Identifiants incorrects. Veuillez réessayer.";
    }
   
}


if(isset($_GET['logout'])) {
   
    setcookie("remember_username", "", time() - 3600);  
    setcookie("remember_password", "", time() - 3600);  
   
    header("Location: login.php");
    exit;
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
     @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

* {
    box-sizing: border-box;
}

body {
    font-family: 'Montserrat', sans-serif;
    background: linear-gradient(135deg, black 0%, #3e893e 100%); Image de fond professionnelle */
    background-size: cover, cover;
    background-blend-mode: overlay;
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.container {
    display: flex;
    width: 800px;
    height: 500px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
}

.login-section, .image-section {
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-section {
    flex: 4; 
    background-color: rgba(0, 0, 0, 0.8);
    padding: 20px;
    text-align: center;
    border-radius: 10px;
}

.login-section h1 {
    margin: 0;
    color: white;
    font-size: 2.5em;
}

.login-section .input-container {
    position: relative;
    margin: 10px 0;
}

.login-section input {
    background-color: #333;
    border: none;
    padding: 12px 40px;
    margin: 10px 0;
    width: 100%;
    color: white;
    border-radius: 5px;
}

.login-section input:focus {
    outline: none;
    border-color: #4CAF50;
}

.login-section .input-container i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}

.login-section button {
    border-radius: 20px;
    border: none;
    background: linear-gradient(to right, #3e733e, #d0e6d8);
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 12px 45px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: background 0.3s, transform 0.3s;
}

.login-section button:hover {
    background: linear-gradient(to right, #345b34, #b8d8b0);
}

.login-section button:active {
    transform: scale(0.95);
}

.login-section button:focus {
    outline: none;
}

.login-section a {
    color: #4CAF50;
    font-size: 14px;
    text-decoration: none;
    margin: 15px 0;
    display: block;
}

.login-section p {
    font-size: 14px;
    font-weight: 100;
    margin: 20px 0;
    color: white;
}

.image-section {
    flex: 6;
    background: url('sara.png') no-repeat center center;
    background-size: cover;
}


    </style>
</head>
<body>

    <div class="container">
        <!-- Formulaire de connexion -->
        <div class="login-section">
            <form method="post">
                <h1 >Connexion</h1>
                <br>
                <br>
              
                <div class="input-container">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required />
                </div>
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Mot de passe" required />
                </div>
                <label>
                    <input type="checkbox" name="remember" /> Se souvenir de moi
                </label>
                <br>
                <button type="submit">Se Connecter</button>
                <br>
                <a href="request_password_reset.php">Mot de passe oublié ?</a>
                <?php if (!empty($error_message)): ?>
                    <p id="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            </form>
        </div>
        <!-- Image Section -->
        <div class="image-section"></div>
    </div>
</body>
</html>