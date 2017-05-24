<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 18/11/16
 * Time: 6:21 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Cache\Node;


use Twig_Compiler;

/**
 * Class CacheNode
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Cache\Node
 */
class CacheNode extends \Twig_Node
{

	/**
	 * CacheNode constructor.
	 *
	 * @param array|\Twig_Node $params
	 * @param int              $lineno
	 * @param null             $tag
	 */
	public function __construct( $params, $lineno = 0, $tag = null )
	{
		parent::__construct(array('params' => $params), array(), $lineno, $tag);
	}

	/**
	 * @param \Twig_Compiler $compiler
	 */
	public function compile( Twig_Compiler $compiler )
	{
		$count = count($this->getNode('params'));

		$compiler->addDebugInfo($this);

		for ( $i = $count - 1 ; ($i >= 0) ; $i-- ) {
			// argument is not an expression (such as, a \Twig_Node_Textbody)
			// we should trick with output buffering to get a valid argument to pass
			// to the functionToCall() function.

			if ( ! ($this->getNode('params')->getNode($i) instanceof \Twig_Node_Expression) ) {
				$compiler->write('$cache = app("Flinnt\Core\View\Engines\Twig\Tags\Cache\TokenParser\CacheTokenParser")->cacheInit(')->raw('$_cache);')->raw(PHP_EOL);

				$compiler->write("if (\$cache == false) {\n")->indent()->write("ob_start(); \n")->indent()->subcompile($this->getNode('params')->getNode($i))->outdent()->write("\n")->write("\$_cache[] = ob_get_clean();\n")->write('$cache = app("Flinnt\Core\View\Engines\Twig\Tags\Cache\TokenParser\CacheTokenParser")->cacheStore(')->raw("\$_cache); \n")->outdent()->write("}\n")->write('echo $cache;')->raw(PHP_EOL);
			}
			else {

				if ( $this->getNode('params')->getNode($i) instanceof \Twig_Node_Expression_Name ) {
					$name = $this->getNode('params')->getNode($i)->getAttribute('name');

					$compiler->write('$_cache[] = ')->string($name)->raw(';')->raw(PHP_EOL);
				}
				else {
					if ( $this->getNode('params')->getNode($i) instanceof \Twig_Node_Expression_Constant ) {
						$value = $this->getNode('params')->getNode($i)->getAttribute('value');

						$compiler->write('$_cache[] = ')->string($value)->raw(';')->raw(PHP_EOL);
					}
					else {
						$compiler->subcompile($this->getNode('params')->getNode($i))->raw(';')->raw(PHP_EOL);
					}
				}
			}
		}
	}


}