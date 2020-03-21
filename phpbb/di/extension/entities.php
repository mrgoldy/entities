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

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
* Container entities extension
*/
class entities extends Extension
{
	/**
	 * Config path
	 * @var string
	 */
	protected $config_path;

	/**
	 * Constructor
	 *
	 * @param string $config_path Config path
	 */
	public function __construct($config_path)
	{
		$this->config_path = $config_path;
	}

	/**
	 * Loads a specific configuration.
	 *
	 * @param array            $configs   An array of entity values
	 * @param ContainerBuilder $container A ContainerBuilder instance
	 *
	 * @throws \InvalidArgumentException When provided tag is not defined in this extension
	 * @throws \Exception
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$config = $this->getConfiguration($configs, $container);
		$config = $this->processConfiguration($config, $configs);

		$container->setParameter('entities', $config);
	}

	/**
	 * Get configuration.
	 *
	 * @param array            $config
	 * @param ContainerBuilder $container
	 * @throws \ReflectionException
	 * @return entities_configuration
	 */
	public function getConfiguration(array $config, ContainerBuilder $container)
	{
		$rc = new \ReflectionClass('\phpbb\di\extension\entities_configuration');
		$container->addResource(new FileResource($rc->getFileName()));

		return new entities_configuration();
	}

	/**
	 * Returns the recommended alias to use in XML.
	 *
	 * This alias is also the mandatory prefix to use when using YAML.
	 *
	 * @return string The alias
	 */
	public function getAlias()
	{
		return 'entities';
	}
}
