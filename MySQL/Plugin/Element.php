<?php
/**
 * A class that generates MySQL Plugin soure and documenation files
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Tools and Utilities
 * @package    CodeGen_MySQL_Plugin
 * @author     Hartmut Holzgraefe <hartmut@php.net>
 * @copyright  2005 Hartmut Holzgraefe
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/CodeGen_MySQL_Plugin
 */

/**
 * includes
 */
// {{{ includes

require_once "CodeGen/Element.php";
require_once "CodeGen/Tools/Indent.php";

// }}} 

/**
 * A class that generates Plugin extension soure and documenation files
 *
 * @category   Tools and Utilities
 * @package    CodeGen_MySQL_Plugin
 * @author     Hartmut Holzgraefe <hartmut@php.net>
 * @copyright  2005 Hartmut Holzgraefe
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/CodeGen_MySQL_Plugin
 */

abstract class CodeGen_MySQL_Plugin_Element
  extends CodeGen_Element
{
   /**
    * Plugin initialization code
    *
    * @var string
    */
    protected $initCode;

   /**
    * Plugin shutdown code
    *
    * @var string
    */
    protected $deinitCode;

    /**
     * Constructor
     */
    function __construct()
    {
 	  $this->setInitCode("return 0;");
	  $this->setDeinitCode("return 0;");
      $this->setSummary("no summary given");
    }

    /**
    * Name setter
    *
    * @param  string  function name
    * @return bool    success status
    */
    function setName($name) 
    {
        if (!self::isName($name)) {
            return PEAR::raiseError("'$name' is not a valid plugin name");
        }
	
        // keywords are not allowed as function names
        if (self::isKeyword($name)) {
            return PEAR::raiseError("'$name' is a reserved word which is not valid for plugin names");
        }
	
        return parent::setName($name);
    }

    /**
    * Init Code setter
    *
    * @param  string  code snippet
    * @return bool    success status
    */
    function setInitCode($code) 
    {
        $this->initCode = $this->indentCode($code);
        return true;
    }

    /**
    * Deinit Code setter
    *
    * @param  string  code snippet
    * @return bool    success status
    */
    function setDeinitCode($code) 
    {
        $this->deinitCode = $this->indentCode($code);
        return true;
    }

    /**
     * Plugin type specifier is needed for plugin registration
     *
     * @param  void
     * @return string
     */
    abstract function getPluginType();

    /**
     * Plugin registration
     *
     * @param  void
     * @return string
     */
    function getPluginRegistration(CodeGen_MySQL_Plugin_Extension $ext)
    {
        $name    = $this->name;
        $type    = $this->getPluginType();
        $desc    = $this->summary;
        $author  = "TODO";
        $version = 1;

        return "
{
  $type,
  &{$name}_descriptor, 
  \"$name\",
  \"$author\",
  \"$desc\",
  {$name}_plugin_init,
  {$name}_plugin_deinit,
  $version,
  NULL
}
";
    }


    function getPluginCode()
    {  
        return "
static int {$this->name}_plugin_init(void)
{
{$this->initCode}
}

static int {$this->name}_plugin_deinit(void)
{
{$this->deinitCode}
}
";
    }

    function indentCode($code, $level=2)
    {
        $code = CodeGen_Tools_Indent::linetrim($code);
        $code = CodeGen_Tools_Indent::untabify($code);
        $code = CodeGen_Tools_Indent::indent($level, $code);

        return $code;
    }
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * indent-tabs-mode:nil
 * End:
 */
?>