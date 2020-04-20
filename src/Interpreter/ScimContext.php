<?php

/*
 * This file is part of the Artemeon Core - Web Application Framework.
 *
 * (c) Artemeon <www.artemeon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use stdClass;

class ScimContext extends Context
{
    /** @var stdClass */
    public $jsonData;

    /** @var mixed */
    public $currentData;

    /** @var string */
    public $path = '';

    public function __construct(string $jsonData)
    {
        $this->jsonData = json_decode($jsonData);
        $this->currentData = $this->jsonData;
        parent::__construct();
    }

    /**
     * @return stdClass
     */
    public function getJsonData(): stdClass
    {
        return $this->jsonData;
    }

    /**
     * @return mixed
     */
    public function &getCurrentData()
    {
        return $this->currentData;
    }

    /**
     * @param mixed $currentData
     */
    public function setCurrentData(&$currentData): void
    {
        $this->currentData = &$currentData;
    }

    public function concatPath(string $path)
    {
        $this->path.= $path;
    }
}