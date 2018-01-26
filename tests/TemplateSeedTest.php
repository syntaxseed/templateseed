<?php 
/**
*  Basic testing for TemplateSeed.
*/
class TemplateSeedTest extends PHPUnit_Framework_TestCase{
	
    /**
    * Basic test of class instantiation.
    */
    public function testIsThereAnySyntaxErrors(){
    	$tpl = new syntaxseed\templateseed\TemplateSeed(__DIR__."/views/");
    	$this->assertTrue(is_object($tpl));
    	unset($tpl);
    }
  
    /**
    * Check if retrieving a template works.
    */
    public function testTemplateRetrieval(){
        $tpl = new syntaxseed\templateseed\TemplateSeed(__DIR__."/views/");
        $tpl->setTemplate('greeting');
        $tpl->params->name = "Keegan";
        $output = $tpl->retrieve();
    	$this->assertTrue($output == '<h1>Well hello there, Keegan!</h1>');
        unset($tpl);
    }
}