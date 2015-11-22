<h2>Participant list at <a href="//<?= $filter ?>/wiki/"><?= $filter; ?></a></h2>
<ol class="breadcrumb">
    <li><a href="<?= $__next_page; ?>.php">#</a></li>
    <li><a class="active" href="<?= $__next_page; ?>.php?filter=<?= $filter; ?>"><?= $filter; ?></a></li>
</ol>
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th rowspan="2">Username</th>
        <th colspan="5">Statistics <button class="btn btn-default btn-xs" id="check-all-stats">Check stats of all users</button></th>
      </tr>
      <tr>
        <th>Article count</th>
        <th>Judged articles</th>
        <th>Pending articles</th>
        <th>Valid articles</th>
        <th>Invalid articles</th>
      </tr>
    </thead>
    <tbody>
<?php
    sort($participants);
    for ($i = 0; $i < count($participants); $i++) {
        if (!empty($filter) && $participants[$i]['wiki'] !== $filter) {
            continue;
        }
    ?>
    <tr>
      <td>
        <a href="<?= $__next_page; ?>.php?username=<?= $participants[$i]['username'] ?>&amp;wiki=<?= $participants[$i]['wiki'] ?>"><?= $participants[$i]['username'] ?></a>
      </td>
      <td colspan="5"><button class="btn btn-default check-stats btn-xs" data-username="<?= $participants[$i]['username'] ?>" data-wiki="<?= $participants[$i]['wiki'] ?>">Check stats</button></td>
    </tr>
    <?php
    }
?>
  </tbody>
</table>
<strong>Not listed?</strong> Add yourself in at <a href="https://meta.wikimedia.org/wiki/Wikipedia_Asian_Month/Participants">this meta-wiki page</a>.
