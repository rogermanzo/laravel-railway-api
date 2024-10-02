<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        if($students -> isEmpty()){
            $data = [
                'message' => 'No se encontraron estudiantes',
                'status' => 200
            ];
            return response()-> json($data, 200);
        }

        return response()-> json($students, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:en,es'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()-> json($data, 400);
        }

        try {
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'language' => $request->language,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al crear el estudiante'], 500);
        }

        $data = [
            'student' => $student,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if(!$student) {
            $data = [
                'message' => 'Estudiante no encontrado.',
                'status' => 403
            ];
            return response()->json($data, 403);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if(!$student){
            $data = [
                'message' => 'Estudiante no encontrado.',
                'status' => 403
            ];
            return response()->json($data, 403);
        }

        $student->delete();

        $data = [
            'message' => 'Se ha eliminado el estudiante.',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if(!$student){
            $data = [
                'message' => 'Estudiante no encontrado.',
                'status' => 403
            ];
            return response()->json($data, 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:en,es'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()-> json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
            'student' => $student,
            'status' => 200
        ];

        return response()-> json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $student = Student::find($id);

        if(!$student){
            $data = [
                'message' => 'Estudiante no encontrado.',
                'status' => 403
            ];
            return response()->json($data, 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'max:25',
            'email' => 'email|unique:student',
            'phone' => 'digits:10',
            'language' => 'in:en,es'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()-> json($data, 400);
        }

        if($request->has('name')) {
            $student->name = $request->name;
        }
        
        if($request->has('email')) {
            $student->email = $request->email;
        }

        if($request->has('phone')) {
            $student->phone = $request->phone;
        }

        if($request->has('language')) {
            $student->language = $request->language;
        }

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
