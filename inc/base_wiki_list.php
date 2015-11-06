<h2>Check progress at:</h2>
<ol class="breadcrumb">
    <li><a href="<?= $__next_page; ?>.php">#</a></li>
</ol>
<ul>
<?php
    $sav = [];
    $wiki_list = [];
    for ($i = 0; $i < count($participants); $i++) {
        if (isset($sav[$participants[$i]['wiki']])) {
            continue;
        }
        $sav[$participants[$i]['wiki']] = 1;
        array_push($wiki_list, $participants[$i]['wiki']);
    }
    for ($i = 0; $i < count($wiki_list); $i++) {
    ?>
    <li><a href="<?= $__next_page; ?>.php?filter=<?= $wiki_list[$i] ?>"><?= $wiki_list[$i] ?></a></li>
    <?php
    }
?>
</ul>
