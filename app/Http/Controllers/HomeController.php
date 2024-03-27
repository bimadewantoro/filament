<?php

namespace App\Http\Controllers;


use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home', [
            'courses' =>  CourseResource::collection(Course::all()),
        ]);
    }
}
