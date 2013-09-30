<?php

class Crawler_ParserTest extends PHPUnit_Framework_TestCase
{
	private $parser;

	private $formKeys = array(
		'method',
		'action',
		'fields'
	);

	public function testParse()
	{
		$url = 'http://jeka.by/ask/add';
		$parsedForms = $this->getParser()->parse(array('url' => $url));

		$this->assertGreaterThanOrEqual(1, count($parsedForms),
			"At least one form must be at {$url}"
		);

		foreach ($parsedForms as $form) {
			foreach ($this->formKeys as $key) {
				$this->assertArrayHasKey($key, $form,
					"{key $key must be in parsed form}"
				);
			}
		}
	}

	public function clientCallback(Http_ClientRequest $request)
	{
		$domain = Net::detectDomain($request->getUrl()->toString());

		$response = new Http_CurlResponse(new Http_ClientRequest());
		$response->setBody(file_get_contents(__DIR__ . '/data/' . $domain . '/page'));
		$response->setRequest($request);

		return $response;
	}

	/**
	 * @return Crawler_Parser
	 */
	private function getParser()
	{
		if (!$this->parser) {
			$this->parser = new Crawler_Parser();
		}

		return $this->parser;
	}

	protected function setUp()
	{
		parent::setUp();

		$client = $this->getMock('Http_CurlHttpClient');
		$client
			->expects($this->any())
			->method('send')
			->will($this->returnCallback(array($this, 'clientCallback')));

		$parser = $this->getParser();
		$parser->setClient($client);
		$this->parser = $parser;
	}

}