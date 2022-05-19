<?php

declare(strict_types=1);

namespace Chiron\Testing\Stub;

use Symfony\Component\Console\Output\Output;

// TODO : classe à déplacer dans le package chiron/console ????
// TODO : renommer en ArrayOutput ????
class SpyOutput extends Output
{
    /** @var string[] */
    private array $messages = [];

    /**
     * {@inheritdoc}
     */
    protected function doWrite(string $message, bool $newline)
    {
        $this->messages[] = $message;

        if ($newline) {
            $this->messages[] = '';
        }
    }

    /**
     * Empties buffer and returns its content.
     *
     * @return string
     */
    public function fetch(): string
    {
        return implode("\n", $this->messages);
    }

    /**
     * Return the raw messages.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
