<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My App</title>
  <link rel="stylesheet" href="<?= base_url('css/nav.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<!-- Sidebar -->
<input type="checkbox" id="check">
<label for="check" class="toggle-btn">
  <i class="fas fa-chevron-left" id="sidebar-icon"></i>
</label>

<div class="sidebar">
  <header>My App</header>
  <ul>
    <li><a href="#"><i class="fas fa-qrcode"></i>Dashboard</a></li>
    <li><a href="#"><i class="fas fa-link"></i>Shortcuts</a></li>
    <li><a href="#"><i class="fas fa-stream"></i>Overview</a></li>
    <li><a href="#"><i class="fas fa-calendar-week"></i>Events</a></li>
    <li><a href="#"><i class="far fa-question-circle"></i>About</a></li>
    <li><a href="#"><i class="fas fa-sliders-h"></i>Services</a></li>
    <li><a href="#"><i class="far fa-envelope"></i>Contact</a></li>
  </ul>
</div>

<!-- Main Content Area -->
<div class = "main-content">
  <?= $this->renderSection('content') ?>
</div>

<script>
  const checkbox = document.getElementById('check');
  const icon = document.getElementById('sidebar-icon');

  checkbox.addEventListener('change', function () {
    if (this.checked) {
      icon.classList.remove('fa-chevron-left');
      icon.classList.add('fa-chevron-right');
    } else {
      icon.classList.remove('fa-chevron-right');
      icon.classList.add('fa-chevron-left');
    }
  });
</script>

</body>
</html>
