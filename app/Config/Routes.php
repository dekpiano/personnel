<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'ConUserHome::index');
//User งานจองห้อง

$routes->get('/LoginOfficerPersonnel', 'ConLogin::LoginOfficerPersonnel');
//$routes->get('/LoginEoffice', 'ConUserHome::LoginEoffice');
$routes->get('/LogoutOfficerGeneral', 'ConLogin::LogoutOfficerGeneral');

//Admin
$routes->get('Admin/Home', 'ConAdminHome::index');
$routes->get('Admin/LocationRoom/LocationRoomMain', 'ConAdminLocationRoom::LocationRoomMain');

$routes->post('Admin/LocationRoom/Insert', 'ConAdminLocationRoom::LocationRoomInsert');
$routes->match(['get', 'post'],'Admin/LocationRoom/Delete', 'ConAdminLocationRoom::LocationRoomDelete');
$routes->match(['get', 'post'],'Admin/LocationRoom/ShowData', 'ConAdminLocationRoom::LocationRoomShowData');

$routes->get('Admin/Rloes/Setting', 'ConAdminRoles::index');
$routes->match(['get', 'post'],'Admin/Rloes/RloesSettingManager', 'ConAdminRoles::RloesSettingManager');

//Admin Person
$routes->get('Admin/WorkPerson/Personnel', 'ConAdminWorkPerson::index');
$routes->get('Admin/WorkPerson/Personnel/Add', 'ConAdminWorkPerson::FormAdd');
$routes->get('Admin/WorkPerson/Personnel/Group/(:any)', 'ConAdminWorkPerson::PersonneViewGroup/$1');
$routes->get('Admin/WorkPerson/Personnel/Update/(:any)', 'ConAdminWorkPerson::FormPersonneUpdate/$1');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/SortableTeacher', 'ConAdminWorkPerson::SortableTeacher');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Insert', 'ConAdminWorkPerson::PersonnelInsert');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/DataGeneral', 'ConAdminWorkPerson::PersonneUpdateDataGeneral');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/DataHistory', 'ConAdminWorkPerson::PersonneUpdateDataHistory');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/Img', 'ConAdminWorkPerson::PersonnelUpdateImg');

$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Get/(:any)', 'ConAdminWorkPerson::PersonnelGet/$1');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
