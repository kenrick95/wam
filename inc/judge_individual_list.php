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
        <th>Approximate word count<br><button id="check-all-wc" class="btn btn-default btn-xs">Check all</button></th>
        <th>Verdict</th>
        <th>Remarks</th>
        <th>Checked by</th>
        <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $page_shown = [];
        for ($i = 0; $i < count($all_pages); $i++) {
            if (isset($all_page_sizes[$all_pages[$i]['pageid']])) {
                $page_size = $all_page_sizes[$all_pages[$i]['pageid']]['revisions'][0]['size'];
            } else {
                $page_size = 0;
            }

            $status = isset($all_verdicts[$all_pages[$i]['title']])
                ? $all_verdicts[$all_pages[$i]['title']]['verdict']
                : (($page_size >= 3500) ? "pending" : "pending");
            $remarks = isset($all_verdicts[$all_pages[$i]['title']]) ? $all_verdicts[$all_pages[$i]['title']]['remarks'] : "";
            $judged_by = isset($all_verdicts[$all_pages[$i]['title']]) ? $all_verdicts[$all_pages[$i]['title']]['last_updated_by'] : "";

            $page_shown[$all_pages[$i]['title']] = true;
        ?>
        <tr>
            <td><a href="//<?= $wiki ?>/wiki/<?= $all_pages[$i]['title'] ?>"><?= $all_pages[$i]['title'] ?></a></td>
            <td><?= date("j F Y, H:i:s", strtotime($all_pages[$i]['timestamp'])) ?> (created)</td>
            <td><?= $page_size ?></td>
            <td><button class="btn btn-default check-wc btn-xs" data-status="<?= $status ?>" data-pageid="<?= $all_pages[$i]['pageid'] ?>" data-wiki="<?= $wiki ?>">Check word count</button></td>
            <td><!-- <?= $all_pages[$i]['pageid'] ?> --><?php
            if ($status === "yes") { ?>
                <span class="label label-success">Yes</span>
            <?php } else if ($status === "pending") { ?>
                <span class="label label-warning">Pending</span>
            <?php } else { ?>
                <span class="label label-danger">No</span>
            <?php } ?></td>
            <td><?= $remarks ?></td>
            <td><a href="//<?= $wiki ?>/wiki/User:<?= $judged_by ?>"><?= $judged_by ?></a></td>
            <td>
                <a class="btn btn-default btn-xs"
                href="judging.php?pageid=<?= $all_pages[$i]['pageid'] ?>&amp;username=<?= $username ?>&amp;wiki=<?= $wiki ?>">
                    Judge
                </a>
            </td>
        </tr>
        <?php
        }

        $newer_pageids = [];
        foreach ($all_verdicts as $page_title => $data) {
            if (!isset($page_shown[$page_title])) {
                array_push($newer_pageids, get_page_id($page_title, $wiki));
            }
        }
        $newer_page_sizes = get_page_size($newer_pageids, $wiki)['query']['pages'];
        if (count($newer_page_sizes) === 0) {
          $newer_page_sizes = array();
        }
        foreach ($newer_page_sizes as $page_id => $data) {
          $page_size = $data['revisions'][0]['size'];
            $status = !empty($all_verdicts[$data['title']])
                ? $all_verdicts[$data['title']]['verdict']
                : "pending";
            $remarks = isset($all_verdicts[$data['title']]) ? $all_verdicts[$data['title']]['remarks'] : "";
            $judged_by = isset($all_verdicts[$data['title']]) ? $all_verdicts[$data['title']]['last_updated_by'] : "";

        ?>
        <tr>
            <td><a href="//<?= $wiki ?>/wiki/<?= $data['title'] ?>"><?= $data['title'] ?></a></td>
            <td><?= date("j F Y, H:i:s", strtotime($data['revisions'][0]['timestamp'])) ?> (last edited)</td>
            <td><?= $page_size ?></td>
            <td><button class="btn btn-default check-wc btn-xs" data-status="<?= $status ?>" data-pageid="<?= $data['pageid'] ?>" data-wiki="<?= $wiki ?>">Check word count</button></td>
            <td><!-- <?= $data['pageid'] ?> --><?php
            if ($status === "yes") { ?>
                <span class="label label-success">Yes</span>
            <?php } else if ($status === "pending") { ?>
                <span class="label label-warning">Pending</span>
            <?php } else { ?>
                <span class="label label-danger">No</span>
            <?php } ?></td>
            <td><?= $remarks ?></td>
            <td><a href="//<?= $wiki ?>/wiki/User:<?= $judged_by ?>"><?= $judged_by ?></a></td>
            <td>
                <a class="btn btn-default btn-xs"
                href="judging.php?pageid=<?= $data['pageid'] ?>&amp;username=<?= $username ?>&amp;wiki=<?= $wiki ?>">
                    Judge
                </a>
            </td>
        </tr>
        <?php
        }
    ?>
    </tbody>
    </table>
    The judging data for this user is saved on <a href="//meta.wikimedia.org/wiki/Wikipedia_Asian_Month/Judging/<?= $wiki ?>/<?= $username ?>">this meta-wiki page</a>.
    <br>
    <button class="btn btn-default" data-toggle="modal" data-target="#add-art"><span class="glyphicon glyphicon-plus"></span> Add article to this list</button>
</div>
<div class="modal fade" id="add-art" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Manually adds article</h4>
      </div>
      <form class="form-horizontal" id="manual-add" data-wiki="<?= $wiki ?>" data-username="<?= $username ?>">
      <div class="modal-body">
          <div class="form-group">
            <label for="art-name" class="col-sm-2 control-label">Article title</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="art-name" required="required">
              <span class="help-block">
                <strong>Important:</strong> Make sure the title is using the exact spelling and letter case as what is used in the wiki! Also, please use space instead of underscore.</span>
            </div>
          </div>
              <div class="form-group">
                <label for="art-remarks" class="col-sm-2 control-label">Remarks</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="art-remarks">
                  <span class="help-block">Reason why the article is added.</span>
                </div>
              </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              Please make sure that this addition adheres to the local wiki rules.
              <div id="status"></div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="#manual-add-btn">Add article</button>
      </div>
    </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
