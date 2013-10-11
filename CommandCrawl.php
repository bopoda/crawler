<?php

class Command_CommandCrawl implements Core_Cli_CommandInterface
{
    private $dryRun;

    public function run($url = 'jeka/ask/add', $dryRun = false)
    {
        $this->dryRun = $dryRun;

		$log = new Logging_ConsoleLog();

        $crawlerParser = new Crawler_Parser();
        $crawlerParser->setLog($log);
        $parsedForms = $crawlerParser->parse(array('url' => $url));

        if (!$this->dryRun && $parsedForms) {
            foreach ($parsedForms as $parsedForm) {
                $dataToInsert = Crawler_Generator::createFormData($parsedForm);

				if (empty($dataToInsert['dataToInsert'])) {
					$log->write("Cannot create dataToInsert for form to url={$dataToInsert['action']}");
					continue;
				}

				$formSender = new Crawler_Sender();
				$formSender->setLog($log);
				$formSender->parse($dataToInsert);
            }
        }
    }
}