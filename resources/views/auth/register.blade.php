<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – OCD Venue Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('OCDLOGO.png') }}">
    <style>
        :root {
            --ocd-blue: #1a3c72;
            --ocd-orange: #e87722;
        }

        body {
            min-height: 100vh;
            background: url('{{ asset('bgimg.jpg') }}') no-repeat center center fixed;
            background-size: contain;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            overflow: hidden;
            border-top: 6px solid var(--ocd-orange);
        }

        .register-header {
            background: #0a1144;
            color: #fff;
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .register-header img {
            max-width: 80px;
            height: auto;
            margin-bottom: 1rem;
        }

        .register-header h4 {
            font-weight: 700;
            margin: 0;
            margin-bottom: 0.25rem;
        }

        .register-body {
            padding: 1.75rem 2rem;
        }

        .btn-register {
            background: var(--ocd-orange);
            border: none;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: .65rem;
            border-radius: 8px;
            transition: opacity .2s;
        }

        .btn-register:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--ocd-blue);
            box-shadow: 0 0 0 .2rem rgba(26, 60, 114, .2);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 2.75rem;
        }

        .toggle-eye {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            color: #888;
            cursor: pointer;
            font-size: 1.05rem;
            line-height: 1;
            z-index: 5;
            transition: color .15s;
        }

        .toggle-eye:hover {
            color: var(--ocd-blue);
        }

        .password-feedback {
            font-size: .78rem;
            margin-top: .3rem;
            min-height: 1rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .password-feedback.match {
            color: #198754;
        }

        .password-feedback.no-match {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="register-card">
        <div class="register-header">
            <img src="{{ asset('OCDLOGO.png') }}" alt="OCD Logo">
            <h4>Register Account</h4>
            <p class="mb-0 small opacity-75">Venue Management System</p>
        </div>
        <div class="register-body">

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="First Name, M.I, Last Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="emailInput"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="@ocd.gov.ph" pattern=".*@(gmail\.com|ocd\.gov\.ph)$"
                        title="Only @gmail.com or @ocd.gov.ph emails are allowed." required oninput="validateForm()">
                    <div id="emailFeedback" class="password-feedback" style="display: none;"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Division / Service <span
                            class="text-danger">*</span></label>
                    <select name="division_id" id="divisionSelect"
                        class="form-select @error('division_id') is-invalid @enderror" required>
                        <option value="" selected disabled>Select your Division</option>
                        @if (isset($divisions))
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control"
                        value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimum of eight(8) characters" required oninput="validateForm()">
                        <button type="button" class="toggle-eye" onclick="toggleEye('password', 'eyeIcon1')"
                            tabindex="-1" aria-label="Toggle password visibility">
                            <i id="eyeIcon1" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <ul id="password-requirements" class="list-unstyled mt-2 mb-0"
                        style="display: none; font-size: 0.78rem;">
                        <li id="req-length" class="text-danger"><i class="bi bi-x-circle"></i> Minimum of 8 characters
                        </li>
                        <li id="req-letter" class="text-danger"><i class="bi bi-x-circle"></i> At least 1 letter</li>
                        <li id="req-number" class="text-danger"><i class="bi bi-x-circle"></i> At least 1 number</li>
                        <li id="req-special" class="text-danger"><i class="bi bi-x-circle"></i> At least 1 special
                            character</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Confirm Password <span
                            class="text-danger">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="confirmPassword" name="password_confirmation" class="form-control"
                            placeholder="Re-enter your password" required oninput="validateForm()">
                        <button type="button" class="toggle-eye" onclick="toggleEye('confirmPassword', 'eyeIcon2')"
                            tabindex="-1" aria-label="Toggle confirm password visibility">
                            <i id="eyeIcon2" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div id="passwordFeedback" class="password-feedback"></div>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-register" disabled>
                    Submit Registration
                </button>
            </form>

            <div class="text-center mt-3 small">
                Already have an account? <a href="{{ route('login') }}"
                    style="color:var(--ocd-blue);font-weight:600;">Log in</a>
            </div>
        </div>
    </div>

    <script>
        function toggleEye(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function validateForm() {
            const email = document.getElementById('emailInput').value;
            const pw = document.getElementById('password').value;
            const cpw = document.getElementById('confirmPassword').value;
            const emailFeedback = document.getElementById('emailFeedback');
            const matchFeedback = document.getElementById('passwordFeedback');
            const submitBtn = document.getElementById('submitBtn');
            const reqList = document.getElementById('password-requirements');

            let isEmailValid = email.toLowerCase().endsWith('@gmail.com') || email.toLowerCase().endsWith('@ocd.gov.ph');

            if (!isEmailValid && email.includes('@') && email.split('@')[1].length > 0) {
                const domainTyped = email.toLowerCase().split('@')[1];
                if (!"gmail.com".startsWith(domainTyped) && !"ocd.gov.ph".startsWith(domainTyped)) {
                    emailFeedback.style.display = 'flex';
                    emailFeedback.innerHTML =
                        '<i class="bi bi-x-circle-fill"></i> Only @gmail.com or @ocd.gov.ph emails are allowed';
                    emailFeedback.className = 'password-feedback no-match';
                } else {
                    emailFeedback.style.display = 'none';
                }
            } else {
                emailFeedback.style.display = 'none';
            }

            if (pw.length > 0) {
                reqList.style.display = 'block';
            } else {
                reqList.style.display = 'none';
            }

            const hasLength = pw.length >= 8;
            const hasLetter = /[a-zA-Z]/.test(pw);
            const hasNumber = /\d/.test(pw);
            const hasSpecial = /[\W_]/.test(pw);

            const updateReqUI = (id, isValid, text) => {
                const el = document.getElementById(id);
                if (isValid) {
                    el.className = 'text-success';
                    el.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${text}`;
                } else {
                    el.className = 'text-danger';
                    el.innerHTML = `<i class="bi bi-x-circle"></i> ${text}`;
                }
            };

            updateReqUI('req-length', hasLength, 'Minimum of 8 characters');
            updateReqUI('req-letter', hasLetter, 'At least 1 letter');
            updateReqUI('req-number', hasNumber, 'At least 1 number');
            updateReqUI('req-special', hasSpecial, 'At least 1 special character');

            let isPasswordStrong = hasLength && hasLetter && hasNumber && hasSpecial;
            let isPasswordMatch = false;

            if (cpw === '') {
                matchFeedback.textContent = '';
                matchFeedback.className = 'password-feedback';
            } else if (pw === cpw) {
                matchFeedback.innerHTML = '<i class="bi bi-check-circle-fill"></i> Passwords match';
                matchFeedback.className = 'password-feedback match';
                isPasswordMatch = true;
            } else {
                matchFeedback.innerHTML = '<i class="bi bi-x-circle-fill"></i> Passwords do not match';
                matchFeedback.className = 'password-feedback no-match';
            }

            if (isEmailValid && isPasswordStrong && isPasswordMatch) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const email = document.getElementById('emailInput').value;
            const pw = document.getElementById('password').value;
            const cpw = document.getElementById('confirmPassword').value;

            const hasLength = pw.length >= 8;
            const hasLetter = /[a-zA-Z]/.test(pw);
            const hasNumber = /\d/.test(pw);
            const hasSpecial = /[\W_]/.test(pw);

            const isPasswordStrong = hasLength && hasLetter && hasNumber && hasSpecial;
            const isEmailValid = email.toLowerCase().endsWith('@gmail.com') || email.toLowerCase().endsWith(
                '@ocd.gov.ph');

            if (!isEmailValid || pw !== cpw || !isPasswordStrong) {
                e.preventDefault();
                validateForm();
            }
        });
    </script>
</body>

</html>
