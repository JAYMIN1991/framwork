<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/11/16
 * Time: 12:02 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Permission\Node;


use Twig_Compiler;

/**
 * Class PermissionNode
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Permission\Node
 */
class PermissionNode extends \Twig_Node
{

	/**
	 * PermissionNode constructor.
	 *
	 * @param \Twig_NodeInterface $permission
	 * @param \Twig_NodeInterface $else
	 * @param int                 $lineno
	 * @param null                $tag
	 */
	public function __construct( \Twig_NodeInterface $permission, \Twig_NodeInterface $else = null, $lineno, $tag = null )
	{
		$nodes = array('permission' => $permission);
		if ( null !== $else ) {
			$nodes['else'] = $else;
		}

		parent::__construct($nodes, array(), $lineno, $tag);
	}

	/**
	 * @param \Twig_Compiler $compiler
	 */
	public function compile( Twig_Compiler $compiler )
	{
		$compiler->addDebugInfo($this);
		for ( $i = 0, $count = count($this->getNode('permission')) ; $i < $count ; $i += 2 ) {
			$compiler->write("if ( app('Flinnt\Core\View\Engines\Twig\Tags\Permission\TokenParser\PermissionTokenParser')->checkPermissions(");
			if ( $this->getNode('permission')->getNode($i) instanceof \Twig_Node_Expression_Constant ) {
				$value = $this->getNode('permission')->getNode($i)->getAttribute('value');
				$compiler->string($value)->raw(")");
			}
			else {
				$name = $this->getNode('permission')->getNode($i)->getAttribute('name');
				$compiler->string($name)->raw(")");
			}

			$compiler->raw(") {\n");

			$compiler->indent()->subcompile($this->getNode('permission')->getNode($i + 1));

		}

		if ( $this->hasNode('else') ) {
			$compiler->outdent()->write("} else {\n")->indent()->subcompile($this->getNode('else'));
		}

		$compiler->outdent()->write("}\n");
	}


}