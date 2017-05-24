<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 18/11/16
 * Time: 6:09 PM
 */

namespace Flinnt\Core\View\Engines\Twig\Tags\Cache\TokenParser;


use Flinnt\Core\Cache\CacheManager;
use Flinnt\Core\View\Engines\Twig\Tags\Cache\Node\CacheNode;


use Twig_Token;

/**
 * Class CacheTokenParser
 *
 * @package Flinnt\Core\View\Engines\Twig\Tags\Cache\TokenParser
 */
class CacheTokenParser extends \Twig_TokenParser
{

	protected $params = null;

	protected $cache;

	/**
	 * CacheTokenParser constructor.
	 *
	 * @internal param $cache
	 */
	public function __construct()
	{
		$this->cache = new CacheManager();
	}


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

		// recovers all inline parameters close to your tag name
		$this->params = array_merge(array(), $this->getInlineParams($token));

		$continue = true;

		while ( $continue ) {

			// create subtree until the decideMyTagFork() callback returns true
			$body = $this->parser->subparse(array($this, 'decideMyTagFork'));

			// I like to put a switch here, in case you need to add middle tags, such
			// as: {% mytag %}, {% nextmytag %}, {% endmytag %}.
			$tag = $stream->next()->getValue();

			switch ( $tag ) {
				case 'endcache':
					$continue = false;
					break;
				default:
					throw new \Twig_Error_Syntax(sprintf('Unexpected end of template. Twig was looking for the following tags "endcache" to close the "cache" block started at line %d)', $lineno), -1);
			}

			// you want $body at the beginning of your arguments
			array_unshift($this->params, $body);

			// if your endmytag can also contains params, you can uncomment this line:
			// $params = array_merge($params, $this->getInlineParams($token));
			// and comment this one:
			$stream->expect(\Twig_Token::BLOCK_END_TYPE);
		}

		return new CacheNode(new \Twig_Node($this->params), $lineno, $this->getTag());
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
		return 'cache';
	}

	/**
	 * Callback called at each tag name when subparsing, must return
	 * true when the expected end tag is reached.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return bool
	 */
	public function decideMyTagFork( \Twig_Token $token )
	{
		return $token->test(array('endcache'));
	}

	/**
	 * @param $cache
	 *
	 * @return bool|String
	 */
	public function cacheStore( $cache )
	{
		$count = count($cache);
		if ( $count == 4 ) {
			// All the values i.e, key, time and tags are provided to cache tag.
			return $this->cache->view($cache[3], $cache[2], $cache[1], explode(',', $cache[0]));
		}
		else {
			if ( $count == 3 ) {
				// If only key and time are provided to cache tag
				return $this->cache->view($cache[2], $cache[1], $cache[0]);
			}
			else {
				// If only key is provided to cache tag
				return $this->cache->view($cache[1], $cache[0]);
			}
		}
	}

	/**
	 * @param $cache
	 *
	 * @return bool|String
	 */
	public function cacheInit( $cache )
	{

		if ( count($cache) == 3 ) {
			return $this->cache->getView($cache[2], explode(',', $cache[0]));
		}

		return $this->cache->getView($cache[1]);

	}
}