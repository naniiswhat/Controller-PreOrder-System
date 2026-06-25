<?php
session_start();

$navLabel = isset($_SESSION['role']) ? 'Logout' : 'Login';
$navHref = isset($_SESSION['role']) ? '../logout.php' : 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About | Controller Pre Order System</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body data-page="about">
  
<?php include __DIR__ . '/../includes/navbar.php'; ?>
  
  <main class="about-main">
    <section class="about-survivor" aria-labelledby="survivor-title">
      <p>It's us!</p>
      <h1 id="survivor-title">SURVIVOR</h1>
    </section>

    <section class="about-proof" aria-label="Controller preorder proof">
      <div class="proof-copy">
        <p>Trusted by players worldwide for limited-run controllers, early access drops, and clean preorder updates from checkout to collection.</p>

        <dl class="proof-stats">
          <div>
            <dt>Controllers reserved</dt>
            <dd>12,500+</dd>
          </div>
          <div>
            <dt>Drop value</dt>
            <dd>RM1.2M</dd>
          </div>
          <div>
            <dt>Players served</dt>
            <dd>9,500</dd>
          </div>
        </dl>
      </div>

      <div class="proof-showcase" aria-label="Featured controller preorder display">
        <div class="showcase-shelf" aria-hidden="true">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
        <img src="assets/front.png" alt="Black Steam controller ready for preorder">
      </div>
    </section>

    <section class="about-team" aria-labelledby="team-title">
      <h2 id="team-title">The Team</h2>

      <div class="team-grid">
        <article class="team-member">
          <img src="assets/team-ryan.svg" alt="Ryan Mitchell">
          <h3>Yee Ching Yang</h3>
          <p>Founder</p>
        </article>
        <article class="team-member">
          <img src="assets/team-madison.svg" alt="Madison Clarke">
          <h3>Daniel Iman Haqimie Bin Yusuff</h3>
          <p>Drop Marketing</p>
        </article>
        <article class="team-member">
          <img src="assets/team-jake.svg" alt="Jake Mitchell">
          <h3>Pravein A/L Letchuman</h3>
          <p>Co Founder</p>
        </article>
        <article class="team-member">
          <img src="assets/team-ava.svg" alt="Ava Bennett">
          <h3>Abdurrafiq Bin Zakaria</h3>
          <p>Operations Lead</p>
        </article>
        <article class="team-member">
          <img src="assets/team-noah.svg" alt="Noah Tan">
          <h3>Muhammad Hazim Bin Zulkanain</h3>
          <p>Support Lead</p>
        </article>
      </div>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
