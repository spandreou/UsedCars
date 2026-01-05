<?php
require_once "includes/db.php";
require_once "includes/auth.php";
include "includes/header.php";

/* ---------- Search flag ---------- */
$hasSearch =
    isset($_GET['brand']) ||
    isset($_GET['cc']) ||
    isset($_GET['price']);

/* ---------- Filters (GET) ---------- */
$brand = trim($_GET['brand'] ?? '');
$cc    = (int)($_GET['cc'] ?? 0);
$price = (float)($_GET['price'] ?? 0);

/* ---------- Pagination ---------- */
$per_page = 4;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

/* ---------- WHERE + Params ---------- */
$where = " WHERE 1=1 ";
$params = [];

if ($brand !== '') {
    $where .= " AND brand LIKE ? ";
    $params[] = "%$brand%";
}
if ($cc > 0) {
    $where .= " AND engine_cc <= ? ";
    $params[] = $cc;
}
if ($price > 0) {
    $where .= " AND price <= ? ";
    $params[] = $price;
}

/* ---------- COUNT + RESULTS ---------- */
$total_results = 0;
$total_pages = 1;
$results = [];

if ($hasSearch) {

    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM cars $where");
    $stmtCount->execute($params);
    $total_results = (int)$stmtCount->fetchColumn();

    $total_pages = max(1, (int)ceil($total_results / $per_page));
    if ($page > $total_pages) {
        $page = $total_pages;
        $offset = ($page - 1) * $per_page;
    }

    $sql = "
        SELECT id, brand, model, engine_cc, price
        FROM cars
        $where
        ORDER BY id DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $pdo->prepare($sql);

    $i = 1;
    foreach ($params as $p) {
        $stmt->bindValue($i++, $p);
    }
    $stmt->bindValue($i++, $per_page, PDO::PARAM_INT);
    $stmt->bindValue($i, $offset, PDO::PARAM_INT);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ---------- Helper ---------- */
function build_query(array $extra = []): string {
    return http_build_query(array_merge($_GET, $extra));
}
?>

<div class="container mt-5 fade-in">
  <div class="card shadow">
    <div class="card-body">

      <h3 class="mb-4">Αναζήτηση Οχημάτων</h3>

      <!-- FORM -->
      <form method="get" action="search.php" class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label">Μάρκα</label>
          <input type="text" name="brand" class="form-control"
                 value="<?= htmlspecialchars($brand) ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Κυβικά έως</label>
          <input type="number" name="cc" class="form-control"
                 value="<?= htmlspecialchars($_GET['cc'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Τιμή έως (€)</label>
          <input type="number" step="0.01" name="price" class="form-control"
                 value="<?= htmlspecialchars($_GET['price'] ?? '') ?>">
        </div>

        <div class="col-12 d-flex gap-2">
          <button class="btn btn-primary">Αναζήτηση</button>
          <a class="btn btn-outline-secondary" href="search.php">Καθαρισμός</a>
        </div>
      </form>

      <!-- BEFORE SEARCH -->
      <?php if (!$hasSearch): ?>
        <div class="alert alert-secondary">
          Συμπληρώστε φίλτρα και πατήστε <strong>Αναζήτηση</strong>.
        </div>
      <?php endif; ?>

      <!-- RESULTS -->
      <?php if ($hasSearch): ?>

        <?php if ($total_results === 0): ?>
          <div class="alert alert-info">Δεν βρέθηκαν οχήματα.</div>
        <?php else: ?>

          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="text-muted">
              Βρέθηκαν <strong><?= $total_results ?></strong> αποτέλεσμα(τα)
            </div>
            <div class="text-muted">
              Σελίδα <strong><?= $page ?></strong> / <?= $total_pages ?>
            </div>
          </div>

          <div class="list-group mb-3">
            <?php foreach ($results as $car): ?>
              <a href="car_view.php?id=<?= (int)$car['id'] ?>"
                 class="list-group-item list-group-item-action">
                <strong><?= htmlspecialchars($car['brand'].' '.$car['model']) ?></strong><br>
                Κυβικά: <?= (int)$car['engine_cc'] ?> cc |
                Τιμή: <?= number_format((float)$car['price'], 2) ?> €
              </a>
            <?php endforeach; ?>
          </div>

          <!-- PAGINATION -->
          <?php if ($total_pages > 1): ?>
            <nav>
              <ul class="pagination">

                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                  <a class="page-link"
                     href="search.php?<?= build_query(['page' => $page - 1]) ?>">«</a>
                </li>

                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                  <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                    <a class="page-link"
                       href="search.php?<?= build_query(['page' => $p]) ?>">
                      <?= $p ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                  <a class="page-link"
                     href="search.php?<?= build_query(['page' => $page + 1]) ?>">»</a>
                </li>

              </ul>
            </nav>
          <?php endif; ?>

        <?php endif; ?>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
