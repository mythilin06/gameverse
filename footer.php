</div> 
<footer class="bg-dark text-white py-5 mt-5">
<div class="container">
<div class="row">
<div class="col-md-4 mb-4 mb-md-0">
<h5>GameVerse</h5>
<p class="text-muted">Your ultimate gaming hub with wiki content, forums, game tracking, and more.</p>
<div class="social-links mt-3">
<a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
<a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
<a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
<a href="#" class="text-white me-2"><i class="fab fa-discord"></i></a>
</div>
</div>

<div class="col-md-4 mb-4 mb-md-0">
<h5>Quick Links</h5>
<ul class="list-unstyled">
<li><a href="index.php" class="text-decoration-none footer-link">Home</a></li>
<li><a href="wiki.php" class="text-decoration-none footer-link">Game Wiki</a></li>
<li><a href="forums.php" class="text-decoration-none footer-link">Forums</a></li>
<li><a href="premium.php" class="text-decoration-none footer-link">Premium</a></li>
<li><a href="#" class="text-decoration-none footer-link">Privacy Policy</a></li>
<li><a href="#" class="text-decoration-none footer-link">Terms of Service</a></li>
</ul>
</div>

<div class="col-md-4">
<h5>Newsletter</h5>
<p class="text-muted">Subscribe to our newsletter for gaming updates.</p>
<form action="newsletter_subscribe.php" method="post">
<div class="input-group mb-3">
<input type="email" name="email" class="form-control" placeholder="Your email" required>
<button class="btn btn-primary" type="submit">Subscribe</button>
</div>
</form>
</div>
</div>

<hr class="mt-4">

<div class="text-center text-muted">
<p>&copy; <?php echo date("Y"); ?> GameVerse. All rights reserved.</p>
</div>
</div>
</footer>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
<div id="cookieConsent" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
<div class="toast-header">
<strong class="me-auto">Cookie Notice</strong>
<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
</div>
<div class="toast-body">
<p>We use cookies to enhance your browsing experience.</p>
<div class="mt-2 pt-2 border-top">
<button type="button" class="btn btn-primary btn-sm" id="acceptCookies">Accept</button>
<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Decline</button>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>