<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<head>
  <meta charset="UTF-8">
  <title>Modern Login Form | CodingStella </title>
  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap'>
  <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>

<body>
  <div class="wrapper">
    <div class="login_box">
      <div class="login-header">
        <span>Login</span>
      </div>

      <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
          <?= session()->getFlashdata('success') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('auth/doLogin') ?>" method="post">
        <?= csrf_field() ?>

        <div class="input_box">
          <input type="text" id="email" name="email" class="input-field" required>
          <label for="email" class="label">Email atau Username</label>
          <i class="bx bx-user icon"></i>
        </div>

        <div class="input_box">
          <input type="password" id="password" name="password" class="input-field" required>
          <label for="password" class="label">Password</label>
          <i class="bx bx-lock-alt icon"></i>
        </div>

        <div class="remember-forgot">
          <div class="remember-me">
            <input type="checkbox" id="remember">
            <label for="remember">Remember me</label>
          </div>

          <div class="forgot">
            <a href="#">Forgot password?</a>
          </div>
        </div>

        <div class="input_box">
          <input type="submit" class="input-submit" value="Login">
        </div>
      </form>

      <div class="register">
        <span>Don't have an account? <a href="<?= base_url('auth/register') ?>">Register here</a></span>
      </div>
    </div>
  </div>

</body>

<?= $this->endSection() ?>
