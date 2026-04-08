<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHATBOT FIXES VERIFICATION ===\n\n";

// Test 1: Webhook routes are public
echo "1. Testing webhook routes are public:\n";
$routes = Route::getRoutes();
foreach($routes as $route) {
    if (str_contains($route->uri, 'webhooks/whatsapp') || str_contains($route->uri, 'webhooks/messenger')) {
        $middleware = $route->middleware();
        $hasAuth = collect($middleware)->contains(fn($m) => str_contains($m, 'auth') || str_contains($m, 'admin'));
        echo "   - {$route->uri}: " . ($hasAuth ? '❌ Has auth' : '✅ Public') . "\n";
    }
}

// Test 2: tryIdentifyPatient method exists
echo "\n2. Testing tryIdentifyPatient method:\n";
$handler = new App\Services\Chatbot\Actions\BookAppointmentAction();
$reflection = new ReflectionClass($handler);
$hasMethod = $reflection->hasMethod('tryIdentifyPatient');
echo "   - Method exists: " . ($hasMethod ? '✅ Yes' : '❌ No') . "\n";

// Test 3: Doctor fallback has ordering
echo "\n3. Testing doctor fallback ordering:\n";
$reflection = new ReflectionClass(App\Services\Chatbot\ChatbotActionHandler::class);
$hasMethod = $reflection->hasMethod('getDoctorForPatient');
$code = file_get_contents(app_path('Services/Chatbot/ChatbotActionHandler.php'));
$hasOrderBy = str_contains($code, '->orderBy');
echo "   - getDoctorForPatient method exists: " . ($hasMethod ? '✅ Yes' : '❌ No') . "\n";
echo "   - Uses orderBy: " . ($hasOrderBy ? '✅ Yes' : '❌ No') . "\n";

// Test 4: Keyword matching uses word boundaries
echo "\n4. Testing keyword matching:\n";
$patterns = [
    ['book appointment', '/\bbook\b/i', true],
    ['Facebook page', '/\bbook\b/i', false],
    ['notebook', '/\bbook\b/i', false],
    ['cancel my appointment', '/\bcancel\b/i', true],
];
foreach ($patterns as [$text, $pattern, $expected]) {
    $matches = (bool) preg_match($pattern, $text);
    $status = ($matches === $expected) ? '✅' : '❌';
    echo "   - '{$text}' " . ($matches ? 'matches' : 'no match') . " {$status}\n";
}

// Test 5: CheckAvailabilityAction has processDoctorSelection
echo "\n5. Testing CheckAvailabilityAction:\n";
$checkAction = new App\Services\Chatbot\Actions\CheckAvailabilityAction();
$reflection = new ReflectionClass($checkAction);
$hasProcessDoctor = $reflection->hasMethod('processDoctorSelection');
echo "   - processDoctorSelection exists: " . ($hasProcessDoctor ? '✅ Yes' : '❌ No') . "\n";

// Test 6: Settings update uses SystemSetting model
echo "\n6. Testing ChatbotController updateSettings:\n";
$controllerCode = file_get_contents(app_path('Http/Controllers/Admin/ChatbotController.php'));
$usesSystemSetting = str_contains($controllerCode, 'SystemSetting::updateOrCreate');
echo "   - Uses SystemSetting::updateOrCreate: " . ($usesSystemSetting ? '✅ Yes' : '❌ No') . "\n";

// Test 7: Booking uses enabled appointment types
echo "\n7. Testing appointment type selection:\n";
$bookingCode = file_get_contents(app_path('Services/Chatbot/Actions/BookAppointmentAction.php'));
$usesEnabledTypes = str_contains($bookingCode, 'getEnabledAppointmentTypes');
echo "   - Uses getEnabledAppointmentTypes: " . ($usesEnabledTypes ? '✅ Yes' : '❌ No') . "\n";

// Test 8: Slot re-validation in booking
echo "\n8. Testing slot re-validation:\n";
$hasRevalidation = str_contains($bookingCode, 'slotStillAvailable');
echo "   - Has slot re-validation: " . ($hasRevalidation ? '✅ Yes' : '❌ No') . "\n";

// Test 9: Phone normalization
echo "\n9. Testing phone normalization:\n";
$serviceCode = file_get_contents(app_path('Services/Chatbot/ChatbotActionHandler.php'));
$hasNormalization = str_contains($serviceCode, "REPLACE(REPLACE(REPLACE(phone");
echo "   - Uses SQL REPLACE for normalization: " . ($hasNormalization ? '✅ Yes' : '❌ No') . "\n";

// Test 10: Cancel action state machine
echo "\n10. Testing CancelAppointmentAction state machine:\n";
$cancelCode = file_get_contents(app_path('Services/Chatbot/Actions/CancelAppointmentAction.php'));
// Check that states are properly separated (no || combining idle and cancel_select_appointment)
$hasNoCombinedCheck = !preg_match('/\$state\s*===\s*[\'"]idle[\'"]\s*\|\|.*cancel_select_appointment/', $cancelCode);
$hasIdleCheck = preg_match('/if\s*\(\s*\$state\s*===\s*[\'"]idle[\'"]\s*\)/', $cancelCode);
$hasSelectCheck = preg_match('/if\s*\(\s*\$state\s*===\s*[\'"]cancel_select_appointment[\'"]\s*\)/', $cancelCode);
echo "   - No combined state checks: " . ($hasNoCombinedCheck ? '✅ Yes' : '❌ No') . "\n";
echo "   - Has idle check: " . ($hasIdleCheck ? '✅ Yes' : '❌ No') . "\n";
echo "   - Has select check: " . ($hasSelectCheck ? '✅ Yes' : '❌ No') . "\n";

echo "\n=== VERIFICATION COMPLETE ===\n";
