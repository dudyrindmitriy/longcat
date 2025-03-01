@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Добавить посетителя</h1>
    <p class="text-muted">* - обязательные для заполнения поля</p>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('visitors.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="full_name" class="form-label">ФИО *</label>
            <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" maxlength="150" required>
            @error('full_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Отдел</label>
            <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                <option value="">Не выбрано</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
            @error('department_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="birth_date" class="form-label">Дата рождения *</label>
            <input type="text" class="form-control datepicker @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
            @error('birth_date')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Должность *</label>
            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" maxlength="150" required>
            @error('position')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Номер телефона *</label>
            <input type="text" class="form-control phone-mask @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Документ, удостоверяющий личность *</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="document_type" id="passport" value="passport" {{ old('document_type', 'passport') == 'passport' ? 'checked' : '' }}>
                <label class="form-check-label" for="passport">Паспорт</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="document_type" id="drivers_license" value="drivers_license" {{ old('document_type') == 'drivers_license' ? 'checked' : '' }}>
                <label class="form-check-label" for="drivers_license">Водительское удостоверение</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="document_type" id="other" value="other" {{ old('document_type') == 'other' ? 'checked' : '' }}>
                <label class="form-check-label" for="other">Прочее</label>
            </div>
            @error('document_type')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div id="passport_fields" class="document-fields" style="display: none;">
            <div class="mb-3">
                <label for="series" class="form-label">Серия *</label>
                <input type="text" class="form-control series-mask @error('series') is-invalid @enderror" id="series" name="series" value="{{ old('series') }}">
                @error('series')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="number" class="form-label">Номер *</label>
                <input type="text" class="form-control number-mask @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}">
                @error('number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issue_date" class="form-label">Дата выдачи *</label>
                <input type="text" class="form-control datepicker @error('issue_date') is-invalid @enderror" id="issue_date" name="issue_date" value="{{ old('issue_date') }}">
                @error('issue_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issued_by" class="form-label">Кем выдан *</label>
                <input type="text" class="form-control @error('issued_by') is-invalid @enderror" id="issued_by" name="issued_by" value="{{ old('issued_by') }}" maxlength="250">
                @error('issued_by')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="department_code" class="form-label">Код подразделения *</label>
                <input type="text" class="form-control department-code-mask @error('department_code') is-invalid @enderror" id="department_code" name="department_code" value="{{ old('department_code') }}">
                @error('department_code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div id="drivers_license_fields" class="document-fields" style="display: none;">
            <div class="mb-3">
                <label for="series_dl" class="form-label">Серия *</label>
                <input type="text" class="form-control series-mask @error('series_dl') is-invalid @enderror" id="series_dl" name="series_dl" value="{{ old('series_dl') }}">
                @error('series_dl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="number_dl" class="form-label">Номер *</label>
                <input type="text" class="form-control number-mask @error('number_dl') is-invalid @enderror" id="number_dl" name="number_dl" value="{{ old('number_dl') }}">
                @error('number_dl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issue_date_dl" class="form-label">Дата выдачи *</label>
                <input type="text" class="form-control datepicker @error('issue_date_dl') is-invalid @enderror" id="issue_date_dl" name="issue_date_dl" value="{{ old('issue_date_dl') }}">
                @error('issue_date_dl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="region_dl" class="form-label">Регион *</label>
                <input type="text" class="form-control @error('region_dl') is-invalid @enderror" id="region_dl" name="region_dl" value="{{ old('region_dl') }}" maxlength="150">
                @error('region_dl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issued_by_dl" class="form-label">Кем выдан *</label>
                <input type="text" class="form-control @error('issued_by_dl') is-invalid @enderror" id="issued_by_dl" name="issued_by_dl" value="{{ old('issued_by_dl') }}" maxlength="250">
                @error('issued_by_dl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div id="other_fields" class="document-fields" style="display: none;">
            <div class="mb-3">
                <label for="document_name_other" class="form-label">Название документа *</label>
                <input type="text" class="form-control @error('document_name_other') is-invalid @enderror" id="document_name_other" name="document_name_other" value="{{ old('document_name_other') }}" maxlength="150">
                @error('document_name_other')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="series_other" class="form-label">Серия *</label>
                <input type="text" class="form-control @error('series_other') is-invalid @enderror" id="series_other" name="series_other" value="{{ old('series_other') }}">
                @error('series_other')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="number_other" class="form-label">Номер *</label>
                <input type="text" class="form-control @error('number_other') is-invalid @enderror" id="number_other" name="number_other" value="{{ old('number_other') }}">
                @error('number_other')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issue_date_other" class="form-label">Дата выдачи *</label>
                <input type="text" class="form-control datepicker @error('issue_date_other') is-invalid @enderror" id="issue_date_other" name="issue_date_other" value="{{ old('issue_date_other') }}">
                @error('issue_date_other')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issued_by_other" class="form-label">Кем выдан *</label>
                <input type="text" class="form-control @error('issued_by_other') is-invalid @enderror" id="issued_by_other" name="issued_by_other" value="{{ old('issued_by_other') }}" maxlength="250">
                @error('issued_by_other')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="entry_time" class="form-label">Дата и время входа *</label>
            <input type="text" class="form-control datetimepicker @error('entry_time') is-invalid @enderror" id="entry_time" name="entry_time" value="{{ old('entry_time') }}" required>
            @error('entry_time')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="exit_time" class="form-label">Дата и время выхода *</label>
            <input type="text" class="form-control datetimepicker @error('exit_time') is-invalid @enderror" id="exit_time" name="exit_time" value="{{ old('exit_time') }}" required>
            @error('exit_time')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Замечание</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" maxlength="256">{{ old('notes') }}</textarea>
            @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('visitors.create') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection

@section('scripts')

<script>
    console.log(2);
    console.log(typeof $);
    $(document).ready(function() {
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'd.m.Y',
            lang: 'ru'
        });

        $('.datetimepicker').datetimepicker({
            format: 'd.m.Y H:i',
            lang: 'ru',
            step: 5
        });

        $('.phone-mask').mask('+7(999)999-99-99');
        $('.department-code-mask').mask('999-999');
        $('.series-mask').mask('9999');
        $('.number-mask').mask('999999');

        // Function to toggle document fields visibility
        function toggleDocumentFields() {
            var documentType = $('input[name="document_type"]:checked').val();
            $('.document-fields').hide(); // Hide all document fields
            $('#' + documentType + '_fields').show(); // Show the selected document fields

            // Make required fields based on document type
            $('.document-fields input').prop('required', false); //Clear all first
            $('#' + documentType + '_fields input').prop('required', true);
            if (documentType == 'other') {
                $('#document_name').prop('required', true);
            }
        }

        // Initial call to toggleDocumentFields
        toggleDocumentFields();

        // Attach toggleDocumentFields to radio button change event
        $('input[name="document_type"]').change(function() {
            toggleDocumentFields();
        });
    });
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    .document-fields {
        margin-top: 15px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
    }
</style>
@endsection