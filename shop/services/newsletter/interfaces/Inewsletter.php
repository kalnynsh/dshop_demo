<?php

namespace shop\services\newsletter\interfaces;

interface Inewsletter
{
    public function subscribe($email): void;
    public function unsubscribe($email): void;
}
