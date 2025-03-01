<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Visitor;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        return view('visitors.create', compact('departments'));
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Основные поля посетителя
                'full_name'      => 'required|string|max:150',
                'department_id'  => 'nullable|exists:departments,id',
                'birth_date'     => 'required|date_format:d.m.Y|before:today',
                'position'       => 'required|string|max:150',
                'phone'          => 'required|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
                'entry_time'     => 'required|date_format:d.m.Y H:i|before:exit_time|before:today',
                'exit_time'      => 'required|date_format:d.m.Y H:i|after:entry_time|before:today',
                'notes'          => 'nullable|string|max:256',
            
                // Тип документа
                'document_type'  => 'required|in:passport,drivers_license,other',
            
                // Поля для паспорта (валидируются только если document_type == passport)
                'series'         => 'exclude_unless:document_type,passport|required|numeric|digits:4',
                'number'         => 'exclude_unless:document_type,passport|required|numeric|digits:6',
                'issue_date'     => 'exclude_unless:document_type,passport|required|date_format:d.m.Y|after:birth_date|before:today',
                'issued_by'      => 'exclude_unless:document_type,passport|required|string|max:250',
                'department_code'=> 'exclude_unless:document_type,passport|nullable|regex:/^\d{3}-\d{3}$/',
            
                // Поля для водительских прав (валидируются только если document_type == drivers_license)
                'series_dl'      => 'exclude_unless:document_type,drivers_license|required|numeric|digits:4',
                'number_dl'      => 'exclude_unless:document_type,drivers_license|required|numeric|digits:6',
                'issue_date_dl'  => 'exclude_unless:document_type,drivers_license|required|date_format:d.m.Y|after:birth_date|before:today',
                'issued_by_dl'   => 'exclude_unless:document_type,drivers_license|required|string|max:250',
                'region_dl'      => 'exclude_unless:document_type,drivers_license|nullable|string|max:150',
            
                // Поля для другого документа (валидируются только если document_type == other)
                'document_name_other'  => 'exclude_unless:document_type,other|required|string|max:150',
                'series_other'         => 'exclude_unless:document_type,other|required|numeric',
                'number_other'         => 'exclude_unless:document_type,other|required|numeric',
                'issue_date_other'     => 'exclude_unless:document_type,other|required|date_format:d.m.Y|after:birth_date|before:today',
                'issued_by_other'      => 'exclude_unless:document_type,other|required|string|max:250',
                'department_code_other'=> 'exclude_unless:document_type,other|nullable|regex:/^\d{3}-\d{3}$/',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput(); 
            }
            $validated = $validator->validated();
            DB::transaction(function () use ($validated) {
               
                $visitor = Visitor::create([
                    'full_name'     => $validated['full_name'],
                    'department_id' => $validated['department_id'],
                    'birth_date'    => DateTime::createFromFormat('d.m.Y', $validated['birth_date']),
                    'position'      => $validated['position'],
                    'phone'         => $validated['phone'],
                    'entry_time'    => DateTime::createFromFormat('d.m.Y H:i', $validated['entry_time']),
                    'exit_time'     => DateTime::createFromFormat('d.m.Y H:i', $validated['exit_time']),
                    'notes'         => $validated['notes'] ?? null,
                ]);
            
               
                switch ($validated['document_type']) {
                    case 'passport':
                        $documentData = [
                            'type'             => 'passport',
                            'document_name'    => null,
                            'series'           => $validated['series'],
                            'number'           => $validated['number'],
                            'issue_date'       => DateTime::createFromFormat('d.m.Y', $validated['issue_date']),
                            'issued_by'        => $validated['issued_by'],
                            'department_code'  => $validated['department_code'] ?? null,
                            'region'           => null,
                        ];
                        break;
            
                    case 'drivers_license':
                        $documentData = [
                            'type'             => 'drivers_license',
                            'document_name'    => null,
                            'series'           => $validated['series_dl'],
                            'number'           => $validated['number_dl'],
                            'issue_date'       => DateTime::createFromFormat('d.m.Y', $validated['issue_date_dl']),
                            'issued_by'        => $validated['issued_by_dl'],
                            'department_code'  => null,
                            'region'           => $validated['region_dl'] ?? null,
                        ];
                        break;
            
                    case 'other':
                        $documentData = [
                            'type'             => 'other',
                            'document_name'    => $validated['document_name_other'],
                            'series'           => $validated['series_other'],
                            'number'           => $validated['number_other'],
                            'issue_date'       => DateTime::createFromFormat('d.m.Y', $validated['issue_date_other']),
                            'issued_by'        => $validated['issued_by_other'],
                            'department_code'  => $validated['department_code_other'] ?? null,
                            'region'           => null,
                        ];
                        break;
                }
            
                $visitor->document()->create($documentData);
            });

            return redirect()->back()->with('success', 'Успешно');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Ошибка: " . $e->getMessage());
        }
    }
}
