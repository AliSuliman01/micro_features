<?php


namespace AliSuliman\MicroFeatures\Services\Translations;

trait HasTranslations
{
    public static function bootHasTranslations()
    {
        static::addGlobalScope(new TranslationScope);
    }

    public function translationRelationName()
    {
        return 'translations';
    }

    public function translationForeignKey(){
        return strtolower(class_basename($this)). '_id';
    }

    public function defaultLocale()
    {
        return null;
    }
}
