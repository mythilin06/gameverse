<?php
include_once "header.php";

if (!isLoggedIn()) {
    header("location: login.php");
    exit;
}
$action = isset($_GET['action']) ? $_GET['action'] : '';
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;

$tracking_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = intval($_POST["game_id"]);
    $status = trim($_POST["status"]);
    $rating = !empty($_POST["rating"]) ? intval($_POST["rating"]) : NULL;
    $notes = !empty($_POST["notes"]) ? trim($_POST["notes"]) : NULL;
    
    if (empty($game_id) || empty($status)) {
        $tracking_err = "Game and status are required fields.";
    } else {
        $check_query = "SELECT id FROM game_tracking WHERE user_id = ? AND game_id = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $game_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $tracking = mysqli_fetch_assoc($result);
            $update_query = "UPDATE game_tracking SET status = ?, rating = ?, notes = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "sisi", $status, $rating, $notes, $tracking['id']);
        } else {
            $insert_query = "INSERT INTO game_tracking (user_id, game_id, status, rating, notes) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "iisis", $_SESSION["id"], $game_id, $status, $rating, $notes);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: tracker.php");
            exit;
        } else {
            $tracking_err = "Something went wrong. Please try again.";
        }
    }
}

$games_query = "SELECT id, title FROM games ORDER BY title";
$games_result = mysqli_query($conn, $games_query);
$games = mysqli_fetch_all($games_result, MYSQLI_ASSOC);

$tracked_query = "SELECT gt.*, g.title, g.slug, g.image_url 
                FROM game_tracking gt 
                JOIN games g ON gt.game_id = g.id 
                WHERE gt.user_id = ? 
                ORDER BY gt.status, g.title";
$stmt = mysqli_prepare($conn, $tracked_query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt);
$tracked_result = mysqli_stmt_get_result($stmt);
$selected_game = null;
if ($action == 'add' && $game_id > 0) {
    $game_query = "SELECT id, title FROM games WHERE id = ?";
    $stmt = mysqli_prepare($conn, $game_query);
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $selected_game = mysqli_fetch_assoc($result);
    }
}
$played_count = $playing_count = $to_play_count = 0;
$all_tracked = mysqli_fetch_all($tracked_result, MYSQLI_ASSOC);
mysqli_data_seek($tracked_result, 0); 
foreach ($all_tracked as $game) {
    if ($game['status'] == 'played') $played_count++;
    else if ($game['status'] == 'playing') $playing_count++;
    else if ($game['status'] == 'to play') $to_play_count++;
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">Game Tracker</h1>
            <p class="text-muted">Keep track of your gaming journey - games you've played, currently playing, or want to play.</p>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGameModal">
                <i class="fas fa-plus-circle me-1"></i> Add Game
            </button>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Played</h5>
                    <p class="display-4"><?php echo $played_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100 border-info">
                <div class="card-body">
                    <h5 class="card-title">Currently Playing</h5>
                    <p class="display-4"><?php echo $playing_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100 border-warning">
                <div class="card-body">
                    <h5 class="card-title">Want to Play</h5>
                    <p class="display-4"><?php echo $to_play_count; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Your Game Library</h5>
        </div>
        <div class="card-body">
            <?php if(mysqli_num_rows($tracked_result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Game</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($game = mysqli_fetch_assoc($tracked_result)): ?>
                                <tr>
                                    <td>
                                        <a href="wiki-detail.php?slug=<?php echo htmlspecialchars($game['slug']); ?>" class="fw-medium">
                                            <?php echo htmlspecialchars($game['title']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $game['status']; ?>">
                                            <?php echo ucfirst(htmlspecialchars($game['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($game['rating']): ?>
                                            <div class="star-rating">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo $i <= $game['rating'] ? 'active' : 'inactive'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Not rated</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($game['notes']): ?>
                                            <span><?php echo htmlspecialchars(substr($game['notes'], 0, 30)) . (strlen($game['notes']) > 30 ? '...' : ''); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No notes</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-game" 
                                                data-bs-toggle="modal" data-bs-target="#editGameModal"
                                                data-id="<?php echo $game['id']; ?>"
                                                data-game-id="<?php echo $game['game_id']; ?>"
                                                data-title="<?php echo htmlspecialchars($game['title']); ?>"
                                                data-status="<?php echo htmlspecialchars($game['status']); ?>"
                                                data-rating="<?php echo $game['rating'] ?? ''; ?>"
                                                data-notes="<?php echo htmlspecialchars($game['notes'] ?? ''); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    You haven't added any games to your tracker yet. Get started by clicking the "Add Game" button.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="addGameModal" tabindex="-1" aria-labelledby="addGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGameModalLabel">Add Game to Tracker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($tracking_err)): ?>
                    <div class="alert alert-danger"><?php echo $tracking_err; ?></div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="game_id" class="form-label">Game</label>
                        <select class="form-select" id="game_id" name="game_id" required>
                            <option value="">Select a game</option>
                            <?php foreach ($games as $game): ?>
                            <option value="<?php echo $game['id']; ?>" <?php echo ($selected_game && $selected_game['id'] == $game['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($game['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select status</option>
                            <option value="played">Played</option>
                            <option value="playing">Currently Playing</option>
                            <option value="to play">Want to Play</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (optional)</label>
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star rating-star inactive" data-value="<?php echo $i; ?>"></i>
                            <?php endfor; ?>
                            <input type="hidden" name="rating" id="rating-input" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editGameModal" tabindex="-1" aria-labelledby="editGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGameModalLabel">Edit Game Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="editForm">
                    <input type="hidden" name="tracking_id" id="edit_tracking_id">
                    <input type="hidden" name="game_id" id="edit_game_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Game</label>
                        <p class="form-control-static fw-medium" id="edit_game_title"></p>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="played">Played</option>
                            <option value="playing">Currently Playing</option>
                            <option value="to play">Want to Play</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_rating" class="form-label">Rating (optional)</label>
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star rating-star edit-star inactive" data-value="<?php echo $i; ?>"></i>
                            <?php endfor; ?>
                            <input type="hidden" name="rating" id="edit_rating_input" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes (optional)</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-game').forEach(button => {
        button.addEventListener('click', function() {
            const gameId = this.getAttribute('data-game-id');
            const title = this.getAttribute('data-title');
            const status = this.getAttribute('data-status');
            const rating = this.getAttribute('data-rating');
            const notes = this.getAttribute('data-notes');
            
            document.getElementById('edit_game_id').value = gameId;
            document.getElementById('edit_game_title').textContent = title;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_notes').value = notes;
            
            document.getElementById('edit_rating_input').value = rating;
            document.querySelectorAll('.edit-star').forEach(star => {
                const starValue = star.getAttribute('data-value');
                if (starValue <= rating) {
                    star.classList.remove('inactive');
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                    star.classList.add('inactive');
                }
            });
        });
    });
    document.querySelectorAll('.edit-star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            document.getElementById('edit_rating_input').value = value;
            
            document.querySelectorAll('.edit-star').forEach(s => {
                const starValue = s.getAttribute('data-value');
                if (starValue <= value) {
                    s.classList.remove('inactive');
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                    s.classList.add('inactive');
                }
            });
        });
    });
});
</script>

<?php include "footer.php"; ?>