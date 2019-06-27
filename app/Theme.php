<?php

namespace App;

/**
 * Class Theme
 * @package App
 */
class Theme
{
    /**
     * @return bool|mixed
     */
    private function getConfig()
    {
        if (file_exists(get_template_directory() .'/app/config/classes/config.php')) {
            return require(get_template_directory().'/app/config/classes/config.php');
        } else {
            return false;
        }
    }

    /**
     *  Require all classes
     */
    public function run()
    {
        $config = $this->getConfig();
        foreach ($config as $key => $value) {
            if (class_exists($value)) {
                new $value;
            }
        }
    }
}
