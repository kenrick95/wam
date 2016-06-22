<?php
require_once("config.php");
require_once("utils.php");

function get_page_wordcount_cache($pageids = [], $wiki = null) {
    global $db;
    global $settings;
    $wiki = isset($wiki) ? $wiki : $settings['main_page_wiki'];

    $clean_pageids = implode(",", $pageids);

    $sql = "SELECT * FROM `word_count_cache` WHERE `pageid` IN ($clean_pageids) AND `wiki` = '$wiki'";
    $result = $db->query($sql);
    $ret = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($ret, array(
                'pageid' => $row['pageid'],
                'last_updated' => $row['last_updated'],
                'word_count' => $row['word_count']
            ));
        }
    }
    
    return $ret;
}

function save_page_wordcount_cache($data = [], $wiki = null) {
    // data: key-value pair of pageid-word_count
    global $db;
    global $settings;
    $wiki = isset($wiki) ? $wiki : $settings['main_page_wiki'];
    
    if ($data) {
        $current_time = date("Y-m-d H:i:s");
        foreach ($data as $key => $value) {
            $sql = "INSERT INTO `word_count_cache` (`pageid`, `wiki`, `last_updated`, `word_count`) VALUES ('$key', '$wiki', '$current_time', '$value');";
            $db->query($sql);
        }
    }
    
}