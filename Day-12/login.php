<?php
// session_start() must run before ANY HTML output — even a blank line
// before this <?php tag would break it, so this file starts exactly here.
session_start();
include 'db_connect.php';

// If someone who's already logged in visits login.php again, just
// send them straight to the dashboard instead of showing the form.
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Challenge: empty-field check before we even touch the database
    if ($email === '' || $password === '') {
        $error = "Please enter both email and password.";
    } else {
        // Mission 2: look up the user by email using a prepared statement
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // NOTE: plain-text password comparison for now — this session
        // focuses on the login/session flow. A later session covers
        // password_hash() / password_verify() for real security.
        if ($user && $password === $user['password']) {

            // ----- Module 3: start the session and store user data -----
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['login_time'] = date("g:i A");

            // Bonus: record + update the last login timestamp
            $update = mysqli_prepare($conn, "UPDATE users SET last_login = NOW() WHERE id = ?");
            mysqli_stmt_bind_param($update, "i", $user['id']);
            mysqli_stmt_execute($update);

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Student Management System</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">

        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">🔐 Sign In</h3>
            <small>Student Management System</small>
          </div>

          <div class="card-body p-4">

            <?php if ($error !== ""): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="you@example.com"
                  value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                  required
                />
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
                  />
                  <!-- Bonus: show/hide password toggle -->
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    👁️
                  </button>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-muted text-center mt-3 mb-0">
              <small>Demo login: admin@skit.ac.in / admin123</small>
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // Bonus: Show Password toggle
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const isHidden = passwordInput.type === 'password';
      passwordInput.type = isHidden ? 'text' : 'password';
      this.textContent = isHidden ? '🙈' : '👁️';
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
