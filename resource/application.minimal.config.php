<?php

return [
    "routes" => [
        // app initialization
        [
            "name" => "init",
            "defaults" => [
                "controller" => "project",
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
    ],
];
