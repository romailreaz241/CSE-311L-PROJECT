<?php

require_once __DIR__ . '/../php/db.php';

require_once __DIR__ . '/support/http.php';
require_once __DIR__ . '/support/flash.php';
require_once __DIR__ . '/support/auth.php';

require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/AdminRepository.php';
require_once __DIR__ . '/repositories/TestRepository.php';
require_once __DIR__ . '/repositories/BookingRepository.php';

require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/CartService.php';
require_once __DIR__ . '/services/TestService.php';
require_once __DIR__ . '/services/BookingService.php';

require_once __DIR__ . '/views/NavigationView.php';
require_once __DIR__ . '/views/TestView.php';
require_once __DIR__ . '/views/AdminView.php';

require_once __DIR__ . '/handlers/AuthHandlers.php';
require_once __DIR__ . '/handlers/CartHandlers.php';
require_once __DIR__ . '/handlers/BookingHandlers.php';
require_once __DIR__ . '/handlers/AdminHandlers.php';
