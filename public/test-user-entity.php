<?php
// Access: http://yourdomain.com/test-user-entity.php

require_once __DIR__ . '/../app/Config/Paths.php';
$paths = new Config\Paths();
require_once rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';

$app = \CodeIgniter\CodeIgniter::createApplication($paths);

echo "<h1>🔍 User Entity Test</h1>";

// Test 1: Check if UserModel is configured correctly
$userModel = model('UserModel');
echo "<h2>Test 1: UserModel ReturnType</h2>";

$reflection = new ReflectionClass($userModel);
$returnType = $reflection->getProperty('returnType');
$returnType->setAccessible(true);
$value = $returnType->getValue($userModel);

echo "UserModel::\$returnType = " . ($value ?: 'NOT SET') . "<br>";

if ($value === \App\Entities\User::class) {
    echo "<span style='color: green; font-size: 18px;'>✅ CORRECT! UserModel is set to use App\\Entities\\User</span><br>";
} else {
    echo "<span style='color: red; font-size: 18px;'>❌ WRONG! UserModel is set to: " . ($value ?: 'NOTHING') . "</span><br>";
    echo "Expected: App\\Entities\\User<br>";
}

// Test 2: Try to get a user
echo "<h2>Test 2: Actual User Object</h2>";
$user = $userModel->find(1);

if ($user) {
    echo "User class: " . get_class($user) . "<br>";
    
    if (get_class($user) === 'App\Entities\User') {
        echo "<span style='color: green; font-size: 18px;'>✅ SUCCESS! User is App\\Entities\\User</span><br>";
        
        $traits = class_uses($user);
        if (in_array('CodeIgniter\Shield\Authentication\Traits\HasRememberMe', $traits)) {
            echo "<span style='color: green;'>✅ HasRememberMe trait FOUND</span><br>";
            echo "<span style='color: green; font-size: 16px;'>🎉 REMEMBER ME WILL WORK NOW!</span><br>";
        } else {
            echo "<span style='color: red;'>❌ HasRememberMe trait MISSING in User entity</span><br>";
        }
    } else {
        echo "<span style='color: red; font-size: 18px;'>❌ FAILED! User is still " . get_class($user) . "</span><br>";
        echo "The returnType setting is NOT taking effect!<br>";
    }
} else {
    echo "No user found with ID 1<br>";
}