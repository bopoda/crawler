<?php

class Crawler_Sender extends Parser_AbstractHttp
{
    public function parse(array $formDataToInsert)
    {
        try {
            $response = $this->getResponse($formDataToInsert);
//            return $response;
            var_dump($response->getBody());
        } catch (Exception $e) {
            // failed by curl exception
            $data['curlException'] = $e;
            return $data;
        }
    }

    private function getResponse(array $formDataToInsert)
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
            ->setMethod(strtoupper($formDataToInsert['method']))
            ->setUrl(new Http_Url($formDataToInsert['action']));

        $names = '';
        foreach ($formDataToInsert['dataToInsert'] as $data) {
            $request->setPostVar($data['key'], $data['value']);
            $names .= $data['key'] . ', ';
        }
        $names = rtrim(trim($names), ',');

        $this->getLog()->write("Try send form({$names}) to url={$formDataToInsert['action']}...");

        try {
            $response = $this->getClient()->send($request);
        } catch (RuntimeException $e) {
            $request->setUrl(new Http_Url($formDataToInsert['action']));
            $response = $this->client->send($request);
        }
        return $response;
    }

}