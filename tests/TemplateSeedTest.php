<?php 

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class TemplateSeedTest extends PHPUnit_Framework_TestCase{
	
    /**
    * Just check if the class has no syntax errors
    */
    public function testIsThereAnySyntaxErrors(){
    	$var = new syntaxseed\templateseed\YourClass;
    	$this->assertTrue(is_object($var));
    	unset($var);
    }
  
    /**
    * Check if a method works.
    *
    * /

    public function testMethod1(){
    $var = new Buonzz\Template\YourClass;
    	$this->assertTrue($var->method1("hey") == 'Hello World');
    	unset($var);
    }
    */
}