<?php

return [
    "io" => [
        "adapter" => "\PPM\IO\Adapter\Console",
    ],
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

        // route module discover
        [
            "name" => "module discover",
            "description" => "Initialize project in working directory",
            "defaults" => [
                "controller" => "Module",
                "action" => "discover"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "module"
                    ],
                ], // end argument module
                [
                    "type" => "static",
                    "options" => [
                        "name" => "discover"
                    ],
                ], // end argument discover
            ], // end definition
        ], // end module discover

        // route mkassets
        [
            "name" => "mkassets",
            "description" => "Make symlinks of module assets to assets dir",
            "defaults" => [
                "controller" => "Asset",
                "action" => "build"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "mkassets"
                    ],
                ], // end argument mkassets
            ], // end definition
        ], // end module discover

    ],
];
