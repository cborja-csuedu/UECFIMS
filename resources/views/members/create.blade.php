@extends('layouts.app')

@section('content')
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .modal-content h2 {
        color: #28a745;
        margin-bottom: 15px;
    }
    .modal-content p {
        color: #666;
        margin-bottom: 20px;
    }
    .btn-modal {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }
    .btn-modal:hover {
        background: #0056b3;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register New Member</div>

                <div class="card-body">
                    <form id="memberForm" method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}" required>

                            @error('birthdate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="local_center">Local Center</label>
                            <input id="local_center" type="text" class="form-control @error('local_center') is-invalid @enderror" name="local_center" value="{{ old('local_center') }}" required>

                            @error('local_center')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" required>{{ old('address') }}</textarea>

                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">

                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="civil_status">Civil Status</label>
                            <select id="civil_status" class="form-control @error('civil_status') is-invalid @enderror" name="civil_status">
                                <option value="">Select Civil Status</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>

                            @error('civil_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input id="occupation" type="text" class="form-control @error('occupation') is-invalid @enderror" name="occupation" value="{{ old('occupation') }}">

                            @error('occupation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact_name">Emergency Contact Name</label>
                            <input id="emergency_contact_name" type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">

                            @error('emergency_contact_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact_phone">Emergency Contact Phone</label>
                            <input id="emergency_contact_phone" type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">

                            @error('emergency_contact_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="baptism_date">Baptism Date</label>
                            <input id="baptism_date" type="date" class="form-control @error('baptism_date') is-invalid @enderror" name="baptism_date" value="{{ old('baptism_date') }}">

                            @error('baptism_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="membership_date">Membership Date</label>
                            <input id="membership_date" type="date" class="form-control @error('membership_date') is-invalid @enderror" name="membership_date" value="{{ old('membership_date') }}">

                            @error('membership_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="picture">1x1 Picture</label>
                            <input id="picture" type="file" class="form-control @error('picture') is-invalid @enderror" name="picture" accept="image/*">

                            @error('picture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="esign">E-Signature</label>
                            <input id="esign" type="file" class="form-control @error('esign') is-invalid @enderror" name="esign" accept="image/*">

                            @error('esign')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Register Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <h2>✓ Registration Successful!</h2>
        <p>Member has been registered successfully. Secretary 1 has been notified to review the registration.</p>
        <button class="btn-modal" onclick="closeModalAndReset()">Register Another Member</button>
    </div>
</div>

<script>
    // Check if there's a success message in the page
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showSuccessModal();
        @endif
    });

    function showSuccessModal() {
        document.getElementById('successModal').classList.add('show');
    }

    function closeModalAndReset() {
        document.getElementById('successModal').classList.remove('show');
        document.getElementById('memberForm').reset();
        // Scroll to top so user can start filling the form
        window.scrollTo(0, 0);
        // Focus on first input field
        document.getElementById('name').focus();
    }
</script>

@endsection