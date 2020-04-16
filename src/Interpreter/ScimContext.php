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
    private $jsonData;

    /** @var mixed */
    private $currentData = [];

    /** @var string */
    private $query = '';

    public function __construct(string $jsonData)
    {
        $this->jsonData = json_decode($jsonData);
        $this->currentData[] = $this->jsonData;
        $this->query = '$this->jsonData';
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
        return $this->currentData[count($this->currentData) - 1];
    }

    /**
     * @param mixed $currentData
     */
    public function setCurrentData(&$currentData): void
    {
        $this->currentData[] = $currentData;
    }

    /**
     * @param string $query
     */
    public function concatQuery(string $query): void
    {
        $this->query.= $query;
    }

    public function replace($replace)
    {
        eval($this->query . " = $replace;");
    }


}