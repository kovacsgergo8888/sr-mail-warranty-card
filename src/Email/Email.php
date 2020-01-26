<?php
/**
 * Created by PhpStorm.
 * User: kgergo
 * Date: 2019.03.04.
 * Time: 19:43
 */

namespace Email;


class Email
{
    protected $templateDir;

    protected $orderData;

    public function __construct($orderData)
    {
        $this->templateDir = __DIR__ . "/../../template/Email";
        $this->orderData = $orderData;
    }

    /**
     * @param string $templateDir
     */
    public function setTemplateDir($templateDir)
    {
        $this->templateDir = $templateDir;
    }

    public function placeHolderReplacer($string)
    {
        foreach ($this->orderData as $key => $value) {
            if (is_string($value)) {
                $string = str_replace("[$key]", $value, $string);
            }
        }
        return $string;
    }

    public function getTemplate($templateFile)
    {
        $string = file_get_contents("{$this->templateDir}/$templateFile");
        return $this->placeHolderReplacer($string);
    }
}