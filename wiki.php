<?php
include_once "header.php";
$games_query = "SELECT * FROM games ORDER BY title";
$games_result = mysqli_query($conn, $games_query);
$games = mysqli_fetch_all($games_result, MYSQLI_ASSOC);
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">Game Wiki</h1>
            <p class="text-muted">Explore our comprehensive database of popular games.</p>
        </div>
        <div class="col-md-4">
            <div class="input-group mt-3">
                <input type="text" id="game-filter" class="form-control" placeholder="Search games...">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($games_result) > 0): ?>
            <?php foreach($games as $game): ?>
                <div class="col-md-6 col-lg-4 game-item">
                    <div class="card h-100 game-card">
                        <img src="<?php echo htmlspecialchars($game['image_url']); ?>" class="card-img-top game-card-img" alt="<?php echo htmlspecialchars($game['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title game-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($game['genre']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($game['release_date']); ?></span>
                                <a href="wiki-detail.php?slug=<?php echo htmlspecialchars($game['slug']); ?>" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="fas fa-code-branch me-1"></i> <?php echo htmlspecialchars($game['developer']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No games found in the database.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>