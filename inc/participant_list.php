<div class="table-responsive">
<table class="table">
<thead>
    <tr>
    <th>Username</th>
    <th>Wiki</th>
    <th>&nbsp;</th>
    </tr>
</thead>
<tbody>
<?php
    for ($i = 0; $i < count($participants); $i++) {
    ?>
    <tr>
        <td><a href="//<?= $participants[$i]['wiki'] ?>/wiki/User:<?= $participants[$i]['username'] ?>"><?= $participants[$i]['username'] ?></a></td>
        <td><a href="//<?= $participants[$i]['wiki'] ?>/wiki/"><?= $participants[$i]['wiki'] ?></a></td>
        <td><a class="btn btn-default btn-xs" href="progress.php?username=<?= $participants[$i]['username'] ?>&amp;wiki=<?= $participants[$i]['wiki'] ?>">Check progress</a></td>
    </tr>
    <?php
    }
?>
</tbody>
</table>
</div>
<strong>Not listed?</strong> Add yourself in at <a href="https://meta.wikimedia.org/wiki/Wikipedia_Asian_Month/Participants.json">this meta page</a>.