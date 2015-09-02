<h2>Progress for <a href="//<?= $wiki ?>/wiki/User:<?= $username ?>"><?= $username ?></a> at <a href="//<?= $wiki ?>/wiki/"><?= $wiki; ?></a></h2>
<div class="table-responsive">
    <table class="table">
    <thead>
        <tr>
        <th>Article name</th>
        <th>Date and time</th>
        <th>Current byte count</th>
        <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
        for ($i = 0; $i < count($all_pages); $i++) {
            if (isset($all_page_sizes[$all_pages[$i]['pageid']])) {
                $page_size = $all_page_sizes[$all_pages[$i]['pageid']]['revisions'][0]['size'];
            } else {
                $page_size = 0;
            }
        ?>
        <tr>
            <td><a href="//<?= $wiki ?>/wiki/<?= $all_pages[$i]['title'] ?>"><?= $all_pages[$i]['title'] ?></a></td>
            <td><?= date("j F Y, H:i:s", strtotime($all_pages[$i]['timestamp'])) ?></td>
            <td><?= $page_size ?></td>
            <td><!-- <?= $all_pages[$i]['pageid'] ?> --><?php
            if ($page_size >= 3500) { ?>
                <span class="label label-warning">Pending</span>
            <?php } else { ?>
                <span class="label label-danger">No</span>
            <?php } ?></td>
        </tr>
        <?php
        }
    ?>
    </tbody>
    </table>
</div>