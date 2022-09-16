<?php

namespace AliSuliman\MicroFeatures\Services\Translations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;

class TranslationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->with($model->translationRelationName(), function ($query)use($model) {

        $locale_language = $query->clone()->where('language_code', App::getLocale())->pluck($model->translationForeignKey());

        $query->where(function ($query) use ($locale_language, $model) {
            $query->whereIn($model->translationForeignKey(), $locale_language)->where('language_code', App::getLocale());
        })
            ->orWhere(function ($query) use ($locale_language, $model) {
                $query->whereNotIn($model->translationForeignKey(), $locale_language)->where('language_code', $this->defaultLocale() ?? config('translations.default_locale'));
            });
    });
    }

}
