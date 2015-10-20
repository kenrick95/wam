<h2>Progress for <a href="//<?= $wiki ?>/wiki/User:<?= $username ?>"><?= $username ?></a> at <a href="//<?= $wiki ?>/wiki/"><?= $wiki; ?></a></h2>
<ol class="breadcrumb">
    <li><a href="judging.php">#</a></li>
    <li><a href="judging.php?filter=<?= $wiki; ?>"><?= $wiki; ?></a></li>
    <li><a class="active" href="judging.php?username=<?= $username ?>&amp;wiki=<?= $wiki ?>"><?= $username; ?></a></li>
</ol>
<div class="table-responsive">
    <table class="table">
    <thead>
        <tr>
        <th>Article name</th>
        <th>Date and time</th>
        <th>Current byte count</th>
        <th>Approximate word count<br><button id="check-all-wc" class="btn btn-default btn-xs">Check word count for pending articles</button></th>
        <th>Status</th>
        <th>&nbsp;</th>
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
            $status = "no";
            if ($page_size >= 3500) {
                $status = "pending";
            }
        ?>
        <tr>
            <td><a href="//<?= $wiki ?>/wiki/<?= $all_pages[$i]['title'] ?>"><?= $all_pages[$i]['title'] ?></a></td>
            <td><?= date("j F Y, H:i:s", strtotime($all_pages[$i]['timestamp'])) ?></td>
            <td><?= $page_size ?></td>
            <td><button class="btn btn-default check-wc btn-xs" data-status="<?= $status ?>" data-pageid="<?= $all_pages[$i]['pageid'] ?>" data-wiki="<?= $wiki ?>">Check word count</button></td>
            <td><!-- <?= $all_pages[$i]['pageid'] ?> --><?php
            if ($status === "pending") { ?>
                <span class="label label-warning">Pending</span>
            <?php } else { ?>
                <span class="label label-danger">No</span>
            <?php } ?></td>
            <td>
                <a class="btn btn-default btn-xs"
                href="judging.php?pageid=<?= $all_pages[$i]['pageid'] ?>&amp;username=<?= $username ?>&amp;wiki=<?= $wiki ?>">
                    Judge
                </a>
            </td>
        </tr>
        <?php
        }
    ?>
    </tbody>
    </table>
</div>
