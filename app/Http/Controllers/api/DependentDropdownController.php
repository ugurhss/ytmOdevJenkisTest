<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\City\CityService;
use App\Services\ModelClass\ModelClassService;
use App\Services\University\UniversityService;
use App\Services\Faculty\FacultyService;
use App\Services\Department\DepartmentService;
use Illuminate\Http\JsonResponse;

class DependentDropdownController extends Controller
{
    protected CityService $cityService;
    protected UniversityService $universityService;
    protected FacultyService $facultyService;
    protected DepartmentService $departmentService;
    protected  ModelClassService  $classService;

    public function __construct(
        CityService $cityService,
        UniversityService $universityService,
        FacultyService $facultyService,
        DepartmentService $departmentService,
        ModelClassService $classService
    ) {
        $this->cityService = $cityService;
        $this->universityService = $universityService;
        $this->facultyService = $facultyService;
        $this->departmentService = $departmentService;
        $this->classService = $classService;
    }


    public function universities(int $cityId): JsonResponse
    {
        $universities = $this->universityService->getByForeignKey('city_id', $cityId);
        return response()->json($universities);
    }


    public function faculties(int $universityId): JsonResponse
    {
        $faculties = $this->facultyService->getByForeignKey('university_id', $universityId);
        return response()->json($faculties);
    }


    public function departments(int $facultyId): JsonResponse
    {
        $departments = $this->departmentService->getByForeignKey('faculty_id', $facultyId);
        return response()->json($departments);
    }


    public function classes(int $departmentId): JsonResponse
    {
        $classes = $this->classService->getByForeignKey('department_id', $departmentId);
        return response()->json($classes);
    }
}
