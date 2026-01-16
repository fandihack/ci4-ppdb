<?php

namespace Config;

use CodeIgniter\Config\Routing\Router;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/ranking', 'Ranking::index');
$routes->get('/ranking/(:any)', 'Ranking::index/$1');
$routes->get('/tracking', 'Tracking::index');
$routes->post('/tracking/check', 'Tracking::check');

$routes->get('/register', 'Register::index');
$routes->post('/register/submit', 'Register::submit');
$routes->post('/register/check-nisn', 'Register::checkNISN');

$routes->get('/admin', 'Admin::index'); // LOGIN → TANPA FILTER
$routes->post('/admin/login', 'Admin::login');
$routes->get('/admin/logout', 'Admin::logout');

$routes->get('/admin/dashboard', 'Admin::dashboard', ['filter' => 'auth']);
$routes->post('/admin/add-student', 'Admin::addStudent', ['filter' => 'auth']);
$routes->get('/admin/reset-data', 'Admin::resetData', ['filter' => 'auth']);
$routes->get('/admin/failed-verifications', 'Admin::failedVerifications', ['filter' => 'auth']);
$routes->post('/admin/run-selection', 'Admin::runSelection', ['filter' => 'auth']);

$routes->cli('seed/dummy', 'Admin::seedDummy');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}