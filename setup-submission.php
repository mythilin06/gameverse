<?php
include_once "header.php";
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$submission_success = false;
$submitted_data = [];
$error = "";
$debug_info = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_setup"])) {
    $setup_name = trim($_POST["setup_name"] ?? "");
    $setup_type = trim($_POST["setup_type"] ?? "");
    $processor = trim($_POST["processor"] ?? "");
    $graphics_card = trim($_POST["graphics_card"] ?? "");
    $ram = trim($_POST["ram"] ?? "");
    $storage = trim($_POST["storage"] ?? "");
    $monitor = trim($_POST["monitor"] ?? "");
    $keyboard = trim($_POST["keyboard"] ?? "");
    $mouse = trim($_POST["mouse"] ?? "");
    $headset = trim($_POST["headset"] ?? "");
    $additional_peripherals = trim($_POST["additional_peripherals"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $primary_game = trim($_POST["primary_game"] ?? "");
    $image_url = trim($_POST["image_url"] ?? "");
    
    if (empty($image_url)) {
        $image_url = "https://images.prismic.io/leetdesk/c5527f47-def9-433c-9cce-23f286dbfea2_Front.jpg?auto=compress,format&rect=0,374,4000,2250&w=1920&h=1080";
    }
    if (empty($setup_name) || empty($setup_type) || empty($processor)) {
        $error = "Setup name, type, and processor are required.";
    } else {
        $insert_query = "INSERT INTO gaming_setups (user_id, setup_name, setup_type, processor, graphics_card, ram, storage, 
                         monitor, keyboard, mouse, headset, additional_peripherals, description, primary_game, image_url, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        try {
            $stmt = mysqli_prepare($conn, $insert_query);
            
            if ($stmt === false) {
                $error = "Failed to prepare statement: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "issssssssssssss", 
                    $user_id, $setup_name, $setup_type, $processor, $graphics_card, $ram, $storage, 
                    $monitor, $keyboard, $mouse, $headset, $additional_peripherals, $description, $primary_game, $image_url);
                
                if (mysqli_stmt_execute($stmt)) {
                    $submission_success = true;
                    
                    // Store submitted data for display in modal
                    $submitted_data = [
                        'setup_name' => $setup_name,
                        'setup_type' => $setup_type,
                        'processor' => $processor,
                        'graphics_card' => $graphics_card,
                        'ram' => $ram,
                        'storage' => $storage,
                        'monitor' => $monitor,
                        'keyboard' => $keyboard,
                        'mouse' => $mouse,
                        'headset' => $headset,
                        'additional_peripherals' => $additional_peripherals,
                        'description' => $description,
                        'primary_game' => $primary_game,
                        'image_url' => $image_url
                    ];
                } else {
                    $error = "Error executing statement: " . mysqli_stmt_error($stmt);
                    $debug_info = "SQL Error: " . mysqli_error($conn);
                }
                
                mysqli_stmt_close($stmt);
            }
        } catch (Exception $e) {
            $error = "Exception occurred: " . $e->getMessage();
        }
    }
}

// Get game list for dropdown
$games_query = "SELECT id, title FROM games ORDER BY title";
$games_result = mysqli_query($conn, $games_query);
$games = [];
if ($games_result) {
    while ($game = mysqli_fetch_assoc($games_result)) {
        $games[] = $game;
    }
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Submit Your Gaming Setup</h1>
            <p class="lead">Share your gaming setup with the GameVerse community!</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Setup Details</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <strong>Error:</strong> <?php echo $error; ?>
                            <?php if (!empty($debug_info)): ?>
                                <br><small><?php echo $debug_info; ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="setup-form">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="setup_name" class="form-label">Setup Name*</label>
                                <input type="text" class="form-control" id="setup_name" name="setup_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="setup_type" class="form-label">Setup Type*</label>
                                <select class="form-select" id="setup_type" name="setup_type" required>
                                    <option value="">Select Type</option>
                                    <option value="PC">PC</option>
                                    <option value="Console">Console</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Mobile">Mobile</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image_url" class="form-label">Setup Image URL</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/your-setup-image.jpg">
                            <div class="form-text">Leave blank to use a placeholder image.</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="processor" class="form-label">Processor/CPU*</label>
                                <input type="text" class="form-control" id="processor" name="processor" required>
                            </div>
                            <div class="col-md-6">
                                <label for="graphics_card" class="form-label">Graphics Card/GPU</label>
                                <input type="text" class="form-control" id="graphics_card" name="graphics_card">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ram" class="form-label">RAM/Memory</label>
                                <input type="text" class="form-control" id="ram" name="ram">
                            </div>
                            <div class="col-md-6">
                                <label for="storage" class="form-label">Storage</label>
                                <input type="text" class="form-control" id="storage" name="storage">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="monitor" class="form-label">Monitor/Display</label>
                            <input type="text" class="form-control" id="monitor" name="monitor">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="keyboard" class="form-label">Keyboard</label>
                                <input type="text" class="form-control" id="keyboard" name="keyboard">
                            </div>
                            <div class="col-md-6">
                                <label for="mouse" class="form-label">Mouse</label>
                                <input type="text" class="form-control" id="mouse" name="mouse">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="headset" class="form-label">Headset/Audio</label>
                            <input type="text" class="form-control" id="headset" name="headset">
                        </div>
                        
                        <div class="mb-3">
                            <label for="additional_peripherals" class="form-label">Additional Peripherals</label>
                            <input type="text" class="form-control" id="additional_peripherals" name="additional_peripherals" placeholder="e.g., controllers, webcam, microphone, etc.">
                        </div>
                        
                        <div class="mb-3">
                            <label for="primary_game" class="form-label">Primary Game You Play</label>
                            <select class="form-select" id="primary_game" name="primary_game">
                                <option value="">Select Game (Optional)</option>
                                <?php foreach ($games as $game): ?>
                                    <option value="<?php echo htmlspecialchars($game['title']); ?>"><?php echo htmlspecialchars($game['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Tell us about your setup, what you love about it, and any special features..."></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="submit_setup" class="btn btn-primary btn-lg">
                                <i class="fas fa-upload me-2"></i> Submit Gaming Setup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tips for Great Submissions</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-camera text-primary me-2"></i> Include a clear image URL of your setup
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-microchip text-primary me-2"></i> Be specific about components
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-comment text-primary me-2"></i> Add a detailed description
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-lightbulb text-primary me-2"></i> Mention unique features or modifications
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-gamepad text-primary me-2"></i> Tell us what games you play on this setup
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">What Happens Next?</h5>
                </div>
                <div class="card-body">
                    <p>After submission, your setup will:</p>
                    <ol>
                        <li>Be reviewed by our moderators</li>
                        <li>Appear in the Gaming Setups showcase</li>
                        <li>Be visible to the entire GameVerse community</li>
                        <li>Be eligible for our monthly "Best Setup" contest</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="setupSuccessModal" tabindex="-1" aria-labelledby="setupSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="setupSuccessModalLabel">
                    <i class="fas fa-check-circle me-2"></i> Setup Submitted Successfully!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-1 text-success">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h4>Thank you for sharing your gaming setup!</h4>
                    <p class="lead">Your submission has been received and will be featured in our community showcase.</p>
                </div>
                
                <h5 class="border-bottom pb-2">Your Submission Details:</h5>
                
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6><i class="fas fa-desktop me-2 text-primary"></i> Setup Information</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Name:</strong> <span id="modal-setup-name"></span></li>
                                    <li><strong>Type:</strong> <span id="modal-setup-type"></span></li>
                                    <li><strong>Primary Game:</strong> <span id="modal-primary-game">N/A</span></li>
                                </ul>
                                
                                <h6 class="mt-3"><i class="fas fa-microchip me-2 text-primary"></i> Core Components</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Processor:</strong> <span id="modal-processor"></span></li>
                                    <li><strong>Graphics Card:</strong> <span id="modal-graphics-card">N/A</span></li>
                                    <li><strong>RAM:</strong> <span id="modal-ram">N/A</span></li>
                                    <li><strong>Storage:</strong> <span id="modal-storage">N/A</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6><i class="fas fa-keyboard me-2 text-primary"></i> Peripherals</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Monitor:</strong> <span id="modal-monitor">N/A</span></li>
                                    <li><strong>Keyboard:</strong> <span id="modal-keyboard">N/A</span></li>
                                    <li><strong>Mouse:</strong> <span id="modal-mouse">N/A</span></li>
                                    <li><strong>Headset:</strong> <span id="modal-headset">N/A</span></li>
                                    <li><strong>Additional:</strong> <span id="modal-additional-peripherals">N/A</span></li>
                                </ul>
                                
                                <h6 class="mt-3"><i class="fas fa-quote-left me-2 text-primary"></i> Description</h6>
                                <p id="modal-description" class="small">N/A</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug info
    console.log('DOM loaded, submission_success: <?php echo $submission_success ? "true" : "false"; ?>');
    
    <?php if ($submission_success): ?>
    // Populate modal with submitted data
    document.getElementById('modal-setup-name').textContent = <?php echo json_encode(htmlspecialchars($submitted_data['setup_name'])); ?>;
    document.getElementById('modal-setup-type').textContent = <?php echo json_encode(htmlspecialchars($submitted_data['setup_type'])); ?>;
    document.getElementById('modal-processor').textContent = <?php echo json_encode(htmlspecialchars($submitted_data['processor'])); ?>;
    
    document.getElementById('modal-graphics-card').textContent = <?php echo !empty($submitted_data['graphics_card']) ? json_encode(htmlspecialchars($submitted_data['graphics_card'])) : "'N/A'"; ?>;
    document.getElementById('modal-ram').textContent = <?php echo !empty($submitted_data['ram']) ? json_encode(htmlspecialchars($submitted_data['ram'])) : "'N/A'"; ?>;
    document.getElementById('modal-storage').textContent = <?php echo !empty($submitted_data['storage']) ? json_encode(htmlspecialchars($submitted_data['storage'])) : "'N/A'"; ?>;
    document.getElementById('modal-monitor').textContent = <?php echo !empty($submitted_data['monitor']) ? json_encode(htmlspecialchars($submitted_data['monitor'])) : "'N/A'"; ?>;
    document.getElementById('modal-keyboard').textContent = <?php echo !empty($submitted_data['keyboard']) ? json_encode(htmlspecialchars($submitted_data['keyboard'])) : "'N/A'"; ?>;
    document.getElementById('modal-mouse').textContent = <?php echo !empty($submitted_data['mouse']) ? json_encode(htmlspecialchars($submitted_data['mouse'])) : "'N/A'"; ?>;
    document.getElementById('modal-headset').textContent = <?php echo !empty($submitted_data['headset']) ? json_encode(htmlspecialchars($submitted_data['headset'])) : "'N/A'"; ?>;
    document.getElementById('modal-additional-peripherals').textContent = <?php echo !empty($submitted_data['additional_peripherals']) ? json_encode(htmlspecialchars($submitted_data['additional_peripherals'])) : "'N/A'"; ?>;
    document.getElementById('modal-primary-game').textContent = <?php echo !empty($submitted_data['primary_game']) ? json_encode(htmlspecialchars($submitted_data['primary_game'])) : "'N/A'"; ?>;
    document.getElementById('modal-description').textContent = <?php echo !empty($submitted_data['description']) ? json_encode(htmlspecialchars($submitted_data['description'])) : "'No description provided.'"; ?>;
    
    // Show success modal
    var successModal = new bootstrap.Modal(document.getElementById('setupSuccessModal'));
    successModal.show();
    <?php endif; ?>
    
    // Form submission handler - add client-side validation
    document.getElementById('setup-form').addEventListener('submit', function(e) {
        var setupName = document.getElementById('setup_name').value.trim();
        var setupType = document.getElementById('setup_type').value;
        var processor = document.getElementById('processor').value.trim();
        
        if (!setupName || !setupType || !processor) {
            e.preventDefault();
            alert('Please fill in all required fields (Setup Name, Setup Type, and Processor).');
            return false;
        }
        
        return true;
    });
});
</script>

<?php include "footer.php"; ?>