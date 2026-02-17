<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Edit User - Gizmo SD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #2c3e50;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        /* Form Container */
        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .form-header {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            padding: 2.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .form-header-icon {
            font-size: 3rem;
            opacity: 0.9;
            min-width: 60px;
        }

        .form-header-text h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .form-header-text p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin: 0;
        }

        /* Form Body */
        .form-body {
            padding: 2.5rem;
        }

        /* Section Title */
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-title i {
            color: #DC143C;
            font-size: 1.3rem;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label .optional {
            font-size: 0.8rem;
            color: #95a5a6;
            font-weight: 400;
            margin-left: auto;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(255, 153, 0, 0.15);
            outline: none;
        }

        /* Grid Layout */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-check {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            margin-bottom: 1.5rem;
        }

        .form-check input {
            cursor: pointer;
            accent-color: #DC143C;
        }

        .form-check label {
            cursor: pointer;
            margin-bottom: 0;
            font-weight: 500;
        }

        /* Form Text */
        .form-text {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 0.4rem;
            display: block;
        }

        /* Error Messages */
        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #fadbd8;
            color: #c0392b;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #e74c3c;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .alert-danger li {
            margin-bottom: 0.5rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .btn-submit {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-submit:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 153, 0, 0.3);
        }

        .btn-cancel {
            background: #ecf0f1;
            color: #2c3e50;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-cancel:hover {
            background: #bdc3c7;
            color: white;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .form-header {
                flex-direction: column;
                text-align: center;
                padding: 2rem;
            }

            .form-header-text h1 {
                font-size: 1.5rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .form-header {
                padding: 1.5rem;
            }

            .form-header-icon {
                font-size: 2.5rem;
            }

            .form-header-text h1 {
                font-size: 1.3rem;
            }

            .form-body {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-card">
            <!-- Header -->
            <div class="form-header">
                <i class="fas fa-user-edit form-header-icon"></i>
                <div class="form-header-text">
                    <h1>Edit User Profile</h1>
                    <p>Update user information and settings</p>
                </div>
            </div>

            <!-- Form Body -->
            <div class="form-body">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert-danger">
                        <strong><i class="fas fa-exclamation-circle me-2"></i>Please fix the following errors:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i> Personal Information
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user" style="color: #DC143C;"></i> Full Name
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                placeholder="Enter full name"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope" style="color: #DC143C;"></i> Email Address
                            </label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                placeholder="user@example.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone" style="color: #DC143C;"></i> Phone Number
                                <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                placeholder="+1 (555) 000-0000"
                            >
                            <span class="form-text"><i class="fas fa-info-circle"></i> Include country code</span>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Address Information
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-globe" style="color: #DC143C;"></i> Country
                            </label>
                            <select name="country" class="form-select @error('country') is-invalid @enderror">
                                <option value="">-- Select a Country --</option>
                                <option value="Afghanistan" {{ old('country', $user->country) === 'Afghanistan' ? 'selected' : '' }}>Afghanistan</option>
                                <option value="Albania" {{ old('country', $user->country) === 'Albania' ? 'selected' : '' }}>Albania</option>
                                <option value="Algeria" {{ old('country', $user->country) === 'Algeria' ? 'selected' : '' }}>Algeria</option>
                                <option value="Andorra" {{ old('country', $user->country) === 'Andorra' ? 'selected' : '' }}>Andorra</option>
                                <option value="Angola" {{ old('country', $user->country) === 'Angola' ? 'selected' : '' }}>Angola</option>
                                <option value="Argentina" {{ old('country', $user->country) === 'Argentina' ? 'selected' : '' }}>Argentina</option>
                                <option value="Armenia" {{ old('country', $user->country) === 'Armenia' ? 'selected' : '' }}>Armenia</option>
                                <option value="Australia" {{ old('country', $user->country) === 'Australia' ? 'selected' : '' }}>Australia</option>
                                <option value="Austria" {{ old('country', $user->country) === 'Austria' ? 'selected' : '' }}>Austria</option>
                                <option value="Azerbaijan" {{ old('country', $user->country) === 'Azerbaijan' ? 'selected' : '' }}>Azerbaijan</option>
                                <option value="Bahamas" {{ old('country', $user->country) === 'Bahamas' ? 'selected' : '' }}>Bahamas</option>
                                <option value="Bahrain" {{ old('country', $user->country) === 'Bahrain' ? 'selected' : '' }}>Bahrain</option>
                                <option value="Bangladesh" {{ old('country', $user->country) === 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                <option value="Barbados" {{ old('country', $user->country) === 'Barbados' ? 'selected' : '' }}>Barbados</option>
                                <option value="Belarus" {{ old('country', $user->country) === 'Belarus' ? 'selected' : '' }}>Belarus</option>
                                <option value="Belgium" {{ old('country', $user->country) === 'Belgium' ? 'selected' : '' }}>Belgium</option>
                                <option value="Belize" {{ old('country', $user->country) === 'Belize' ? 'selected' : '' }}>Belize</option>
                                <option value="Benin" {{ old('country', $user->country) === 'Benin' ? 'selected' : '' }}>Benin</option>
                                <option value="Bhutan" {{ old('country', $user->country) === 'Bhutan' ? 'selected' : '' }}>Bhutan</option>
                                <option value="Bolivia" {{ old('country', $user->country) === 'Bolivia' ? 'selected' : '' }}>Bolivia</option>
                                <option value="Bosnia and Herzegovina" {{ old('country', $user->country) === 'Bosnia and Herzegovina' ? 'selected' : '' }}>Bosnia and Herzegovina</option>
                                <option value="Botswana" {{ old('country', $user->country) === 'Botswana' ? 'selected' : '' }}>Botswana</option>
                                <option value="Brazil" {{ old('country', $user->country) === 'Brazil' ? 'selected' : '' }}>Brazil</option>
                                <option value="Brunei" {{ old('country', $user->country) === 'Brunei' ? 'selected' : '' }}>Brunei</option>
                                <option value="Bulgaria" {{ old('country', $user->country) === 'Bulgaria' ? 'selected' : '' }}>Bulgaria</option>
                                <option value="Burkina Faso" {{ old('country', $user->country) === 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                                <option value="Burundi" {{ old('country', $user->country) === 'Burundi' ? 'selected' : '' }}>Burundi</option>
                                <option value="Cambodia" {{ old('country', $user->country) === 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
                                <option value="Cameroon" {{ old('country', $user->country) === 'Cameroon' ? 'selected' : '' }}>Cameroon</option>
                                <option value="Canada" {{ old('country', $user->country) === 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="Cape Verde" {{ old('country', $user->country) === 'Cape Verde' ? 'selected' : '' }}>Cape Verde</option>
                                <option value="Central African Republic" {{ old('country', $user->country) === 'Central African Republic' ? 'selected' : '' }}>Central African Republic</option>
                                <option value="Chad" {{ old('country', $user->country) === 'Chad' ? 'selected' : '' }}>Chad</option>
                                <option value="Chile" {{ old('country', $user->country) === 'Chile' ? 'selected' : '' }}>Chile</option>
                                <option value="China" {{ old('country', $user->country) === 'China' ? 'selected' : '' }}>China</option>
                                <option value="Colombia" {{ old('country', $user->country) === 'Colombia' ? 'selected' : '' }}>Colombia</option>
                                <option value="Comoros" {{ old('country', $user->country) === 'Comoros' ? 'selected' : '' }}>Comoros</option>
                                <option value="Congo" {{ old('country', $user->country) === 'Congo' ? 'selected' : '' }}>Congo</option>
                                <option value="Costa Rica" {{ old('country', $user->country) === 'Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
                                <option value="Croatia" {{ old('country', $user->country) === 'Croatia' ? 'selected' : '' }}>Croatia</option>
                                <option value="Cuba" {{ old('country', $user->country) === 'Cuba' ? 'selected' : '' }}>Cuba</option>
                                <option value="Cyprus" {{ old('country', $user->country) === 'Cyprus' ? 'selected' : '' }}>Cyprus</option>
                                <option value="Czech Republic" {{ old('country', $user->country) === 'Czech Republic' ? 'selected' : '' }}>Czech Republic</option>
                                <option value="Czechia" {{ old('country', $user->country) === 'Czechia' ? 'selected' : '' }}>Czechia</option>
                                <option value="Denmark" {{ old('country', $user->country) === 'Denmark' ? 'selected' : '' }}>Denmark</option>
                                <option value="Djibouti" {{ old('country', $user->country) === 'Djibouti' ? 'selected' : '' }}>Djibouti</option>
                                <option value="Dominica" {{ old('country', $user->country) === 'Dominica' ? 'selected' : '' }}>Dominica</option>
                                <option value="Dominican Republic" {{ old('country', $user->country) === 'Dominican Republic' ? 'selected' : '' }}>Dominican Republic</option>
                                <option value="Ecuador" {{ old('country', $user->country) === 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                                <option value="Egypt" {{ old('country', $user->country) === 'Egypt' ? 'selected' : '' }}>Egypt</option>
                                <option value="El Salvador" {{ old('country', $user->country) === 'El Salvador' ? 'selected' : '' }}>El Salvador</option>
                                <option value="Equatorial Guinea" {{ old('country', $user->country) === 'Equatorial Guinea' ? 'selected' : '' }}>Equatorial Guinea</option>
                                <option value="Eritrea" {{ old('country', $user->country) === 'Eritrea' ? 'selected' : '' }}>Eritrea</option>
                                <option value="Estonia" {{ old('country', $user->country) === 'Estonia' ? 'selected' : '' }}>Estonia</option>
                                <option value="Ethiopia" {{ old('country', $user->country) === 'Ethiopia' ? 'selected' : '' }}>Ethiopia</option>
                                <option value="Fiji" {{ old('country', $user->country) === 'Fiji' ? 'selected' : '' }}>Fiji</option>
                                <option value="Finland" {{ old('country', $user->country) === 'Finland' ? 'selected' : '' }}>Finland</option>
                                <option value="France" {{ old('country', $user->country) === 'France' ? 'selected' : '' }}>France</option>
                                <option value="Gabon" {{ old('country', $user->country) === 'Gabon' ? 'selected' : '' }}>Gabon</option>
                                <option value="Gambia" {{ old('country', $user->country) === 'Gambia' ? 'selected' : '' }}>Gambia</option>
                                <option value="Georgia" {{ old('country', $user->country) === 'Georgia' ? 'selected' : '' }}>Georgia</option>
                                <option value="Germany" {{ old('country', $user->country) === 'Germany' ? 'selected' : '' }}>Germany</option>
                                <option value="Ghana" {{ old('country', $user->country) === 'Ghana' ? 'selected' : '' }}>Ghana</option>
                                <option value="Greece" {{ old('country', $user->country) === 'Greece' ? 'selected' : '' }}>Greece</option>
                                <option value="Grenada" {{ old('country', $user->country) === 'Grenada' ? 'selected' : '' }}>Grenada</option>
                                <option value="Guatemala" {{ old('country', $user->country) === 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
                                <option value="Guinea" {{ old('country', $user->country) === 'Guinea' ? 'selected' : '' }}>Guinea</option>
                                <option value="Guinea-Bissau" {{ old('country', $user->country) === 'Guinea-Bissau' ? 'selected' : '' }}>Guinea-Bissau</option>
                                <option value="Guyana" {{ old('country', $user->country) === 'Guyana' ? 'selected' : '' }}>Guyana</option>
                                <option value="Haiti" {{ old('country', $user->country) === 'Haiti' ? 'selected' : '' }}>Haiti</option>
                                <option value="Honduras" {{ old('country', $user->country) === 'Honduras' ? 'selected' : '' }}>Honduras</option>
                                <option value="Hungary" {{ old('country', $user->country) === 'Hungary' ? 'selected' : '' }}>Hungary</option>
                                <option value="Iceland" {{ old('country', $user->country) === 'Iceland' ? 'selected' : '' }}>Iceland</option>
                                <option value="India" {{ old('country', $user->country) === 'India' ? 'selected' : '' }}>India</option>
                                <option value="Indonesia" {{ old('country', $user->country) === 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                <option value="Iran" {{ old('country', $user->country) === 'Iran' ? 'selected' : '' }}>Iran</option>
                                <option value="Iraq" {{ old('country', $user->country) === 'Iraq' ? 'selected' : '' }}>Iraq</option>
                                <option value="Ireland" {{ old('country', $user->country) === 'Ireland' ? 'selected' : '' }}>Ireland</option>
                                <option value="Israel" {{ old('country', $user->country) === 'Israel' ? 'selected' : '' }}>Israel</option>
                                <option value="Italy" {{ old('country', $user->country) === 'Italy' ? 'selected' : '' }}>Italy</option>
                                <option value="Jamaica" {{ old('country', $user->country) === 'Jamaica' ? 'selected' : '' }}>Jamaica</option>
                                <option value="Japan" {{ old('country', $user->country) === 'Japan' ? 'selected' : '' }}>Japan</option>
                                <option value="Jordan" {{ old('country', $user->country) === 'Jordan' ? 'selected' : '' }}>Jordan</option>
                                <option value="Kazakhstan" {{ old('country', $user->country) === 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
                                <option value="Kenya" {{ old('country', $user->country) === 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                <option value="Kiribati" {{ old('country', $user->country) === 'Kiribati' ? 'selected' : '' }}>Kiribati</option>
                                <option value="Kuwait" {{ old('country', $user->country) === 'Kuwait' ? 'selected' : '' }}>Kuwait</option>
                                <option value="Kyrgyzstan" {{ old('country', $user->country) === 'Kyrgyzstan' ? 'selected' : '' }}>Kyrgyzstan</option>
                                <option value="Laos" {{ old('country', $user->country) === 'Laos' ? 'selected' : '' }}>Laos</option>
                                <option value="Latvia" {{ old('country', $user->country) === 'Latvia' ? 'selected' : '' }}>Latvia</option>
                                <option value="Lebanon" {{ old('country', $user->country) === 'Lebanon' ? 'selected' : '' }}>Lebanon</option>
                                <option value="Lesotho" {{ old('country', $user->country) === 'Lesotho' ? 'selected' : '' }}>Lesotho</option>
                                <option value="Liberia" {{ old('country', $user->country) === 'Liberia' ? 'selected' : '' }}>Liberia</option>
                                <option value="Libya" {{ old('country', $user->country) === 'Libya' ? 'selected' : '' }}>Libya</option>
                                <option value="Liechtenstein" {{ old('country', $user->country) === 'Liechtenstein' ? 'selected' : '' }}>Liechtenstein</option>
                                <option value="Lithuania" {{ old('country', $user->country) === 'Lithuania' ? 'selected' : '' }}>Lithuania</option>
                                <option value="Luxembourg" {{ old('country', $user->country) === 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                                <option value="Madagascar" {{ old('country', $user->country) === 'Madagascar' ? 'selected' : '' }}>Madagascar</option>
                                <option value="Malawi" {{ old('country', $user->country) === 'Malawi' ? 'selected' : '' }}>Malawi</option>
                                <option value="Malaysia" {{ old('country', $user->country) === 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                <option value="Maldives" {{ old('country', $user->country) === 'Maldives' ? 'selected' : '' }}>Maldives</option>
                                <option value="Mali" {{ old('country', $user->country) === 'Mali' ? 'selected' : '' }}>Mali</option>
                                <option value="Malta" {{ old('country', $user->country) === 'Malta' ? 'selected' : '' }}>Malta</option>
                                <option value="Marshall Islands" {{ old('country', $user->country) === 'Marshall Islands' ? 'selected' : '' }}>Marshall Islands</option>
                                <option value="Mauritania" {{ old('country', $user->country) === 'Mauritania' ? 'selected' : '' }}>Mauritania</option>
                                <option value="Mauritius" {{ old('country', $user->country) === 'Mauritius' ? 'selected' : '' }}>Mauritius</option>
                                <option value="Mexico" {{ old('country', $user->country) === 'Mexico' ? 'selected' : '' }}>Mexico</option>
                                <option value="Micronesia" {{ old('country', $user->country) === 'Micronesia' ? 'selected' : '' }}>Micronesia</option>
                                <option value="Moldova" {{ old('country', $user->country) === 'Moldova' ? 'selected' : '' }}>Moldova</option>
                                <option value="Monaco" {{ old('country', $user->country) === 'Monaco' ? 'selected' : '' }}>Monaco</option>
                                <option value="Mongolia" {{ old('country', $user->country) === 'Mongolia' ? 'selected' : '' }}>Mongolia</option>
                                <option value="Montenegro" {{ old('country', $user->country) === 'Montenegro' ? 'selected' : '' }}>Montenegro</option>
                                <option value="Morocco" {{ old('country', $user->country) === 'Morocco' ? 'selected' : '' }}>Morocco</option>
                                <option value="Mozambique" {{ old('country', $user->country) === 'Mozambique' ? 'selected' : '' }}>Mozambique</option>
                                <option value="Myanmar" {{ old('country', $user->country) === 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                                <option value="Namibia" {{ old('country', $user->country) === 'Namibia' ? 'selected' : '' }}>Namibia</option>
                                <option value="Nauru" {{ old('country', $user->country) === 'Nauru' ? 'selected' : '' }}>Nauru</option>
                                <option value="Nepal" {{ old('country', $user->country) === 'Nepal' ? 'selected' : '' }}>Nepal</option>
                                <option value="Netherlands" {{ old('country', $user->country) === 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
                                <option value="New Zealand" {{ old('country', $user->country) === 'New Zealand' ? 'selected' : '' }}>New Zealand</option>
                                <option value="Nicaragua" {{ old('country', $user->country) === 'Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
                                <option value="Niger" {{ old('country', $user->country) === 'Niger' ? 'selected' : '' }}>Niger</option>
                                <option value="Nigeria" {{ old('country', $user->country) === 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                                <option value="North Korea" {{ old('country', $user->country) === 'North Korea' ? 'selected' : '' }}>North Korea</option>
                                <option value="North Macedonia" {{ old('country', $user->country) === 'North Macedonia' ? 'selected' : '' }}>North Macedonia</option>
                                <option value="Norway" {{ old('country', $user->country) === 'Norway' ? 'selected' : '' }}>Norway</option>
                                <option value="Oman" {{ old('country', $user->country) === 'Oman' ? 'selected' : '' }}>Oman</option>
                                <option value="Pakistan" {{ old('country', $user->country) === 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                <option value="Palau" {{ old('country', $user->country) === 'Palau' ? 'selected' : '' }}>Palau</option>
                                <option value="Palestine" {{ old('country', $user->country) === 'Palestine' ? 'selected' : '' }}>Palestine</option>
                                <option value="Panama" {{ old('country', $user->country) === 'Panama' ? 'selected' : '' }}>Panama</option>
                                <option value="Papua New Guinea" {{ old('country', $user->country) === 'Papua New Guinea' ? 'selected' : '' }}>Papua New Guinea</option>
                                <option value="Paraguay" {{ old('country', $user->country) === 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
                                <option value="Peru" {{ old('country', $user->country) === 'Peru' ? 'selected' : '' }}>Peru</option>
                                <option value="Philippines" {{ old('country', $user->country) === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                <option value="Poland" {{ old('country', $user->country) === 'Poland' ? 'selected' : '' }}>Poland</option>
                                <option value="Portugal" {{ old('country', $user->country) === 'Portugal' ? 'selected' : '' }}>Portugal</option>
                                <option value="Qatar" {{ old('country', $user->country) === 'Qatar' ? 'selected' : '' }}>Qatar</option>
                                <option value="Romania" {{ old('country', $user->country) === 'Romania' ? 'selected' : '' }}>Romania</option>
                                <option value="Russia" {{ old('country', $user->country) === 'Russia' ? 'selected' : '' }}>Russia</option>
                                <option value="Rwanda" {{ old('country', $user->country) === 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                                <option value="Saint Kitts and Nevis" {{ old('country', $user->country) === 'Saint Kitts and Nevis' ? 'selected' : '' }}>Saint Kitts and Nevis</option>
                                <option value="Saint Lucia" {{ old('country', $user->country) === 'Saint Lucia' ? 'selected' : '' }}>Saint Lucia</option>
                                <option value="Saint Vincent and Grenadines" {{ old('country', $user->country) === 'Saint Vincent and Grenadines' ? 'selected' : '' }}>Saint Vincent and Grenadines</option>
                                <option value="Samoa" {{ old('country', $user->country) === 'Samoa' ? 'selected' : '' }}>Samoa</option>
                                <option value="San Marino" {{ old('country', $user->country) === 'San Marino' ? 'selected' : '' }}>San Marino</option>
                                <option value="Sao Tome and Principe" {{ old('country', $user->country) === 'Sao Tome and Principe' ? 'selected' : '' }}>Sao Tome and Principe</option>
                                <option value="Saudi Arabia" {{ old('country', $user->country) === 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                                <option value="Senegal" {{ old('country', $user->country) === 'Senegal' ? 'selected' : '' }}>Senegal</option>
                                <option value="Serbia" {{ old('country', $user->country) === 'Serbia' ? 'selected' : '' }}>Serbia</option>
                                <option value="Seychelles" {{ old('country', $user->country) === 'Seychelles' ? 'selected' : '' }}>Seychelles</option>
                                <option value="Sierra Leone" {{ old('country', $user->country) === 'Sierra Leone' ? 'selected' : '' }}>Sierra Leone</option>
                                <option value="Singapore" {{ old('country', $user->country) === 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                <option value="Slovakia" {{ old('country', $user->country) === 'Slovakia' ? 'selected' : '' }}>Slovakia</option>
                                <option value="Slovenia" {{ old('country', $user->country) === 'Slovenia' ? 'selected' : '' }}>Slovenia</option>
                                <option value="Solomon Islands" {{ old('country', $user->country) === 'Solomon Islands' ? 'selected' : '' }}>Solomon Islands</option>
                                <option value="Somalia" {{ old('country', $user->country) === 'Somalia' ? 'selected' : '' }}>Somalia</option>
                                <option value="South Africa" {{ old('country', $user->country) === 'South Africa' ? 'selected' : '' }}>South Africa</option>
                                <option value="South Korea" {{ old('country', $user->country) === 'South Korea' ? 'selected' : '' }}>South Korea</option>
                                <option value="South Sudan" {{ old('country', $user->country) === 'South Sudan' ? 'selected' : '' }}>South Sudan</option>
                                <option value="Spain" {{ old('country', $user->country) === 'Spain' ? 'selected' : '' }}>Spain</option>
                                <option value="Sri Lanka" {{ old('country', $user->country) === 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                                <option value="Sudan" {{ old('country', $user->country) === 'Sudan' ? 'selected' : '' }}>Sudan</option>
                                <option value="Suriname" {{ old('country', $user->country) === 'Suriname' ? 'selected' : '' }}>Suriname</option>
                                <option value="Sweden" {{ old('country', $user->country) === 'Sweden' ? 'selected' : '' }}>Sweden</option>
                                <option value="Switzerland" {{ old('country', $user->country) === 'Switzerland' ? 'selected' : '' }}>Switzerland</option>
                                <option value="Syria" {{ old('country', $user->country) === 'Syria' ? 'selected' : '' }}>Syria</option>
                                <option value="Taiwan" {{ old('country', $user->country) === 'Taiwan' ? 'selected' : '' }}>Taiwan</option>
                                <option value="Tajikistan" {{ old('country', $user->country) === 'Tajikistan' ? 'selected' : '' }}>Tajikistan</option>
                                <option value="Tanzania" {{ old('country', $user->country) === 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                                <option value="Thailand" {{ old('country', $user->country) === 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                <option value="Timor-Leste" {{ old('country', $user->country) === 'Timor-Leste' ? 'selected' : '' }}>Timor-Leste</option>
                                <option value="Togo" {{ old('country', $user->country) === 'Togo' ? 'selected' : '' }}>Togo</option>
                                <option value="Tonga" {{ old('country', $user->country) === 'Tonga' ? 'selected' : '' }}>Tonga</option>
                                <option value="Trinidad and Tobago" {{ old('country', $user->country) === 'Trinidad and Tobago' ? 'selected' : '' }}>Trinidad and Tobago</option>
                                <option value="Tunisia" {{ old('country', $user->country) === 'Tunisia' ? 'selected' : '' }}>Tunisia</option>
                                <option value="Turkey" {{ old('country', $user->country) === 'Turkey' ? 'selected' : '' }}>Turkey</option>
                                <option value="Turkmenistan" {{ old('country', $user->country) === 'Turkmenistan' ? 'selected' : '' }}>Turkmenistan</option>
                                <option value="Tuvalu" {{ old('country', $user->country) === 'Tuvalu' ? 'selected' : '' }}>Tuvalu</option>
                                <option value="Uganda" {{ old('country', $user->country) === 'Uganda' ? 'selected' : '' }}>Uganda</option>
                                <option value="Ukraine" {{ old('country', $user->country) === 'Ukraine' ? 'selected' : '' }}>Ukraine</option>
                                <option value="United Arab Emirates" {{ old('country', $user->country) === 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                                <option value="United Kingdom" {{ old('country', $user->country) === 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="United States" {{ old('country', $user->country) === 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="Uruguay" {{ old('country', $user->country) === 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                                <option value="Uzbekistan" {{ old('country', $user->country) === 'Uzbekistan' ? 'selected' : '' }}>Uzbekistan</option>
                                <option value="Vanuatu" {{ old('country', $user->country) === 'Vanuatu' ? 'selected' : '' }}>Vanuatu</option>
                                <option value="Vatican City" {{ old('country', $user->country) === 'Vatican City' ? 'selected' : '' }}>Vatican City</option>
                                <option value="Venezuela" {{ old('country', $user->country) === 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                                <option value="Vietnam" {{ old('country', $user->country) === 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                <option value="Yemen" {{ old('country', $user->country) === 'Yemen' ? 'selected' : '' }}>Yemen</option>
                                <option value="Zambia" {{ old('country', $user->country) === 'Zambia' ? 'selected' : '' }}>Zambia</option>
                                <option value="Zimbabwe" {{ old('country', $user->country) === 'Zimbabwe' ? 'selected' : '' }}>Zimbabwe</option>
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-city" style="color: #DC143C;"></i> City / Area
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('city_area') is-invalid @enderror" 
                                name="city_area" 
                                value="{{ old('city_area', $user->city_area) }}" 
                                placeholder="New York, London, etc."
                            >
                            @error('city_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row full">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-road" style="color: #DC143C;"></i> Street Name
                                <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="street_name" 
                                value="{{ old('street_name', $user->street_name) }}" 
                                placeholder="e.g., 5th Avenue"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building" style="color: #DC143C;"></i> Building / Number
                                <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="building_name" 
                                value="{{ old('building_name', $user->building_name) }}" 
                                placeholder="Apt 123, Suite 100, etc."
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-door-open" style="color: #DC143C;"></i> Floor / Apartment
                                <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="floor_apartment" 
                                value="{{ old('floor_apartment', $user->floor_apartment) }}" 
                                placeholder="Floor 3, Apt B, etc."
                            >
                        </div>
                    </div>

                    <div class="form-row full">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map" style="color: #DC143C;"></i> Landmark
                                <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="landmark" 
                                value="{{ old('landmark', $user->landmark) }}" 
                                placeholder="Near the park, opposite the mall, etc."
                            >
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <div class="section-title">
                        <i class="fas fa-shield-alt"></i> Account Settings
                    </div>

                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="is_admin" 
                            id="is_admin" 
                            value="1"
                            {{ $user->is_admin ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="is_admin">
                            <i class="fas fa-crown"></i> Administrator
                        </label>
                        <span class="form-text" style="margin-left: 1.8rem;">Grant administrative privileges to this user</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
