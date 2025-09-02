<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="stylesheet" href="<?= url("/theme/assets/css/app.css") ?>">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <title><?= SITE . " - " . $title; ?></title>
</head>

<body>

  <?= $this->section("content"); ?>

  <div id="background-loader"></div>
  <div id="loader"></div>

  <script src="<?= url("/theme/assets/js/index.js"); ?>"></script>
  <script src="<?= url("/theme/assets/js/jquery.js"); ?>"></script>

  <?= $this->section("js"); ?>
</body>

</html>