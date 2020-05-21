<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog</title>
</head>
<body>
  <header>
    <h1>Blog</h1>
  </header>
  <ul>
    <?php foreach($users as $user): ?>
    <li><?= $user->display_name; ?></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>