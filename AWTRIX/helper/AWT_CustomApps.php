<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection DuplicatedCode */

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

    public function UpdateCustomApps(): void
    {
        $apps = json_decode($this->ReadPropertyString('CustomApps'), true);
        foreach ($apps as $app) {
            //Update
            if ($app['UseUpdate']) {
                if ($app['UseScript']) {
                    $script = $app['Script'];
                    IPS_RunScriptText($script);
                }
            }
            //Trigger
            if ($app['UseTrigger']) {
                $execute = true;
                //Check primary condition
                if (!IPS_IsConditionPassing($app['PrimaryCondition'])) {
                    $execute = false;
                }
                //Check secondary condition
                if (!IPS_IsConditionPassing($app['SecondaryCondition'])) {
                    $execute = false;
                }
                if ($execute) {
                    if ($app['UseScript']) {
                        $script = $app['Script'];
                        IPS_RunScriptText($script);
                    }
                }
            }
        }
        $this->SetTimerInterval('UpdateCustomApps', $this->ReadPropertyInteger('CustomAppsUpdateInterval') * 1000);
    }

    ########## Protected

    protected function TriggerCustomApps(int $SenderID, bool $ValueChanged): void
    {
        $valueChangedText = 'nicht ';
        if ($ValueChanged) {
            $valueChangedText = '';
        }
        $this->SendDebug(__FUNCTION__, 'Der Wert der Variable ' . $SenderID . ' hat sich ' . $valueChangedText . 'geändert', 0);
        $variables = json_decode($this->ReadPropertyString('CustomApps'), true);
        foreach ($variables as $variable) {
            if (!$variable['UseTrigger']) {
                continue;
            }
            if ($variable['PrimaryCondition'] != '') {
                $primaryCondition = json_decode($variable['PrimaryCondition'], true);
                if (array_key_exists(0, $primaryCondition)) {
                    if (array_key_exists(0, $primaryCondition[0]['rules']['variable'])) {
                        $id = $primaryCondition[0]['rules']['variable'][0]['variableID'];
                        if ($SenderID == $id) {
                            if (!$variable['UseMultipleAlerts'] && !$ValueChanged) {
                                $this->SendDebug(__FUNCTION__, 'Abbruch, die Mehrfachauslösung ist nicht aktiviert!', 0);
                                continue;
                            }
                            $execute = true;
                            //Check primary condition
                            if (!IPS_IsConditionPassing($variable['PrimaryCondition'])) {
                                $execute = false;
                            }
                            //Check secondary condition
                            if (!IPS_IsConditionPassing($variable['SecondaryCondition'])) {
                                $execute = false;
                            }
                            if (!$execute) {
                                $this->SendDebug(__FUNCTION__, 'Abbruch, die Bedingungen wurden nicht erfüllt!', 0);
                            } else {
                                $this->SendDebug(__FUNCTION__, 'Die Bedingungen wurden erfüllt.', 0);
                                if ($variable['UseScript']) {
                                    $script = $variable['Script'];
                                    IPS_RunScriptText($script);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    ########## Private

    private function CheckCustomAppsTrigger(int $VariableID): bool
    {
        $result = false;
        $variables = json_decode($this->ReadPropertyString('CustomApps'), true);
        if (!empty($variables)) {
            foreach ($variables as $variable) {
                if ($variable['PrimaryCondition'] != '') {
                    $primaryCondition = json_decode($variable['PrimaryCondition'], true);
                    if (array_key_exists(0, $primaryCondition)) {
                        if (array_key_exists(0, $primaryCondition[0]['rules']['variable'])) {
                            $id = $primaryCondition[0]['rules']['variable'][0]['variableID'];
                            if ($id == $VariableID) {
                                if ($id > 1 && @IPS_ObjectExists($id)) {
                                    if ($variable['UseTrigger']) {
                                        $result = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}