<?php

class FormTest extends PHPUnit_Framework_TestCase {
	public function testExportInput() {
		$form = new WFA\FormBuilder\Form();

		$form->addElement('text', 'webmail', 'E-mail');
		$form->addRule('webmail', 'required');
		$form->addRule('webmail', 'email');

		$export = $form->exportInput('webmail');

		$expectedOutput = array(
			'inputType' => 'text',
			'name' => 'webmail',
			'label' => 'E-mail',
			'rule' => array('required', 'email'),
			'defaultValue' => null,
			);

		$this->assertEquals($expectedOutput, $export);
	}
}
