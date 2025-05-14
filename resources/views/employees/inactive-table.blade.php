<div id="inactive-employees-section">
    <h2>Inactive Employees</h2>
    <div class="mb-3">
        <input type="text" id="inactiveEmployeeSearch" class="form-control" placeholder="Search by name..." 
               hx-get="{{ route('employees.inactive') }}" 
               hx-target="#inactive-employees-section" 
               hx-swap="innerHTML" 
               hx-trigger="input delay:500ms" 
               name="search" 
               value="{{ $search ?? '' }}">
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" id="success-message">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" id="error-message">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('error') }}
        </div>
    @endif
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible" id="error-message">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="fallback-error" class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        An unexpected error occurred. Please try again.
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Inactive Date</th>
                    <th>QR Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ $employee->fname }} {{ $employee->mname ? $employee->mname . ' ' : '' }}{{ $employee->lname }}</td>
                        <td>{{ $employee->position->position_name ?? 'N/A' }}</td>
                        <td>{{ $employee->deleted_at ? $employee->deleted_at->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            @if ($employee->qr_code)
                                <button class="btn btn-sm btn-info view-qr" data-qr="{{ $employee->qr_code }}">View QR</button>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <form hx-post="{{ route('employees.restore', $employee->employee_id) }}" 
                                  hx-target="#inactive-employees-section" 
                                  hx-swap="innerHTML" 
                                  hx-push-url="false"
                                  hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' 
                                  hx-indicator="#restore-loading-{{ $employee->employee_id }}"
                                  style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" 
                                        onclick="return confirm('Are you sure you want to restore this employee?')">Restore</button>
                                <span id="restore-loading-{{ $employee->employee_id }}" class="htmx-indicator">Loading...</span>
                            </form>
                            <form hx-delete="{{ route('employees.destroy', $employee->employee_id) }}" 
                                  hx-target="#inactive-employees-section" 
                                  hx-swap="innerHTML" 
                                  hx-push-url="false"
                                  hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' 
                                  hx-indicator="#delete-loading-{{ $employee->employee_id }}"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to permanently delete this employee?')">Delete</button>
                                <span id="delete-loading-{{ $employee->employee_id }}" class="htmx-indicator">Loading...</span>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No inactive employees found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>