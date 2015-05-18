<?php
/**
 * YouTube video looper
 */

function parseVideoURL($youtubeData)
{
    if (empty($youtubeData)) {
        throw new Exception('YouTube URL is empty!');
    }

    try {
        $vString = vStringSearch($youtubeData);
        return htmlentities($vString);

    } catch(Exception $e) {
        throw $e;

    }

}

/**
 * @param $youtubeData
 * @return string
 */
function vStringSearch($youtubeData)
{
    try {
        $videoIDquery = strpos($youtubeData, '?v=');

        if ($videoIDquery === false) {
            return false;
        }

        $videoID = substr($youtubeData, $videoIDquery + 3);

        $ampIndex = strpos($videoID, '&');
        if ($ampIndex !== false) {
            return substr($videoID, 0, $ampIndex);

        }

        return $videoID;

    } catch (Exception $e) {
        throw new Exception('Failed query string search for videoID, \'?v=\'.');

    }

}

function displayVideo($videoID)
{
$html =  <<<HERE
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>YouTube Looper</title>
</head>
<body>

<iframe width="640" height="480" src="https://www.youtube.com/embed/$videoID?loop=1&autoplay=1&playlist=$videoID" allowfullscreen>
</iframe>

</body>
</html>
HERE;

    echo $html;

}

/**
 * Client
 */

$youtubeData = [];
$youtubeData[] = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

if ($youtubeData) {
    $youtubeData[] = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
}

$youtubeData = filter_var($youtubeData[1]['v'], FILTER_SANITIZE_URL);

if (empty($youtubeData)) {
    throw new Exception('Empty');
}

if (filter_var($youtubeData, FILTER_VALIDATE_URL) !== false) {
    //parse the videoID
    try {
        $videoID = parseVideoURL($youtubeData);

    } catch (Exception $e) {
        throw $e;

    }

}

if ($videoID) {
    try {

        displayVideo($videoID);

    } catch (Exception $e) {
        throw $e;

    }

} else {
    throw new Exception('videoID must not be empty.');

}






