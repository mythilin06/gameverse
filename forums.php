<?php
// Include header
include_once "header.php";

// Check if a specific game filter is applied
$game_filter = isset($_GET['game']) ? $_GET['game'] : null;

// Fetch games for the dropdown
$games_query = "SELECT id, title, slug FROM games ORDER BY title";
$games_result = mysqli_query($conn, $games_query);
$games = mysqli_fetch_all($games_result, MYSQLI_ASSOC);

// Fetch forum posts based on filter
if ($game_filter) {
    $posts_query = "SELECT fp.*, u.username, g.title as game_title 
                  FROM forum_posts fp 
                  JOIN users u ON fp.user_id = u.id 
                  JOIN games g ON fp.game_slug = g.slug 
                  WHERE fp.game_slug = ? 
                  ORDER BY fp.created_at DESC";
    $stmt = mysqli_prepare($conn, $posts_query);
    mysqli_stmt_bind_param($stmt, "s", $game_filter);
    mysqli_stmt_execute($stmt);
    $posts_result = mysqli_stmt_get_result($stmt);
} else {
    $posts_query = "SELECT fp.*, u.username, g.title as game_title 
                  FROM forum_posts fp 
                  JOIN users u ON fp.user_id = u.id 
                  JOIN games g ON fp.game_slug = g.slug 
                  ORDER BY fp.created_at DESC";
    $posts_result = mysqli_query($conn, $posts_query);
}

// Count comments for each post
function getCommentCount($postId, $conn) {
    $query = "SELECT COUNT(*) as count FROM comments WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $postId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

// Process form submission
$post_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isLoggedIn()) {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $game_slug = trim($_POST["game_slug"]);
    
    // Basic validation
    if (empty($title) || empty($content) || empty($game_slug)) {
        $post_err = "Please fill all the required fields.";
    } else {
        // Insert the post
        $insert_query = "INSERT INTO forum_posts (title, content, user_id, game_slug) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssis", $title, $content, $_SESSION["id"], $game_slug);
        
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to refresh the page
            header("location: forums.php" . ($game_filter ? "?game=$game_filter" : ""));
            exit;
        } else {
            $post_err = "Something went wrong. Please try again.";
        }
    }
}

// Get the current game title if filtered
$current_game_title = "";
if ($game_filter) {
    $game_title_query = "SELECT title FROM games WHERE slug = ?";
    $stmt = mysqli_prepare($conn, $game_title_query);
    mysqli_stmt_bind_param($stmt, "s", $game_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $current_game_title = $row['title'];
    }
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold"><?php echo $game_filter ? htmlspecialchars($current_game_title) . " Forums" : "Forums"; ?></h1>
            <p class="text-muted">Join the conversation about your favorite games.</p>
        </div>
        
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="gameFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $game_filter ? "Viewing: " . htmlspecialchars($current_game_title) : "Filter by Game"; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="gameFilterDropdown">
                <li><a class="dropdown-item <?php echo !$game_filter ? 'active' : ''; ?>" href="forums.php">All Games</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php foreach ($games as $game): ?>
                <li>
                    <a class="dropdown-item <?php echo $game_filter == $game['slug'] ? 'active' : ''; ?>" 
                       href="forums.php?game=<?php echo htmlspecialchars($game['slug']); ?>">
                        <?php echo htmlspecialchars($game['title']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <?php if(isLoggedIn()): ?>
    <!-- New Post Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create New Post</h5>
        </div>
        <div class="card-body">
            <?php if(!empty($post_err)): ?>
                <div class="alert alert-danger"><?php echo $post_err; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($game_filter ? "?game=$game_filter" : "")); ?>" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="game_slug" class="form-label">Game</label>
                    <select class="form-select" id="game_slug" name="game_slug" required>
                        <option value="">Select a game</option>
                        <?php foreach ($games as $game): ?>
                        <option value="<?php echo htmlspecialchars($game['slug']); ?>" <?php echo $game_filter == $game['slug'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($game['title']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Post</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Forum Posts -->
    <div class="forum-posts">
        <?php if(mysqli_num_rows($posts_result) > 0): ?>
            <?php while ($post = mysqli_fetch_assoc($posts_result)): 
                $comment_count = getCommentCount($post['id'], $conn);
            ?>
                <div class="forum-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="h5 fw-bold mb-0">
                            <a href="forum-topic.php?id=<?php echo $post['id']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($post['game_title']); ?></span>
                    </div>
                    <p class="text-muted mb-3">
                        <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200)) . (strlen($post['content']) > 200 ? '...' : '')); ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Posted by <span class="fw-medium"><?php echo htmlspecialchars($post['username']); ?></span>
                                on <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                            </small>
                        </div>
                        <div>
                            <a href="forum-topic.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-comments me-1"></i> <?php echo $comment_count; ?> Comments
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo $game_filter ? "No discussions found for this game yet. Be the first to start one!" : "No forum posts found."; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>