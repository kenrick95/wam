<h2>
    <a href="//<?= $wiki ?>/wiki/<?= $article_title ?>"><?= $article_title ?></a> by <a href="//<?= $wiki ?>/wiki/User:<?= $username ?>"><?= $username ?></a> at <a href="//<?= $wiki ?>/wiki/"><?= $wiki; ?></a>
</h2>
<ol class="breadcrumb">
    <li><a href="judging.php">#</a></li>
    <li><a href="judging.php?filter=<?= $wiki; ?>"><?= $wiki; ?></a></li>
    <li><a href="judging.php?username=<?= $username ?>&amp;wiki=<?= $wiki ?>"><?= $username; ?></a></li>
    <li><a class="active" href="judging.php?pageid=<?= $pageid; ?>&amp;username=<?= $username ?>&amp;wiki=<?= $wiki ?>"><?= $article_title; ?></a></li>
</ol>
<?php
$label_page_size = "danger";
$minimumArticlePageSize = isset($settings['minimumArticlePageSize'][$wiki]) ? $settings['minimumArticlePageSize'][$wiki] : $settings['minimumArticlePageSize']['*'];
$minimumWordCount = isset($settings['minimumWordCount'][$wiki]) ? $settings['minimumWordCount'][$wiki] : $settings['minimumWordCount']['*'];

if ($article_page_size >= $minimumArticlePageSize) {
    $label_page_size = "success";
}
$label_word_count = "danger";
if ($article_word_count >= $minimumWordCount) {
    $label_word_count = "success";
}

?>
<div class=" pull-right" style="margin-left: 1em; margin-bottom: 1em; border: 1px solid #ccc; padding: .5em;">
    <form class="form-horizontal">
        <h3>Judging</h3>
        <div class="form-group">
            <label for="remarks" class="col-sm-3 control-label">Remarks: </label>
            <div class="col-sm-9">
                <input id="remarks" type="text" class="form-control" value="<?= $remarks ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Verdict: </label>
            <div class="btn-group col-sm-9" role="group">
                <button type="button" class="btn btn-default btn-sm judge-article<?php if ($verdict == "yes") echo " active"; ?>"
                    data-page-title="<?= $article_title ?>" data-username="<?= $username ?>"
                    data-wiki="<?= $wiki ?>" data-verdict="yes">Yes</button>
                <button type="button" class="btn btn-default btn-sm judge-article<?php if ($verdict == "no") echo " active"; ?>"
                    data-page-title="<?= $article_title ?>" data-username="<?= $username ?>"
                    data-wiki="<?= $wiki ?>" data-verdict="no">No</button>
                <button type="button" class="btn btn-default btn-sm judge-article<?php if ($verdict == "pending") echo " active"; ?>"
                    data-page-title="<?= $article_title ?>" data-username="<?= $username ?>"
                    data-wiki="<?= $wiki ?>" data-verdict="pending">Pending</button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <span id="status"></span>
            </div>
        </div>


</form>
</div>
<span class="label label-<?= $label_page_size ?>"><?= $article_page_size ?> bytes</span>
<span class="label label-<?= $label_word_count ?>"><?= $article_word_count ?> words (approx.)</span>

<?= get_page_content_html($article_title, $wiki) ?>
