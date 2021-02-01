<?php

declare(strict_types=1);

namespace Manager\Models;

use DigitalStars\SimpleVK\LongPoll as lp;
use DigitalStars\SimpleVK\SimpleVkException;

class LongPoll extends lp
{
    use Bot;

    /**
     * Управление многопоточностью
     * simplevk\longpool
     * @param bool $bool
     * @return lp
     * @throws SimpleVkException
     */
    public function isMultiThread($bool = true): lp
    {
        parent::isMultiThread($bool);
        return $this;

    }
}