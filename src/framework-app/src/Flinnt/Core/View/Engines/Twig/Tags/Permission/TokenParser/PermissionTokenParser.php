<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 30/11/16
 * Time: 12:03 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Permission\TokenParser;


use Flinnt\Core\View\Engines\Twig\Tags\Permission\Node\PermissionNode;
use Twig_Token;
use Sentinel;

/**
 * Class PermissionTokenParser
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Permission\TokenParser
 */
class PermissionTokenParser extends \Twig_TokenParser
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

		$permissions = array_merge(array(), $this->getInlineParams($token));
		$body = $this->parser->subparse(array($this, 'decidePermissionFork'));
		array_push($permissions, $body);

		$else = null;

		$end = false;

		while ( ! $end ) {
			switch ( $stream->next()->getValue() ) {
				case 'else' :
					$stream->expect(Twig_Token::BLOCK_END_TYPE);
					$else = $this->parser->subparse(array($this, 'decidePermissionEnd'));
					break;
				case 'endpermission' :
					$end = true;
					break;
				default :
					throw new \Twig_Error_Syntax(sprintf('Unexpected end of template. Twig was looking for the following tags "endrole" to close the "role" block started at line %d)', $lineno), -1);
			}
		}

		$stream->expect(Twig_Token::BLOCK_END_TYPE);

		return new PermissionNode(new \Twig_Node($permissions), $else, $lineno, $this->getTag());
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
		return 'permission';
	}

	/**
	 * Callback called at each tag name when subparsing, must return
	 * true when the expected tag is reached.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return bool
	 */
	public function decidePermissionFork( \Twig_Token $token )
	{
		return $token->test(array('else', 'endpermission'));
	}

	/**
	 * Callback called at each tag name when subparsing, must return
	 * true when the expected end tag is reached.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return bool
	 */
	public function decidePermissionEnd( \Twig_Token $token )
	{
		return $token->test(array('endpermission'));
	}

	/**
	 * @param $permissions
	 *
	 * @return mixed
	 */
	public function checkPermissions( $permissions )
	{
		$permissionsArray = explode(',', $permissions);

		$user = Sentinel::getUser();

		return $user->hasAccess($permissionsArray);
	}
}