<?php

class Crawler_Generator
{
    public static function createFormData(array $formStructure)
    {
        $postData = array();
        foreach ($formStructure['fields'] as $field) {
            if (!@$field['name']) {
                continue;
            }

            if (@strtolower($field['type']) == 'text') {
                $postData[] = array(
                    'key' => $field['name'],
                    'value' => 'anyText',
                );
            }
        }

        return array(
            'action' => $formStructure['action'],
            'method' => $formStructure['method'],
            'dataToInsert' => self::generateDataToInsert($formStructure['fields']),
        );
    }

    public static function generateDataToInsert($fields)
    {
        $dataToInsert = array();
        foreach ($fields as $field) {
            $type = strtolower(@$field['type']);
            if (!@$field['name'] || !$type) {
                continue;
            }

            if (in_array($type, array('text', 'hidden'))) {
                $dataToInsert[] = array(
                    'key' => $field['name'],
                    'value' => 'anyText',
                );
            }
            elseif ($type == 'textarea') {
                $dataToInsert[] = array(
                    'key' => $field['name'],
                    'value' => '<p><b>privet!</b>. chto za bred.</p>',
                );
            }
        }

        return $dataToInsert;
    }

}