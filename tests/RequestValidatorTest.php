<?php

declare(strict_types=1);

namespace VoiceML\Tests;

use PHPUnit\Framework\TestCase;
use VoiceML\RequestValidator;

/**
 * Canonical webhook-signature vectors. Account SID literals use the masked
 * placeholder form (AC + 32 `X`) so the published spec examples stay
 * obviously fictional and GitHub push protection sees no real-shaped SID.
 */
final class RequestValidatorTest extends TestCase
{
    private const KEY = 'test-api-key-0123456789';
    private const URL = 'https://example.com/voice/answer?case=1';
    private const V1_SIG = 'RweoLyWqNC7H8+MLXPb7EPz18OI=';

    /** @return array<string,string> */
    private function v1Params(): array
    {
        return [
            'CallSid' => 'CA0123456789abcdef0123456789abcdef',
            'From' => '+14155550100',
            'To' => '+14155550101',
            'Digits' => '1234',
            'AccountSid' => 'AC' . \str_repeat('X', 32),
        ];
    }

    public function testV1PostSignature(): void
    {
        $validator = new RequestValidator(self::KEY);
        self::assertSame(self::V1_SIG, RequestValidator::computeSignature(self::URL, $this->v1Params(), self::KEY));
        self::assertTrue($validator->validate(self::URL, $this->v1Params(), self::V1_SIG));
    }

    public function testV1DefaultPortNormalisation(): void
    {
        $validator = new RequestValidator(self::KEY);
        self::assertTrue($validator->validate('https://example.com:443/voice/answer?case=1', $this->v1Params(), self::V1_SIG));
        self::assertTrue($validator->validate(self::URL, $this->v1Params(), 'xE0r2wPa6d1ktQeCFpPyINmTNYg='));
    }

    public function testV3RepeatedKeys(): void
    {
        $validator = new RequestValidator(self::KEY);
        self::assertTrue($validator->validate('https://example.com/hook', [
            'To' => '+14155550101',
            'Event' => ['answered', 'initiated'],
        ], 'jCnpBgyg45aZYwUie5fmq4EYUic='));
    }

    public function testV4GetSignature(): void
    {
        $validator = new RequestValidator(self::KEY);
        $url = 'https://example.com/voice/answer?CallSid=CA0123456789abcdef0123456789abcdef&From=%2B14155550100';
        self::assertTrue($validator->validate($url, [], '9I4odWBHhF7TMIkIrNtPEPjD9fU='));
    }

    public function testRejectsWrongKeyAndTamperedValue(): void
    {
        $validator = new RequestValidator(self::KEY);
        $other = new RequestValidator('wrong-key');
        self::assertFalse($validator->validate(self::URL, $this->v1Params(), RequestValidator::computeSignature(self::URL, $this->v1Params(), "wrong-key")));
        self::assertFalse($validator->validate(self::URL, \array_merge($this->v1Params(), ['Digits' => '9999']), self::V1_SIG));
    }
}
