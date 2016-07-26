<?php
namespace Rolice\Speedy\Components;


class Language
{
    const Available = ['BG', 'EN'];

    const BG = 'BG';
    const EN = 'EN';

    protected $lang = self::BG;

    public function __construct($language = null)
    {
        if (!$language || !preg_match('#[a-z]{2}#i', $language)) {
            return;
        }

        if (!in_array($language, self::Available)) {
            return;
        }

        $this->lang = $language;
    }

    public function get()
    {
        return $this->lang;
    }

    public static function create()
    {
        return new static(app()->getLocale());
    }
}