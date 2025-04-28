<?php
include_once "header.php";

$games_query = "SELECT * FROM games LIMIT 4";
$games_result = mysqli_query($conn, $games_query);
$games = mysqli_fetch_all($games_result, MYSQLI_ASSOC);
?>

<section class="hero-section">
<img src="https://images.unsplash.com/photo-1511512578047-dfb367046420" alt="Gaming Background" class="hero-background">
<div class="hero-content">
<h1 class="display-4 fw-bold text-white mb-4">Your Ultimate Gaming Hub</h1>
<p class="lead text-white-50 mb-5">Discover, discuss, and track your favorite games all in one place.</p>
<div class="d-flex flex-wrap justify-content-center gap-3">
<a href="wiki.php" class="btn btn-primary btn-lg">
<i class="fas fa-book-open me-2"></i>Explore Wiki
</a>
<a href="forums.php" class="btn btn-outline-light btn-lg">
<i class="fas fa-comments me-2"></i>Join Forums
</a>
</div>
</div>
</section>

<section class="py-5 bg-light">
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="fw-bold">Featured Games</h2>
<a href="wiki.php" class="btn btn-link text-decoration-none">
View All <i class="fas fa-chevron-right ms-1"></i>
</a>
</div>

<div class="row g-4">
<?php if(mysqli_num_rows($games_result) > 0): ?>
<?php foreach($games as $game): ?>
<div class="col-md-6 col-lg-3">
<div class="card h-100 game-card">
<img src="<?php echo htmlspecialchars($game['image_url']); ?>" class="card-img-top game-card-img" alt="<?php echo htmlspecialchars($game['title']); ?>">
<div class="game-card-overlay">
<h5 class="game-card-title"><?php echo htmlspecialchars($game['title']); ?></h5>
<p class="game-card-text"><?php echo htmlspecialchars($game['genre']); ?></p>
<a href="wiki-detail.php?slug=<?php echo htmlspecialchars($game['slug']); ?>" class="btn btn-sm btn-primary">Learn More</a>
</div>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="col-12 text-center">
<p>No games found</p>
</div>
<?php endif; ?>
</div>
</div>
</section>

<section class="py-5">
<div class="container">
<h2 class="text-center fw-bold mb-5">Why Choose GameVerse?</h2>

<div class="row g-4">
<div class="col-md-4">
<div class="card h-100 border-0 shadow-sm">
<div class="card-body text-center p-4">
<div class="feature-icon mx-auto mb-4">
<i class="fas fa-book-open fa-2x"></i>
</div>
<h3 class="h5 fw-bold mb-3">Comprehensive Wiki</h3>
<p class="text-muted">
Access detailed information about your favorite games, characters, strategies, and more.
</p>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card h-100 border-0 shadow-sm">
<div class="card-body text-center p-4">
<div class="feature-icon mx-auto mb-4">
<i class="fas fa-comments fa-2x"></i>
</div>
<h3 class="h5 fw-bold mb-3">Active Community Forums</h3>
<p class="text-muted">
Connect with other gamers, share experiences, and participate in discussions.
</p>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card h-100 border-0 shadow-sm">
<div class="card-body text-center p-4">
<div class="feature-icon mx-auto mb-4">
<i class="fas fa-gamepad fa-2x"></i>
</div>
<h3 class="h5 fw-bold mb-3">Personal Game Tracker</h3>
<p class="text-muted">
Keep track of games you've played, want to play, and rate your gaming experiences.
</p>
</div>
</div>
</div>
</div>
</div>
</section>

<section class="py-5 bg-dark text-white">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8 text-center">
<span class="badge bg-warning text-dark mb-3">Premium Membership</span>
<h2 class="fw-bold mb-3">Upgrade Your Gaming Experience</h2>
<p class="lead text-white-50 mb-4">
Get exclusive benefits and features with our premium membership for just ₹1000.
</p>

<div class="row g-3 mb-4">
<div class="col-md-4">
<div class="p-3 bg-dark-subtle rounded">
<i class="fas fa-trophy text-warning mb-2"></i>
<h5 class="fw-medium">Ad-Free Experience</h5>
</div>
</div>
<div class="col-md-4">
<div class="p-3 bg-dark-subtle rounded">
<i class="fas fa-bell text-warning mb-2"></i>
<h5 class="fw-medium">Early Access</h5>
</div>
</div>
<div class="col-md-4">
<div class="p-3 bg-dark-subtle rounded">
<i class="fas fa-users text-warning mb-2"></i>
<h5 class="fw-medium">Discord Integration</h5>
</div>
</div>
</div>

<a href="premium.php" class="btn btn-primary btn-lg">
Go Premium <i class="fas fa-chevron-right ms-2"></i>
</a>
</div>
</div>
</div>
</section>

<section class="py-5 bg-light">
<div class="container">
<h2 class="text-center fw-bold mb-5">Gaming Setup Showcase</h2>

<div class="setup-showcase">
<ul class="nav nav-pills mb-4 justify-content-center setup-tabs" id="setupTabs" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pro-tab" data-bs-toggle="pill" data-bs-target="#pro-setup" type="button" role="tab" aria-controls="pro-setup" aria-selected="true">Pro Setups</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="budget-tab" data-bs-toggle="pill" data-bs-target="#budget-setup" type="button" role="tab" aria-controls="budget-setup" aria-selected="false">Budget Builds</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="streamer-tab" data-bs-toggle="pill" data-bs-target="#streamer-setup" type="button" role="tab" aria-controls="streamer-setup" aria-selected="false">Streamer Stations</button>
</li>
</ul>
<div class="tab-content" id="setupTabContent">
<div class="tab-pane fade show active" id="pro-setup" role="tabpanel" aria-labelledby="pro-tab">
<div class="row g-3">
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1616588589676-62b3bd4ff6d2" class="img-fluid rounded setup-image" alt="Gaming Setup 1">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Pro Battlestation</h6>
<span class="badge bg-dark">RTX 4090</span>
<span class="badge bg-dark">i9-13900K</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1547082299-de196ea013d6" class="img-fluid rounded setup-image" alt="Gaming Setup 2">
<div class="setup-overlay">
<div class="setup-specs">
<h6>RGB Masterpiece</h6>
<span class="badge bg-dark">RX 7900 XT</span>
<span class="badge bg-dark">Ryzen 9 7950X</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1593640495253-23196b27a87f" class="img-fluid rounded setup-image" alt="Gaming Setup 3">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Ultrawide Experience</h6>
<span class="badge bg-dark">RTX 4080</span>
<span class="badge bg-dark">i7-13700K</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1603481588273-2f908a9a7a1b" class="img-fluid rounded setup-image" alt="Gaming Setup 4">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Minimalist Power</h6>
<span class="badge bg-dark">RTX 3090</span>
<span class="badge bg-dark">i9-12900K</span>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="budget-setup" role="tabpanel" aria-labelledby="budget-tab">
<div class="row g-3">
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1598550476439-6847785fcea6" class="img-fluid rounded setup-image" alt="Budget Setup 1">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Budget Champion</h6>
<span class="badge bg-dark">RTX 3060</span>
<span class="badge bg-dark">i5-12400F</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://www.makerstations.io/content/images/2022/08/jb-bardoles-desk-setup-01.jpg" class="img-fluid rounded setup-image" alt="Budget Setup 2">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Student Setup</h6>
<span class="badge bg-dark">RX 6600 XT</span>
<span class="badge bg-dark">Ryzen 5 5600X</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5" class="img-fluid rounded setup-image" alt="Budget Setup 3">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Compact Powerhouse</h6>
<span class="badge bg-dark">RTX 3050</span>
<span class="badge bg-dark">i3-12100F</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7" class="img-fluid rounded setup-image" alt="Budget Setup 4">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Value King</h6>
<span class="badge bg-dark">RX 6500 XT</span>
<span class="badge bg-dark">Ryzen 3 4100</span>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="streamer-setup" role="tabpanel" aria-labelledby="streamer-tab">
<div class="row g-3">
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://i.pinimg.com/736x/73/94/80/7394808e3879042625b2749698d701bd.jpg" class="img-fluid rounded setup-image" alt="Streamer Setup 1">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Content Creator</h6>
<span class="badge bg-dark">Dual PC</span>
<span class="badge bg-dark">Elgato Capture</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://i.pinimg.com/736x/09/56/12/09561200fef8e4e6b307e6da46b32e9c.jpg" class="img-fluid rounded setup-image" alt="Streamer Setup 2">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Live Broadcaster</h6>
<span class="badge bg-dark">NVIDIA Broadcast</span>
<span class="badge bg-dark">GoXLR</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://i.pinimg.com/736x/3c/ab/c7/3cabc74db0ae50f9a38015e2be54989e.jpg" class="img-fluid rounded setup-image" alt="Streamer Setup 3">
<div class="setup-overlay">
<div class="setup-specs">
<h6>YouTuber Desk</h6>
<span class="badge bg-dark">Multi-Monitor</span>
<span class="badge bg-dark">DSLR Cam</span>
</div>
</div>
</div>
</div>
<div class="col-6 col-md-3">
<div class="setup-card">
<img src="https://i.pinimg.com/736x/11/51/ac/1151ac2c5ba3eccfe5425a8472418c7c.jpg" class="img-fluid rounded setup-image" alt="Streamer Setup 4">
<div class="setup-overlay">
<div class="setup-specs">
<h6>Pro Broadcast</h6>
<span class="badge bg-dark">Stream Deck</span>
<span class="badge bg-dark">Microphone Arm</span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="mt-5">
<div class="card border-0 shadow-sm overflow-hidden">
<div class="row g-0">
<div class="col-md-6">
<img src="https://i.pinimg.com/736x/f4/de/a8/f4dea8f93d28a301902d6d66728f78f4.jpg" class="img-fluid featured-setup-image" alt="Featured Gaming Setup">
</div>
<div class="col-md-6">
<div class="card-body p-4">
<div class="d-flex justify-content-between">
<span class="badge bg-primary mb-2">Featured Setup</span>
<span class="badge bg-warning text-dark">Community Pick</span>
</div>
<h4 class="card-title fw-bold">Ultimate Gaming Experience</h4>
<p class="card-text">This stunning setup features a custom water-cooled PC with synchronized RGB lighting, triple ultrawide monitor setup, and ergonomic gaming chair for those long gaming sessions.</p>
<div class="mb-3">
<span class="fw-medium">Key Components:</span>
<ul class="specs-list mt-2">
<li><i class="fas fa-microchip me-2"></i>Intel Core i9-13900KS</li>
<li><i class="fas fa-desktop me-2"></i>NVIDIA RTX 4090 24GB</li>
<li><i class="fas fa-memory me-2"></i>64GB DDR5-6000 RAM</li>
<li><i class="fas fa-hdd me-2"></i>4TB NVMe Gen4 SSD</li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="text-center mt-5">
<h4>Show Off Your Gaming Setup!</h4>
<p>Share your battle station with our community and get featured on our showcase.</p>
<div class="text-center mt-4">
<a href="setup-submission.php" class="btn btn-primary">
<i class="fas fa-upload me-2"></i> Submit Your Setup
</a>
</div>
</div>
</div>
</div>
</section>
<div class="modal fade" id="setupSubmitModal" tabindex="-1" aria-labelledby="setupSubmitModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="setupSubmitModalLabel">Submit Your Gaming Setup</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
<form>
<div class="mb-3">
<label for="setupName" class="form-label">Setup Name</label>
<input type="text" class="form-control" id="setupName" placeholder="Give your setup a name">
</div>
<div class="mb-3">
<label for="setupSpecs" class="form-label">PC Specifications</label>
<textarea class="form-control" id="setupSpecs" rows="3" placeholder="CPU, GPU, RAM, etc."></textarea>
</div>
<div class="mb-3">
<label for="setupDescription" class="form-label">Description</label>
<textarea class="form-control" id="setupDescription" rows="3" placeholder="Tell us about your setup"></textarea>
</div>
<div class="mb-3">
<label for="setupImage" class="form-label">Upload Image</label>
<input class="form-control" type="file" id="setupImage">
</div>
</form>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="button" class="btn btn-primary">Submit Setup</button>
</div>
</div>
</div>
</div>
<?php
include_once "footer.php";
?>