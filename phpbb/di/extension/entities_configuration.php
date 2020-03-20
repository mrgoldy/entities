<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace phpbb\di\extension;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class entities_configuration implements ConfigurationInterface
{
	/**
	 * Generates the configuration tree builder.
	 *
	 * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
	 */
	public function getConfigTreeBuilder()
	{
		$tree = new TreeBuilder();
		$tree
			->root('entities', 'array')
				->useAttributeAsKey('name')
				->arrayPrototype()
					->children()
						->scalarNode('alias')->end()
						->scalarNode('table')->end()
						->scalarNode('entity')->end()
						->arrayNode('columns')
							->isRequired()
							->cannotBeEmpty()
							->ignoreExtraKeys()
							->useAttributeAsKey('name')
							->scalarPrototype()
						->end()
					->end()
				->end()
			->end()
		;

		return $tree;
	}
}
