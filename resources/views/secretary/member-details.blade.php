@extends('layouts.dashboard-header')

@section('content')
    <div class="member-details-container">
        <div class="member-header">
            <h1>Member Application Details</h1>
            <a href="{{ route('secretary.dashboard') }}" class="btn-back">← Back to Dashboard</a>
        </div>

        <div class="member-details-card">
            <!-- Basic Information -->
            <div class="detail-section">
                <h3>📋 Basic Information</h3>
                <div class="detail-row">
                    <div class="detail-label">Full Name:</div>
                    <div class="detail-value">{{ $member->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $member->email ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value">{{ $member->phone ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date of Birth:</div>
                    <div class="detail-value">{{ $member->birthdate ? $member->birthdate->format('M d, Y') : 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Gender:</div>
                    <div class="detail-value">{{ $member->gender ? ucfirst($member->gender) : 'N/A' }}</div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="detail-section">
                <h3>🏠 Contact Information</h3>
                <div class="detail-row">
                    <div class="detail-label">Local Center:</div>
                    <div class="detail-value">{{ $member->local_center }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Address:</div>
                    <div class="detail-value">{{ $member->address }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Zip Code:</div>
                    <div class="detail-value">{{ $member->zip_code }}</div>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="detail-section">
                <h3>👤 Personal Details</h3>
                <div class="detail-row">
                    <div class="detail-label">Civil Status:</div>
                    <div class="detail-value">{{ $member->civil_status ? ucfirst($member->civil_status) : 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Occupation:</div>
                    <div class="detail-value">{{ $member->occupation ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Emergency Contact Name:</div>
                    <div class="detail-value">{{ $member->emergency_contact_name ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Emergency Contact Phone:</div>
                    <div class="detail-value">{{ $member->emergency_contact_phone ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Religious Information -->
            <div class="detail-section">
                <h3>⛪ Religious Information</h3>
                <div class="detail-row">
                    <div class="detail-label">Baptism Date:</div>
                    <div class="detail-value">{{ $member->baptism_date ? $member->baptism_date->format('M d, Y') : 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Membership Date:</div>
                    <div class="detail-value">{{ $member->membership_date ? $member->membership_date->format('M d, Y') : 'N/A' }}</div>
                </div>
            </div>

            <!-- Submission Information -->
            <div class="detail-section">
                <h3>📝 Submission Information</h3>
                <div class="detail-row">
                    <div class="detail-label">Submitted By:</div>
                    <div class="detail-value">{{ $member->user->name ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Submitted Date:</div>
                    <div class="detail-value">{{ $member->created_at->format('M d, Y H:i A') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Current Status:</div>
                    <div class="detail-value">
                        <span class="status-badge status-{{ $member->status }}">{{ ucfirst($member->status) }}</span>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            @if($member->picture || $member->esign)
            <div class="detail-section">
                <h3>📄 Documents</h3>
                @if($member->picture)
                <div class="document-row">
                    <div class="document-label">Picture:</div>
                    <div class="document-preview">
                        <img src="{{ asset('storage/' . $member->picture) }}" alt="Member Picture" class="member-photo">
                        <a href="{{ asset('storage/' . $member->picture) }}" target="_blank" class="btn-small">View Full Size</a>
                    </div>
                </div>
                @endif
                @if($member->esign)
                <div class="document-row">
                    <div class="document-label">E-Signature:</div>
                    <div class="document-preview">
                        <img src="{{ asset('storage/' . $member->esign) }}" alt="E-Signature" class="member-esign">
                        <a href="{{ asset('storage/' . $member->esign) }}" target="_blank" class="btn-small">View Full Size</a>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Action Buttons -->
            @if(Auth::user()->email === 'secretary1@example.com' && $member->status === 'submitted')
            <div class="action-buttons">
                <form action="{{ route('secretary.members.verify', $member->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-approve">
                        ✓ Approve Application
                    </button>
                </form>
                <a href="{{ route('secretary.dashboard') }}" class="btn-cancel">
                    ✗ Cancel & Return
                </a>
            </div>
            @elseif($member->status !== 'submitted')
            <div class="info-message">
                <p>This application has already been processed with status: <strong>{{ ucfirst($member->status) }}</strong></p>
            </div>
            @endif
        </div>
    </div>

    <style>
        .member-details-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .member-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }

        .member-header h1 {
            margin: 0;
            color: #333;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .member-details-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .detail-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .detail-section:last-of-type {
            border-bottom: none;
        }

        .detail-section h3 {
            color: #007bff;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.2em;
            border-left: 4px solid #007bff;
            padding-left: 10px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 12px 0;
            gap: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .detail-value {
            color: #333;
            word-break: break-word;
        }

        .document-row {
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .document-row:last-child {
            border-bottom: none;
        }

        .document-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }

        .document-preview {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .member-photo {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            object-fit: cover;
        }

        .member-esign {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-submitted {
            background: #ffc107;
            color: #333;
        }

        .status-approved {
            background: #28a745;
            color: white;
        }

        .status-rejected {
            background: #dc3545;
            color: white;
        }

        .action-buttons {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #007bff;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-approve {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-approve:hover {
            background: #218838;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 1em;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .info-message {
            background: #e7f3f8;
            border: 1px solid #b8dce8;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            border-radius: 4px;
            color: #004085;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .detail-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .member-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .action-buttons {
                flex-direction: column;
                justify-content: flex-start;
            }

            .btn-approve, .btn-cancel {
                width: 100%;
            }

            .document-preview {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection
