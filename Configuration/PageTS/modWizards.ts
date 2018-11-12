#mod.wizards.newContentElement >
mod.wizards.newContentElement {
    renderMode = tabs
    wizardItems {
        common.header = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:common
        common.elements {

            text {
                icon = EXT:frontend/Resources/Public/Icons/ContentElementWizard/regular_text.gif
                title = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:common_regularText_title
                description = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:common_regularText_description
                tt_content_defValues {
                    CType = text
                }
            }

            image {
                icon = EXT:frontend/Resources/Public/Icons/ContentElementWizard/images_only.gif
                title = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:common_imagesOnly_title
                description = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:common_imagesOnly_description
                tt_content_defValues {
                    CType = image
                }
            }
        }

        common.show := addToList(text,image)

		special.header = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:special
		special.elements {
			uploads {
				icon = EXT:frontend/Resources/Public/Icons/ContentElementWizard/filelinks.gif
				title = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:special_filelinks_title
				description = LLL:EXT:cms/layout/locallang_db_new_content_el.xlf:special_filelinks_description
				tt_content_defValues {
					CType = uploads
				}
			}
		}

		special.show := addToList(uploads)
    }
}