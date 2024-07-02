<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

trait AWT_CustomApps
{
    ########## Public

    public function GetActualCustomApps(): void
    {
        $actualApps = [];
        $this->UpdateFormField('ActualCustomApps', 'visible', false);
        $apps = json_decode($this->GetAppsInLoop(), true);

        /* Dummy data
        $apps = json_decode('{"Time":0,"Date":1,"Temperature":2,"Humidity":3,"Battery":4,"Solar":5}', true);
         */

        foreach ($apps as $name => $number) {
            $actualApps[] = ['Number' => $number, 'Name' => $name];
        }
        $amount = count($actualApps);
        if ($amount == 0) {
            $amount = 1;
        }
        $this->UpdateFormField('ActualCustomApps', 'visible', true);
        $this->UpdateFormField('ActualCustomApps', 'rowCount', $amount);
        $this->UpdateFormField('ActualCustomApps', 'values', json_encode($actualApps));
    }

    public function UpdateCustomApps(bool $CheckUpdate): void
    {
        $apps = json_decode($this->ReadPropertyString('CustomApps'), true);
        foreach ($apps as $app) {
            if ($CheckUpdate) {
                if (!$app['UseUpdate']) {
                    continue;
                }
            }
            $action = json_decode($app['Action'], true);
            @IPS_RunAction($action['actionID'], $action['parameters']);
        }
        $this->SetTimerInterval('UpdateCustomApps', $this->ReadPropertyInteger('CustomAppsUpdateInterval') * 1000);
    }
}