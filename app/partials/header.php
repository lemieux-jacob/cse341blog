<?php if (isset($_SESSION['msg'])) {$msg = $_SESSION['msg']; unset($_SESSION['msg']);} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/styles/app.css">
  <script src="https://kit.fontawesome.com/ec58e5f1d7.js" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary">
    <div class="container">
      <a class="navbar-brand" href="/">MyBlog</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/">Posts</a>
          </li>
        </ul>
        <form class="form-inline" action="/posts" method="GET" class="ml-auto form-inline my-2 my-lg-0">
          <input name="search" class="form-control mr-sm-2" type="search" value="<?php $search; ?>" placeholder="Search Posts" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <div class="navbar border-0">
          <?php if (!user()): ?>
            <a class="nav-link" href="/login">Login</a>
            <a class="nav-link" href="/register">Register</a>
          <?php else: ?>
            <?php if (user()->isAdmin()):?>
            <a class="nav-link" href="/posts/create">New Post</a>
            <?php endif; ?>
            <a class="nav-link" href="/dashboard">Account</a>
            <a class="nav-link" href="/logout">Log Out</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <div class="container my-3">
    <?php if (isset($msg) && $msg != ""):?>
    <div class="alert alert-warning text-warning mt-3">
      <?= $msg; ?>
    </div>
    <?php endif; ?>
    <main>