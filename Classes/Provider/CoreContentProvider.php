<?php
namespace FluidTYPO3\FluidcontentCore\Provider;

/*
 * This file is part of the FluidTYPO3/FluidcontentCore project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\FluidcontentCore\Service\ConfigurationService;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Hooks\HookHandler;
use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Utility\ExtensionNamingUtility;
use FluidTYPO3\Flux\Utility\PathUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * ConfigurationProvider for records in tt_content
 *
 * This Configuration Provider has the lowest possible priority
 * and is only used to execute a set of hook-style methods for
 * processing records. This processing ensures that relationships
 * between content elements get stored correctly.
 */
class CoreContentProvider extends AbstractProvider {

	const MODE_RECORD = 'record';
	const MODE_PRESELECT = 'preselect';
//	const CTYPE_MENU = 'menu';
//	const CTYPE_TABLE = 'table';
//	const CTYPE_FIELDNAME = 'CType';
//	const MENUTYPE_FIELDNAME = 'menu_type';
//	const MENU_SELECTEDPAGES = 0;
//	const MENU_SUBPAGESOFSELECTEDPAGES = 1;
//	const MENU_SUBPAGESOFSELECTEDPAGESWITHABSTRACT = 4;
//	const MENU_SUBPAGESOFSELECTEDPAGESWITHSECTIONS = 7;
//	const MENU_SITEMAP = 2;
//	const MENU_SITEMAPSOFSELECTEDPAGES = 8;
//	const MENU_SECTIONINDEX = 3;
//	const MENU_RECENTLYUPDATED = 5;
//	const MENU_RELATEDPAGES = 6;
//	const MENU_CATEGORIZEDPAGES = 'categorized_pages';
//	const MENU_CATEGORIZEDCONTENT = 'categorized_content';
//	const THEAD_NONE = 'none';
//	const THEAD_TOP = 'top';
//	const THEAD_LEFT = 'left';
//
	/**
	 * @var string
	 */
	protected $extensionKey = 'FluidTYPO3.FluidcontentCore';

	/**
	 * @var integer
	 */
	protected $priority = 0;

	/**
	 * @var string
	 */
	protected $tableName = 'tt_content';

	/**
	 * @var string
	 */
	protected $fieldName = 'content_options';
//
//	/**
//	 * Filled with an integer-or-string -> Fluid section name
//	 * map which maps machine names of menu types to human
//	 * readable values that are sensible as Fluid section names.
//	 * When type is selected in menu element, corresponding
//	 * section gets rendered.
//	 *
//	 * @var array
//	 */
//	protected $menuTypeToSectionNameMap = array(
//		self::MENU_SELECTEDPAGES => 'SelectedPages',
//		self::MENU_SUBPAGESOFSELECTEDPAGES => 'SubPagesOfSelectedPages',
//		self::MENU_SUBPAGESOFSELECTEDPAGESWITHABSTRACT => 'SubPagesOfSelectedPagesWithAbstract',
//		self::MENU_SUBPAGESOFSELECTEDPAGESWITHSECTIONS => 'SubPagesOfSelectedPagesWithSections',
//		self::MENU_SITEMAP => 'SiteMap',
//		self::MENU_SITEMAPSOFSELECTEDPAGES => 'SiteMapsOfSelectedPages',
//		self::MENU_SECTIONINDEX => 'SectionIndex',
//		self::MENU_RECENTLYUPDATED => 'RecentlyUpdated',
//		self::MENU_RELATEDPAGES => 'RelatedPages',
//		self::MENU_CATEGORIZEDPAGES => 'CategorizedPages',
//		self::MENU_CATEGORIZEDCONTENT => 'CategorizedContent'
//	);
//
	/**
	 * @var ConfigurationService
	 */
	protected $contentConfigurationService;

	/**
	 * @param ConfigurationService $contentConfigurationService
	 * @return void
	 */
	public function injectContentConfigurationService(ConfigurationService $contentConfigurationService): void
	{
		$this->contentConfigurationService = $contentConfigurationService;
	}
//
//	/**
//	 * @return void
//	 */
//	public function initializeObject() {
//		$typoScript = $this->contentConfigurationService->getAllTypoScript();
//		$settings = $typoScript['plugin']['tx_fluidcontentcore']['settings'];
//		$this->templateVariables['settings'] = $settings;
////		$this->templatePathAndFilename = PathUtility::translatePath($settings['defaults']['template']);
//	}

	/**
	 * Note: This Provider will -always- trigger on any tt_content record
	 * but has the lowest possible (0) priority, ensuring that any
	 * Provider which wants to take over, can do so.
	 *
	 * @param array $row
	 * @param string $table
	 * @param string $field
	 * @param string $extensionKey
	 * @return boolean
	 */
	public function trigger(array $row, $table, $field, $extensionKey = NULL): bool
	{
		$registeredTypes = (array) $GLOBALS['TYPO3_CONF_VARS']['FluidTYPO3.FluidcontentCore']['types'];

		return (
			$table === $this->tableName
			&& ($field === $this->fieldName || NULL === $field)
//			&& TRUE === \in_array($row['CType'], $registeredTypes, true)
		);
	}

//	/**
//	 * @param array $row
//	 * @return Form
//	 */
//	public function getForm(array $row) {
//		if (self::CTYPE_MENU === $row[self::CTYPE_FIELDNAME]) {
//			// addtional menu variables
//			$menuType = $row[self::MENUTYPE_FIELDNAME];
//			$partialTemplateName = $this->menuTypeToSectionNameMap[$menuType];
//			$this->templateVariables['menuPartialTemplateName'] = $partialTemplateName;
//			$this->templateVariables['pageUids'] = GeneralUtility::trimExplode(',', $row['pages']);
//		}
//		if (self::CTYPE_TABLE == $row[self::CTYPE_FIELDNAME]) {
//			$this->templateVariables['tableHeadPositions'] = array(
//				self::THEAD_NONE => $this->translateLabel('tableHead.none', 'fluidcontent_core'),
//				self::THEAD_TOP => $this->translateLabel('tableHead.top', 'fluidcontent_core'),
//				self::THEAD_LEFT => $this->translateLabel('tableHead.left', 'fluidcontent_core'),
//			);
//		}
//		return parent::getForm($row);
//	}


	/**
	 * @param array $row
	 * @return string|NULL
	 */
	public function getExtensionKey(array $row): string
	{
		$extensionKey = $this->extensionKey;
		if (FALSE === empty($row['content_variant'])) {
			$extensionKey = $row['content_variant'];
		}
		return ExtensionNamingUtility::getExtensionKey($extensionKey);
	}

	/**
	 * @param array $row
	 * @return string
	 */
	public function getControllerExtensionKeyFromRecord(array $row): string
	{
		if (FALSE === empty($row['content_variant'])) {
			return $row['content_variant'];
		}
		return $this->extensionKey;
	}

	/**
	 * @param array $row
	 * @return string
	 */
	public function getTemplatePathAndFilename(array $row): string
	{
		$extensionKey = $this->getExtensionKey($row);

		$registeredTypes = (array) $GLOBALS['TYPO3_CONF_VARS']['FluidTYPO3.FluidcontentCore']['types'];
		if (!\in_array($row['CType'], $registeredTypes, true)) {
			return $this->contentConfigurationService->resolveTemplateFileForVariant($extensionKey, 'Default');
			return parent::getTemplatePathAndFilename($row);
		}

		$variant = $this->getVariant($row);
		$version = $this->getVersion($row);
		$templateName = $row['CType'];
		$template = $this->contentConfigurationService->resolveTemplateFileForVariant($extensionKey, $templateName, $variant, $version);
		return $template;
	}

	/**
	 * @param array $row
	 * @return array
	 */
	public function getPreview(array $row): array
	{
		list ($previewHeader, $previewContent, $continueDrawing) = parent::getPreview($row);
		return [null, $previewContent, TRUE];
	}

	/**
	 * @param array $row
	 * @return string
	 */
	protected function getVariant(array $row): ?string
	{
		$defaults = $this->contentConfigurationService->getDefaults();
		if (self::MODE_RECORD !== $defaults['mode'] && TRUE === empty($row['content_variant'])) {
			return $defaults['variant'];
		}
		return $row['content_variant'];
	}

	/**
	 * @param array $row
	 * @return string
	 */
	protected function getVersion(array $row): ?string
	{
		$defaults = $this->contentConfigurationService->getDefaults();
		if (self::MODE_RECORD !== $defaults['mode'] && TRUE === empty($row['content_version'])) {
			return $defaults['version'];
		}
		return $row['content_version'];
	}

	/**
	 * @param array $row
	 * @return string
	 */
	public function getControllerActionFromRecord(array $row): string
	{
		return strtolower($row['CType']);
	}

	/**
	 * @param string $operation
	 * @param integer $id
	 * @param array $row
	 * @param DataHandler $reference
	 * @param array $removals Allows overridden methods to pass an additional array of field names to remove from the stored Flux value
	 * @return void
	 */
	public function postProcessRecord($operation, $id, array &$row, DataHandler $reference, array $removals = []): void
	{
		$defaults = $this->contentConfigurationService->getDefaults();
		if (self::MODE_RECORD === $defaults['mode']) {
			if (TRUE === empty($row['content_variant'])) {
				$row['content_variant'] = $defaults['variant'];
			}
			if (TRUE === empty($row['content_version'])) {
				$row['content_version'] = $defaults['version'];
			}
		}
		parent::postProcessRecord($operation, $id, $row, $reference);
	}

	/**
	 * @param array $row
	 * @return array
	 */
	public function getTemplatePaths(array $row): array
	{
		$paths = parent::getTemplatePaths($row);

		$variant = $this->getVariant($row);
		if (FALSE === empty($variant)) {
			$extensionKey = ExtensionNamingUtility::getExtensionKey($variant);
			if (FALSE === empty($extensionKey)) {
				$overlayPaths = $this->contentConfigurationService->getViewConfigurationForExtensionName($extensionKey);
				$paths = array_merge_recursive($paths, $overlayPaths);
			}
		}

		return $paths;
	}

	/**
	 * @param string $key
	 * @return string|NULL
	 */
	protected function translateLabel($key): string
	{
		return LocalizationUtility::translate($key, 'fluidcontent_core');
	}

}
