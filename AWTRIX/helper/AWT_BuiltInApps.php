<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

trait AWT_BuiltInApps
{
    ########## Protected

    protected function UpdateBuiltInApps(): void
    {
        //Time
        $payload = '{"TIM":' . json_encode($this->ReadPropertyBoolean('UseBuiltInAppTime')) . '}';
        $this->SetSettings($payload);

        //Date
        $payload = '{"DAT":' . json_encode($this->ReadPropertyBoolean('UseBuiltInAppDate')) . '}';
        $this->SetSettings($payload);

        //Temperature
        $payload = '{"TEMP":' . json_encode($this->ReadPropertyBoolean('UseBuiltInAppTemperature')) . '}';
        $this->SetSettings($payload);

        //Humidity
        $payload = '{"HUM":' . json_encode($this->ReadPropertyBoolean('UseBuiltInAppHumidity')) . '}';
        $this->SetSettings($payload);

        //Battery
        $payload = '{"BAT":' . json_encode($this->ReadPropertyBoolean('UseBuiltInAppBattery')) . '}';
        $this->SetSettings($payload);
    }
}