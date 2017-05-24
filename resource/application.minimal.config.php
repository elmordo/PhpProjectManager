<?php

return [
    "routes" => [
        // route app initialization
        [
            "name" => "init",
            "description" => "Initialize project in working directory",
            "defaults" => [
                "controller" => "Project",
                "action" => "init"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "init"
                    ],
                ], // end argument init
            ], // end definition
        ], // end route init

        // route help (by empty)
        [
            "name" => "",
            "defaults" => [
                "controller" => "Help",
                "action" => "index"
            ],
            "definition" => [
            ], // end definition
        ], // end help empty

        // route help (by help command)
        [
            "name" => "help",
            "description" => "Display this help.",
            "defaults" => [
                "controller" => "Help",
                "action" => "index"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "help"
                    ]
                ]
            ], // end definition
        ], // end help empty

    ],
];
