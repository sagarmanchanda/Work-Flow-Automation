<?php

class FiniteAutomataTest extends PHPUnit_Framework_TestCase {
	public function testexportState() {
		$DFA = new WFA\RequestHandling\FiniteAutomata();

		$DFA->addState('test01', '007', 'generation');
		$DFA->addState('test02','008','translation');
		$DFA->addState('test03', '009','final');

		$export = $DFA->exportState('test02');

		$expectedOutput = array(
			'stateName' => 'test02',
			'stateID' => '008',
			'stateType' => 'translation'
			);

		$this->assertEquals($expectedOutput, $export);
	}

	public function testexportTransition() {
		$DFA = new WFA\RequestHandling\FiniteAutomata();

		$DFA->addState('test01', '007', 'generation');
		$DFA->addState('test02','008','translation');
		$DFA->addTransition('test_transit','test01','test02','success');

		$export = $DFA->exportTransition('test_transit', 'test01');
		$expectedOutput = array (
			'transitionName' => 'test_transit',
			'presentState' => 'test01',
			'nextState' => 'test02',
			'response' => 'success'
			);

		$this->assertEquals($expectedOutput, $export);
	}

}
