<?php
require_once("config.php");
require_once("utils.php");
function api_query ($params) {
    if (is_array($params)) {
        $params = array_to_string($params, "&", "=");
    }

    global $settings;
    $url = $settings['wikiroot'] . "/w/api.php?action=query&format=json";
    $data = http_request($url, $params);
    if (empty($data)) {
        throw new Exception("No data received from server. Check that API is enabled.");
    }
    return $data;
}
function get_new_pages_of_user ($user = "", $limit = 500) {
    if (empty($limit)) {
        $limit = 500;   
    }

    $params = array(
        "action" => "query",
        "list" => "usercontribs",
        "format" => "json",
        "uclimit" => $limit,
        "ucuser" => $user,
        "ucnamespace" => "0",
        "ucprop" => "ids|title|timestamp|flags",
        "ucshow" => "new"
        );
    return json_decode(api_query($params), true);
}
/**
 * Get content of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_content ($pageids = []) {
    $params = array(
        "action" => "query",
        "prop" => "revisions",
        "format" => "json",
        "rvprop" => "content",
        "pageids" => implode("|", $pageids)
        );
    return json_decode(api_query($params), true);
}

/**
 * Get page size of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_size ($pageids = []) {
    $params = array(
        "action" => "query",
        "prop"   => "revisions",
        "format" => "json",
        "rvprop" => "size",
        "pageids" => implode("|", $pageids)
        );
    return json_decode(api_query($params), true);
}

function get_meta_page () {
    $url = "http://rest.wikimedia.org:80/meta.wikimedia.org/v1/page/html/Wikipedia_Asian_Month";
    $params = "";
    $data = http_request($url, $params);
    if (empty($data)) {
        throw new Exception("No data received from server. Check that API is enabled.");
    }
    // little processing
    $data = explode("</head>", $data)[1];
    $data = explode("<link>", $data)[0];

    return $data;
}
get_meta_page();