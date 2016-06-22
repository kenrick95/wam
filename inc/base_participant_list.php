<h2>Participant list at <a href="//<?= $filter ?>/wiki/"><?= $filter; ?></a></h2>
<ol class="breadcrumb">
    <li><a href="<?= $__next_page; ?>.php">#</a></li>
    <li><a class="active" href="<?= $__next_page; ?>.php?filter=<?= $filter; ?>"><?= $filter; ?></a></li>
</ol>
<div class="table-responsive">
  <table class="table datatable">
    <thead>
      <tr>
        <th>Username</th>
        <!--<td colspan="5">Statistics <button class="btn btn-default btn-xs" id="check-all-stats">Check stats of all users</button></td>-->
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

    $wiki_list = [];
    $user_list = [];
    if (!empty($filter)) {
      $wiki_list = [$filter];
    } else {
      for ($i = 0; $i < count($participants); $i++) {
        $wiki = $participants[$i]['wiki'];
        if (!array_search($wiki, $wiki_list)) {
          array_push($wiki_list, $wiki);
        }
      }
    }
    $all_stats = [];
    foreach ($wiki_list as $wiki) {
      $user_list[$wiki] = array();
    }

    for ($i = 0; $i < count($participants); $i++) {
      if (!empty($filter) && $participants[$i]['wiki'] !== $filter) {
          continue;
      }
      array_push($user_list[$participants[$i]['wiki']], $participants[$i]['username']);
    }
      
    foreach ($wiki_list as $wiki) {
      $cur_stats = get_users_stats($user_list[$wiki], $wiki);
      $all_stats[$wiki] = $cur_stats;
    }


    for ($i = 0; $i < count($participants); $i++) {
        if (!empty($filter) && $participants[$i]['wiki'] !== $filter) {
            continue;
        }
        $stats = $all_stats[$participants[$i]['wiki']][$participants[$i]['username']];
    ?>
    <tr>
      <td>
        <a href="<?= $__next_page; ?>.php?username=<?= $participants[$i]['username'] ?>&amp;wiki=<?= $participants[$i]['wiki'] ?>"><?= $participants[$i]['username'] ?></a>
      </td><!--
      <td colspan="5"><button class="btn btn-default check-stats btn-xs" data-username="<?= $participants[$i]['username'] ?>" data-wiki="<?= $participants[$i]['wiki'] ?>">Check stats</button></td>-->
      <td><?= $stats['all'] ?></td>
      <td><?= $stats['yes'] + $stats['no'] ?></td>
      <td><?= $stats['pending'] ?></td>
      <td><?= $stats['yes'] ?></td>
      <td><?= $stats['no'] ?></td>
    </tr>
    <?php
    }
?>
  </tbody>
</table>
<strong>Not listed?</strong> Add yourself in at <a href="<?= $settings['participant_list_page_link'] ?>">this page</a>.
