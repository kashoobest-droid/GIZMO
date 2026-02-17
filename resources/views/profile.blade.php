<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>{{ __('messages.nav_profile') }} - KS Tech</title>
    <style>
        /* Light Mode (Default) */
        body { background: #f5f5f5; color: #333333; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .profile-card { max-width: 900px; margin: 40px auto; border-radius: 12px; overflow: hidden; border: 1px solid #ddd; }
        .profile-side { background: #1a1a1a; color: white; padding: 30px; display:flex; align-items:center; justify-content:center; }
        .profile-side i { color: #DC143C; font-size: 48px; }
        .profile-side p { color: #b0b0b0; }
        .avatar-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #DC143C; }
        .profile-body { padding: 24px; background: #ffffff; color: #333333; }
        .profile-body h4, .profile-body h5 { color: #333333; }
        .btn-secondary { background-color: #e9ecef; border-color: #ddd; color: #333333; }
        .btn-secondary:hover { background-color: #f5f5f5; border-color: #999; color: #333333; }
        .form-label { color: #333333; }
        .form-control { background-color: #ffffff; border-color: #ddd; color: #333333; }
        .form-control:focus { background-color: #ffffff; border-color: #DC143C; color: #333333; box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25); }
        .form-select { background-color: #ffffff; border-color: #ddd; color: #333333; }
        .form-select:focus { border-color: #DC143C; box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25); }
        .form-select option { background-color: #ffffff; color: #333333; }
        .text-muted { color: #666666 !important; }
        .alert { background-color: #ffffff; border-color: #ddd; color: #333333; }
        .alert-success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .invalid-feedback { display: block; color: #dc3545; }
        hr { border-color: #ddd; }
        
        /* Dark Mode */
        html.dark-mode body { background-color: #0f0f0f; color: #e0e0e0; }
        html.dark-mode .profile-card { border-color: #2a2a2a; }
        html.dark-mode .profile-body { background: #1a1a1a; color: #e0e0e0; }
        html.dark-mode .profile-body h4, html.dark-mode .profile-body h5 { color: #ffffff; }
        html.dark-mode .btn-secondary { background-color: #2a2a2a; border-color: #3a3a3a; color: #e0e0e0; }
        html.dark-mode .btn-secondary:hover { background-color: #3a3a3a; border-color: #4a4a4a; color: #ffffff; }
        html.dark-mode .form-label { color: #ffffff; }
        html.dark-mode .form-control { background-color: #2a2a2a; border-color: #3a3a3a; color: #e0e0e0; }
        html.dark-mode .form-control:focus { background-color: #2a2a2a; border-color: #DC143C; color: #e0e0e0; }
        html.dark-mode .form-select { background-color: #2a2a2a; border-color: #3a3a3a; color: #e0e0e0; }
        html.dark-mode .form-select:focus { border-color: #DC143C; }
        html.dark-mode .form-select option { background-color: #1a1a1a; color: #e0e0e0; }
        html.dark-mode .text-muted { color: #b0b0b0 !important; }
        html.dark-mode .alert { background-color: #1a1a1a; border-color: #2a2a2a; color: #e0e0e0; }
        html.dark-mode .alert-success { background-color: #1a2a1a; border-color: #2a5a2a; color: #90ee90; }
        html.dark-mode .invalid-feedback { color: #DC143C; }
        html.dark-mode hr { border-color: #2a2a2a; }
        
        .btn-save { background: linear-gradient(135deg,#DC143C 0%,#8B0000 100%); border:none; color:white; }
        .btn-save:hover { background: linear-gradient(135deg,#8B0000 0%,#6B0000 100%); color:white; }
    </style>
    <script>
        // Apply dark mode preference from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeEnabled = localStorage.getItem('gizmo-store-dark-mode') === 'true';
            if (darkModeEnabled) {
                document.documentElement.classList.add('dark-mode');
            }
        });
    </script>
</head>
<body>
    <div class="card profile-card">
        <div class="row g-0">
            <div class="col-md-4 profile-side">
                <div style="text-align:center;">
                    @if($user->avatar)
                        <img src="{{ filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : asset($user->avatar) }}" alt="Avatar" class="avatar-img">
                    @else
                        <i class="fas fa-user-circle" style="font-size: 80px;"></i>
                    @endif
                    <h4 style="margin-top:12px;">{{ $user->name }}</h4>
                    <p style="opacity:.85;">{{ __('messages.profile_manage_account') }}</p>
                </div>
            </div>
            <div class="col-md-8 profile-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_avatar') }}</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                        <small class="text-muted">{{ __('messages.profile_avatar_help') }}</small>
                        @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_full_name') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_email') }}</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_phone') }}</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr>
                    <h5 class="mb-3">{{ __('messages.profile_address') }}</h5>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_country') }}</label>
                        <select name="country" class="form-select @error('country') is-invalid @enderror">
                            <option value="">{{ __('messages.profile_select_country') }}</option>
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
                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_street') }}</label>
                        <input type="text" name="street_name" class="form-control @error('street_name') is-invalid @enderror" value="{{ old('street_name', $user->street_name) }}">
                        @error('street_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_building') }}</label>
                        <input type="text" name="building_name" class="form-control @error('building_name') is-invalid @enderror" value="{{ old('building_name', $user->building_name) }}">
                        @error('building_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_floor') }} <span style="font-size:0.85rem; color:#999;">({{ __('messages.profile_optional') }})</span></label>
                        <input type="text" name="floor_apartment" class="form-control @error('floor_apartment') is-invalid @enderror" value="{{ old('floor_apartment', $user->floor_apartment) }}">
                        @error('floor_apartment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_landmark') }} <span style="font-size:0.85rem; color:#999;">({{ __('messages.profile_optional') }})</span></label>
                        <input type="text" name="landmark" class="form-control @error('landmark') is-invalid @enderror" value="{{ old('landmark', $user->landmark) }}" placeholder="{{ __('messages.profile_landmark_help') }}">
                        @error('landmark')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_city') }}</label>
                        <input type="text" name="city_area" class="form-control @error('city_area') is-invalid @enderror" value="{{ old('city_area', $user->city_area) }}">
                        @error('city_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-save">{{ __('messages.profile_save') }}</button>
                        <a href="/" class="btn btn-secondary">{{ __('messages.profile_cancel') }}</a>
                    </div>
                </form>

                <hr class="my-4">

                <h5 class="mb-3">{{ __('messages.profile_change_password') }}</h5>
                <form action="{{ route('profile.password.update') }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_current_password') }}</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_new_password') }}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                        <small class="text-muted">{{ __('messages.profile_password_help') }}</small>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.profile_confirm_password') }}</label>
                        <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-save">{{ __('messages.profile_update_password') }}</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
