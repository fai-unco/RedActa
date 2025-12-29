import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../auth-core/auth.service';
import { finalize } from 'rxjs/operators';

@Component({
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.component.html',
})
export class ForgotPasswordComponent {
  form: FormGroup;
  loading = false;
  success = false;
  error = '';

  constructor(private fb: FormBuilder, private auth: AuthService) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]]
    });
  }

  submit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }
    this.loading = true;
    this.error = '';
    this.auth.forgotPassword(this.form.get('email')!.value)
      .pipe(finalize(()=> this.loading = false))
      .subscribe({
        next: () => {
          this.success = true;
        },
        error: (e) => {
          this.error = e?.error?.message || 'Ha habido un error. Intente nuevamente.';
        }
      });
  }
}