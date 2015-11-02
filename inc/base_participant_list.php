<h2>Participant list at <a href="//<?= $filter ?>/wiki/"><?= $filter; ?></a></h2>
<ol class="breadcrumb">
    <li><a href="<?= $__next_page; ?>.php">#</a></li>
    <li><a class="active" href="<?= $__next_page; ?>.php?filter=<?= $filter; ?>"><?= $filter; ?></a></li>
</ol>
<ul>
<?php
    sort($participants);
    for ($i = 0; $i < count($participants); $i++) {
        if (!empty($filter) && $participants[$i]['wiki'] !== $filter) {
            continue;
        }
    ?>
    <li><a href="<?= $__next_page; ?>.php?username=<?= $participants[$i]['username'] ?>&amp;wiki=<?= $participants[$i]['wiki'] ?>"><?= $participants[$i]['username'] ?></a>
    </li>
    <?php
    }
?>
</ul>
<strong>Not listed?</strong> Add yourself in at <a href="https://meta.wikimedia.org/wiki/Wikipedia_Asian_Month/Participants">this meta-wiki page</a>.
