<?php
$brand = trim($_GET['brand'] ?? '');
$cc    = (int)($_GET['cc'] ?? 0);
$price = (float)($_GET['price'] ?? 0);
?>

<h3 class="mb-4">Αναζήτηση Οχημάτων</h3>

<form method="get" action="search.php" class="row g-3 mb-4">

  <div class="col-md-4">
    <label class="form-label">Μάρκα</label>
    <input type="text" name="brand" class="form-control"
           placeholder="π.χ. Toyota"
           value="<?= htmlspecialchars($brand) ?>">
  </div>

  <div class="col-md-4">
    <label class="form-label">Κυβικά έως</label>
    <input type="number" name="cc" class="form-control"
           placeholder="π.χ. 1600"
           value="<?= htmlspecialchars($_GET['cc'] ?? '') ?>">
  </div>

  <div class="col-md-4">
    <label class="form-label">Τιμή έως (€)</label>
    <input type="number" step="0.01" name="price" class="form-control"
           placeholder="π.χ. 15000"
           value="<?= htmlspecialchars($_GET['price'] ?? '') ?>">
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary">Αναζήτηση</button>
    <a href="index.php" class="btn btn-outline-secondary">Καθαρισμός</a>
  </div>
</form>
