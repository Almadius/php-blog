<?php

declare(strict_types=1);

namespace App;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $root = dirname(__DIR__);

        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($root . '/templates');
        $this->smarty->setCompileDir($root . '/templates_c');
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}