<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Appointment;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GetdoctorsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;




// AUTHENTICATION ROUTES
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisterController::class, 'apiRegister']);

// Login
Route::post('/login', [LoginController::class, 'apiLogin']);
// Doctor login
Route::post('/login/doctor', [LoginController::class, 'apiDoctorLogin']);

// Logout
Route::post('/logout', [LoginController::class, 'apiLogout']);
// Route::post('/login', [LoginController::class, 'apiLogin']);


// USERS ROUTES
// Upload user avt
Route::post('/user/{userID}/upload-avatar', [UsersController::class, 'apiUploadAvatar']);
Route::get('/user/{userID}/get-avatar', [UsersController::class, 'apiGetAvatarByUserId']);
// Add address
// Add user address
Route::put('/users/{userID}/address', [UsersController::class, 'apiUpdateAddress']);
Route::get('/users/{userID}', [UsersController::class, 'apiGetUserByID']);



// DOCTORS ROUTES

// Get list doctor
Route::middleware('auth:sanctum')->get('/doctors', [GetdoctorsController::class, 'apiHome']);
//Get doctors controller
Route::get('/alldoctors', [DoctorsController::class, 'apiGetAllDoctors']);
Route::get('/doctors/{doctorID}', [DoctorsController::class, 'apiGetDoctorsByDoctorId']);

// PRODUCT ROUTES
Route::get('/products', [ProductController::class, 'getAllProducts']);
Route::get('/products/{id}', [ProductController::class, 'getProductById']);

// APPOINTMENT ROUTES

//Get all appointment
Route::get('/appointments', [AppointmentsController::class, 'getAllAppointments']);
// Get appointment by user
Route::get('/appointments/{userID}', [AppointmentsController::class, 'getAppointmentsByUser']);
//Create appointmet by user
Route::post('/appointments/{userID}', [AppointmentsController::class, 'createAppointment']);
// Get current appointmentappointment
Route::get('/appointments/upcoming/{userID}', [AppointmentsController::class, 'getCurrentAppointments']);


// APPOINTMENT BOOKING
Route::post('appointments/{userID}/create', [AppointmentsController::class, 'apicreateAppointment']);
//Update status
Route::put('/appointments/{appointmentID}/confirm', [AppointmentsController::class, 'apiConfirmAppointment']);
Route::put('/appointments/{appointmentID}/complete', [AppointmentsController::class, 'apiCompleteAppointment']);
//Get appointmet buy DoctorID
Route::get('/appointments/doctor/{doctorID}/all', [AppointmentsController::class, 'apiGetAllAppointmentsByDoctor']);
Route::get('/appointments/doctor/{doctorID}/recent', [AppointmentsController::class, 'apiGetRecentAppointments']);
Route::delete('/appointments/{appointmentID}/reject', [AppointmentsController::class, 'apiDeleteAppointment']);


// CART ROUTES
Route::get('/cart/{userID}', [CartController::class, 'apiGetUserCart']);
// Add more product to cart
Route::middleware('auth:sanctum')->post('/cart/add', [CartController::class, 'apiAddProductToCart']);
//Remove product by useruser
Route::delete('/cart/{userId}/{productId}', [CartController::class, 'apiRemoveFromCartByUser']);
// Update product quantity
Route::put('/cart/{userId}/{productId}', [CartController::class, 'apiUpdateUserCartQuantity']);


// POST ROUTES
Route::get('/posts', [PostController::class, 'apiGetAllPosts']);
Route::get('/posts/{slug}', [PostController::class, 'apiGetPostBySlug']);

// Default User Route
// Route::middleware('auth:sanctum')->get('/api/user', function (Request $request) {
//     return $request->user();
// });

// ORDER ROUTES
// Order Routes
Route::middleware(['auth:sanctum'])->get('/orders', [OrderController::class, 'apiGetUserOrders']);
Route::middleware(['auth:sanctum'])->post('/orders/create', [OrderController::class, 'apiCreateOrder']);

// Order status 
Route::middleware(['auth:sanctum'])->get('/orders/{order_id}/status', [OrderController::class, 'apiGetOrderStatus']);
Route::middleware(['auth:sanctum'])->get('/orders/status', [OrderController::class, 'apiGetUserOrdersStatus']);
