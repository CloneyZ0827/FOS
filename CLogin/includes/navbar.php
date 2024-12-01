<nav class="navbar navbar-expand-lg shadow sticky-top" style="background-color: #3a3a3a;">
  <div class="container">

    <a class="navbar-brand" href="index.php?table_no=<?= isset($_SESSION['customerUser']['table_no']) ? htmlspecialchars($_SESSION['customerUser']['table_no']) : ''; ?>" style="color: #d8bc97;">Urban Daybreak</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        

        <?php if(!isset($_SESSION['customerLoggedIn'])) : ?>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link active" href="../Customers?table_no=<?= isset($_SESSION['customerUser']['table_no']) ? htmlspecialchars($_SESSION['customerUser']['table_no']) : ''; ?>" style="color: #d8bc97;">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="table.php?table_no=<?= isset($_SESSION['customerUser']['table_no']) ? htmlspecialchars($_SESSION['customerUser']['table_no']) : ''; ?>" style="color: #d8bc97;"><?= htmlspecialchars($_SESSION['customerUser']['name']);?></a>
          </li>
          <li class="nav-item">
            <a class="btn btn-danger" href="logout.php?table_no=<?= isset($_SESSION['customerUser']['table_no']) ? htmlspecialchars($_SESSION['customerUser']['table_no']) : ''; ?>">Logout</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
