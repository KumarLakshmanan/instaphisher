<?php

$commentiPost;
$likePost;
$postData;
$image;
$urlprofilo;
$followers;
$username;
$follow;
$like;
$commenti;

function getMediaByUsername($count)
{
    global $image;
    global $commentiPost;
    global $likePost;
    global $urlprofilo;
    global $followers;
    global $username;
    global $follow;
    global $postData;
    global $like;
    global $commenti;
    $uname      = htmlspecialchars($_GET["name"]);
    $username   = strtolower(str_replace(' ', '_', $uname));
    $url        = "https://www.instagram.com/" . $username . "/?__a=1";

    $userinfo   = url_get_contents($url);
    $userdata   = json_decode($userinfo, true);
    $user       = $userdata['graphql']['user'];
    $iteration_url = $url;



    if (!empty($user)) {

        $followers  = $user['edge_followed_by']['count'];
        $follow     = $user['edge_follow']['count'];
        $fullname   = $user['full_name'];
        $username   = $user['username'];
        $profilepic = $user['profile_pic_url'];
        $profilepic = (explode("/", $profilepic));
        $urlprofilo = "https://scontent-frt3-1.cdninstagram.com/v/t51.2885-19/s150x150/$profilepic[6]";


        $limit      = $count;
        $tryNext    = true;
        $found      = 0;


        while ($tryNext) {
            $tryNext = false;

            $remote = file_get_contents($iteration_url);

            $response = $remote;

            if ($response === false) {
                return false;
            }
            $data = json_decode($response, true);

            if ($data === null) {
                return false;
            }
            $media = $data['graphql']['user']['edge_owner_to_timeline_media'];

            foreach ($media['edges'] as $index => $node) {
                if ($found + $index < $limit) {
                    if (isset($node['node']['is_video']) && $node['node']['is_video'] == true) {
                        $type = 'video';
                    } else {
                        $type = 'image';
                    }
                    $like = $like + $node['node']['edge_liked_by']['count'];
                    $commenti = $commenti + $node['node']['edge_media_to_comment']['count'];
                    $image[] = array("<a href=" . $node['node']['display_url'] . ">
                                    <img src=" . $node['node']['display_url'] . " alt=" . " />
                                    <h3>Like: </strong>" . $node['node']['edge_liked_by']['count'] . "</strong>    Commenti: <strong>" . $node['node']['edge_media_to_comment']['count'] . "</strong></h3>
                                </a>");
                    $postData[] = array(" '" . gmdate("d-m-Y", $node['node']['taken_at_timestamp']) . "',");
                    $likePost[] = array(" " . $node['node']['edge_liked_by']['count'] . ",");
                    $commentiPost[] = array(" " . $node['node']['edge_media_to_comment']['count'] . ",");
                }
            }

            $found += count($media['edges']);


            if ($media['page_info']['has_next_page'] && $found < $limit) {
                $iteration_url = $url . '&max_id=' . $media['page_info']['end_cursor'];
                $tryNext = true;
            }
        }
    } else {
    }
}
getMediaByUsername(12);

if (isset($image)) {
    $postTot = count($image);
} else {
    $postTot = 0;
}
if ($postTot > 0 and $followers > 0) {
    $ER = round(((($like + $commenti) / $postTot) / $followers) * 100, 1);
} else {
    $ER = 0;
}


function url_get_contents ( $url ) {
    if ( ! function_exists( 'curl_init' ) ){
        die( 'The cURL library is not installed.' );
    }

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $output = curl_exec( $ch );

    if(curl_errno( $ch )) {
        die ('Curl error: ' . curl_error($ch));
    }
    curl_close( $ch );
    var_dump($url);
    return $output;
}