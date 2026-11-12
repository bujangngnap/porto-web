<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryPhotoController;
use App\Http\Controllers\Admin\PortfolioContentController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\VisitorCommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

// Serve seeded frontend portfolio images & logos safely for Admin Dashboard previews
Route::get('/portfolio-media/{path}', function (string $path) {
    $frontendPublic = realpath(dirname(base_path()).DIRECTORY_SEPARATOR.'public');
    abort_unless($frontendPublic !== false, 404);

    $cleanPath = ltrim(str_replace(['..', '\\'], ['', '/'], $path), '/');
    $ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
    abort_unless(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'pdf'], true), 404);

    $filePath = realpath($frontendPublic.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $cleanPath));
    abort_unless(
        $filePath !== false && str_starts_with($filePath, $frontendPublic.DIRECTORY_SEPARATOR) && is_file($filePath),
        404
    );

    return response()->file($filePath);
})->where('path', '[A-Za-z0-9._\/-]+');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Edit Portofolio (Profil, Hero, Pendidikan, Pengalaman, Medsos)
        Route::get('/portfolio', [PortfolioContentController::class, 'index'])->name('portfolio.index');
        Route::put('/portfolio/profile', [PortfolioContentController::class, 'updateProfile'])->name('portfolio.profile.update');
        Route::post('/portfolio/education', [PortfolioContentController::class, 'storeEducation'])->name('portfolio.education.store');
        Route::put('/portfolio/education/{education}', [PortfolioContentController::class, 'updateEducation'])->name('portfolio.education.update');
        Route::delete('/portfolio/education/{education}', [PortfolioContentController::class, 'destroyEducation'])->name('portfolio.education.destroy');
        Route::post('/portfolio/experience', [PortfolioContentController::class, 'storeExperience'])->name('portfolio.experience.store');
        Route::put('/portfolio/experience/{experience}', [PortfolioContentController::class, 'updateExperience'])->name('portfolio.experience.update');
        Route::delete('/portfolio/experience/{experience}', [PortfolioContentController::class, 'destroyExperience'])->name('portfolio.experience.destroy');
        Route::post('/portfolio/social', [PortfolioContentController::class, 'storeSocial'])->name('portfolio.social.store');
        Route::put('/portfolio/social/{social}', [PortfolioContentController::class, 'updateSocial'])->name('portfolio.social.update');
        Route::delete('/portfolio/social/{social}', [PortfolioContentController::class, 'destroySocial'])->name('portfolio.social.destroy');

        // Contact Inbox
        Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::patch('/messages/{message}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

        // Visitor Comments Moderation
        Route::get('/comments', [VisitorCommentController::class, 'index'])->name('comments.index');
        Route::patch('/comments/{comment}/toggle', [VisitorCommentController::class, 'toggleApproval'])->name('comments.toggle');
        Route::delete('/comments/{comment}', [VisitorCommentController::class, 'destroy'])->name('comments.destroy');

        // Projects CRUD
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::patch('/projects/{project}/toggle-featured', [ProjectController::class, 'toggleFeatured'])->name('projects.toggle-featured');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Gallery CRUD
        Route::get('/gallery', [GalleryPhotoController::class, 'index'])->name('gallery.index');
        Route::get('/gallery/create', [GalleryPhotoController::class, 'create'])->name('gallery.create');
        Route::post('/gallery', [GalleryPhotoController::class, 'store'])->name('gallery.store');
        Route::get('/gallery/{gallery}/edit', [GalleryPhotoController::class, 'edit'])->name('gallery.edit');
        Route::put('/gallery/{gallery}', [GalleryPhotoController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{gallery}', [GalleryPhotoController::class, 'destroy'])->name('gallery.destroy');

        // Admin Settings
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    });
});
