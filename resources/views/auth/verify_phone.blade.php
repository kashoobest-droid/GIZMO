<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Verify Phone - Gizmo Store</title>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; }
        .card { width:100%; max-width:520px; border-radius:10px; padding:20px; box-shadow:0 10px 40px rgba(0,0,0,0.3); background:white; }
        .card-header { font-weight:700; font-size:1.1rem; margin-bottom:10px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Verify Phone</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <p>Please enter the 6-digit code sent to your phone number.</p>

            <form method="post" action="{{ route('verify.check') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $phone) }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" placeholder="6-digit code" value="{{ old('code') }}">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(session('attempts_left') !== null)
                        <div class="form-text text-muted mt-1">Attempts left: {{ session('attempts_left') }}</div>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Verify</button>
                    <button type="button" id="resendBtn" class="btn btn-outline-secondary">Send OTP</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function(){
        const btn = document.getElementById('resendBtn');
        if (!btn) return;
        let timerId = null;
        const disableFor = (secs) => {
            let remaining = secs;
            btn.disabled = true;
            const origText = btn.innerText;
            btn.innerText = `${origText} (${remaining}s)`;
            timerId = setInterval(() => {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(timerId);
                    btn.disabled = false;
                    btn.innerText = 'Send OTP';
                    return;
                }
                btn.innerText = `Send OTP (${remaining}s)`;
            }, 1000);
        };

        btn.addEventListener('click', async function () {
            const phone = document.querySelector('input[name="phone"]').value;
            // Optimistically disable for 60s
            disableFor(60);
            try {
                const res = await fetch('{{ route('verify.send') }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ phone })
                });

                let data = null;
                try { data = await res.json(); } catch (e) { data = null; }

                if (res.status === 429 && data && data.retry_after) {
                    // server asks to wait fewer/more seconds — reset timer
                    clearInterval(timerId);
                    disableFor(parseInt(data.retry_after, 10));
                    alert(data.message || 'Please wait before retrying');
                    return;
                }

                if (!res.ok) {
                    const msg = (data && data.message) ? data.message : 'Failed to send OTP';
                    alert(msg);
                    // re-enable button so user can try again sooner
                    clearInterval(timerId);
                    btn.disabled = false;
                    btn.innerText = 'Send OTP';
                    return;
                }

                alert((data && data.message) ? data.message : 'OTP sent');
            } catch (err) {
                alert('Failed to send OTP');
                clearInterval(timerId);
                btn.disabled = false;
                btn.innerText = 'Send OTP';
            }
        });
    })();
    </script>
</body>
</html>
