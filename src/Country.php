<?php

namespace Kurt\LaravelIntl;

use Illuminate\Support\Arr;
use Kurt\LaravelIntl\Contracts\Intl;
use Kurt\LaravelIntl\Concerns\WithLocales;
use Kurt\LaravelIntl\Exceptions\MissingLocaleException;

class Country extends Intl
{
    use WithLocales;

    /**
     * Loaded localized country data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Get a localized record by key.
     *
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return Arr::get($this->all(), $key);
    }

    /**
     * Alias of get().
     *
     * @param string $key
     * @return string
     */
    public function name($key)
    {
        return $this->get($key);
    }

    /**
     * Get all localized records.
     *
     * @return array
     */
    public function all()
    {
        $default = $this->data($this->getLocale());
        $fallback = $this->data($this->getFallbackLocale());

        return $default + $fallback;
    }

    /**
     * Get the data for the given locale.
     *
     * @param string $locale
     * @return array
     */
    protected function data($locale)
    {
        if (! array_key_exists($locale, $this->data)) {
            $localePath = storage_path("locales/{$locale}/country.php");

            if (! is_file($localePath)) {
                throw new MissingLocaleException($locale);
            }

            $this->data[$locale] = require $localePath;
        }

        return $this->data[$locale];
    }
}
