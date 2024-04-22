<?php
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('config/dbcon.php');
?>

<style>
    #home-box {
        max-width: 1101px;
        height: auto; /* Adjusted to accommodate variable content */
        display: flex;
        background: #283971;
        border-radius: 10px;
        margin-bottom: 100px;
        margin-top: 1px;
        padding: 30px; /* Adjusted for better spacing */
        box-sizing: border-box; /* Ensures padding is included in the width */
    }

    #home-layer {
        border: none;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    #home-header {
        font-family: Helvetica, sans-serif;
        font-style: normal;
        font-weight: 900;
        font-size: 28px;
        line-height: 34px;
        letter-spacing: 0.5em;
        color: #FFFFFF;
    }

    #sample-photo {
        max-width: 100%; /* Adjusted to fit variable content */
        height: auto;
        flex: 1;
    }

    .home-article {
        flex: 2;
        text-align: left;
        padding: 0 30px; /* Adjusted for better spacing */
        box-sizing: border-box; /* Ensures padding is included in the width */
        font-size:24px;
    }

    #find-out-more-button {
        width: 140px;
        height: 52px;
        border: none;
        background: #A19158;
        border-radius: 30px;
        font-family: Helvetica, sans-serif;
        font-style: normal;
        font-weight: 400;
        font-size: 23px;
        line-height: 16px;
        text-align: center;
        color: #FFFFFF;
        margin-top: 20px; /* Adjusted for better spacing */
    }

    #find-out-more-button:hover {
        background-color: #A19158;
        transition: color 0.5s;
    }

    #find-out-more-button:active {
        background-color: #8D7F4D;
    }

   


    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    .card-body {
        box-sizing: border-box;
        border-radius: 10px;
        height: 120px;
    }

    a {
        text-decoration: none;
    }


    #project-header {
        padding: 20px;
        font-weight: 700;
        font-size: 32px;
        line-height: 39px;
        color: #FFFFFF;
    }


  
</style>

<link rel="stylesheet" href="assets/css/custom.css">
<main>
<div class="container" id="home-box" style="width: 1101px; margin: 0 auto;">
    <aside style="flex: 1; display: flex; align-items: center;">
    </aside>
    <article class="home-article" style="flex: 2; padding: 30px;">
        <h4 id="home-header">CENTRAL PROCUREMENT UNIT</h4>
        <p class="" style="color: white;">
        Xavier University is currently focused on developing a robust Procurement Process Management System to enhance its administrative operations. The university's various departments frequently submit requests to the Central Procurement Unit (CPU) for goods and services, yet the existing tracking process proves inefficient and time-consuming. Our primary objective is to overcome these challenges by creating a comprehensive system that centralizes procurement request submission, tracking, and management across the institution. Collaborating closely with stakeholders from Xavier University departments and the CPU, we aim to develop a user-friendly website/web application tailored to the university's specific procurement needs.

    
        </p>
        <a href="meet_the_team.php"><button id="find-out-more-button">Meet The Team</button></a>
    </article>
</div>
</main>