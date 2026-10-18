<?php

namespace Thanhnt\Amuaglobal\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

final class CategoryController extends Controller
{
    /**
     * show list of category
     *
     * @return void
     */
    public function listCategory(){
        return Inertia::render('Amuaglobal/Screens/Adminhtml/Category/CategoryList');
    }
}
