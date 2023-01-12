<?php
require __DIR__ .'/vendor/autoload.php';

use GuzzleHttp\Client;
use HeadlessChromium\BrowserFactory;

$client = new Client();
$response = $client->get('http://192.168.8.139:9222/json/version');

try {
    $body = \GuzzleHttp\json_decode($response->getBody()->getContents());
    $ws = $body->webSocketDebuggerUrl;

    $browserFactory = new BrowserFactory();
    $browserFactory->setOptions([
        'headless' => true,
        'noSandbox' => true,
        'proxyServer' => '192.168.8.139:1081',
    ]);
    $browser = $browserFactory::connectToBrowser($ws);

    foreach ($browser->getPages() as $page)
    {
        $page->close();
    }
    $page = $browser->createPage();
    $page->navigate('https://bscscan.com/token/0xc54b96b04aa8828b63cf250408e1084e9f6ac6c8?a=101129')->waitForNavigation();

    $pageTitle = $page->evaluate("$('#tokentxnsiframe').attr('src')")->getReturnValue();

    var_dump($pageTitle);
    $page->close();
    $browser->close();
} catch (Exception $exception) {
    var_dump($exception->getMessage());
}
