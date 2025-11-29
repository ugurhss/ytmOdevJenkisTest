<?php

namespace App\Http\Controllers\Student;

use App\Exports\StudentsSampleExport;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\Group\GroupService;
use App\Services\Student\Importers\StudentImporterFactory;
use App\Services\Student\StudentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use AuthorizesRequests;

    protected StudentService $studentService;
    protected GroupService $groupService;

    public function __construct(StudentService $studentService, GroupService $groupService)
    {
        $this->studentService = $studentService;
        $this->groupService = $groupService;
    }

    public function studentCreate(int $groupId)
    {
        $group = $this->groupService->getById($groupId);
        $this->authorize('create', Group::class);

        return Inertia::render('Students/Create', [
            'group' => $group,
        ]);
    }

   public function studentStore(Request $request, int $groupId)
    {
        $importType = $request->input('import_type');

        // MANUEL KAYIT
        if ($importType === 'manual') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'no'   => 'required|string|max:950',
            ]);
        }
        // CSV veya EXCEL
        else {
            $validated = $request->validate([
                'file' => 'required|file|mimes:csv,xlsx,xls',
            ]);
        }

        // Importer seç
        $importer = StudentImporterFactory::make($importType);

        try {
            // Import işlemi
            $students = $importer->import($validated, $groupId);

            // Role ataması
            foreach ($students as $student) {
                if(!$student->hasRole('student')){
                    $student->assignRole('student');
                }
            }

            // --- DEĞİŞİKLİK BURADA ---
            // back() yerine redirect()->route('dashboard') kullanıyoruz.
            return redirect()->route('dashboard')
                             ->with('success', 'Öğrenci(ler) başarıyla eklendi!');

        } catch (\Exception $e) {
            // Hata durumunda formda kalması daha iyidir, o yüzden burası back() kalabilir.
            // Toast mesajı için 'error' key'ini kullanıyoruz.
            return back()->with('error', 'Hata oluştu: ' . $e->getMessage());
        }
    }

    // Örnek CSV dosyası indir
    public function downloadSampleCsv()
    {
        $filename = 'ornek_ogrenci_listesi.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Sadece bu iki alan yeterli
        $columns = ['name', 'no'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($file, $columns);

            // Örnek veriler
            fputcsv($file, ['Ahmet Yılmaz', '101']);
            fputcsv($file, ['Ayşe Demir', '102']);
            fputcsv($file, ['Mehmet Kaya', '103']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Örnek Excel dosyası indir
    public function downloadSampleExcel()
    {
        return Excel::download(new StudentsSampleExport(), 'ornek_ogrenci_listesi.xlsx');
    }

    public function studentIndex(int $groupId)
    {
        $group = $this->groupService->getById($groupId);
        $this->authorize('view', $group);
        $students = $this->groupService->getStudents($groupId);

        return Inertia::render('Students/Index', [
            'group' => $group,
            'students' => $students,
        ]);
    }

    public function studentDestroy(int $groupId, int $studentId)
    {
        $group = $this->groupService->getById($groupId);
        $this->authorize('delete', $group);
        $group->students()->detach($studentId);
        return back()->with('success', 'Öğrenci gruptan çıkarıldı.');
    }
}
