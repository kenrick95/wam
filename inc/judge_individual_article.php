<h2>
    <a href="//<?= $wiki ?>/wiki/<?= $article_title ?>"><?= $article_title ?></a> by <a href="//<?= $wiki ?>/wiki/User:<?= $username ?>"><?= $username ?></a> at <a href="//<?= $wiki ?>/wiki/"><?= $wiki; ?></a>
</h2>
<ol class="breadcrumb">
    <li><a href="judging.php">#</a></li>
    <li><a href="judging.php?filter=<?= $wiki; ?>"><?= $wiki; ?></a></li>
    <li><a href="judging.php?username=<?= $username ?>&amp;wiki=<?= $wiki ?>"><?= $username; ?></a></li>
    <li><a class="active" href="judging.php?pageid=<? $pageid; ?>&amp;username=<?= $username ?>&amp;wiki=<?= $wiki ?>"><?= $article_title; ?></a></li>
</ol>
<?php
$label_page_size = "danger";
if ($article_page_size >= 3500) {
    $label_page_size = "success";
}
$label_word_count = "danger";
if ($article_word_count >= 300) {
    $label_word_count = "success";
}
?>
<div class=" pull-right">
    <strong>Verdict: </strong>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default btn-sm judge-article"
            data-pageid="<?= $pageid ?>" data-wiki="<?= $wiki ?>">Yes</button>
        <button type="button" class="btn btn-default btn-sm judge-article"
            data-pageid="<?= $pageid ?>" data-wiki="<?= $wiki ?>">No</button>
        <button type="button" class="btn btn-default btn-sm judge-article"
            data-pageid="<?= $pageid ?>" data-wiki="<?= $wiki ?>">Pending</button>
    </div>
</div>
<span class="label label-<?= $label_page_size ?>"><?= $article_page_size ?> bytes</span>
<span class="label label-<?= $label_word_count ?>"><?= $article_word_count ?> words (approx.)</span>
<?php
$wiki_exp = explode('.', $wiki);
$wiki_mobile = $wiki_exp[0] . ".m." . $wiki_exp[1] . "." . $wiki_exp[2];

?>
<iframe src="//<?= $wiki_mobile ?>/wiki/<?= $article_title ?>?redirect=no" width="1150" height="500"></iframe>
