#!/usr/bin/php
<?php
$inputFileName = "urls.csv";
$domainUrlToRedirectToo = "https://www.mynewdomain.nl";
$stringStatusCode = "302";

$csvLocation = "CSV";
$inputFile = $csvLocation . "/" . $inputFileName;
$outputFile = ".htaccess_" . $inputFileName;

//execute script
run($inputFile, $outputFile, $domainUrlToRedirectToo, $stringStatusCode);

function run($inputFile, $outputFile, $domainUrlToRedirectToo, $stringStatusCode)
{
    if (!file_exists($inputFile)) {
        exit('Can not read input file [' . $inputFile . ']');
    }
    if (file_exists($outputFile)) {
        unlink($outputFile);
    }
    if (!file_exists($outputFile) && !touch($outputFile)) {
        exit('Can not write output file [' . $outputFile . ']');
    }

    //build array of links
    $csv = array_map('str_getcsv', file($inputFile));

    //loop through array
    foreach ($csv as $key => $value) {

        $arr = explode("/", $value[0], 2);
        $website = $arr[0];
        $websiteUrl = $arr[1];


        file_put_contents($outputFile,
            "RewriteCond %{HTTP_HOST} ^(www\.)?" .$website."$" . PHP_EOL ."RewriteRule ^" . $websiteUrl . "$ " . $domainUrlToRedirectToo . " [L,R=" . $stringStatusCode . "]" .PHP_EOL.PHP_EOL,
            FILE_APPEND);
    }

    print('Generated redirects file') . PHP_EOL;

}