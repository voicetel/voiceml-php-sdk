<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Base class for every request DTO that serializes to a form-encoded body.
 *
 * Subclasses declare public readonly properties whose **JSON-encoded name** equals the
 * Twilio-wire PascalCase field (`To`, `From`, `Url`, `StatusCallbackEvent`, ...). The
 * subclass declares the wire mapping by overriding {@see fieldMap()} — each entry maps
 * a wire field name to the local property name.
 *
 * {@see toForm()} returns an associative array of *only* the properties the caller set
 * (non-null). Booleans are rendered to the strings `"true"` / `"false"` (Twilio convention).
 */
abstract class FormRequest
{
    /**
     * @return array<string,string> Map of wire field name (PascalCase) → PHP property name (snake/camel).
     */
    abstract protected static function fieldMap(): array;

    /**
     * Render the request as a form-encodable array. Null fields are omitted; booleans become
     * `"true"` / `"false"`; arrays are emitted as-is so Guzzle encodes them as repeated form
     * params (e.g. `StatusCallbackEvent=ringing&StatusCallbackEvent=completed`).
     *
     * @return array<string,mixed>
     */
    public function toForm(): array
    {
        $out = [];
        foreach (static::fieldMap() as $wireName => $propName) {
            if (!property_exists($this, $propName)) {
                continue;
            }
            /** @psalm-suppress MixedAssignment */
            $value = $this->{$propName};
            if ($value === null) {
                continue;
            }
            if (is_bool($value)) {
                $out[$wireName] = $value ? 'true' : 'false';
                continue;
            }
            if ($value instanceof \BackedEnum) {
                $out[$wireName] = $value->value;
                continue;
            }
            if (is_array($value)) {
                $out[$wireName] = array_map(static fn ($v) => match (true) {
                    is_bool($v) => $v ? 'true' : 'false',
                    $v instanceof \BackedEnum => $v->value,
                    default => $v,
                }, $value);
                continue;
            }
            $out[$wireName] = $value;
        }
        return $out;
    }
}
