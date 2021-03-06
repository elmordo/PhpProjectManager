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

        // route save
        [
            "name" => "save",
            "description" => "Add all, commit and push all modules",
            "defaults" => [
                "controller" => "Multiroute",
                "action" => "dispatch",
                "actions" => [
                    [ "commit" ],
                    [ "push" ],
                ],
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "save"
                    ],
                ], // end argument save
            ], // end definition
        ], // end save

        // route update
        [
            "name" => "update",
            "description" => "Pull all data from the server and run mkassets",
            "defaults" => [
                "controller" => "Multiroute",
                "action" => "dispatch",
                "actions" => [
                    [ "pull" ],
                    [ "mkassets" ],
                ],
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "update"
                    ],
                ], // end argument save
            ], // end definition
        ], // end update

        // route module discover
        [
            "name" => "module discover",
            "description" => "Search for new modules (not registered in config)",
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

        // route module ignore
        [
            "name" => "module ignore [modulename]",
            "description" => "Add module to the ignore list (ignored by module discover)",
            "defaults" => [
                "controller" => "Module",
                "action" => "ignore"
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
                        "name" => "ignore"
                    ],
                ], // end argument ignore
                [
                    "type" => "positional",
                    "options" => [
                        "name" => "moduleName",
                        "mapping" => "moduleName",
                    ],
                ], // end argument moduleName
            ], // end definition
        ], // end module ignore

        // route module ignored
        [
            "name" => "module ignoreds",
            "description" => "Display list of ignored modules",
            "defaults" => [
                "controller" => "Module",
                "action" => "ignoreds"
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
                        "name" => "ignoreds"
                    ],
                ], // end argument ignoreds
            ], // end definition
        ], // end module ignore

        // route module unignore
        [
            "name" => "module unignore [modulename]",
            "description" => "Remove module from the ignore list",
            "defaults" => [
                "controller" => "Module",
                "action" => "unignore"
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
                        "name" => "unignore"
                    ],
                ], // end argument unignore
                [
                    "type" => "positional",
                    "options" => [
                        "name" => "moduleName",
                        "mapping" => "moduleName",
                    ],
                ], // end argument moduleName
            ], // end definition
        ], // end module unignore

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
        ], // end mkassets

        // route pull
        [
            "name" => "pull",
            "description" => "Pull data from server in main repo and all modules",
            "defaults" => [
                "controller" => "Vcs",
                "action" => "pullAll"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "pull"
                    ],
                ], // end argument pull
            ], // end definition
        ], // end pull

        // route commit
        [
            "name" => "commit",
            "description" => "Add and commit all changes in main repo and all modules",
            "defaults" => [
                "controller" => "Vcs",
                "action" => "commitAll"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "commit"
                    ],
                ], // end argument pull
            ], // end definition
        ], // end commit

        // route push
        [
            "name" => "push",
            "description" => "Push data to server in main repo and all modules",
            "defaults" => [
                "controller" => "Vcs",
                "action" => "pushAll"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "push"
                    ],
                ], // end argument pull
            ], // end definition
        ], // end push

        // route selfupdate
        [
            "name" => "selfupdate",
            "description" => "Update PhpProjectManager",
            "defaults" => [
                "controller" => "Application",
                "action" => "update"
            ],
            "definition" => [
                [
                    "type" => "static",
                    "options" => [
                        "name" => "selfupdate"
                    ],
                ], // end argument selfupdate
            ], // end definition
        ], // end selfupdate
    ],
    "resources" => [
        "defaultConfigs" => [
            "module" => __DIR__ . "/module.default.config.php"
        ]
    ],
];
