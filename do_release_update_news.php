<?php

/* vim : $Source$ */
// +------------------------------------------------------------------------+
// $Author$
// $Header$
// $lastlog$
// +------------------------------------------------------------------------+
//
// $Date$

function convertUTF8($html)
{
    $html_encoding = mb_detect_encoding($html, array('UTF-8', 'ASCII', 'GB2312', 'GBK'), true);
    if ($html_encoding != 'UTF-8') {
        return mb_convert_encoding($html, 'UTF-8', $html_encoding);
    }

    return $html;
}

function getNewPHP()
{

    //php升级
    $content = file_get_contents("http://php.net/downloads.php#v5");

    if ($content) {
        $content = convertUTF8($content);
        preg_match('/<h1 id="(.*)">(.*)Current stable/iU', $content, $matches);
        if (isset($matches[1])) {
            //echo "PHP current stable:{$matches[1]}\n";
            return $matches[1];
        }

    }

    return false;
}

function getNewMySQL()
{
    $content = file_get_contents("http://www.mysql.com/downloads/");
    if ($content) {
        $content = convertUTF8($content);

        preg_match_all('/<a href="(.*)">(.*)<\/a><p class="subText">(.*)<\/p>/iU', $content, $matches);

        $arr = array();
        if (isset($matches[2]) && isset($matches[3])) {
            foreach ($matches[2] as $idx => $row) {
                $arr[] = "{$row} {$matches[3][$idx]}";
            }
        }

        return $arr;

    }
    return false;
}

function getNewNginx()
{
    $content = file_get_contents("http://nginx.org");

    if ($content) {
        $content = convertUTF8($content);

        preg_match_all('/nginx-(.*)<\/a>/i', $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
    }

    return false;
}

function getNewjQuery()
{
    $content = file_get_contents("http://jquery.com");

    if ($content) {
        $content = convertUTF8($content);

        preg_match('/<p class="jq-version"><strong>Current Release:<\/strong>([\s\S]*)<\/p>/iU', $content, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

    }

    return false;
}


echo "--------------------------------------------------------------\n";
echo "PHP:\n\t";
echo getNewPHP();
echo "\nNow:\n\t" . `php -v`;
echo "--------------------------------------------------------------\n";
echo "\nMySQL:\n\t";

$arr = getNewMySQL();
if ($arr) {
    echo implode("\n\t", $arr);
}
echo "\nNow:\n\t" . `mysql -V`;
echo "--------------------------------------------------------------\n";
echo "\nNginx:\n\t";

$arr = getNewNginx();
if ($arr) {
    echo implode("\n\t", array_slice($arr, 0, 4));
}
echo "\nNow:\n\t";
echo `nginx -v`;
echo "\n";
echo "--------------------------------------------------------------\n";

echo "jQuery:\n\t";
echo trim(getNewjQuery());
echo "\nNow:\n\t";
echo "v1.8.1\n";
echo "--------------------------------------------------------------\n";

