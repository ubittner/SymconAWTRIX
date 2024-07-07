<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

trait AWT_ConfigurationForm
{
    ########## Public

    public function ReloadConfig(): void
    {
        $this->ReloadForm();
    }

    public function ExpandExpansionPanels(bool $State): void
    {
        for ($i = 1; $i <= 6; $i++) {
            $this->UpdateFormField('Panel' . $i, 'expanded', $State);
        }
    }

    public function ModifyListButton(string $Field, string $Condition): void
    {
        $id = 0;
        $state = false;
        //Get variable id
        $primaryCondition = json_decode($Condition, true);
        if (array_key_exists(0, $primaryCondition)) {
            if (array_key_exists(0, $primaryCondition[0]['rules']['variable'])) {
                $id = $primaryCondition[0]['rules']['variable'][0]['variableID'];
                if ($id > 1 && @IPS_ObjectExists($id)) {
                    $state = true;
                }
            }
        }
        $this->UpdateFormField($Field, 'caption', 'ID ' . $id . ' Bearbeiten');
        $this->UpdateFormField($Field, 'visible', $state);
        $this->UpdateFormField($Field, 'objectID', $id);
    }

    public function UIShowMessage(string $Message): void
    {
        $this->UpdateFormField('InfoMessage', 'visible', true);
        $this->UpdateFormField('InfoMessageLabel', 'caption', $Message);
    }

    public function GetConfigurationForm(): string
    {
        $form = [];

        $library = IPS_GetLibrary(self::LIBRARY_GUID);
        $module = IPS_GetModule(self::MODULE_GUID);

        ########## Elements

        //Configuration buttons
        $form['elements'][] =
            [
                'type'  => 'RowLayout',
                'items' => [
                    [
                        'type'    => 'Button',
                        'caption' => 'Konfiguration ausklappen',
                        'onClick' => $module['Prefix'] . '_ExpandExpansionPanels($id, true);'
                    ],
                    [
                        'type'    => 'Button',
                        'caption' => 'Konfiguration einklappen',
                        'onClick' => $module['Prefix'] . '_ExpandExpansionPanels($id, false);'
                    ],
                    [
                        'type'    => 'Button',
                        'caption' => 'Konfiguration neu laden',
                        'onClick' => $module['Prefix'] . '_ReloadConfig($id);'
                    ]
                ]
            ];

        //Info
        $form['elements'][] = [
            'type'     => 'ExpansionPanel',
            'name'     => 'Panel1',
            'caption'  => 'Info',
            'expanded' => false,
            'items'    => [
                [
                    'type'    => 'Label',
                    'caption' => "ID:\t\t\t" . $this->InstanceID
                ],
                [
                    'type'    => 'Label',
                    'caption' => "Modul:\t\t" . $module['ModuleName']
                ],
                [
                    'type'    => 'Label',
                    'caption' => "Präfix:\t\t" . $module['Prefix']
                ],
                [
                    'type'    => 'Label',
                    'caption' => "Version:\t\t" . $library['Version'] . '-' . $library['Build'] . ', ' . date('d.m.Y', $library['Date'])
                ],
                [
                    'type'    => 'Label',
                    'caption' => "Entwickler:\t" . $library['Author']
                ],
                [
                    'type'    => 'Label',
                    'caption' => ' '
                ],
                [
                    'type'    => 'ValidationTextBox',
                    'name'    => 'Note',
                    'caption' => 'Notiz',
                    'width'   => '600px'
                ]
            ]
        ];

        //Smart Pixel Clock
        $form['elements'][] =
            [
                'type'    => 'ExpansionPanel',
                'name'    => 'Panel2',
                'caption' => 'Smart Pixel Clock',
                'items'   => [
                    [
                        'type'    => 'Label',
                        'caption' => 'Netzwerk',
                        'bold'    => true,
                        'italic'  => true
                    ],
                    [
                        'type'    => 'ValidationTextBox',
                        'name'    => 'DeviceIP',
                        'caption' => 'IP-Adresse'
                    ],
                    [
                        'type'    => 'NumberSpinner',
                        'name'    => 'Timeout',
                        'caption' => 'Timeout',
                        'suffix'  => 'Millisekunden'
                    ],
                    [
                        'type'    => 'Label',
                        'caption' => ' '
                    ],
                    [
                        'type'    => 'Label',
                        'caption' => 'Authentifizierung',
                        'bold'    => true,
                        'italic'  => true
                    ],
                    [
                        'type'    => 'ValidationTextBox',
                        'name'    => 'UserName',
                        'caption' => 'Benutzername'
                    ],
                    [
                        'type'    => 'PasswordTextBox',
                        'name'    => 'Password',
                        'caption' => 'Kennwort'
                    ],
                    [
                        'type'    => 'Label',
                        'caption' => ' '
                    ],
                    [
                        'type'    => 'Label',
                        'caption' => 'Status',
                        'bold'    => true,
                        'italic'  => true
                    ],
                    [
                        'type'    => 'NumberSpinner',
                        'name'    => 'StatusInterval',
                        'caption' => 'Statusaktualisierung',
                        'minimum' => 0,
                        'suffix'  => 'Sekunden'
                    ],
                    [
                        'type'    => 'Label',
                        'caption' => ' '
                    ],
                    [
                        'type'    => 'Label',
                        'caption' => 'Neustart',
                        'bold'    => true,
                        'italic'  => true
                    ],
                    [
                        'type'    => 'CheckBox',
                        'name'    => 'UseAutomaticReboot',
                        'caption' => 'Automatischer Neustart'
                    ],
                    [
                        'type'    => 'SelectTime',
                        'name'    => 'RebootTime',
                        'caption' => 'Uhrzeit'
                    ]
                ]
            ];

        //Settings
        $form['elements'][] =
            [
                'type'    => 'ExpansionPanel',
                'name'    => 'Panel3',
                'caption' => 'Settings',
                'items'   => [
                    [
                        'type'    => 'NumberSpinner',
                        'name'    => 'AppDisplayDuration',
                        'caption' => 'App Anzeigedauer',
                        'suffix'  => 'Sekunden'
                    ],
                    [
                        'type'    => 'NumberSpinner',
                        'name'    => 'TimeAppStyle',
                        'caption' => 'Stil der Zeit-App',
                        'minimum' => 0,
                        'maximum' => 4
                    ],
                ]
            ];

        //Built-in Apps
        $form['elements'][] =
            [
                'type'    => 'ExpansionPanel',
                'name'    => 'Panel4',
                'caption' => 'Built-in Apps',
                'items'   => [
                    [
                        'type'    => 'Label',
                        'caption' => 'Manueller Geräte-Neustart erforderlich!',
                        'bold'    => true,
                        'italic'  => true
                    ],
                    [
                        'type'    => 'CheckBox',
                        'name'    => 'UseBuiltInAppTime',
                        'caption' => 'Uhrzeit'
                    ],
                    [
                        'type'    => 'CheckBox',
                        'name'    => 'UseBuiltInAppDate',
                        'caption' => 'Datum'
                    ],
                    [
                        'type'    => 'CheckBox',
                        'name'    => 'UseBuiltInAppTemperature',
                        'caption' => 'Temperatur'
                    ],
                    [
                        'type'    => 'CheckBox',
                        'name'    => 'UseBuiltInAppHumidity',
                        'caption' => 'Luftfeuchtigkeit'
                    ],
                    [
                        'type'    => 'CheckBox',
                        'name'    => 'UseBuiltInAppBattery',
                        'caption' => 'Batterie'
                    ]
                ]
            ];

        //Custom Apps
        $customApps = json_decode($this->ReadPropertyString('CustomApps'), true);
        $amountCustomAppsRows = count($customApps) + 1;
        if ($amountCustomAppsRows == 1) {
            $amountCustomAppsRows = 3;
        }
        $amountCustomApps = count($customApps);
        $customAppsValues = [];
        foreach ($customApps as $customApp) {
            $rowColor = '#DFDFDF'; //grey
            if ($customApp['UseUpdate']) {
                $rowColor = '#C0FFC0'; //light green
            }
            $customAppsValues[] = ['rowColor' => $rowColor];
        }

        $customAppsScript = '<?php' . "\n\n" . '$appName = "App Name";' . "\n" . '$icon = "21256";' . "\n" . '$energyID = 12345;' . "\n" . '$energyValue = GetValue($energyID);' . "\n" . '$text = $energyValue . " W";' . "\n" . '$duration = 10;' . "\n\n" . '//Keine Änderungen ab hier!' . "\n" . '$payload = [];' . "\n" . '$payload["icon"] = $icon;' . "\n" . '$payload["text"] = $text;' . "\n" . '$payload["duration"] = $duration;' . "\n" . 'AWT_AddCustomApp(' . $this->InstanceID . ', $appName, json_encode($payload));';

        $form['elements'][] =
            [
                'type'    => 'ExpansionPanel',
                'name'    => 'Panel5',
                'caption' => 'Custom Apps',
                'items'   => [
                    [
                        'type'     => 'List',
                        'name'     => 'CustomApps',
                        'caption'  => 'Custom Apps: ' . $amountCustomApps,
                        'rowCount' => $amountCustomAppsRows,
                        'add'      => true,
                        'delete'   => true,
                        'onDelete' => $module['Prefix'] . '_DeleteCustomApp($id, $CustomApps["Name"]);',
                        'sort'     => [
                            'column'    => 'Name',
                            'direction' => 'descending'
                        ],
                        'columns' => [
                            [
                                'caption' => 'Aktualisieren',
                                'name'    => 'UseUpdate',
                                'width'   => '130px',
                                'add'     => true,
                                'edit'    => [
                                    'type' => 'CheckBox'
                                ]
                            ],
                            [
                                'caption' => 'App Name',
                                'name'    => 'Name',
                                'width'   => '200px',
                                'add'     => '',
                                'edit'    => [
                                    'type' => 'ValidationTextBox'
                                ]
                            ],
                            [
                                'caption' => 'Benutze Skript',
                                'name'    => 'UseScript',
                                'width'   => '150px',
                                'add'     => true,
                                'edit'    => [
                                    'type' => 'CheckBox'
                                ]
                            ],
                            [
                                'caption'  => 'Skript',
                                'name'     => 'Script',
                                'width'    => '600px',
                                'add'      => $customAppsScript,
                                'edit'     => [
                                    'type' => 'ScriptEditor'
                                ]
                            ]
                        ],
                        'values' => $customAppsValues,
                        'form'   => [
                            [
                                'type'    => 'Label',
                                'caption' => 'Allgemein',
                                'bold'    => true,
                                'italic'  => true
                            ],
                            [
                                'type'    => 'CheckBox',
                                'name'    => 'UseUpdate',
                                'caption' => 'Aktualisieren'
                            ],
                            [
                                'type'    => 'ValidationTextBox',
                                'name'    => 'Name',
                                'caption' => 'App Name'
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => ' '
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => 'Skript',
                                'bold'    => true,
                                'italic'  => true
                            ],
                            [
                                'type'    => 'CheckBox',
                                'name'    => 'UseScript',
                                'caption' => 'Benutze Skript'
                            ],
                            [
                                'type'     => 'ScriptEditor',
                                'name'     => 'Script',
                                'rowCount' => 15
                            ]
                        ]
                    ],
                    [
                        'type'    => 'NumberSpinner',
                        'name'    => 'CustomAppsUpdateInterval',
                        'caption' => 'Aktualisierung',
                        'minimum' => 0,
                        'suffix'  => 'Sekunden'
                    ]
                ]
            ];

        //Notifications
        $notificationsValues = [];
        $notifications = json_decode($this->ReadPropertyString('Notifications'), true);
        $amountNotificationsRows = count($notifications) + 1;
        if ($amountNotificationsRows == 1) {
            $amountNotificationsRows = 3;
        }
        $amountNotifications = count($notifications);
        foreach ($notifications as $notification) {
            $sensorID = 0;
            if ($notification['PrimaryCondition'] != '') {
                $primaryCondition = json_decode($notification['PrimaryCondition'], true);
                if (array_key_exists(0, $primaryCondition)) {
                    if (array_key_exists(0, $primaryCondition[0]['rules']['variable'])) {
                        $sensorID = $primaryCondition[0]['rules']['variable'][0]['variableID'];
                    }
                }
            }
            //Check conditions first
            $conditions = true;
            if ($sensorID <= 1 || !@IPS_ObjectExists($sensorID)) {
                $conditions = false;
            }
            if ($notification['SecondaryCondition'] != '') {
                $secondaryConditions = json_decode($notification['SecondaryCondition'], true);
                if (array_key_exists(0, $secondaryConditions)) {
                    if (array_key_exists('rules', $secondaryConditions[0])) {
                        $rules = $secondaryConditions[0]['rules']['variable'];
                        foreach ($rules as $rule) {
                            if (array_key_exists('variableID', $rule)) {
                                $id = $rule['variableID'];
                                if ($id <= 1 || !@IPS_ObjectExists($id)) {
                                    $conditions = false;
                                }
                            }
                        }
                    }
                }
            }
            $rowColor = '#FFC0C0'; //red
            if ($conditions) {
                $rowColor = '#C0FFC0'; //light green
                if (!$notification['Use']) {
                    $rowColor = '#DFDFDF'; //grey
                }
            }
            $notificationsValues[] = ['rowColor' => $rowColor];
        }

        $notificationsScript = '<?php' . "\n\n" . '$icon = "112";' . "\n" . '$trigerID = 12345;' . "\n" . '$triggerValue = GetValue($triggerID);' . "\n" . '$text = $triggerValue . " hat einen Alarm ausgelöst!";' . "\n" . '$duration = 30;' . "\n\n" . '//Keine Änderungen ab hier!' . "\n" . '$payload = [];' . "\n" . '$payload["icon"] = $icon;' . "\n" . '$payload["text"] = $text;' . "\n" . '$payload["duration"] = $duration;' . "\n" . 'AWT_SendNotification(' . $this->InstanceID . ', json_encode($payload));';

        $form['elements'][] =
            [
                'type'    => 'ExpansionPanel',
                'name'    => 'Panel6',
                'caption' => 'Notifications',
                'items'   => [
                    [
                        'type'     => 'List',
                        'name'     => 'Notifications',
                        'caption'  => 'Notifications: ' . $amountNotifications,
                        'rowCount' => $amountNotificationsRows,
                        'add'      => true,
                        'delete'   => true,
                        'sort'     => [
                            'column'    => 'Designation',
                            'direction' => 'descending'
                        ],
                        'columns' => [
                            [
                                'caption' => 'Aktiviert',
                                'name'    => 'Use',
                                'width'   => '100px',
                                'add'     => true,
                                'edit'    => [
                                    'type' => 'CheckBox'
                                ]
                            ],
                            [
                                'caption' => 'Bezeichnung',
                                'name'    => 'Designation',
                                'onClick' => $module['Prefix'] . '_ModifyListButton($id, "NotificationsConfigurationButton", $Notifications["PrimaryCondition"]);',
                                'width'   => '300px',
                                'add'     => '',
                                'edit'    => [
                                    'type' => 'ValidationTextBox'
                                ]
                            ],
                            [
                                'caption' => 'Mehrfachauslösung',
                                'name'    => 'UseMultipleAlerts',
                                'width'   => '180px',
                                'add'     => false,
                                'edit'    => [
                                    'type' => 'CheckBox'
                                ]
                            ],
                            [
                                'caption' => 'Primäre Bedingung',
                                'name'    => 'PrimaryCondition',
                                'width'   => '600px',
                                'add'     => '',
                                'visible' => false,
                                'edit'    => [
                                    'type' => 'SelectCondition'
                                ]
                            ],
                            [
                                'caption' => 'Weitere Bedingungen',
                                'name'    => 'SecondaryCondition',
                                'width'   => '600px',
                                'add'     => '',
                                'visible' => false,
                                'edit'    => [
                                    'type'  => 'SelectCondition',
                                    'multi' => true
                                ]
                            ],
                            [
                                'caption' => 'Benutze Skript',
                                'name'    => 'UseScript',
                                'width'   => '150px',
                                'add'     => true,
                                'edit'    => [
                                    'type' => 'CheckBox'
                                ]
                            ],
                            [
                                'caption'  => 'Skript',
                                'name'     => 'Script',
                                'width'    => '600px',
                                'add'      => $notificationsScript,
                                'edit'     => [
                                    'type' => 'ScriptEditor'
                                ]
                            ]
                        ],
                        'values' => $notificationsValues,
                        'form'   => [
                            [
                                'type'    => 'Label',
                                'caption' => 'Allgemein',
                                'bold'    => true,
                                'italic'  => true
                            ],
                            [
                                'type'    => 'CheckBox',
                                'name'    => 'Use',
                                'caption' => 'Aktiviert'
                            ],
                            [
                                'type'    => 'ValidationTextBox',
                                'name'    => 'Designation',
                                'caption' => 'Bezeichnung'
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => ' '
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => 'Auslöser',
                                'bold'    => true,
                                'italic'  => true
                            ],
                            [
                                'type'    => 'CheckBox',
                                'name'    => 'UseMultipleAlerts',
                                'caption' => 'Mehrfachauslösung'
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => ' '
                            ],
                            [
                                'type'    => 'SelectCondition',
                                'name'    => 'PrimaryCondition',
                                'caption' => 'Primäre Bedingung'
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => ' '
                            ],
                            [
                                'type'    => 'SelectCondition',
                                'name'    => 'SecondaryCondition',
                                'caption' => 'Weitere Bedingungen',
                                'multi'   => true
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => ' '
                            ],
                            [
                                'type'    => 'Label',
                                'caption' => 'Skript',
                                'bold'    => true,
                                'italic'  => true
                            ],
                            [
                                'type'    => 'CheckBox',
                                'name'    => 'UseScript',
                                'caption' => 'Benutze Skript'
                            ],
                            [
                                'type'     => 'ScriptEditor',
                                'name'     => 'Script',
                                'rowCount' => 15
                            ]
                        ]
                    ],
                    [
                        'type'     => 'OpenObjectButton',
                        'name'     => 'NotificationsConfigurationButton',
                        'caption'  => 'Bearbeiten',
                        'visible'  => false,
                        'objectID' => 0
                    ]
                ]
            ];

        ########## Actions

        //Control
        $form['actions'][] =
            [
                'type'    => 'Label',
                'caption' => 'Schaltelemente'
            ];

        $form['actions'][] =
            [
                'type' => 'TestCenter',
            ];

        $form['actions'][] =
            [
                'type'    => 'Label',
                'caption' => ' '
            ];

        //Developer area
        $form['actions'][] = [
            'type'    => 'ExpansionPanel',
            'caption' => 'Entwicklerbereich',
            'items'   => [
                [
                    'type'    => 'Label',
                    'caption' => 'Device',
                    'bold'    => true,
                    'italic'  => true
                ],
                [
                    'type'  => 'RowLayout',
                    'items' => [
                        [
                            'type'    => 'PopupButton',
                            'caption' => 'Neustart',
                            'popup'   => [
                                'caption' => 'Gerät wirklich neu starten?',
                                'items'   => [
                                    [
                                        'type'    => 'Button',
                                        'caption' => 'Neustart',
                                        'onClick' => $module['Prefix'] . '_RebootDevice($id);' . $module['Prefix'] . '_UIShowMessage($id, "Gerät wird neu gestartet!");'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type'    => 'Button',
                            'caption' => 'Webinterface',
                            'onClick' => 'echo "http://' . $this->ReadPropertyString('DeviceIP') . '";'
                        ],
                        [
                            'type'    => 'Button',
                            'caption' => 'Live View',
                            'onClick' => 'echo "http://' . $this->ReadPropertyString('DeviceIP') . '/screen";'
                        ]
                    ]
                ],
                [
                    'type'    => 'Label',
                    'caption' => ' '
                ],
                [
                    'type'    => 'Label',
                    'caption' => 'Apps',
                    'bold'    => true,
                    'italic'  => true
                ],
                [
                    'type'    => 'PopupButton',
                    'caption' => 'Anzeigen',
                    'popup'   => [
                        'caption' => 'Custom Apps',
                        'items'   => [
                            [
                                'type'     => 'List',
                                'name'     => 'ActualCustomApps',
                                'add'      => false,
                                'delete'   => true,
                                'onDelete' => $module['Prefix'] . '_DeleteCustomApp($id, $ActualCustomApps["Name"]);',
                                'rowCount' => 1,
                                'sort'     => [
                                    'column'    => 'Number',
                                    'direction' => 'ascending'
                                ],
                                'columns' => [
                                    [
                                        'name'    => 'Number',
                                        'caption' => 'Nummer',
                                        'width'   => '100px',
                                        'save'    => false
                                    ],
                                    [
                                        'name'    => 'Name',
                                        'caption' => 'Name',
                                        'width'   => '200px',
                                        'save'    => false
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'onClick' => $module['Prefix'] . '_GetActualCustomApps($id);'
                ],
                [
                    'type'    => 'Label',
                    'caption' => ' '
                ],
                [
                    'type'    => 'Label',
                    'caption' => 'Notifications',
                    'bold'    => true,
                    'italic'  => true
                ],
                [
                    'type'  => 'RowLayout',
                    'items' => [
                        [
                            'type'    => 'ValidationTextBox',
                            'name'    => 'Notification',
                            'caption' => 'Text',
                            'width'   => '600px',
                            'value'   => 'Dies ist ein Test!'
                        ],
                        [
                            'type'    => 'Button',
                            'caption' => 'Senden',
                            'onClick' => $module['Prefix'] . '_SendNotification($id, \'{"icon": 0, "text": "\' . $Notification . \'", "duration": 10}\');'
                        ]
                    ]
                ]
            ]
        ];

        //Dummy info message
        $form['actions'][] =
            [
                'type'    => 'PopupAlert',
                'name'    => 'InfoMessage',
                'visible' => false,
                'popup'   => [
                    'closeCaption' => 'OK',
                    'items'        => [
                        [
                            'type'    => 'Label',
                            'name'    => 'InfoMessageLabel',
                            'caption' => '',
                            'visible' => true
                        ]
                    ]
                ]
            ];

        ########## Status

        $form['status'][] = [
            'code'    => 101,
            'icon'    => 'active',
            'caption' => $module['ModuleName'] . ' wird erstellt',
        ];
        $form['status'][] = [
            'code'    => 102,
            'icon'    => 'active',
            'caption' => $module['ModuleName'] . ' ist aktiv',
        ];
        $form['status'][] = [
            'code'    => 103,
            'icon'    => 'active',
            'caption' => $module['ModuleName'] . ' wird gelöscht',
        ];
        $form['status'][] = [
            'code'    => 104,
            'icon'    => 'inactive',
            'caption' => $module['ModuleName'] . ' ist inaktiv',
        ];
        $form['status'][] = [
            'code'    => 200,
            'icon'    => 'inactive',
            'caption' => 'Es ist Fehler aufgetreten, weitere Informationen unter Meldungen, im Log oder Debug!',
        ];

        return json_encode($form);
    }
}