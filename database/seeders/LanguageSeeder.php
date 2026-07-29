<?php

namespace Database\Seeders;

use Botble\Language\Database\Seeders\LanguageSeeder as BaseLanguageSeeder;
use Botble\LanguageAdvanced\Database\Seeders\Traits\HasLanguageSeeder;

class LanguageSeeder extends BaseLanguageSeeder
{
    use HasLanguageSeeder;

    public function run(): void
    {
        parent::run();

        $this->createLanguages();
    }
}
