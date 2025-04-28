<?php
include_once "header.php";

if(!isset($_GET["slug"]) || empty($_GET["slug"])){
    header("location: wiki.php");
    exit;
}

$slug = $_GET["slug"];
$game_query = "SELECT * FROM games WHERE slug = ?";
$stmt = mysqli_prepare($conn, $game_query);
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) == 0){
    header("location: wiki.php");
    exit;
}

$game = mysqli_fetch_assoc($result);
$headerClass = "wiki-header " . $slug;
?>
<div class="<?php echo $headerClass; ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold"><?php echo htmlspecialchars($game['title']); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($game['description']); ?></p>
                <div class="d-flex mt-3">
                    <a href="forums.php?game=<?php echo htmlspecialchars($game['slug']); ?>" class="btn btn-light me-2">
                        <i class="fas fa-comments me-1"></i> Visit Forum
                    </a>
                    <?php if(isLoggedIn()): ?>
                    <a href="tracker.php?action=add&game_id=<?php echo $game['id']; ?>" class="btn btn-outline-light">
                        <i class="fas fa-gamepad me-1"></i> Track Game
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block">
                <img src="<?php echo htmlspecialchars($game['image_url']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="wiki-content">
                <h2 class="mb-4">About the Game</h2>
                
                <?php
                $paragraphs = explode("\r\n\r\n", $game['wiki_content']);
                foreach($paragraphs as $paragraph): ?>
                    <p><?php echo htmlspecialchars($paragraph); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Game Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-medium">Developer:</span>
                            <span><?php echo htmlspecialchars($game['developer']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-medium">Publisher:</span>
                            <span><?php echo htmlspecialchars($game['publisher']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-medium">Release Date:</span>
                            <span><?php echo htmlspecialchars($game['release_date']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-medium">Genre:</span>
                            <span><?php echo htmlspecialchars($game['genre']); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Platforms</h5>
                </div>
                <div class="card-body">
                    <?php
                    $platforms = explode(", ", $game['platforms']);
                    foreach($platforms as $platform): ?>
                        <span class="badge bg-secondary me-1 mb-1"><?php echo htmlspecialchars($platform); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>