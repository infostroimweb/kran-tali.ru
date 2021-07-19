<?php //declare(strict_types=1);

namespace Guideplugin\Helper;

class MultilanguageHelper
{
    public function getLanguageCode()
    {
        /**
         * Return language code from WPML
         */
        if (class_exists('\SitePress')) {
            return apply_filters('wpml_current_language', null);
        }

        /**
         * Return language code from Polylang
         */
        if (class_exists('\Polylang') && function_exists('pll_current_language')) {
            return pll_current_language();
        }

        return;
    }

    public function setLanguage(string $language)
    {
        /**
         * Set language for WPML
         */
        if (class_exists('\SitePress')) {
            global $sitepress;
            $sitepress->switch_lang($language);
        }

        /**
         * Polylang will take the lang ajax attribute in account
         */

        return;
    }

    public function addPostBaseMultilanguageSupport(array $arguments)
    {
        /**
         * WPML suppress_filters argument is already set
         */

        /**
         * Check if Polylang is active and add lang argument
         */
        if (class_exists('\Polylang') && function_exists('pll_current_language')) {
            $arguments['lang'] = pll_current_language();
        }

        return $arguments;
    }

    public function addWpmlSupport(array $fieldGroup)
    {
        /**
         * Only apply if WPML is active
         */
        if (class_exists('\Sitepress')) {
            foreach ((array) $fieldGroup['fields'] as $key => $field) {
                $fieldGroup['fields'][$key] = $this->integrateAcfField($field, $fieldGroup);
            }
        }

        return $fieldGroup;
    }

    private function integrateAcfField(array $field, array $fieldGroup)
    {
        if (isset($field['sub_fields']) && count($field['sub_fields']) > 0) {
            foreach ($field['sub_fields'] as $key => $subfield) {
                $field['sub_fields'][$key] = $this->integrateAcfField($subfield, $fieldGroup);
            }
        }

        $field['wpml_cf_preferences'] = WPML_IGNORE_CUSTOM_FIELD;

        return $field;
    }

    public function setIndexBuilderLanguage(int $postId)
    {
        if (class_exists('\Sitepress')) {
            global $sitepress;
            $languageInformation = apply_filters('wpml_post_language_details', null, $postId);
            $language = (isset($languageInformation['language_code'])) ? $languageInformation['language_code'] : ICL_LANGUAGE_CODE;
            $sitepress->switch_lang($language);
        }
    }
}
