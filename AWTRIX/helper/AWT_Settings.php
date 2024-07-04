<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

trait AWT_Settings
{
    ########## Protected

    protected function UpdateSettings(): void
    {
        //ATIME, duration an app is displayed in seconds.
        $payload = '{"ATIME":' . $this->ReadPropertyInteger('AppDisplayDuration') . '}';
        $this->SetSettings($payload);

        //TMODE, changes the time app style.
        $payload = '{"TMODE":' . $this->ReadPropertyInteger('TimeAppStyle') . '}';
        $this->SetSettings($payload);
    }
}