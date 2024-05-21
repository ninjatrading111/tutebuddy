<?php

/*
 * All route names are prefixed with 'admin.'.
 */

//===== Users Routes =====//
Route::resource('users','UserController');
Route::get('user/restore/{id}', 'UserController@restore')->name('user.restore');
Route::get('user/delete/forever/{id}', 'UserController@foreverDelete')->name('user.foreverDelete');
Route::post('ajax/user/verifycode/send', 'UserController@sendVerifyCode')->name('user.verifycode.send');
Route::post('ajax/user/change/email', 'UserController@changeUserEmail')->name('user.email.change');

Route::get('ajax/users/list/{type}', 'UserController@getList')->name('getUsersByAjax');
Route::get('account', 'UserController@myAccount')->name('myaccount');
Route::post('child-account', 'UserController@childAccount')->name('myaccount.child');
Route::post('child-update', 'UserController@childAccountUpdate')->name('myaccount.child.update');
Route::get('my/instructors', 'UserController@studentInstructors')->name('student.instructors');
Route::get('ajax/my-instructors', 'UserController@getStudentInstructorsByAjax')->name('student.getStudentInstructorsByAjax');
Route::get('access-history', 'UserController@getHistory')->name('users.access_history');
Route::get('ajax/access-history', 'UserController@getHistoryByAjax')->name('ajax.getAccessHistoryByAjax');

//===== Roles Routes =====//
Route::resource('roles','RoleController');

//===== Dashboard Routes =====//
Route::get('/', 'DashboardController@index')->name('dashboard');

// Workspace for Teachers
Route::get('live-sessions/all', 'LessonController@instructorLiveSessions')->name('instructor.liveSessions');
Route::get('ajax/instructor-sessions/{type}', 'LessonController@getInstructorLiveSessionsByAjax')->name('teacher.getInstructorSessionsByAjax');
Route::get('enrolled-students', 'UserController@enrolledStudents')->name('students.enrolled');
Route::get('ajax/enrolled-students', 'UserController@getEnrolledStudentsByAjax')->name('getEnrolledStudentsByAjax');

//===== Categories Routes =====//
Route::resource('categories', 'CategoryController');
Route::get('ajax/categories/table', 'CategoryController@getTableData')->name('table.getCategoriesByAjax');

//===== Levels Routes =====//
Route::resource('levels', 'LevelController');
Route::get('get/levels/{category_id}', 'LevelController@getLevelsByCategory')->name('getLevelsByCategory');
Route::get('ajax/levels/list', 'LevelController@getList')->name('getLevelsByAjax');

//===== Course types Routes =====//
Route::resource('types', 'TypeController');
Route::get('ajax/types/list', 'TypeController@getList')->name('getTypesByAjax');

//===== Media Library Routes =====//
Route::resource('media', 'MediaController');

//===== Courses Routes =====//
Route::resource('courses', 'CourseController');
Route::get('courses/restore/{id}', 'CourseController@restore')->name('courses.restore');
Route::get('courses/get/favorites', 'CourseController@favorites')->name('courses.favorites');
Route::get('ajax/courses/list/{type}', 'CourseController@getList')->name('getCoursesByAjax');
Route::get('ajax/courses/publish/{id}', 'CourseController@publish')->name('courses.publish');
Route::get('ajax/courses/delete/forever/{id}', 'CourseController@foreverDelete')->name('courses.foreverDelete');
Route::get('ajax/course/add-favorite/{course_id}', 'CourseController@addFavorite')->name('course.addFavorite');
Route::get('ajax/course/remove-favorite/{course_id}', 'CourseController@removeFavorite')->name('course.removeFavorite');
Route::get('my/courses', 'CourseController@studentCourses')->name('student.courses');
Route::get('ajax/my-courses/{type}', 'CourseController@getStudentCoursesByAjax')->name('student.getMyCoursesByAjax');

Route::get('ajax/slug', 'CourseController@getSlugByTitle')->name('slug');

//===== Bundles Routes =====//
Route::resource('bundles', 'BundlesController');
Route::get('bundles/restore/{id}', 'BundlesController@restore')->name('bundle.restore');
Route::get('ajax/bundles/list/{type}', 'BundlesController@getList')->name('getBundlesByAjax');
Route::get('ajax/bundles/publish/{id}', 'BundlesController@publish')->name('bundle.publish');
Route::get('ajax/bundle/add-favorite/{course_id}', 'BundlesController@addFavorite')->name('bundle.addFavorite');
Route::get('my/paths', 'BundlesController@studentBundles')->name('student.bundles');
Route::get('ajax/bundle/delete/forever/{id}', 'BundlesController@foreverDelete')->name('bundle.foreverDelete');

//===== Assignment Routes =====//
Route::resource('assignments', 'AssignmentsController');
Route::get('assignments/restore/{id}', 'AssignmentsController@restore')->name('assignment.restore');
Route::get('ajax/assignments/list/{type}', 'AssignmentsController@getList')->name('getAssignmentsByAjax');
Route::get('ajax/assignments/publish/{id}', 'AssignmentsController@publish')->name('assignment.publish');
Route::get('ajax/assignments/delete/forever/{id}', 'AssignmentsController@foreverDelete')->name('assignment.foreverDelete');
Route::get('ajax/assignments/lessons', 'AssignmentsController@getLessons')->name('assignment.getLessonsByCourse');
Route::get('submited-assignments', 'AssignmentsController@submitedAssignments')->name('instructor.submitedAssignments');
Route::get('ajax/submited-assignments/{type}', 'AssignmentsController@getSubmitedAssignmentsByAjax')->name('instructor.getSubmitedAssignmentsByAjax');
Route::get('submited-assignments/result/{id}', 'AssignmentsController@show_result')->name('assignments.show_result');
Route::post('assignment-result/answer', 'AssignmentsController@result_answer')->name('assignments.result_answer');
Route::get('my/assignments', 'AssignmentsController@studentAssignments')->name('student.assignments');
Route::get('ajax/my-assignments/{type}', 'AssignmentsController@getStudentAssignmentsByAjax')->name('student.getMyAssignmentsByAjax');

//===== Lessons Routes =====//
Route::resource('lessons', 'LessonController');
Route::get('lessons/delete/{id}', 'LessonController@deleteLesson')->name('lessons.delete');
Route::get('lessons/lesson/{id}', 'LessonController@getLesson')->name('lesson.getById');
Route::get('steps/delete/{id}', 'LessonController@deleteStep')->name('steps.delete');
Route::get('ajax/lessons-by-course', 'LessonController@getLessons')->name('lessons.getLessonsByCourse');
Route::get('my/live-sessions', 'LessonController@studentLiveSessions')->name('student.liveSessions');
Route::get('ajax/live-sessions/{type}', 'LessonController@getStudentLiveSessionsByAjax')->name('student.getLiveSessionsByAjax');

//===== Schedule Routes ====//
Route::get('schedule', 'ScheduleController@index')->name('schedule');
Route::get('schedule/source', 'ScheduleController@getScheduleData')->name('getScheduleData');
Route::post('schedule/new', 'ScheduleController@storeSchedule')->name('storeSchedule');
Route::post('schedule/lesson/add', 'ScheduleController@addLesson')->name('addLesson');
Route::post('schedule/update', 'ScheduleController@updateSchedule')->name('updateSchedule');
Route::get('schedule/delete', 'ScheduleController@deleteSchedule')->name('removeSchedule');

Route::get('get/course/lessons', 'ScheduleController@getLessons')->name('schedule.getLessonsByCourse');

//==== Learn Routes ====//
Route::get('learn/course/{id}', 'LearnController@course')->name('learn.course');

//===== Quiz Routes =====//
Route::resource('quizs', 'QuizController');
Route::get('ajax/quizs/restore/{id}', 'QuizController@restore')->name('quizs.restore');
Route::get('ajax/quizs/publish/{id}', 'QuizController@publish')->name('quizs.publish');
Route::get('ajax/quizs/list/{type}', 'QuizController@getList')->name('getquizzesByAjax');
Route::get('ajax/quizs/delete/forever/{id}', 'QuizController@foreverDelete')->name('quizs.foreverDelete');
Route::get('my/quizs', 'QuizController@studentQuizs')->name('student.quizs');
Route::get('ajax/my-quizs/{type}', 'QuizController@getStudentQuizsByAjax')->name('student.getMyQuizzesByAjax');

//===== Questions Routes =====//
Route::resource('questions', 'QuestionController');
Route::get('ajax/questions/list/{course_id}/{test_id}', 'QuestionController@getList')->name('getQuestionsByAjax');
Route::get('ajax/question/{question_id}', 'QuestionController@getQuestion')->name('getQuestionByAjax');
Route::get('questions/delete/{id}', 'QuestionController@delete')->name('questions.delete');
Route::get('questions/restore/{id}', 'QuestionController@restore')->name('questions.restore');
Route::post('questions/add-section', 'QuestionController@addSection')->name('questions.addsection');

//===== Questions Options Routes =====//
Route::resource('questions_options', 'QuestionOptionsController');
Route::get('questions_options/delete/{id}', 'QuestionOptionsController@delete')->name('questions_options.delete');

//==== Settings Route ====//
Route::get('settings/general', 'ConfigController@getGeneralSettings')->name('settings.general');
Route::post('settings/general', 'ConfigController@saveGeneralSettings')->name('settings.general.save');

// Review
Route::resource('reviews', 'ReviewController');
Route::get('ajax/reviews/{id}/publish', 'ReviewController@publish')->name('review.publishByAjax');
Route::get('ajax/reviews/{id}/delete', 'ReviewController@destroy')->name('review.removeByAjax');
Route::get('ajax/reviews/list', 'ReviewController@getTableData')->name('getReviewsByAjax');

// Certificate
Route::get('certificates', 'CertificateController@index')->name('certificates.index');
Route::get('certificate/{id}/show', 'CertificateController@show')->name('certificates.show');
Route::get('ajax/certificates', 'CertificateController@getCertificates')->name('table.getCertsByAjax');
Route::post('certificates/generate', 'CertificateController@generateCertificate')->name('certificates.generate');
Route::get('certificates/download', ['uses' => 'CertificateController@download', 'as' => 'certificates.download']);

// Discussion
Route::resource('discussions', 'DiscussionController');
Route::get('topics', 'DiscussionController@getTopics')->name('discussions.topics');
Route::get('ajax/discussions', 'DiscussionController@getTopicsByAjax')->name('table.getTopicsByAjax');
Route::post('ajax/comment', 'DiscussionController@postComment')->name('ajax.postComment');
Route::get('ajax/similar', 'DiscussionController@getSimilar')->name('ajax.getSimilar');

// Messages Routes
Route::get('messages', 'MessagesController@index')->name('messages.index');
Route::get('messages/show/{threadId}/{userId}', 'MessagesController@show')->name('messages.show');
Route::get('messages/users/{key}', 'MessagesController@getUsers')->name('messages.users');
Route::get('messages/get', 'MessagesController@getMessages')->name('messages.get');
Route::get('messages/last', 'MessagesController@lastMessages')->name('messages.last');

Route::post('messages/reply', 'MessagesController@reply')->name('messages.reply');
Route::post('messages/unread', 'MessagesController@getUnreadMessages')->name('messages.unread');

Route::get('messages/get/enroll-thread', 'MessagesController@getEnrollThread')->name('messages.getEnrollThread');
Route::post('messages/enroll-send', 'MessagesController@sendEnrollChat')->name('messages.sendEnrollChat');

// Pre enroll
Route::get('pre-enrolled', 'MessagesController@getPreEnrolledStudents')->name('messages.preEnrolledStudents');
Route::get('ajax/pre-enrolled', 'MessagesController@getPreEnrolledStudentsData')->name('ajax.getPreEnrolledStudentsData');

// Pages
Route::resource('pages', 'PagesController');
Route::get('ajax/pages/list/{type}', 'PagesController@getList')->name('getPagesByAjax');
Route::get('ajax/pages/publish/{id}', 'PagesController@publish')->name('pages.publish');
Route::get('pages/restore/{id}', 'PagesController@restore')->name('pages.restore');

// Faqs
Route::resource('faqs', 'FaqsController');

// Email Tempate
Route::resource('mailedits', 'EmailtemplateController');
Route::get('mailedits/tempalte/{id}/edit', 'EmailtemplateController@editTemplate')->name('mailedits.template_edit');
Route::get('ajax/mailedits', 'EmailtemplateController@getListByAjax')->name('table.getTemplatesByAjax');
Route::get('ajax/send-test', 'EmailtemplateController@sendTestEmail')->name('ajax.sendTestEmail');

//===== Test Routes =====//
Route::resource('tests', 'TestController');
Route::get('tests/restore/{id}', 'TestController@restore')->name('test.restore');
Route::get('ajax/tests/list/{type}', 'TestController@getList')->name('getTestsByAjax');
Route::get('ajax/test/publish/{id}', 'TestController@publish')->name('test.publish');
Route::get('ajax/test/delete/forever/{id}', 'TestController@foreverDelete')->name('test.foreverDelete');
Route::get('ajax/test/lessons', 'TestController@getLessons')->name('test.getLessonsByCourse');
Route::get('my/tests', 'TestController@studentTests')->name('student.tests');
Route::get('ajax/my-tests/{type}', 'TestController@getStudentTestsByAjax')->name('student.getMyTestsByAjax');
Route::get('submited-tests', 'TestController@submitedTests')->name('instructor.submitedTests');
Route::get('ajax/submited-tests/{type}', 'TestController@getSubmitedTestsByAjax')->name('instructor.getSubmitedTestsByAjax');
Route::get('submited-tests/result/{id}', 'TestController@show_result')->name('tests.show_result');
Route::post('test-result/answer', 'TestController@result_answer')->name('tests.result_answer');

//==== Transactions Route ==== //
Route::get('transactions', 'PaymentController@getTransactions')->name('transactions');
Route::get('transactions/detail/{id}', 'PaymentController@transactionsDetail')->name('transactions.detail');
Route::get('transactions/download/invoice/{id}', 'PaymentController@downloadInvoiceByTransactionId')->name('transactions.invoice.download');

//==== Orders Route ==== //
Route::get('orders', 'PaymentController@getOrders')->name('orders');
Route::get('orders/detail/{id}', 'PaymentController@orderDetail')->name('orders.detail');
Route::get('orders/invoice/{id}', 'PaymentController@downloadInvoice')->name('orders.invoice');
Route::get('orders/refund/{id}', 'PaymentController@refundRequest')->name('orders.refundrequest');

//==== Refunds ====//
Route::get('refunds', 'PaymentController@getRefunds')->name('refunds');
Route::get('refunds/detail/{id}', 'PaymentController@refundDetail')->name('refund.detail');
Route::get('refunds/process/{id}', 'PaymentController@processRefund')->name('refund.process');
Route::get('refunds/reply/{id}', 'PaymentController@refundReply')->name('refund.reply');
Route::post('ajax/refund-reply', 'PaymentController@ajaxRefundReply')->name('refund.replyByAjax');

//==== Invoices ==== //
Route::get('invoices', 'InvoicesController@index')->name('invoices');
Route::get('invoices/sales', 'InvoicesController@salesInvoices')->name('invoices.sales');
Route::get('invoices/service', 'InvoicesController@serviceInvoices')->name('invoices.service');

//==== Withdraw ====//
Route::post('withdraw', 'PaymentController@withdraw')->name('withdraw');
Route::get('withdraws', 'PaymentController@instructorWithdraws')->name('instructor.withdraws');

//==== Contacts Route ====//
Route::resource('contacts', 'ContactsController');

//=== Result Sheet ===//
Route::get('results', 'ResultsController@student')->name('results.student');
Route::get('ajax/results', 'ResultsController@getStudentTableData')->name('results.getTableDataByAjax');
Route::get('results/detail/{id}', 'ResultsController@getResultDetail')->name('results.detail');

Route::get('results/performance/{user_id}/{course_id}', 'ResultsController@getStudentResultDetail')->name('results.studentDetail');

//=== My Badges ===//
Route::get('badges', 'ResultsController@badges')->name('results.student.badges');

//=== Tax route ===//
Route::resource('tax', 'TaxController');
Route::get('tax/publish/{id}', 'TaxController@publish')->name('tax.publish');

//==== Remove Locale FIle ====//
Route::post('delete-locale', function () {
    \Barryvdh\TranslationManager\Models\Translation::where('locale', request('locale'))->delete();
    \Illuminate\Support\Facades\File::deleteDirectory(public_path('../resources/lang/' . request('locale')));
})->name('delete-locale');

//===== Childs Routes =====//
Route::get('childs/{id}', 'ChildsController@getChild')->name('child');
Route::get('childs/courses/{id}', 'ChildsController@getChildCourses')->name('child.courses');
Route::get('childs/assignments/{id}', 'ChildsController@getChildAssignments')->name('child.assignments');
Route::get('childs/tests/{id}', 'ChildsController@getChildTests')->name('child.tests');
Route::get('childs/quizzes/{id}', 'ChildsController@getChildQuizzes')->name('child.quizzes');

//===== KYC verfication ====//
Route::resource('kyc', 'KycController');
Route::get('ajax/kyc/getKyc/{status}', 'KycController@getKyc')->name('kyc.getKycByAjax');
Route::post('ajax/kyc/{id}/{status}', 'KycController@setKyc')->name('kyc.setKycByAjax');

//===== Demo Request ===== //
Route::get('demo/index', 'DemoController@index')->name('demo.index');
Route::post('ajax/demo/time/available', 'DemoController@getAvailableTimes')->name('ajax.demo.times');
Route::post('ajax/demo/create', 'DemoController@create')->name('ajax.demo.create');
Route::post('ajax/demo/setup', 'DemoController@setup')->name('ajax.demo.setup');
