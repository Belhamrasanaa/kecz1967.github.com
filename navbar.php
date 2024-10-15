
<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location:login.php");
    exit;
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Professional Design</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            height: 100vh;
            
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, black 0%, #3e893e 100%);
            background-blend-mode: overlay;
            background-size: cover;
            background-attachment: fixed;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
       

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0; /* Aligne la navbar à gauche */
            width: calc(100% - 20px); /* Réduit la largeur de la navbar pour créer un effet de décalage */
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: background 0.3s ease;
            margin-right:17x;
        }

      

        .navbar ul {
            list-style: none;
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .navbar ul li {
            margin-left: 30px;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 16px;
            position: relative;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
        }

        .navbar ul li a i {
            margin-right: 8px;
        }

        .navbar ul li a::before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: #fff;
            transition: width 0.3s ease, left 0.3s ease;
        }

        .navbar ul li a:hover::before {
            width: 100%;
            left: 0;
        }

        .navbar ul li a:hover {
            color:#3cb371;
        }

        /* Logout Button */
        .logout-button {
            background: #345b34;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .logout-button:hover {
           
            transform: translateY(-3px);
        }

        /* Hero Section */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            background-image: url('https://source.unsplash.com/1600x900/?technology,abstract');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .hero h1 {
            font-size: 60px;
            font-weight: bold;
            z-index: 1;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 1.5s ease-out;
        }

        .hero p {
            font-size: 20px;
            margin-top: 20px;
            z-index: 1;
            animation: fadeInUp 2s ease-out;
        }

        .hero .cta-button {
            background: #345b34;
            padding: 15px 30px;
            margin-top: 30px;
            font-size: 18px;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            z-index: 1;
            animation: fadeInUp 2.5s ease-out;
        }

        .hero .cta-button:hover {
            background:  #345b34;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Features Section */
        .features {
            display: flex;
            justify-content: space-around;
            padding: 60px 40px;
            background-color: black;
            color: #fff;
            text-align: center;
        }

        .features .feature-box {
            flex-basis: 30%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .features .feature-box:hover {
            transform: translateY(-10px);
        }

        .features .feature-box h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .features .feature-box p {
            font-size: 16px;
        }

        .features .feature-box i {
            font-size: 50px;
            margin-bottom: 20px;
            color:  #345b34;
        }

        /* Keyframes for Animations */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
      
        <ul>
            <li><a href="index1.php"><i class="fas fa-user"></i> L'EXPLOITANT</a></li>
            <li><a href="index.php"><i class="fas fa-building"></i> L'ENTREPRISE</a></li>
            <li><a href="index2.php"><i class="fas fa-users"></i> ENTREPRISE<br >&<br> EXPLOITANT</a></li>
            <li><a href="revenu.php"><i class="fas fa-chart-line"></i> REVENU</a></li>
            <li><a href="safae.php"><i class="fas fa-file-invoice-dollar"></i> TAXE</a></li>
            <li><a href="search.php"><i class="fas fa-search"></i> DECLARATION</a></li>
        </ul>
        <button class="logout-button" onclick="location.href='logout.php';">Deconnexion</button>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Bienvenue sur notre site web !</h1>
        <p>Parcourez notre site pour en savoir plus</p>
        <button class="cta-button">Commencer</button>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="feature-box">
            <i class="fas fa-bolt"></i>
            <h2>Rapide et sécurisé</h2>
            <p>Bénéficiez de performances ultra-rapides avec des fonctionnalités de sécurité de premier ordre.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-cogs"></i>
            <h2>Simplicité</h2>
            <p>Des paramètres faciles à ajuster pour vos besoins spécifiques</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-heart"></i>
            <h2>Centré sur l'utilisateur</h2>
            <p>Conçu en pensant à l’utilisateur, garantissant une expérience transparente.</p>
        </div>
    </section>

</body>
</html>
