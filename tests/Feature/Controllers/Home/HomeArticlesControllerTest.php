<?php

namespace Tests\Feature\Controllers\Home;

use App\Article;
use App\Category;
use App\SubCategory;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeArticlesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $articles;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $categories;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $subCategories;

    /**
     * @var string
     */
    private $indexRequestMethod;

    /**
     * @var string
     */
    private $indexRequestRoute;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        // Fill database
        $this->runFactories();

        $this->user = User::first();
        $this->indexRequestMethod = 'get';
        $this->categories = Category::all();
        $this->subCategories = SubCategory::all();
        $this->indexRequestRoute = route('getHomeArticles');
        $this->articles = Article::select('id', 'title', 'sub_category_id')->with('headerImage')->get();
    }

    /**
     * Test index request
     */
    public function testIndex(): void
    {
        // Make request
        $response = $this->json($this->indexRequestMethod, $this->indexRequestRoute);

        // Check response status
        $response->assertStatus(200);

        // Merge categories, sub-categories and articles in categories collection
        $this->margeCategoriesSubCategoriesAndArticles();

        // Convert collection to array
        $array = $this->getCategoriesConvertedIntoArray();

        $response->assertExactJson($array);
    }

    private function getCategoriesConvertedIntoArray(): array
    {
        return $this->categories->map(function (Category $category) {
            $category->sub_categories = $category->sub_categories->map(function (SubCategory $subCategory) {
                $subCategory->articles = $subCategory->articles->values()->toArray();

                return $subCategory;
            })->values()->toArray();

            return $category;
        })->toArray();
    }

    /**
     * Merge categories, sub-categories and articles in categories collection
     */
    private function margeCategoriesSubCategoriesAndArticles(): void
    {
        $this->addArticlesToSubCategories();
        $this->addSubCategoriesToCategories();
    }

    private function addArticlesToSubCategories(): void
    {
        $this->subCategories->transform(function (SubCategory $subCategory) {
            $subCategory['articles'] = $this->articles->where('sub_category_id', $subCategory->id);

            return $subCategory;
        });
    }

    private function addSubCategoriesToCategories(): void
    {
        $this->categories->transform(function (Category $category) {
            $category['sub_categories'] = $this->subCategories->where('category_id', $category->id);

            return $category;
        });
    }

    private function runFactories(): void
    {
        // Create user
        $this->createUser();

        // Create categories, sub-categories and articles
        $this->createCategoriesAndSubCategories();
    }

    private function createCategoriesAndSubCategories(): void
    {
        factory(Category::class, 2)->create()->each(function ($category) {
            $category->subCategories()
                ->saveMany(factory(SubCategory::class, 2)// Create two sub-categories
                ->create(['category_id' => $category->id]))
                ->each(function ($subCategory) {
                    // Create articles
                    factory(Article::class, 8)->create(['sub_category_id' => $subCategory->id]);
                });
        });
    }

    private function createUser(): void
    {
        factory(User::class)->create();
    }
}
