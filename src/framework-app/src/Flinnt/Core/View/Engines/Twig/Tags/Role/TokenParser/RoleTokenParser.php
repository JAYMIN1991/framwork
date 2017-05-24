<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 29/11/16
 * Time: 3:09 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Role\TokenParser;


use Flinnt\Core\View\Engines\Twig\Tags\Role\Node\RoleNode;
use Twig_Token;
use Sentinel;

/**
 * Class RoleTokenParser
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Role\TokenParser
 */
class RoleTokenParser extends \Twig_TokenParser
{

	/**
	 * Parses a token and returns a node.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return \Twig_NodeInterface
	 * @throws \Twig_Error_Syntax
	 */
	public function parse( Twig_Token $token )
	{
		$lineno = $token->getLine();

		$stream = $this->parser->getStream();

		$roles = array_merge(array(), $this->getInlineParams($token));
		$body = $this->parser->subparse(array($this, 'decideRoleFork'));
		array_push($roles, $body);

		$else = null;

		$end = false;
		while ( ! $end ) {
			switch ( $stream->next()->getValue() ) {
				case 'else' :
					$stream->expect(Twig_Token::BLOCK_END_TYPE);
					$else = $this->parser->subparse(array($this, 'decideRoleEnd'));
					break;
				case 'endrole' :
					$end = true;
					break;
				default :
					throw new \Twig_Error_Syntax(sprintf('Unexpected end of template. Twig was looking for the following tags "endrole" to close the "role" block started at line %d)', $lineno), -1);
			}
		}

		$stream->expect(Twig_Token::BLOCK_END_TYPE);

		return new RoleNode(new \Twig_Node($roles), $else, $lineno, $this->getTag());
	}

	/**
	 * Recovers all tag parameters until we find a BLOCK_END_TYPE ( %} )
	 *
	 * @param \Twig_Token $token
	 *
	 * @return array
	 */
	protected function getInlineParams( \Twig_Token $token )
	{
		$stream = $this->parser->getStream();
		$params = array();
		while ( ! $stream->test(\Twig_Token::BLOCK_END_TYPE) ) {
			$params[] = $this->parser->getExpressionParser()->parseExpression();
		}
		$stream->expect(\Twig_Token::BLOCK_END_TYPE);

		return $params;
	}

	/**
	 * Gets the tag name associated with this token parser.
	 *
	 * @return string The tag name
	 */
	public function getTag()
	{
		return 'role';
	}

	/**
	 * Callback called at each tag name when subparsing, must return
	 * true when the expected tag is reached.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return bool
	 */
	public function decideRoleFork( \Twig_Token $token )
	{
		return $token->test(array('else', 'endrole'));
	}

	/**
	 * Callback called at each tag name when subparsing, must return
	 * true when the expected end tag is reached.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return bool
	 */
	public function decideRoleEnd( \Twig_Token $token )
	{
		return $token->test(array('endrole'));
	}

	/**
	 * @param $roles
	 *
	 * @return bool
	 */
	public function checkRoles( $roles )
	{
		$rolesArray = explode(',', $roles);

		$hasRole = false;

		try {
			foreach ( $rolesArray as $role ) {
				$hasRole = Sentinel::inRole($role);
				if ( $hasRole == true ) {
					break;
				}
			}
		} catch ( \Exception $e ) {
			var_dump($e->getMessage());
			die;
		}

		return $hasRole;
	}
}