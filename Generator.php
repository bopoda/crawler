<?php

class Crawler_Generator
{
    public static function createFormData(array $formStructure)
    {
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
			if (
				!isset($field['name'])
					|| !$field['name']
					|| !isset($field['type'])
					|| !$field['type']
			) {
				continue;
			}

			$type = $field['type'];
			$name = $field['name'];
			$value = @$field['value'];

			switch ($type) {
				case 'hidden':
					$dataToInsert[] = array(
						'type' => $type,
						'name' => $name,
						'value' => $value,
					);
					break;

				case 'text':
					$dataToInsert[] = array(
						'type' => $type,
						'name' => $name,
						'value' => 'hehehe',
					);
					break;

				case 'textarea':
					$dataToInsert[] = array(
						'type' => $type,
						'name' => $name,
						'value' => '<p>Очень позновательно.</p>',
					);
					break;
				case 'checkbox':
					$dataToInsert[] = array(
						'type' => $type,
						'name' => $name,
						'value' => $value ? : 'on',	// TODO: check it (if value not declared - on ??)
					);
					break;
				case 'submit':
					$dataToInsert[] = array(
						'type' => $type,
						'name' => $name,
						'value' => $value,
					);
					break;

				default:
					// do nothing
					break;
			}
        }

        return $dataToInsert;
    }

	protected static function generateField()
	{

	}

}