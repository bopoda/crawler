<?php

class Crawler_Parser extends Parser_AbstractHttp
{
    private $url;

    public function parse(array $urlInfo)
    {
        $this->url = $urlInfo['url'];
        try {
            $response = $this->getResponse($urlInfo['url']);
        } catch (Exception $e) {
            // failed by curl exception
            $data['curlException'] = $e;
            return $data;
        }

        $forms = $this->findValidHtmlForms($response->getBody());

        return $forms;
    }

    protected function findValidHtmlForms($body)
    {
        $htmlParser = new Html_Parser('utf-8', $body);

        $formsDom = $htmlParser->getDomElements('form');

        $forms = array();
        foreach ($formsDom as $formDom) {
            $form = $this->parseForm($formDom);
            if ($form) {
                $forms[] = $form;
            }
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

        foreach ($formParser->getDomElements('input') as $inputDom) {
            $fieldParser->loadDom($inputDom);
            $form['fields'][] = $fieldParser->toArray();
        }
        foreach ($formParser->getDomElements('textarea') as $textareaDom) {
            $fieldParser->loadDom($textareaDom);
            $form['fields'][] = array_merge(array('type' => 'textarea'),
                $fieldParser->toArray()
            );
        }

        return $this->filterForm($form);
    }

    private function filterForm(array $form) {
        if (strtolower($form['method']) !== 'post') {
            $this->getLog()->write('Found form without post method with ' . count($form['fields']) . ' fields. Skip it.');
            return false;
        }
        if (!$form['action']) {
            $form['action'] = $this->url;
        }
        if (!$form['fields']) {
            $this->getLog()->write('Found form without fields. Skip it.');
            return false;
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