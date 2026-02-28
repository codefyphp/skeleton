<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?=$page->getTranslation('meta_description');?>" />
    <title><?=$page->getTranslation('meta_title');?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?=phpb_theme_asset('images/favicon.ico');?>" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?=phpb_theme_asset('css/style.css');?>" rel="stylesheet" />
</head>
<body class="d-flex flex-column h-100">
<main class="flex-shrink-0">
    [block slug="navbar"]
    <?=$body;?>
</main>
[block slug="footer"]
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="<?=phpb_theme_asset('js/script.js');?>"></script>
</body>
</html>
