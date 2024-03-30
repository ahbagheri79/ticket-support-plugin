<?php
class LanguageLoader {
    public static function load_language_json() {
        // Get current language
        $language = get_locale();

        // Construct path to language file
        $file_path = plugin_dir_path(__FILE__) . 'languages/' . $language . '.json';

        // Check if language file exists
        if (file_exists($file_path)) {
            // Load and decode JSON data
            $json_data = file_get_contents($file_path);
            $translations = json_decode($json_data, true);

            return $translations; // Return translations
        } else {
            // If the language file doesn't exist, provide default translations
            $default_file_path = plugin_dir_path(__FILE__) . 'languages/fa_IR.json';
            if (file_exists($default_file_path)) {
                $json_data = file_get_contents($default_file_path);
                $translations = json_decode($json_data, true);
                return $translations; // Return default translations
            } else {
                return array(); // Return empty array if default file doesn't exist
            }
        }
    }
}
