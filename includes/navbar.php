<div class="container-fluid px-2">

 <!-- Navbar Top Right -->
<nav class="navbar navbar-expand-lg">
  
    <a class="navbar-brand d=block d-sm-none d-md-none" href="#"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- XU Logo -->

  <a href="index.php" style="width:400px;"> 
    <img src="assets/images/XU_LOGO.png" alt="Logo" style="width: 80%;">
    
  </a>
  
   <!-- Search Bar -->
   <form class="d-flex justify-content-center">
    <input class="form-control me-2 custom-search" type="search" placeholder="Search" aria-label="Search">
    <button class="btn btn-outline-primary" type="submit">Search</button>
  </form>
      <!-- Navbar Links -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <li class="nav-item text-center customfont">
          <a class="nav-link customfont" href="#">About Us</a>
        </li>

        <?php if(isset($_SESSION['auth_user'])) : ?>

        <li class="nav-item dropdown customfont">
          <a class="nav-link dropdown-toggle customfont" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $_SESSION['auth_user']['user_name']; ?>
          </a>
          <ul class="dropdown-menu customfont" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item customfont" href="#">My Profile</a></li>
            <li>
              <form action="allcode.php" method="post">
                <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </li>

        <?php else : ?>

        <li class="nav-item">
          <a class="nav-link customfont" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link customfont" href="register.php" >Register</a>
        </li>

        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

   

<div class="container">
  
  
 
  
</div>



<!-- Nav Center -->
<ul class="nav justify-content-center">
  <li class="nav-item">
    <a class="nav-link active customfont" href="index.php">HOME</a>
  </li>
  <li class="nav-item">
    <a class="nav-link customfont" href="projects.php">PROJECTS</a>
  </li>
  <li class="nav-item">
    <a class="nav-link customfont" href="articles.php">ARTICLES</a>
  </li>
  <li class="nav-item">
    <a class="nav-link customfont" href="registrar.php">REGISTRAR</a>
  </li>
  <li class="nav-item">
    <a class="nav-link customfont" href="gallery.php">GALLERY</a>
  </li>
</ul>

