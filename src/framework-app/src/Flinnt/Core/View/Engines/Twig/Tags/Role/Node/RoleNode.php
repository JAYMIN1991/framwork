<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 29/11/16
 * Time: 3:04 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Role\Node;


use Twig_Compiler;

/**
 * Class RoleNode
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Role\Node
 */
class RoleNode extends \Twig_Node
{

	/**
	 * RoleNode constructor.
	 *
	 * @param \Twig_NodeInterface $roles
	 * @param \Twig_NodeInterface $else
	 * @param int                 $lineno
	 * @param null                $tag
	 */
	public function __construct( \Twig_NodeInterface $roles, \Twig_NodeInterface $else = null, $lineno, $tag = null )
	{
		$nodes = array('roles' => $roles);
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
		for ( $i = 0, $count = count($this->getNode('roles')) ; $i < $count ; $i += 2 ) {
			$compiler->write("if ( app('Flinnt\Core\View\Engines\Twig\Tags\Role\TokenParser\RoleTokenParser')->checkRoles(");
			if ( $this->getNode('roles')->getNode($i) instanceof \Twig_Node_Expression_Constant ) {
				$value = $this->getNode('roles')->getNode($i)->getAttribute('value');
				$compiler->string($value)->raw(")");
			}
			else {
				$name = $this->getNode('roles')->getNode($i)->getAttribute('name');
				$compiler->string($name)->raw(")");
			}

			$compiler->raw(") {\n");

			$compiler->indent()->subcompile($this->getNode('roles')->getNode($i + 1));

		}

		if ( $this->hasNode('else') ) {
			$compiler->outdent()->write("} else {\n")->indent()->subcompile($this->getNode('else'));
		}

		$compiler->outdent()->write("}\n");
	}


}