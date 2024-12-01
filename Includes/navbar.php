<nav class="navbar navbar-expand-lg shadow sticky-top" style="background-color: #3a3a3a;">
  <div class="container">

    <a class="navbar-brand" href="index.php" style="color: #d8bc97;">Urban Daybreak Admin</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="Admins" style="color: #d8bc97;">Home</a>
        </li>

        <?php if (!isset($_SESSION['loggedIn'])) : ?>
        <li class="nav-item">
          <a class="nav-link" href="register.php" style="color: #d8bc97;">Sign Up</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php" style="color: #d8bc97;">Login</a>
        </li>
        <?php else : ?>
        <li class="nav-item">
          <a class="nav-link" href="#" style="color: #d8bc97;"><?= htmlspecialchars($_SESSION['loggedInUser']['name']); ?></a>
        </li>
        <li class="nav-item">
          <a class="btn btn-danger" href="logout.php">Logout</a>
        </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
