<?php
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('config/dbcon.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resume</title>
  <style>
   

    .container {
      max-width: 800px;
      margin: 20px auto;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #283971;
    }

    .profile-img {
      display: block;
      width: 200px;
      height: 200px;
      margin: 20px auto;
      border-radius: 50%;
      background-color: #ccc;
      /* Placeholder background color */
    }

    .contact-details,
    .education,
    .work-experience,
    .skills {
      margin-bottom: 20px;
    }

    .info-title {
      font-weight: bold;
      color: #283971;
    }

    .info-list {
      list-style-type: none;
      padding: 0;
    }

    .info-list li {
      margin-bottom: 5px;
    }

    .image-card {
      background-color: #f9f9f9;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .image-card img {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
    }
  </style>
</head>

<main>
<body>
  <div class="container">
    <h1>Eduardo Dominico Llosa</h1>
    <img src="https://lh3.googleusercontent.com/a/ACg8ocJ1qC1EHNK3eGph4p-5CglLMaxFsF-Rfd0fmeqw8SU0YMqwN5jn=s288-c-no"
      alt="Profile Picture" class="profile-img">
   
    <div class ="divideColumn">
      <div class="contact-details">
        <h2 class="info-title">Contact Details:</h2>
        <ul class="info-list">
          <li>Email: 20190016132@my.xu.edu.ph</li>
          <li>Phone: +639551689258</li>
          <li>Facebook: https://www.facebook.com/nico.llosa.25</li>
          <li>Address: Upper Balulang, Cagayan de Oro</li>
        </ul>
      </div>
      <hr>

      <div class="education">
        <h2 class="info-title">Education:</h2>
        <ul class="info-list">
          <li>Bachelor of Science in Information Technology - Xavier University - Ateneo de Cagayan (2021 -2025)</li>
          <li>High School Diploma - Xavier University Senior High School - Ateneo de Cagayan (2021)</li>
        </ul>
      </div>
      <hr>

      <div class="work-experience">
        <h2 class="info-title">Work Experience:</h2>
        <ul class="info-list">
          <li>Quality Assurance at Abuv The Par LLC. (Aug – Jan 2021, 6 months )</li>
          <li>Sales Auditor at Marcoso Drugstore ( May 2022 – July 2022))</li>
        </ul>
      </div>
      <hr>

      <div class="skills">
        <h2 class="info-title">Skills:</h2>
        <ul class="info-list">
          <li>Programming Languages: HTML, CSS, JavaScript, PHP</li>
          <li>Frameworks: Bootstrap, Spring</li>
          <li>Databases: MySQL, MongoDB</li>
        </ul>
      </div>
      <hr>

      <div class="other-info">
        <h2 class="info-title">Projects:</h2>
        <div class="image-card">

        <p>https://github.com/VinniAgawinUba/CPU</p>
      </div>
      <div class="image-card">
        <p>https://github.com/VinniAgawinUba/SLP</p>
      </div>
      </div>
       </div>
    </div>
</body>
</main>

</html>
