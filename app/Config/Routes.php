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

// route since we don't have to scan directories.
$routes->get('/', 'ConUserHome::index');

// ทำเนียบครูและบุคลากร (Public)
$routes->get('directory', 'ConUserDirectory::index');
$routes->get('directory/detail/(:segment)', 'ConUserDirectory::detail/$1');
$routes->get('directory/api/filter/(:segment)', 'ConUserDirectory::getByLearning/$1');
$routes->post('directory/save-profile', 'ConUserDirectory::saveProfile');

// PA Form routes
// PA Form routes
$routes->get('pa-personnel', 'ConUserPaEvaluation::paPersonnelList');
$routes->get('pa-form', 'ConUserPaEvaluation::paForm');
$routes->get('pa-form/(:segment)', 'ConUserPaEvaluation::paForm/$1');
$routes->post('user/pa-evaluation/save', 'ConUserPaEvaluation::savePaEvaluation');

//User งานจองห้อง

$routes->get('/LoginOfficerPersonnel', 'ConLogin::LoginOfficerPersonnel');
$routes->get('pa-login', 'ConLogin::paLogin');
$routes->post('login-pa-traditional', 'ConLogin::processTraditionalLogin');
//$routes->get('/LoginEoffice', 'ConUserHome::LoginEoffice');
$routes->get('/LogoutOfficerPersonnel', 'ConLogin::LogoutOfficerPersonnel');

//Admin
$routes->get('Admin/Home', 'ConAdminHome::index');
$routes->get('Admin/LocationRoom/LocationRoomMain', 'ConAdminLocationRoom::LocationRoomMain');

$routes->post('Admin/LocationRoom/Insert', 'ConAdminLocationRoom::LocationRoomInsert');
$routes->match(['get', 'post'],'Admin/LocationRoom/Delete', 'ConAdminLocationRoom::LocationRoomDelete');
$routes->match(['get', 'post'],'Admin/LocationRoom/ShowData', 'ConAdminLocationRoom::LocationRoomShowData');

$routes->get('Admin/Rloes/Setting', 'ConAdminRoles::index');
$routes->post('Admin/Rloes/UpdateUser', 'ConAdminRoles::RloesUpdateUser');
$routes->post('Admin/Rloes/AddUser', 'ConAdminRoles::RloesAddUser');
$routes->post('Admin/Rloes/DeleteUser', 'ConAdminRoles::RloesDeleteUser');

$routes->group('Admin', function ($routes) {
    //Admin Leave Management
    $routes->get('Leave', 'ConAdminLeave::index');
    $routes->get('Leave/Settings', 'ConAdminLeave::Settings');
    $routes->post('Leave/SaveType', 'ConAdminLeave::SaveLeaveType');
    $routes->get('Leave/DeleteType/(:num)', 'ConAdminLeave::DeleteLeaveType/$1');
    $routes->post('Leave/UpdateStatus', 'ConAdminLeave::UpdateStatus');
    $routes->get('Leave/Get/(:num)', 'ConAdminLeave::GetLeaveRequest/$1');

    // ระบบจัดการวันหยุด
    $routes->get('Holiday', 'ConAdminHoliday::index');
    $routes->post('Holiday/Save', 'ConAdminHoliday::Save');
    $routes->get('Holiday/Delete/(:num)', 'ConAdminHoliday::Delete/$1');

    // ระบบจัดการปีการศึกษาสำหรับการลา
    $routes->post('Leave/SaveYear', 'ConAdminLeave::SaveLeaveYear');
    $routes->get('Leave/SetActiveYear/(:num)', 'ConAdminLeave::SetActiveLeaveYear/$1');
    $routes->get('Leave/DeleteYear/(:num)', 'ConAdminLeave::DeleteLeaveYear/$1');
});
//Admin Person
$routes->get('Admin/WorkPerson/Personnel', 'ConAdminWorkPerson::index');
$routes->get('Admin/WorkPerson/Personnel/Add', 'ConAdminWorkPerson::FormAdd');
$routes->get('Admin/WorkPerson/Personnel/Group/(:any)', 'ConAdminWorkPerson::PersonneViewGroup/$1');
$routes->get('Admin/WorkPerson/Personnel/Update/(:any)', 'ConAdminWorkPerson::FormPersonneUpdate/$1');
$routes->get('Admin/WorkPerson/Personnel/PDF/(:any)', 'ConAdminWorkPerson::PersonnelPDF/$1');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/SortableTeacher', 'ConAdminWorkPerson::SortableTeacher');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Insert', 'ConAdminWorkPerson::PersonnelInsert');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/DataPersonnel', 'ConAdminWorkPerson::PersonneUpdateDataPersonnel');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/DataHistory', 'ConAdminWorkPerson::PersonneUpdateDataHistory');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/Img', 'ConAdminWorkPerson::PersonnelUpdateImg');

$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Get/(:any)', 'ConAdminWorkPerson::PersonnelGet/$1');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Update/Alone', 'ConAdminWorkPerson::PersonnelUpdateAlone');

$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Select/GetPositionData', 'ConAdminWorkPerson::GetPositionData');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/CleanupImages', 'ConAdminWorkPerson::CleanupImages');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Family/Add', 'ConAdminWorkPerson::PersonnelFamilyAdd');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Family/Delete', 'ConAdminWorkPerson::PersonnelFamilyDelete');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Education/Add', 'ConAdminWorkPerson::PersonnelEducationAdd');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Education/Delete', 'ConAdminWorkPerson::PersonnelEducationDelete');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Education/GetOptions', 'ConAdminWorkPerson::getEducationOptions');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/History/Add', 'ConAdminWorkPerson::PersonnelWorkHistoryAdd');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/History/Update', 'ConAdminWorkPerson::PersonnelWorkHistoryUpdate');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/History/Delete', 'ConAdminWorkPerson::PersonnelWorkHistoryDelete');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/License/Update', 'ConAdminWorkPerson::PersonnelLicenseUpdate');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Decoration/Add', 'ConAdminWorkPerson::PersonnelDecorationAdd');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Decoration/Delete', 'ConAdminWorkPerson::PersonnelDecorationDelete');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Training/Add', 'ConAdminWorkPerson::PersonnelTrainingAdd');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Training/Delete', 'ConAdminWorkPerson::PersonnelTrainingDelete');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Leave/Add', 'ConAdminWorkPerson::PersonnelLeaveAdd');
$routes->match(['get', 'post'],'Admin/WorkPerson/Personnel/DB/Leave/Delete', 'ConAdminWorkPerson::PersonnelLeaveDelete');

// Document Management
$routes->post('Admin/WorkPerson/Personnel/DB/Document/Upload', 'ConAdminWorkPerson::PersonnelDocUpload');
$routes->get('Admin/WorkPerson/Personnel/DB/Document/View/(:num)', 'ConAdminWorkPerson::PersonnelDocView/$1');
$routes->post('Admin/WorkPerson/Personnel/DB/Document/Delete', 'ConAdminWorkPerson::PersonnelDocDelete');
$routes->get('Admin/WorkPerson/Personnel/DB/Document/List/(:segment)', 'ConAdminWorkPerson::PersonnelDocList/$1');

// Attendance Summary for Personnel (with date range)
$routes->get('Admin/WorkPerson/Personnel/DB/Attendance/Summary/(:segment)', 'ConAdminWorkPerson::PersonnelAttendanceSummary/$1');

$routes->get('Admin/SaveAttendance', 'ConAdminSaveAttendance::index');
$routes->match(['get', 'post'],'Admin/SaveAttendance/DB/Select/GetPersonnalData', 'ConAdminSaveAttendance::GetPersonnalData');
$routes->match(['get', 'post'],'Admin/SaveAttendance/DB/Select/GetAttendanceToDate', 'ConAdminSaveAttendance::GetAttendanceToDate');
$routes->match(['get', 'post'],'Admin/SaveAttendance/DB/Select/SaveAttendanceToDB', 'ConAdminSaveAttendance::SaveAttendanceToDB');
$routes->match(['get', 'post'],'Admin/SaveAttendance/DB/Select/DashboardAttendance', 'ConAdminSaveAttendance::DashboardAttendance');
$routes->match(['get', 'post'],'Admin/SaveAttendance/DB/Select/LeaveSummary', 'ConAdminSaveAttendance::GetLeaveSummary');
$routes->get('Admin/SaveAttendance/DB/Select/leaveSummaryByPositionDay', 'ConAdminSaveAttendance::leaveSummaryByPositionDay');
$routes->post('Admin/SaveAttendance/UploadExcel', 'ConAdminSaveAttendance::UploadExcel');

$routes->get('Admin/SaveAttendance/SetupFingerprint', 'ConAdminSaveAttendance::SetupFingerprint');
$routes->post('Admin/SaveAttendance/SetupFingerprint/Save', 'ConAdminSaveAttendance::SaveFingerprint');

$routes->get('Admin/SaveAttendance/SetupTime', 'ConAdminSaveAttendance::SetupTime');
$routes->post('Admin/SaveAttendance/SetupTime/Save', 'ConAdminSaveAttendance::SaveTimeConfig');
$routes->get('Admin/SaveAttendance/DB/Select/GetTimeConfigs', 'ConAdminSaveAttendance::GetTimeConfigs');

// Admin PA Config routes
$routes->get('Admin/PaConfig', 'ConAdminPaConfig::index');
$routes->post('Admin/PaConfig/save', 'ConAdminPaConfig::saveScope');
$routes->get('Admin/PaConfig/delete/(:num)', 'ConAdminPaConfig::deleteScope/$1');
$routes->post('Admin/PaConfig/addEvaluator', 'ConAdminPaConfig::addEvaluator');
$routes->post('Admin/PaConfig/updateEvaluator', 'ConAdminPaConfig::updateEvaluator');
$routes->get('Admin/PaConfig/deleteEvaluator/(:segment)', 'ConAdminPaConfig::deleteEvaluator/$1');
$routes->get('Admin/PaConfig/Rubrics', 'ConAdminPaConfig::rubricItems');
$routes->post('Admin/PaConfig/addRubricItem', 'ConAdminPaConfig::addRubricItem');
$routes->post('Admin/PaConfig/updateRubricItem', 'ConAdminPaConfig::updateRubricItem');
$routes->post('Admin/PaConfig/deleteRubricItem', 'ConAdminPaConfig::deleteRubricItem');
$routes->get('Admin/PaReport', 'ConAdminPaConfig::report');
$routes->get('Admin/PaEvaluation/Scores/(:segment)/(:segment)', 'ConAdminPaConfig::getScores/$1/$2');




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
