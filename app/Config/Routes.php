<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'SiteController::index');
$routes->post('/contact', 'SiteController::submit');
$routes->get('/subscribe-now/(:num)/(:num)', 'SiteController::subscribeNow');
// Authentication routes (provided by Shield)
service('auth')->routes($routes);

// Protected dashboard routes (require login)

$routes->group('', ['filter' => 'auth'], function($routes) {

    // ======================== DASHBOARD & PROFILE ========================
    $routes->get('/dashboard', 'DashboardController::dashboard');  //, ['filter' => 'customPermission:/dashboard,' . PERMISSION_VIEW] 
    $routes->get('/profile', 'DashboardController::userProfile', ['filter' => 'customPermission:/profile,' . PERMISSION_VIEW]);
    $routes->post('profile/update', 'DashboardController::updateProfile', ['filter' => 'customPermission:/profile,' . PERMISSION_EDIT]);
    $routes->post('get-cities-by-state', 'DashboardController::getCitiesByState', ['filter' => 'customPermission:/profile,' . PERMISSION_EDIT]);

    // ======================== SCHOOLS ========================
    
    $routes->match(['GET','POST'], '/schools', 'SchoolController::getSchools', ['filter' => 'customPermission:/schools,' . PERMISSION_VIEW]);
    //$routes->match(['GET', 'POST'], '/schools', 'SchoolController::getSchools', ['filter' => 'customPermission:/schools,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], 'schoolAdd', 'SchoolController::addSchool', ['filter' => 'customPermission:/schools,' . PERMISSION_CREATE]);
    $routes->match(['GET', 'POST'], 'schools/edit/(:num)', 'SchoolController::addSchool/$1', ['filter' => 'customPermission:/schools,' . PERMISSION_EDIT]);
    $routes->post('schools/save', 'SchoolController::saveSchool', ['filter' => 'customPermission:/schools,' . PERMISSION_EDIT]);
    
    // ======================== MASTERS (All lists) ========================
    
    $routes->match(['GET', 'POST'], '/paymentModes', 'MasterController::getpaymentModes', ['filter' => 'customPermission:/paymentModes,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/staffType', 'MasterController::getStaffType', ['filter' => 'customPermission:/staffType,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/staffRoles', 'MasterController::getStaffRoles', ['filter' => 'customPermission:/staffRoles,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/teacherSecializationSubject', 'MasterController::getTeacherSecializationSubject', ['filter' => 'customPermission:/teacherSecializationSubject,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/employementType', 'MasterController::getEmployementTypes', ['filter' => 'customPermission:/employementType,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/classSections', 'MasterController::getClassSections', ['filter' => 'customPermission:/classSections,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/typeSchool', 'MasterController::getTypeSchool', ['filter' => 'customPermission:/typeSchool,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/schoolTradition', 'MasterController::getSchoolTradition', ['filter' => 'customPermission:/schoolTradition,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/schoolMedium', 'MasterController::getSchoolMedium', ['filter' => 'customPermission:/schoolMedium,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/schoolEducationLevel', 'MasterController::getSchoolEducationLevel', ['filter' => 'customPermission:/schoolEducationLevel,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/teachersDegree', 'MasterController::getTeachersDegree', ['filter' => 'customPermission:/teachersDegree,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/schoolAffilation', 'MasterController::getSchoolAffilation', ['filter' => 'customPermission:/schoolAffilation,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/cities', 'MasterController::getCities', ['filter' => 'customPermission:/cities,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/subjects', 'MasterController::getSubjects', ['filter' => 'customPermission:/subjects,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/sectionType', 'MasterController::getSectionType', ['filter' => 'customPermission:/sectionType,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/specialSectionCategory', 'MasterController::getSpecialSectionCategory', ['filter' => 'customPermission:/specialSectionCategory,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/casteCategory', 'MasterController::getCasteCategory', ['filter' => 'customPermission:/casteCategory,' . PERMISSION_VIEW]);

    // ======================== SCHOOL TIME SLOTS & SHIFT ========================
    $routes->match(['GET', 'POST'], '/school-time-slots', 'MasterController::getSchoolsTimeSlots', ['filter' => 'customPermission:/school-time-slots,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/allSchoolTimeSlots', 'MasterController::getAllSchoolsTimeSlots', ['filter' => 'customPermission:/allSchoolTimeSlots,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/allSchoolShift', 'MasterController::getAllSchoolShift', ['filter' => 'customPermission:/allSchoolShift,' . PERMISSION_VIEW]);
    // ======================== COMMON CHANGE STATUS (used by all masters) ========================
    $routes->post('changeStatus', 'MasterController::updateStatus', [
        'filter' => 'customPermission:/masters,' . PERMISSION_EDIT   // ← Change to your actual master menu if you have one
        // OR you can remove this line if you want to handle it only in controller
    ]);
    // ======================== PLANS ========================
    $routes->match(['GET', 'POST'], '/plans', 'PlanController::getPlans', ['filter' => 'customPermission:/plans,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], 'planAdd', 'PlanController::addPlans', ['filter' => 'customPermission:/plans,' . PERMISSION_CREATE]);
    $routes->match(['GET', 'POST'], 'plans/edit/(:num)', 'PlanController::addPlans/$1', ['filter' => 'customPermission:/plans,' . PERMISSION_EDIT]);
    $routes->post('plans/save', 'PlanController::savePlan', ['filter' => 'customPermission:/plans,' . PERMISSION_EDIT]);
    $routes->match(['GET', 'POST'], '/planType', 'PlanController::getPlanType', ['filter' => 'customPermission:/planType,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/planTenure', 'PlanController::getPlanTenure', ['filter' => 'customPermission:/planTenure,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/planServices', 'PlanController::getPlanServices', ['filter' => 'customPermission:/planServices,' . PERMISSION_VIEW]);
    // ======================== EMPLOYEES ========================
    $routes->match(['GET', 'POST'], '/employeesList', 'EmployeeController::getEmplyees', ['filter' => 'customPermission:/employees,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/employeeAdd', 'EmployeeController::addEmployee', ['filter' => 'customPermission:/employees,' . PERMISSION_CREATE]);
    $routes->match(['GET', 'POST'], 'employee/create', 'EmployeeController::create', ['filter' => 'customPermission:/employees,' . PERMISSION_CREATE]);
    $routes->match(['GET', 'POST'], 'employee/edit/(:num)', 'EmployeeController::addEmployee/$1', ['filter' => 'customPermission:/employees,' . PERMISSION_EDIT]);
    $routes->post('employee/reset-password', 'EmployeeController::resetPassword', ['filter' => 'customPermission:/employees,' . PERMISSION_EDIT]);
    $routes->post('employee/delete/(:num)', 'EmployeeController::deleteUser/$1', ['filter' => 'customPermission:/employees,' . PERMISSION_DELETE]);
    $routes->match(['GET', 'POST'], '/user_menu_access/(:num)', 'EmployeeController::assign_user_menu_access/$1', ['filter' => 'customPermission:/employees,' . PERMISSION_EDIT]);
    $routes->post('user_menu_access/save', 'EmployeeController::saveUserMenuAccess', ['filter' => 'customPermission:/employees,' . PERMISSION_EDIT]);
    $routes->match(['GET', 'POST'], '/schoolStaffList', 'EmployeeController::getSchoolStaff', ['filter' => 'customPermission:/employees,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/teachers', 'EmployeeController::getTeachers', ['filter' => 'customPermission:/teachers,' . PERMISSION_VIEW]);
    $routes->match(['GET', 'POST'], '/addTeacher', 'EmployeeController::addTeachers', ['filter' => 'customPermission:/teachers,' . PERMISSION_CREATE]);
    $routes->match(['GET', 'POST'], 'teacher/create', 'EmployeeController::createTeacher', ['filter' => 'customPermission:/teachers,' . PERMISSION_CREATE]);
    $routes->match(['GET', 'POST'], 'teacher/edit/(:num)', 'EmployeeController::addTeachers/$1', ['filter' => 'customPermission:/teachers,' . PERMISSION_EDIT]);
    $routes->post('teacher/delete/(:num)', 'EmployeeController::deleteUser/$1', ['filter' => 'customPermission:/teachers,' . PERMISSION_DELETE]);
});

$routes->group('menus', ['filter' => 'auth'], function($routes) {
    
    // ======================== MENUS ========================
    $routes->get('/', 'MenusController::index', ['filter' => 'customPermission:/menus,' . PERMISSION_VIEW]);                                      // List
    $routes->get('create', 'MenusController::create', ['filter' => 'customPermission:/menus,' . PERMISSION_CREATE]);                              // Add View
    $routes->post('store', 'MenusController::save', ['filter' => 'customPermission:/menus,' . PERMISSION_CREATE]);                                // Create
    $routes->post('store/(:num)', 'MenusController::save/$1', ['filter' => 'customPermission:/menus,' . PERMISSION_EDIT]);                        // Update
    $routes->get('edit/(:num)', 'MenusController::edit/$1', ['filter' => 'customPermission:/menus,' . PERMISSION_EDIT]);                          // Edit View
    $routes->get('delete/(:num)', 'MenusController::delete/$1', ['filter' => 'customPermission:/menus,' . PERMISSION_DELETE]);                    // Delete
    $routes->post('get-priorities', 'MenusController::get_priorities', ['filter' => 'customPermission:/menus,' . PERMISSION_VIEW]);               // AJAX - View level
    
    // AJAX - Reorder (modifying data = EDIT)
    $routes->post('update-sequence', 'MenusController::updateSequence', [
        'as'     => 'menus.update-sequence',
        'filter' => 'customPermission:/menus,' . PERMISSION_EDIT
    ]);

    // Change Status (modifying data = EDIT)
    $routes->post('changeStatus', 'MenusController::updateStatus', ['filter' => 'customPermission:/menus,' . PERMISSION_EDIT]);

    // RESTful presenter
    $routes->presenter('api', ['controller' => 'MenusController', 'only' => ['index', 'show', 'create', 'update', 'delete']]);
});

//FOR USER GROUPS

$routes->group('userGroups', ['filter' => 'auth'], function($routes) {

    // ======================== USER GROUPS ========================
    $routes->match(['GET', 'POST'], '/', 'MasterController::getUserGroups', ['filter' => 'customPermission:/userGroups,' . PERMISSION_VIEW]);                                           // List
    $routes->match(['GET', 'POST'], 'userGroupAdd', 'MasterController::addUserGroup', ['filter' => 'customPermission:/userGroups,' . PERMISSION_CREATE]);                               // Add
    $routes->match(['GET', 'POST'], 'edit/(:num)', 'MasterController::addUserGroup/$1', ['filter' => 'customPermission:/userGroups,' . PERMISSION_EDIT]);                               // Edit
    $routes->match(['GET', 'POST'], 'create', 'MasterController::saveUserGroup', ['filter' => 'customPermission:/userGroups,' . PERMISSION_CREATE]);                                    // Create/Save New

    // Menu Access Assignment (viewing/editing access = EDIT level)
    $routes->match(['GET', 'POST'], 'user_type_menu_access/(:num)', 'MasterController::assign_user_type_menu_access/$1', ['filter' => 'customPermission:/userGroups,' . PERMISSION_EDIT]); // View/Edit Menu Access
    $routes->post('user_type_menu_access/save', 'MasterController::saveUserTypeMenuAccess', ['filter' => 'customPermission:/userGroups,' . PERMISSION_EDIT]);                           // Save Menu Access
    $routes->post('user_type_menu_access/save/(:num)', 'MasterController::saveUserTypeMenuAccess/$1', ['filter' => 'customPermission:/userGroups,' . PERMISSION_EDIT]);                // Save Menu Access (with ID)
});

//FOR ENQUIRY REGISTRATION 

$routes->group('enquiry', ['filter' => 'auth'], function($routes) {
    // ======================== ENQUIRY ========================
    $routes->get('list', 'EnquiryController::listEnquiries', ['filter' => 'customPermission:/enquiry,' . PERMISSION_VIEW]);                          // List
    $routes->match(['GET', 'POST'], '/', 'EnquiryController::addEnquiry', ['filter' => 'customPermission:/enquiry,' . PERMISSION_CREATE]);            // Add
    $routes->match(['GET', 'POST'], 'edit/(:num)', 'EnquiryController::addEnquiry/$1', ['filter' => 'customPermission:/enquiry,' . PERMISSION_EDIT]); // Edit
    $routes->match(['GET', 'POST'], 'create', 'EnquiryController::saveEnquiry', ['filter' => 'customPermission:/enquiry,' . PERMISSION_CREATE]);      // Create/Save New
});


//FOR CLASSESS

$routes->group('classes', ['filter' => 'auth'], function($routes) {

    // ======================== CLASSES ========================
    $routes->get('/', 'ClassesController::classesList', ['filter' => 'customPermission:/classes,' . PERMISSION_VIEW]);                                                                        // List
    $routes->match(['GET', 'POST'], 'addSection/', 'ClassesController::addSection', ['filter' => 'customPermission:/classes,' . PERMISSION_CREATE]);                                          // Add
    $routes->match(['GET', 'POST'], 'edit/(:num)', 'ClassesController::addSection/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_EDIT]);                                         // Edit
    $routes->match(['GET', 'POST'], 'saveSection/', 'ClassesController::saveSectionDetails', ['filter' => 'customPermission:/classes,' . PERMISSION_CREATE]);                                 // Create/Save New

    // ======================== CLASS TEACHERS ========================
    $routes->match(['GET', 'POST'], 'teachers/(:num)', 'ClassesController::getClassTeachersList/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_VIEW]);                           // List
    $routes->match(['GET', 'POST'], 'addTeachers/(:num)', 'ClassesController::addClassTeachers/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_EDIT]);                            // Add/Assign Teacher

    // ======================== CLASS SCHEDULE ========================
    $routes->match(['GET', 'POST'], 'classSchedule/(:num)', 'ClassesController::getClassScheduleList/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_VIEW]);                     // List
    $routes->match(['GET', 'POST'], 'addClassSchedule/(:num)', 'ClassesController::addClassSchedule/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_EDIT]);                      // Add/Edit Schedule

    // ======================== ATTENDANCE ========================
    $routes->match(['GET', 'POST'], 'classAttendance/(:num)', 'ClassesController::getClassAttendanceList/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_VIEW]);                 // List
    $routes->match(['GET', 'POST'], 'addClassAttendance/(:num)', 'ClassesController::addClassAttendance/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_EDIT]);                  // Add/Edit Attendance

    // ======================== ASSIGNMENTS ========================
    $routes->match(['GET', 'POST'], 'classAssignments/(:num)', 'ClassesController::getClassAssignmentsList/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_VIEW]);               // List
    $routes->match(['GET', 'POST'], 'addClassAssignments/(:num)', 'ClassesController::addClassAssignments/$1', ['filter' => 'customPermission:/classes,' . PERMISSION_EDIT]);                // Add/Edit Assignments
});

$routes->group('class-teachers', ['filter' => 'auth'], function($routes) {
    // ======================== CLASS TEACHERS ========================
    $routes->get('/', 'ClassTeachersController::list', ['filter' => 'customPermission:/class-teachers,' . PERMISSION_VIEW]);                                         // List
    $routes->get('add/', 'ClassTeachersController::add', ['filter' => 'customPermission:/class-teachers,' . PERMISSION_CREATE]);                                     // Add View
    $routes->get('add/(:num)', 'ClassTeachersController::add/$1', ['filter' => 'customPermission:/class-teachers,' . PERMISSION_EDIT]);                              // Edit View
    $routes->get('get-sections/(:num)/(:num)/', 'ClassTeachersController::getSectionsByClass/$1/$2', ['filter' => 'customPermission:/class-teachers,' . PERMISSION_VIEW]); // AJAX - Get Sections
    $routes->post('save', 'ClassTeachersController::save', ['filter' => 'customPermission:/class-teachers,' . PERMISSION_EDIT]);                                     // Save (Create/Update)
    $routes->get('delete/(:num)', 'ClassTeachersController::delete/$1', ['filter' => 'customPermission:/class-teachers,' . PERMISSION_DELETE]);                      // Delete
});

//=== FOR CLASS SCHEDULES ===

$routes->group('class-schedules', ['filter' => 'auth'], function($routes) {

    // ======================== CLASS SCHEDULES ========================
    $routes->get('/', 'ClassScheduleController::list', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_VIEW]);                                                               // List
    $routes->match(['GET', 'POST'], 'add', 'ClassScheduleController::add', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_CREATE]);                                        // Add View
    $routes->match(['GET', 'POST'], 'add/(:num)', 'ClassScheduleController::add/$1', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_EDIT]);                                // Edit View

    // ======================== AJAX HELPERS ========================
    $routes->get('get-sections/(:num)/(:num)', 'ClassTeachersController::getSectionsByClass/$1/$2', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_VIEW]);                 // AJAX - Get Sections
    $routes->get('get-subjects/(:num)', 'ClassScheduleController::getSubjectsByClass/$1', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_VIEW]);                           // AJAX - Get Subjects
    $routes->post('get-available-teachers', 'ClassScheduleController::getAvailableTeachers', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_VIEW]);                        // AJAX - Get Available Teachers

    // ======================== SAVE & DELETE ========================
    $routes->post('save', 'ClassScheduleController::save', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_EDIT]);                                                          // Save (Create/Update)
    $routes->get('delete/(:num)', 'ClassScheduleController::delete/$1', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_DELETE]);                                           // Delete

    // ======================== TIMETABLE ========================
    $routes->get('timetable', 'ClassScheduleController::timetable', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_VIEW]);                                                 // Timetable View
    $routes->get('timetable/(:num)/(:num)', 'ClassScheduleController::timetable/$1/$2', ['filter' => 'customPermission:/class-schedules,' . PERMISSION_VIEW]);                             // Timetable View (Filtered)
});

// $routes->group('attendance', ['filter' => 'auth'], function($routes) {
//     $routes->get('mark', 'AttendanceController::markAttendance');
//     $routes->post('save', 'AttendanceController::saveAttendance');
//     $routes->post('get-students', 'AttendanceController::getStudentsForAttendance');
//     $routes->get('report', 'AttendanceController::getAttendanceReport');
// });


$routes->group('class-assignments', ['filter' => 'auth'], function($routes) {

    // ======================== CLASS ASSIGNMENTS ========================
    $routes->get('/', 'ClassAssignmentsController::list', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_VIEW]);                                                          // List
    $routes->match(['GET', 'POST'], 'add', 'ClassAssignmentsController::add', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_CREATE]);                                   // Add View
    $routes->match(['GET', 'POST'], 'add/(:num)', 'ClassAssignmentsController::add/$1', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_EDIT]);                           // Edit View

    // ======================== AJAX HELPERS ========================
    $routes->get('get-sections/(:num)/(:num)', 'ClassTeachersController::getSectionsByClass/$1/$2', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_VIEW]);               // AJAX - Get Sections

    // ======================== SAVE, VIEW & GRADE ========================
    $routes->post('save', 'ClassAssignmentsController::save', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_EDIT]);                                                     // Save (Create/Update)
    $routes->get('view/(:num)', 'ClassAssignmentsController::view/$1', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_VIEW]);                                            // View Assignment
    $routes->post('grade/(:num)', 'ClassAssignmentsController::gradeSubmission/$1', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_EDIT]);                               // Grade Submission

    // ======================== DELETE & DOWNLOAD ========================
    $routes->get('delete/(:num)', 'ClassAssignmentsController::delete/$1', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_DELETE]);                                      // Delete
    $routes->get('download/(:num)', 'ClassAssignmentsController::download/$1', ['filter' => 'customPermission:/class-assignments,' . PERMISSION_VIEW]);                                    // Download Assignment
});


// ======================== STUDENTS ========================
$routes->group('students', ['filter' => 'auth'], function($routes) {

    // ======================== CORE STUDENTS ========================
    $routes->get('/', 'StudentsController::list', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                                           // List
    $routes->get('list', 'StudentsController::list', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                                       // List (alias)
    $routes->get('add/', 'StudentsController::add', ['filter' => 'customPermission:/students,' . PERMISSION_CREATE]);                                                                      // Add View
    $routes->get('add/(:num)', 'StudentsController::add/$1', ['filter' => 'customPermission:/students,' . PERMISSION_EDIT]);                                                               // Edit View
    $routes->get('view/(:num)', 'StudentsController::view/$1', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                             // View Student
    $routes->post('save', 'StudentsController::save', ['filter' => 'customPermission:/students,' . PERMISSION_EDIT]);                                                                      // Save (Create/Update)
    $routes->get('delete/(:num)', 'StudentsController::delete/$1', ['filter' => 'customPermission:/students,' . PERMISSION_DELETE]);                                                       // Delete

    // ======================== AJAX HELPERS ========================
    $routes->get('get-sections/(:num)/(:num)', 'ClassTeachersController::getSectionsByClass/$1/$2', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                        // AJAX - Get Sections
    $routes->get('generate-admission-no', 'StudentsController::generateAdmissionNo', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                       // AJAX - Generate Admission No
    $routes->post('preview-roll-no', 'StudentsController::previewRollNo', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                  // AJAX - Preview Roll No
    $routes->post('preview-admission-no', 'StudentsController::previewAdmissionNo', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                        // AJAX - Preview Admission No

    // ======================== DOCUMENTS ========================
    $routes->group('documents', function($routes) {
        $routes->get('(:num)', 'StudentDocumentsController::list/$1', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                       // List Documents
        $routes->post('upload/(:num)', 'StudentDocumentsController::upload/$1', ['filter' => 'customPermission:/students,' . PERMISSION_CREATE]);                                          // Upload Document
        $routes->get('download/(:num)', 'StudentDocumentsController::download/$1', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                         // Download Document
        $routes->get('view/(:num)', 'StudentDocumentsController::view/$1', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                 // View Document
        $routes->get('delete/(:num)', 'StudentDocumentsController::delete/$1', ['filter' => 'customPermission:/students,' . PERMISSION_DELETE]);                                           // Delete Document
        $routes->post('update-status/(:num)', 'StudentDocumentsController::updateStatus/$1', ['filter' => 'customPermission:/students,' . PERMISSION_EDIT]);                               // Update Document Status
    });

    // ======================== ACADEMIC HISTORY ========================
    $routes->group('academic-history', function($routes) {
        $routes->get('(:num)', 'StudentAcademicHistoryController::list/$1', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                 // List Academic History
        $routes->get('add/(:num)', 'StudentAcademicHistoryController::add/$1', ['filter' => 'customPermission:/students,' . PERMISSION_CREATE]);                                           // Add Academic History
        $routes->get('edit/(:num)', 'StudentAcademicHistoryController::edit/$1', ['filter' => 'customPermission:/students,' . PERMISSION_EDIT]);                                           // Edit Academic History
        $routes->post('save', 'StudentAcademicHistoryController::save', ['filter' => 'customPermission:/students,' . PERMISSION_EDIT]);                                                    // Save Academic History
        $routes->get('delete/(:num)', 'StudentAcademicHistoryController::delete/$1', ['filter' => 'customPermission:/students,' . PERMISSION_DELETE]);                                     // Delete Academic History
        $routes->get('get-sections/(:num)/(:num)', 'StudentAcademicHistoryController::getSectionsByClass/$1/$2', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);           // AJAX - Get Sections
    });

    // ======================== BULK OPERATIONS ========================
    $routes->group('bulk', function($routes) {
        $routes->get('import', 'StudentsController::import', ['filter' => 'customPermission:/students,' . PERMISSION_CREATE]);                                                              // Import View
        $routes->post('process-import', 'StudentsController::processImport', ['filter' => 'customPermission:/students,' . PERMISSION_CREATE]);                                             // Process Import
        $routes->get('export', 'StudentsController::export', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                               // Export
        $routes->post('promote', 'StudentsController::promote', ['filter' => 'customPermission:/students,' . PERMISSION_EDIT]);                                                            // Promote Students
    });

    // ======================== REPORTS ========================
    $routes->group('reports', function($routes) {
        $routes->get('class-wise', 'StudentReportsController::classWise', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                  // Class Wise Report
        $routes->get('section-wise', 'StudentReportsController::sectionWise', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                              // Section Wise Report
        $routes->get('gender-wise', 'StudentReportsController::genderWise', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                // Gender Wise Report
        $routes->get('category-wise', 'StudentReportsController::categoryWise', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                            // Category Wise Report
        $routes->get('birthday', 'StudentReportsController::birthday', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                     // Birthday Report
        $routes->get('export/(:any)', 'StudentReportsController::export/$1', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                               // Export Report
    });

    // ======================== AJAX ========================
    $routes->group('ajax', function($routes) {
        $routes->post('check-admission-no', 'StudentsController::checkAdmissionNo', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                        // AJAX - Check Admission No
        $routes->post('check-roll-no', 'StudentsController::checkRollNo', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                                  // AJAX - Check Roll No
        $routes->post('get-student-details', 'StudentsController::getStudentDetails', ['filter' => 'customPermission:/students,' . PERMISSION_VIEW]);                                      // AJAX - Get Student Details
    });
});

//STUDENT RELATED ROUTES ENDED HERE 
//+++++++++++++++
$routes->group('admitcard', ['namespace' => 'App\Controllers'], function($routes){
    $routes->get('generate/(:num)', 'AdmitCardController::generate/$1');
    $routes->get('print/(:num)', 'AdmitCardController::print/$1');
});

$routes->group('fee', function($routes){
    $routes->get('/', 'FeeController::list');
    $routes->get('list/(:num)', 'FeeController::list/$1');
    $routes->get('collect/(:num)', 'FeeController::collect/$1');
    $routes->get('collect', 'FeeController::collect');
    $routes->post('savePayment', 'FeeController::savePayment');
    $routes->get('receipt/(:num)', 'FeeController::receipt/$1');
});

$routes->group('attendance', function($routes){
    $routes->get('mark', 'AttendanceController::mark');
    $routes->post('save', 'AttendanceController::save');
    $routes->get('report', 'AttendanceController::report');
    $routes->get('report/(:num)', 'AttendanceController::report/$1');
    $routes->post('getStudents', 'AttendanceController::getStudents');
});

$routes->group('result', function($routes){
    $routes->get('enter', 'ResultController::enter');
    $routes->post('saveMarks', 'ResultController::saveMarks');
    $routes->get('report', 'ResultController::report');
    $routes->get('report/(:num)', 'ResultController::report/$1');
    $routes->get('printReport/(:num)/(:num)', 'ResultController::printReport/$1/$2');
});

$routes->group('certificate', function($routes){
    $routes->get('generate/(:num)/(:segment)', 'CertificateController::generate/$1/$2');
    $routes->get('print/(:num)/(:segment)', 'CertificateController::print/$1/$2');
});

$routes->group('academics', function($routes){
    $routes->get('subjects', 'AcademicsController::subjects');
    $routes->post('saveSubject', 'AcademicsController::saveSubject');
    $routes->get('delete/(:num)', 'AcademicsController::delete/$1');
    $routes->post('getClassSubjects', 'AcademicsController::getClassSubjects');
});





