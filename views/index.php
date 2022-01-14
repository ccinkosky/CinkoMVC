<!DOCTYPE HTML>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CinkoMVC</title>

    <!-- CSS -->
    <?php foreach ($this->config->css as $tag) { echo $tag."\n\t"; } ?>

    <!-- Scripts -->
    <?php foreach ($this->config->scripts as $tag) { echo $tag."\n\t"; } ?>

</head>
<body>

<div id="app"></div>

<!-- React Components -->
<?php foreach ($this->config->reactComponents as $tag) { echo $tag."\n"; } ?>

</body>
</html>