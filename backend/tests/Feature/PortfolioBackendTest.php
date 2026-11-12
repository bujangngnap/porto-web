<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\GalleryPhoto;
use App\Models\PortfolioProfile;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\VisitorComment;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioBackendTest extends TestCase
{
    use RefreshDatabase;

    private function tinyPngUpload(string $name = 'test.png'): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent($name, $png);
    }

    public function test_public_portfolio_and_health_endpoints_return_seeded_data(): void
    {
        $this->seed(PortfolioSeeder::class);

        $this->getJson('/api/health')
            ->assertOk()
            ->assertJson(['ok' => true]);

        $response = $this->getJson('/api/portfolio')->assertOk();
        $response->assertJsonCount(3, 'projectsWebsite')
            ->assertJsonCount(3, 'projectsAplikasi')
            ->assertJsonCount(6, 'projectsDesain')
            ->assertJsonCount(9, 'gallery')
            ->assertJsonPath('totalProjects', 12);
    }

    public function test_visitor_can_submit_comment_with_optional_avatar_and_contact_message(): void
    {
        Storage::fake('public');
        $this->seed(PortfolioSeeder::class);

        // Reject invalid comment
        $this->postJson('/api/comments', ['name' => 'A', 'message' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'message']);

        // Store valid comment with PNG avatar
        $commentRes = $this->postJson('/api/comments', [
            'name' => 'Budi Santoso',
            'message' => 'Web portofolionya mantap dan cepat!',
            'avatar' => $this->tinyPngUpload('budi.png'),
        ]);

        $commentRes->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('comment.name', 'Budi Santoso');

        $avatarPath = $commentRes->json('comment.avatar');
        $this->assertStringStartsWith('/storage/avatars/', $avatarPath);
        Storage::disk('public')->assertExists(ltrim(substr($avatarPath, strlen('/storage/')), '/'));

        $this->getJson('/api/comments')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Budi Santoso']);

        // Contact endpoint validation + save
        $this->postJson('/api/contact', ['name' => 'X', 'email' => 'not-an-email', 'message' => 'Hi'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);

        $this->postJson('/api/contact', [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'message' => 'Saya mau diskusi proyek Laravel.',
        ])->assertCreated()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'is_read' => false,
        ]);
    }

    public function test_admin_authentication_and_crud_operations_work_properly(): void
    {
        Storage::fake('public');
        $this->seed(PortfolioSeeder::class);

        // Protected routes redirect guests to login
        $this->get('/admin/messages')->assertRedirect('/admin/login');
        $this->get('/admin')->assertRedirect('/admin/login');

        // Login with seeded credentials (redirects to last intended URL or /admin)
        $this->post('/admin/login', [
            'email' => 'banggob09@gmail.com',
            'password' => 'admin1234',
        ])->assertRedirect('/admin');

        $this->get('/admin')->assertOk()->assertSee('Pesan Masuk Terbaru');
        $this->get('/admin/messages')->assertOk()->assertSee('Tim Rekrutmen Digital');
        $this->get('/admin/comments')->assertOk()->assertSee('Dev Enthusiast');
        $this->get('/admin/projects')->assertOk()->assertSee('SSB Garuda Muda Website');
        $this->get('/admin/gallery')->assertOk()->assertSee('Kebersamaan Tim di BTM AMMAN');

        // Toggle message read status
        $message = ContactMessage::firstOrFail();
        $this->patch("/admin/messages/{$message->id}/toggle-read")->assertRedirect();
        $this->assertTrue($message->fresh()->is_read);

        // Hide comment & verify hidden from public API
        $comment = VisitorComment::firstOrFail();
        $this->patch("/admin/comments/{$comment->id}/toggle")->assertRedirect();
        $this->assertFalse($comment->fresh()->is_approved);
        $this->getJson('/api/comments')->assertOk()->assertJsonMissing(['id' => $comment->id]);

        // Create and delete a project
        $this->post('/admin/projects', [
            'tab' => 'website',
            'category' => 'Custom Portal',
            'title' => 'Portal Kampus Baru',
            'description' => 'Sistem informasi akademik modern.',
            'tags_input' => 'Laravel, React.js',
            'image' => $this->tinyPngUpload('portal.png'),
            'featured' => '1',
            'sort_order' => 10,
        ])->assertRedirect('/admin/projects');

        $created = Project::where('title', 'Portal Kampus Baru')->firstOrFail();
        $this->assertEquals(['Laravel', 'React.js'], $created->tags);

        // Verify appears in API
        $this->getJson('/api/portfolio')->assertOk()->assertJsonFragment(['title' => 'Portal Kampus Baru']);

        // Create gallery photo
        $this->post('/admin/gallery', [
            'title' => 'Kegiatan Kampus',
            'category' => 'Campus',
            'aspect_class' => 'aspect-[4/3]',
            'width_scale' => 1.28,
            'image' => $this->tinyPngUpload('gal.png'),
            'sort_order' => 10,
        ])->assertRedirect('/admin/gallery');

        $this->assertTrue(GalleryPhoto::where('title', 'Kegiatan Kampus')->exists());

        // Clean delete project
        $this->delete("/admin/projects/{$created->id}")->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $created->id]);
    }

    public function test_admin_can_edit_portfolio_profile_education_experience_and_social_links(): void
    {
        Storage::fake('public');
        $this->seed(PortfolioSeeder::class);

        $this->post('/admin/login', [
            'email' => 'banggob09@gmail.com',
            'password' => 'admin1234',
        ])->assertRedirect('/admin');

        $this->get('/admin/portfolio')->assertOk()->assertSee('Hero & Identitas Utama');
        $this->get('/admin/portfolio?section=education')->assertOk()->assertSee('Universitas Muhammadiyah Magelang');
        $this->get('/admin/portfolio?section=experience')->assertOk()->assertSee('Tumbuh Kembang');
        $this->get('/admin/portfolio?section=social')->assertOk()->assertSee('GitHub');

        // Update profile
        $this->put('/admin/portfolio/profile', [
            'hero_top_text' => 'HELLO WORLD',
            'hero_bottom_text' => 'PORTFOLIO',
            'greeting' => 'Halo, saya',
            'first_name' => 'Muhammad Raihan',
            'last_name' => 'Al Ashr',
            'full_name' => 'Muhammad Raihan Al Ashr',
            'bio' => 'Bio terbaru dari dashboard Laravel.',
            'email' => 'banggob09@gmail.com',
            'location' => 'Magelang, Indonesia',
        ])->assertRedirect('/admin/portfolio?section=profile');

        $this->assertDatabaseHas('portfolio_profiles', [
            'hero_top_text' => 'HELLO WORLD',
            'bio' => 'Bio terbaru dari dashboard Laravel.',
        ]);

        // Add Education & Experience & Social
        $this->post('/admin/portfolio/education', [
            'school' => 'Universitas Baru',
            'degree' => 'S2 Informatika',
            'year' => '2029',
            'sort_order' => 5,
        ])->assertRedirect('/admin/portfolio?section=education');
        $this->assertTrue(Education::where('school', 'Universitas Baru')->exists());

        $this->post('/admin/portfolio/experience', [
            'company' => 'PT Teknologi Nusantara',
            'role' => 'Fullstack Laravel Dev',
            'year' => '2027',
            'sort_order' => 5,
        ])->assertRedirect('/admin/portfolio?section=experience');
        $this->assertTrue(Experience::where('company', 'PT Teknologi Nusantara')->exists());

        $this->post('/admin/portfolio/social', [
            'type' => 'instagram',
            'display_name' => 'Instagram Resmi',
            'sub_text' => '@raihan',
            'url' => 'https://instagram.com/raihan',
            'sort_order' => 5,
        ])->assertRedirect('/admin/portfolio?section=social');
        $this->assertTrue(SocialLink::where('display_name', 'Instagram Resmi')->exists());

        // Public API reflects live edited data
        $this->getJson('/api/portfolio')
            ->assertOk()
            ->assertJsonPath('personal.heroTopText', 'HELLO WORLD')
            ->assertJsonPath('personal.bio', 'Bio terbaru dari dashboard Laravel.')
            ->assertJsonFragment(['school' => 'Universitas Baru'])
            ->assertJsonFragment(['company' => 'PT Teknologi Nusantara'])
            ->assertJsonFragment(['displayName' => 'Instagram Resmi']);
    }

    public function test_admin_pages_render_liquid_glass_theme_switcher(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (['/admin/login'] as $guestUrl) {
            $this->get($guestUrl)
                ->assertOk()
                ->assertSee('data-ashr-theme-switcher', false)
                ->assertSee('c-option="1"', false)
                ->assertSee('c-option="2"', false)
                ->assertSee('c-option="3"', false)
                ->assertSee('id="switcher"', false)
                ->assertSee('id="toggler"', false);
        }

        $this->post('/admin/login', [
            'email' => 'banggob09@gmail.com',
            'password' => 'admin1234',
        ])->assertRedirect('/admin');

        $this->get('/admin')
            ->assertOk()
            ->assertSee('data-ashr-theme-switcher', false)
            ->assertSee('ashr_admin_theme', false)
            ->assertSee('id="switcher"', false)
            ->assertSee('id="toggler"', false);
    }
}
