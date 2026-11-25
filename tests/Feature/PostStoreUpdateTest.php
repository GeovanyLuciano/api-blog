<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostStoreUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_posts(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, "sanctum")
            ->getJson("/api/v1/posts")
            ->assertOk();
    }

    public function test_user_can_create_a_post_with_an_image(): void
    {
        Storage::fake("public");
        $user = User::factory()->create();
        $categoria = $user->categorias()->create([
            "name" => "Tecnologia",
            "slug" => "tecnologia",
            "description" => "Categoria de tecnologia",
        ]);

        $response = $this->actingAs($user, "sanctum")->post("/api/v1/posts", [
            "name" => "Meu primeiro post",
            "description" => "Uma descricao longa para validar o cadastro do post.",
            "categoria_id" => $categoria->id,
            "image" => UploadedFile::fake()->create("post.jpg", 10, "image/jpeg"),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas("posts", [
            "name" => "Meu primeiro post",
            "user_id" => $user->id,
            "categoria_id" => $categoria->id,
        ]);
        Storage::disk("public")->assertExists(Post::firstOrFail()->image);
    }

    public function test_user_can_update_their_post_and_replace_its_image(): void
    {
        Storage::fake("public");
        $user = User::factory()->create();
        $categoria = $user->categorias()->create([
            "name" => "Tecnologia",
            "slug" => "tecnologia",
            "description" => "Categoria de tecnologia",
        ]);
        Storage::disk("public")->put("uploads/old.jpg", "old image");

        $post = new Post();
        $post->name = "Post antigo";
        $post->description = "Descricao antiga para o post existente.";
        $post->image = "uploads/old.jpg";
        $post->categoria_id = $categoria->id;
        $post->user_id = $user->id;
        $post->save();

        $response = $this->actingAs($user, "sanctum")->post("/api/v1/posts/{$post->id}", [
            "_method" => "PUT",
            "name" => "Post atualizado",
            "image" => UploadedFile::fake()->create("new.jpg", 10, "image/jpeg"),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas("posts", [
            "id" => $post->id,
            "name" => "Post atualizado",
        ]);
        Storage::disk("public")->assertMissing("uploads/old.jpg");
        Storage::disk("public")->assertExists($post->fresh()->image);
    }

    public function test_update_requires_at_least_one_field(): void
    {
        $user = User::factory()->create();
        $categoria = $user->categorias()->create([
            "name" => "Tecnologia",
            "slug" => "tecnologia",
            "description" => "Categoria de tecnologia",
        ]);
        $post = new Post();
        $post->name = "Post antigo";
        $post->description = "Descricao antiga para o post existente.";
        $post->image = "uploads/post.jpg";
        $post->categoria_id = $categoria->id;
        $post->user_id = $user->id;
        $post->save();

        $this->actingAs($user, "sanctum")
            ->putJson("/api/v1/posts/{$post->id}", [])
            ->assertUnprocessable();

        $this->assertDatabaseHas("posts", [
            "id" => $post->id,
            "name" => "Post antigo",
        ]);
    }

    public function test_user_cannot_update_another_users_post(): void
    {
        $owner = User::factory()->create();
        $category = $owner->categorias()->create([
            "name" => "Tecnologia",
            "slug" => "tecnologia",
            "description" => "Categoria de tecnologia",
        ]);
        $post = new Post();
        $post->name = "Post privado";
        $post->description = "Descricao de um post pertencente a outro usuario.";
        $post->image = "uploads/post.jpg";
        $post->categoria_id = $category->id;
        $post->user_id = $owner->id;
        $post->save();

        $this->actingAs(User::factory()->create(), "sanctum")
            ->putJson("/api/v1/posts/{$post->id}", ["name" => "Alterado"])
            ->assertNotFound();
    }
}
