<?php

class Command_CommandCrawl implements Core_Cli_CommandInterface
{
    public function run($url = 'jeka/ask/add')
    {
        $crawlerParser = new Crawler_Parser();
        $crawlerParser->parse(array('url' => $url));
    }
}