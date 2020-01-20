<?php
declare(strict_types=1);

namespace Yiisoft\Widget\Exception;

class InvalidConfigException extends \Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName(): string
    {
        return 'Invalid Configuration';
    }
}
