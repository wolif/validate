<?php
namespace Wolif\Validate;

class Enum
{
    const dir_necessity = __DIR__ . '/Components/Necessity';
    const dir_type      = __DIR__ . '/Components/Type';

    const ns_necessity = 'Wolif\\Validate\\Necessity';
    const ns_type      = 'Wolif\\Validate\\Type';

    protected static $types;
    protected static $necessities;

    public static function types()
    {
        if (!static::$types) {
            foreach (scandir(static::dir_type) as $file) {
                if (strlen($file) > 4) {
                    static::$types[] = lcfirst(substr($file, 0, strlen($file) - 5));
                }
            }
        }
        return static::$types;
    }

    public static function necessities()
    {
        if (!static::$necessities) {
            foreach (scandir(static::dir_necessity) as $file) {
                if (strlen($file) > 4) {
                    static::$necessities[] = lcfirst(substr($file, 0, strlen($file) - 5));
                }
            }
        }
        return static::$necessities;
    }
}
