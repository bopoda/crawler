<?php

class Crawler_Parser extends Parser_AbstractHttp
{
    public function parse(array $urlInfo)
    {
        try {
            $response = $this->getResponse($urlInfo['url']);
        } catch (Exception $e) {
            // failed by curl exception
            $data['curlException'] = $e;
            return $data;
        }

        $forms = $this->findHtmlForms($response->getBody());

        return $forms;
    }

    protected function findHtmlForms($body)
    {
        $htmlParser = new Html_Parser('utf-8', $body);

        $formsDom = $htmlParser->getDomElements('form');

        $forms = array();
        foreach ($formsDom as $formDom) {
            $forms[] = $this->parseForm($formDom);
        }

        return $forms;
    }

    protected function parseForm(DOMDocument $formDom)
    {
        $formParser = new Html_Parser('utf-8');
        $formParser->loadDom($formDom);

        $formAsArray = $formParser->toArray();

        $form = array(
            'action' => @$formAsArray['action'],
            'method' => @$formAsArray['method'],
            'fields' => array(),
        );

        $fieldParser = new Html_Parser('utf-8');
        foreach ($formParser->getDomElements('input, textarea') as $inputDom) {
            $fieldParser->loadDom($inputDom);
            $form['fields'][] = $fieldParser->toArray();
        }
        return $form;
    }

    private function getResponse ($url)
    {
        $request = new Http_ClientRequest();
        $request
            ->setHeaders(array(
                'User-Agent' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.307.7 Safari/532.9',
                'Accept-Language' => 'en-us,en;q=0.5',
                'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                'Connection' => 'keep-alive',
                'Keep-Alive' => '3',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ))
            ->setMethod('GET')
            ->setUrl(new Http_Url($url));

        try {
            $response = $this->getClient()->send($request);
        } catch (RuntimeException $e) {
            $request->setUrl(new Http_Url($url));
            $response = $this->client->send($request);
        }
        return $response;
    }

}