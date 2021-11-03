<?php

return [
  'handlers' => [
    // A list of handlers/providers that will be appended to existing list of handlers
    'custom' => [],
    // Overrides the list of handlers - use only what you really want
    'override' => [
        \App\Adapters\ResumableJSUploadHandler::class
    ],
  ],
];
