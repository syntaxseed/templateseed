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
    	$this->assertEquals($output, "<h1>Well hello there, Keegan!</h1>\n");
        unset($tpl);
    }

    /**
    * Check if retrieving a template with a global parameter works.
    */
    public function testTemplateGlobalRetrieval(){
        $tpl = new syntaxseed\templateseed\TemplateSeed(__DIR__."/views/");
        $tpl->setTemplate('greetingglobal');
        $tpl->setGlobalParams(['first'=>'Keegan']);
        $tpl->params->last = "Smith";
        $output = $tpl->retrieve();
    	$this->assertEquals($output, "<h1>Well hello there, Keegan Smith!</h1>\n");
        unset($tpl);
    }

    /**
    * Check one line render method.
    */
    public function testTemplateRender(){
        $tpl = new syntaxseed\templateseed\TemplateSeed(__DIR__."/views/");
        $output = $tpl->render('greeting', ['name'=>'Keegan']);
    	$this->assertEquals($output, "<h1>Well hello there, Keegan!</h1>\n");
        unset($tpl);
    }
}
