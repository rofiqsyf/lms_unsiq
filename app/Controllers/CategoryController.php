<?php
namespace App\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Core\Session;

/**
 * CategoryController
 * Manajemen kategori mata kuliah (Admin Only)
 */
class CategoryController extends BaseController
{
    private Category $categoryModel;
    private Course $courseModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->courseModel = new Course();
    }

    /** GET /categories */
    public function index(): void
    {
        $this->setTitle('Manajemen Kategori');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => '/dashboard'],
            ['label' => 'Kategori']
        ]);

        $categories = $this->categoryModel->getAllWithCount();

        $this->render('categories/index', [
            'pageTitle' => 'Kategori Mata Kuliah',
            'categories' => $categories
        ]);
    }

    /** POST /categories */
    public function store(): void
    {
        $this->validateCSRF();
        
        $data = $this->validate($this->allInput(), [
            'name' => 'required|min:3|max:100',
        ]);

        // Auto-generate slug
        $slug = slugify($data['name']);
        
        // Ensure slug is unique
        $existing = $this->categoryModel->findBy('slug', $slug);
        if (!empty($existing)) {
            $slug .= '-' . time();
        }

        $this->categoryModel->create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $this->input('description', '')
        ]);

        Session::flash('success', 'Kategori berhasil ditambahkan.');
        $this->redirect(url('/categories'));
    }

    /** POST /categories/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            $this->redirect(url('/categories'));
            return;
        }

        $data = $this->validate($this->allInput(), [
            'name' => 'required|min:3|max:100',
        ]);

        $slug = slugify($data['name']);

        // Check if slug exists and not current category
        $existing = $this->categoryModel->findBy('slug', $slug);
        if (!empty($existing) && $existing[0]['id'] != $id) {
            $slug .= '-' . time();
        }

        $this->categoryModel->update($id, [
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $this->input('description', '')
        ]);

        Session::flash('success', 'Kategori berhasil diperbarui.');
        $this->redirect(url('/categories'));
    }

    /** POST /categories/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            $this->redirect(url('/categories'));
            return;
        }

        // Check if category is used by any courses
        $courseCount = $this->courseModel->count('category_id = ?', [$id]);
        if ($courseCount > 0) {
            Session::flash('error', 'Gagal: Kategori ini sedang digunakan oleh ' . $courseCount . ' mata kuliah.');
            $this->redirect(url('/categories'));
            return;
        }

        if ($this->categoryModel->delete($id)) {
            Session::flash('success', 'Kategori berhasil dihapus.');
        } else {
            Session::flash('error', 'Gagal menghapus kategori.');
        }

        $this->redirect(url('/categories'));
    }
}
