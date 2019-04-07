<?php

namespace shop\services\newsletter;

use shop\services\newsletter\interfaces\Inewsletter;
use DrewM\MailChimp\MailChimp;

class MailNewsletter implements Inewsletter
{
    private $client;
    private $listId;

    public function __construct(MailChimp $client, $listId)
    {
        $this->client = $client;
        $this->listId = $listId;
    }

    public function subscribe($email): void
    {
        $this->client->post('lists/' . $this->listId . '/members', [
            'email_address' => $email,
            'status' => 'subscribed'
        ]);

        $this->checkError();
    }

    public function unsubscribe($email): void
    {
        $hash = $this->client->subscriberHash($email);
        $this->client->delete('lists/' . $this->listId . '/members/' . $hash);

        $this->checkError();
    }

    private function checkError()
    {
        if ($error = $this->client->getLastError()) {
            throw new \RuntimeException($error);
        }
        return;
    }
}
