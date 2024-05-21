<?php

//===== Course Routes =====//
Route::get('course/{slug}', 'CoursesController@show')->name('courses.show');
Route::post('course/{course_id}/rating', 'CoursesController@rating')->name('courses.rating');
Route::get('category/{category}/courses', 'CoursesController@getByCategory')->name('courses.category');
Route::post('courses/{id}/review', 'CoursesController@addReview')->name('courses.review');
Route::get('courses/review/{id}/edit', 'CoursesController@editReview')->name('courses.review.edit');
Route::post('courses/review/{id}/edit', 'CoursesController@updateReview')->name('courses.review.update');
Route::get('courses/review/{id}/delete', 'CoursesController@deleteReview')->name('courses.review.delete');

// ==== Search Result ====//
Route::get('search/courses', 'SearchController@courses')->name('courses.search');
Route::get('search/instructors', 'SearchController@teachers')->name('teachers.search');

// === Category route == //
Route::get('category', 'CategoryController@index')->name('category.all');

Route::get('ajax/search/courses/', 'SearchController@searchCourse')->name('ajax.search.course');
Route::get('ajax/search/instructor', 'SearchController@searchInstructor')->name('ajax.search.instructor');

// === Bundle route === //
Route::get('bundle/{slug}', 'BundlesController@show')->name('bundles.show');

// === Profile route === //
Route::get('myprofile', 'ProfileController@index')->name('myprofile');
Route::post('myprofile/fetchdata', 'ProfileController@fetchdata')->name('myprofile.fetchdata');
Route::get('writing','WritingController@index')->name('writing');

Route::get('profile/{uuid}', 'UserController@getTeacherProfile')->name('profile.show');

// === Page route == //
Route::get('pages', 'PageController@index')->name('pages');
Route::get('page/{slug}', 'PageController@getPage')->name('page.show');

Route::group(['middleware' => 'auth'], function () {

    Route::get('course/{course_slug}/{lesson_slug}/{step}', 'LessonsController@show')->name('lessons.show');
    Route::get('lesson/live/{lesson_slug}/{lesson_id}/{schedule_id}', 'LessonsController@liveSession')->name('lessons.live');
    Route::get('lesson/demo/{schedule_id}', 'LessonsController@demoSession')->name('demo.live');

    Route::get('lesson/{id}/complete', 'LessonsController@completeLesson')->name('lesson.complete');
    Route::get('ajax/step/{id}/complete/{type}', 'LessonsController@completeStep')->name('ajax.step.complete');

    // Assignment
    Route::get('assignment/{lesson_slug}/{id}', 'StudentController@startAssignment')->name('student.assignment.show');
    Route::post('assignment/save', 'StudentController@saveAssignment')->name('student.assignment.save');
    Route::get('assignment-result/{lesson_slug}/{test_id}', 'StudentController@assignmentResult')->name('student.assignment.result');

    // Take Quiz for Student
    Route::get('quiz/{lesson_slug}/{quiz_id}', 'StudentController@startQuiz')->name('student.quiz.show');
    Route::post('quiz/save', 'StudentController@saveQuiz')->name('student.quiz.save');
    Route::get('quiz-result/{lesson_slug}/{quiz_id}', 'StudentController@quizResult')->name('student.quiz.result');

    // Take test for Student
    Route::get('test/{lesson_slug}/{test_id}', 'StudentController@startTest')->name('student.test.show');
    Route::post('test/save', 'StudentController@saveTest')->name('student.test.save');
    Route::get('test-result/{lesson_slug}/{test_id}', 'StudentController@TestResult')->name('student.test.result');

    // Cart and Checkout
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::get('cart/checkout', 'CartController@checkout')->name('cart.checkout');
    Route::get('cart/remove', 'CartController@remove')->name('cart.remove');
    Route::get('cart/clear', 'CartController@clear')->name('cart.clear');
    Route::post('cart/payment', 'CartController@razorpay')->name('cart.razorpay');

    Route::post('cart/checkout', 'CartController@process')->name('cart.process');
    Route::post('cart/add', 'CartController@addToCart')->name('cart.addToCart');
    Route::get('cart/checkout/remove', 'CartController@checkoutRemove')->name('checkout.remove');

    Route::get('cart/childs/{course_id}', 'CartController@getChilds')->name('cart.getChilds');

});

// ==== Course Subscribe ==== //
Route::post('ajax/course/subscribe', 'EnrollController@subscribe')->name('ajax.course.subscribe');

// BBB callback for end session
Route::get('session/callback', 'LessonsController@endCallback')->name('session.callback');