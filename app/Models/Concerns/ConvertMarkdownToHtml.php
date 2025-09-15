<?php

namespace App\Models\Concerns;

trait ConvertMarkdownToHtml
{
    public function bootConvertMarkdownToHtml()
    {
        static::saving(fn(self $model) => $model->fill([
            'html' => str($model->body)->markdown([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
                'max_nesting_level' => 5,
            ])
        ]));
    }
}
