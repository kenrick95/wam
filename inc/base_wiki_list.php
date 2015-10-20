<h2>Check progress at:</h2>
<ol class="breadcrumb">
    <li><a href="<?= $__next_page; ?>.php">#</a></li>
</ol>
<ul>
<?php
    $sav = [];
    for ($i = 0; $i < count($participants); $i++) {
        if (isset($sav[$participants[$i]['wiki']])) {
            continue;
        }
        $sav[$participants[$i]['wiki']] = 1;

    ?>
    <li><a href="<?= $__next_page; ?>.php?filter=<?= $participants[$i]['wiki'] ?>"><?= $participants[$i]['wiki'] ?></a></li>
    <?php
    }
?>
</ul>
