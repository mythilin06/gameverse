<?php
include_once "header.php";
if (!isset($_GET["id"]) || empty($_GET["id"])) {
header("Location: forums.php");
exit;
}

$post_id = intval($_GET["id"]);
$error = "";
$success = "";
$post_query = "SELECT p.*, u.username FROM forum_posts p JOIN users u ON p.user_id = u.id WHERE p.id = $post_id";
$post_result = mysqli_query($conn, $post_query);

if (mysqli_num_rows($post_result) == 0) {
header("Location: forums.php");
exit;
}

$post = mysqli_fetch_assoc($post_result);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isLoggedIn()) {
$comment_content = trim($_POST["comment_content"] ?? "");

if (empty($comment_content)) {
$error = "Comment cannot be empty";
} else {
$user_id = $_SESSION["id"];
$create_table = "CREATE TABLE IF NOT EXISTS forum_comments (
id INT(11) NOT NULL AUTO_INCREMENT,
post_id INT(11) NOT NULL,
user_id INT(11) NOT NULL,
content TEXT NOT NULL,
created_at DATETIME NOT NULL,
PRIMARY KEY (id),
KEY post_id (post_id),
KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($conn, $create_table);
$insert_query = "INSERT INTO forum_comments (post_id, user_id, content, created_at) 
VALUES ($post_id, $user_id, '" . mysqli_real_escape_string($conn, $comment_content) . "', NOW())";

if (mysqli_query($conn, $insert_query)) {
$success = "Comment added successfully!";
// Clear the form
$_POST["comment_content"] = "";
} else {
$error = "Failed to post comment: " . mysqli_error($conn);
}
}
}
$comments_query = "SELECT c.*, u.username FROM forum_comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = $post_id ORDER BY c.created_at";
$comments_result = mysqli_query($conn, $comments_query);
?>

<div class="container py-5">
<div class="row mb-4">
<div class="col-12">
<h1><?php echo htmlspecialchars($post["title"]); ?></h1>
<p>Posted by <?php echo htmlspecialchars($post["username"]); ?></p>
<a href="forums.php" class="btn btn-primary mb-3">Back to Forums</a>
</div>
</div>

<div class="row">
<div class="col-12">
<div class="card mb-4">
<div class="card-body">
<?php echo nl2br(htmlspecialchars($post["content"])); ?>
</div>
</div>
<div class="card">
<div class="card-header">
<h4>Comments</h4>
</div>
<div class="card-body">
<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<div class="comments mb-4">
<?php if (mysqli_num_rows($comments_result) > 0): ?>
<?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
<div class="comment p-3 mb-3 border rounded">
<div class="d-flex justify-content-between mb-2">
<strong><?php echo htmlspecialchars($comment["username"]); ?></strong>
<small class="text-muted"><?php echo date("M j, Y g:i a", strtotime($comment["created_at"])); ?></small>
</div>
<div><?php echo nl2br(htmlspecialchars($comment["content"])); ?></div>
</div>
<?php endwhile; ?>
<?php else: ?>
<p class="text-muted">No comments yet. Be the first to comment!</p>
<?php endif; ?>
</div>
<?php if (isLoggedIn()): ?>
<h4 class="mb-3">Add a Comment</h4>
<form method="post" action="">
<div class="mb-3">
<textarea class="form-control" name="comment_content" rows="4" placeholder="Write your comment here..." required></textarea>
</div>
<button type="submit" class="btn btn-primary">Post Comment</button>
</form>
<?php else: ?>
<div class="alert alert-info">
Please <a href="login.php">login</a> to post a comment.
</div>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>

<?php include "footer.php"; ?>