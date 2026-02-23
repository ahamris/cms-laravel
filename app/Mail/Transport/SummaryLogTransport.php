<?php

namespace App\Mail\Transport;

use Psr\Log\LoggerInterface;
use Stringable;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class SummaryLogTransport implements TransportInterface, Stringable
{
    public function __construct(
        protected LoggerInterface $logger
    ) {}

    /**
     * {@inheritdoc}
     */
    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $envelope = $envelope ?? Envelope::create($message);

        $to = $this->formatRecipients($envelope);
        $subject = $this->extractSubject($message);

        $this->logger->info('Mail sent', [
            'to' => $to,
            'subject' => $subject,
        ]);

        return new SentMessage($message, $envelope);
    }

    /**
     * Format envelope recipients for log (addresses only, no body).
     */
    private function formatRecipients(Envelope $envelope): string
    {
        $addresses = [];
        foreach ($envelope->getRecipients() as $address) {
            $addresses[] = $address->getAddress();
        }

        return implode(', ', $addresses) ?: '(none)';
    }

    /**
     * Extract Subject from raw message headers only (no body).
     */
    private function extractSubject(RawMessage $message): string
    {
        $raw = $message->toString();
        $headerEnd = strpos($raw, "\r\n\r\n");
        if ($headerEnd === false) {
            $headerEnd = strpos($raw, "\n\n");
        }
        $headers = $headerEnd !== false ? substr($raw, 0, $headerEnd) : $raw;

        if (preg_match('/^Subject:\s*(.+)/mi', $headers, $m)) {
            return trim($m[1], " \t\r\n");
        }

        return '(no subject)';
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'log';
    }
}
