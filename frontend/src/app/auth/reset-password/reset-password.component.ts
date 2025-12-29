import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from '../auth-core/auth.service';
import { finalize } from 'rxjs/operators';

@Component({
  selector: 'app-reset-password',
  templateUrl: './reset-password.component.html'
})
export class ResetPasswordComponent implements OnInit {
  form!: FormGroup;
  token: string | null = null;
  loading = false;
  success = false;
  error = '';
  showPassword = false;
  showPasswordConfirmation = false;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private auth: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      password: ['', [Validators.required, Validators.minLength(8)]],
      passwordConfirmation: ['', Validators.required]
    }, { validators: this.passwordsMatch });

    this.token = this.route.snapshot.queryParamMap.get('token');
    if (!this.token) {
      this.error = 'Token no provisto.';
    }
  }

  passwordsMatch(group: FormGroup) {
    const p = group.get('password')?.value;
    const pc = group.get('passwordConfirmation')?.value;
    return p === pc ? null : { passwordMismatch: true };
  }

  getPasswordFieldType(field: string) {
    const showField = field === 'password' ? this.showPassword : this.showPasswordConfirmation;
    return showField ? 'text' : 'password';
  }

  togglePasswordVisibility(field: string) {
    if (field === 'password') {
      this.showPassword = !this.showPassword;
    } else {
      this.showPasswordConfirmation = !this.showPasswordConfirmation;
    }
  }

  submit() {
    if (!this.token) return;
    this.loading = true;
    this.auth.resetPassword(this.token, this.form.get('password')!.value, this.form.get('passwordConfirmation')!.value)
      .pipe(finalize(()=> this.loading = false))
      .subscribe({
        next: () => {
          this.success = true;
          setTimeout(() => this.router.navigate(['/login']), 200);
        },
        error: (e) => {
          this.error = e?.error?.message || 'Error al restablecer la contraseña';
        }
      });
  }
}