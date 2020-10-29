<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.04.
 * Time: 19:57
 */

namespace Config;


class Config
{
    private $config;

    public function __construct()
    {
        $this->config = \json_decode(
            file_get_contents(__DIR__ . "/../../config.json"),
            true
        );
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function get($key)
    {
        return $this->config[$key];
    }
}