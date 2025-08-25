<?php

echo "🔍 Checking Server Requirements for Canting Food Laravel Project\n\n";

$requirements = [
    'PHP Version' => [
        'required' => '8.0.2',
        'current' => PHP_VERSION,
        'status' => version_compare(PHP_VERSION, '8.0.2', '>=')
    ],
    'ext-exif' => [
        'required' => 'Enabled',
        'current' => extension_loaded('exif') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('exif')
    ],
    'ext-http' => [
        'required' => 'Enabled',
        'current' => extension_loaded('http') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('http')
    ],
    'ext-json' => [
        'required' => 'Enabled',
        'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('json')
    ],
    'ext-pdo' => [
        'required' => 'Enabled',
        'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('pdo')
    ],
    'ext-mbstring' => [
        'required' => 'Enabled',
        'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('mbstring')
    ],
    'ext-openssl' => [
        'required' => 'Enabled',
        'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('openssl')
    ],
    'ext-tokenizer' => [
        'required' => 'Enabled',
        'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('tokenizer')
    ],
    'ext-xml' => [
        'required' => 'Enabled',
        'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('xml')
    ],
    'ext-ctype' => [
        'required' => 'Enabled',
        'current' => extension_loaded('ctype') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('ctype')
    ],
    'ext-fileinfo' => [
        'required' => 'Enabled',
        'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('fileinfo')
    ],
    'ext-bcmath' => [
        'required' => 'Enabled',
        'current' => extension_loaded('bcmath') ? 'Enabled' : 'Disabled',
        'status' => extension_loaded('bcmath')
    ]
];

$allPassed = true;

foreach ($requirements as $requirement => $details) {
    $status = $details['status'] ? '✅ PASS' : '❌ FAIL';
    $allPassed = $allPassed && $details['status'];
    
    echo sprintf("%-20s | %-10s | %-10s | %s\n", 
        $requirement, 
        $details['required'], 
        $details['current'], 
        $status
    );
}

echo "\n" . str_repeat('-', 60) . "\n";

if ($allPassed) {
    echo "🎉 All requirements passed! Your server is ready for deployment.\n";
} else {
    echo "⚠️  Some requirements failed. Please fix them before deployment.\n";
}

echo "\n📋 Additional Checks:\n";

$additionalChecks = [
    'Composer' => shell_exec('composer --version 2>/dev/null') ? 'Available' : 'Not Available',
    'Node.js' => shell_exec('node --version 2>/dev/null') ? 'Available' : 'Not Available',
    'NPM' => shell_exec('npm --version 2>/dev/null') ? 'Available' : 'Not Available',
    'Git' => shell_exec('git --version 2>/dev/null') ? 'Available' : 'Not Available'
];

foreach ($additionalChecks as $tool => $status) {
    echo sprintf("%-20s | %s\n", $tool, $status);
}

echo "\n🚀 Ready for deployment!\n";
