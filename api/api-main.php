<?php
require_once("config.php");
require_once("utils.php");
function api_query ($params, $wiki = "meta.wikimedia.org") {
    if (is_array($params)) {
        $params = array_to_string($params, "&", "=");
    }

    global $settings;
    $url = "http://" . $wiki . "/w/api.php?action=query&format=json";
    $data = http_request($url, $params);
    if (empty($data)) {
        throw new Exception("No data received from server. Check that API is enabled.");
    }
    return $data;
}
function get_new_pages_of_user ($user = "", $wiki = "meta.wikimedia.org", $limit = 500, $uccontinue) {
    if (empty($limit)) {
        $limit = 500;   
    }
    global $settings;

    $params = array(
        "action" => "query",
        "list" => "usercontribs",
        "format" => "json",
        "uclimit" => $limit,
        "ucuser" => $user,
        "ucnamespace" => "0",
        "ucprop" => "ids|title|timestamp|flags",
        "ucshow" => "new",
        "continue" => "",
        "ucdir" => "newer",
        "ucstart" => $settings['period']['start'],
        "ucend" => $settings['period']['end']
        );
    if (!empty($uccontinue)) {
        $params['uccontinue'] = $uccontinue;
    }
    // echo json_encode($params) . "\n\n";
    return json_decode(api_query($params, $wiki), true);
}
/**
 * Get content of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_content ($pageids = [], $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop" => "revisions",
        "format" => "json",
        "rvprop" => "content",
        "pageids" => implode("|", $pageids)
        );
    return json_decode(api_query($params, $wiki), true);
}

/**
 * Get page size of a page
 * @param  array  $pageids Array of page IDs
 * @return [type]         [description]
 */
function get_page_size ($pageids = [], $wiki = "meta.wikimedia.org") {
    $params = array(
        "action" => "query",
        "prop"   => "revisions",
        "format" => "json",
        "rvprop" => "size",
        "pageids" => implode("|", $pageids)
        );
    return json_decode(api_query($params, $wiki), true);
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
    $data = str_replace("./", "https://meta.wikimedia.org/wiki/", $data);

    return $data;
}

function get_all_new_pages_of_user ($user = "", $wiki = "meta.wikimedia.org") {
    $data = null;
    $temp_data = null;
    $uccontinue = "";
    $cnt = 0;
    while ($data === null /*|| $cnt < 10){// */|| isset($temp_data['continue'])) {
        $temp_data = get_new_pages_of_user($user, $wiki, 500, $uccontinue);
        $uccontinue = !empty($temp_data['continue']['uccontinue']) ? $temp_data['continue']['uccontinue'] : "";
        if ($data === null)
            $data = $temp_data;
        else {
            for ($i = 0; $i < count($temp_data['query']['usercontribs']); $i++) {
                array_push($data['query']['usercontribs'], $temp_data['query']['usercontribs'][$i]);
            }
        }
        $cnt++;
    }

    return $data;
}
