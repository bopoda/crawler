<?php

class Command_CommandCrawl implements Core_Cli_CommandInterface
{
    private $dryRun;

    public function run($url = 'jeka/ask/add', $dryRun = false)
    {
        $this->dryRun = $dryRun;

        $crawlerParser = new Crawler_Parser();
        $crawlerParser->setLog(new Logging_ConsoleLog());
        $parsedForms = $crawlerParser->parse(array('url' => $url));

        if (!$this->dryRun && $parsedForms) {
            foreach ($parsedForms as $parsedForm) {
                $dataToInsert = Crawler_Generator::createFormData($parsedForm);

                $formSender = new Crawler_Sender();
                $formSender->setLog(new Logging_ConsoleLog());
                $formSender->parse($dataToInsert);
            }
        }
    }
}