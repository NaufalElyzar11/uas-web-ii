<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sider Menu Bar CSS</title>
    <link rel="stylesheet" href="<?= base_url('css/nav.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  </head>
  <body>
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
   <section class="main-content">
    <?= $this->renderSection('content') ?>
   </section>

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