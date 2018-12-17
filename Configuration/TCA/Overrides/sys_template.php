<?php

call_user_func(function($_EXTKEY) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Fluid Styled Content');
}, 'fluidcontent_core');